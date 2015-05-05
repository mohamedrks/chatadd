<?php

/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 12/16/14
 * Time: 12:06 PM
 */
class NotificationController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {
        return 'Index Method';
    }

    public function toastNotifications($user_id)
    {

        $arrayNotification = array();

        $user_event_notifications = DB::table('notification')
                                        ->leftJoin('event', 'notification.object_id', '=', 'event.id')
                                        ->where('subject_id', '=', $user_id)
                                        ->where('unseen_toaster', '=', 1)
                                        ->where('type', '=', 'events')
                                        ->whereRaw('UNIX_TIMESTAMP(now()) - notification.created_date < 60')
                                        ->select(array('notification.id as id', 'notification.type', 'event.importance', 'event.indicator_name'))
                                        ->get();

        $user_indiator_notifications = DB::table('notification')
                                        ->leftJoin('indicator', 'notification.object_id', '=', 'indicator.id')
                                        ->where('subject_id', '=', $user_id)
                                        ->where('unseen_toaster', '=', 1)
                                        ->where('type', '=', 'indicators')
                                        ->whereRaw('UNIX_TIMESTAMP(now()) - notification.created_date < 60')
                                        ->select(array('notification.id as id', 'notification.type', DB::raw('null as importance '), 'indicator.name as indicator_name'))
                                        ->get();


        foreach ($user_event_notifications as $event) {
            $arrayNotification[] = array(
                'id' => $event->id,
                'notification' => $event->type,
                'title' => $event->indicator_name,
                'importance' => $event->importance
            );
        }

        foreach ($user_indiator_notifications as $indicator) {
            $arrayNotification[] = array(
                'id' => $indicator->id,
                'notification' => $indicator->type,
                'title' => $indicator->indicator_name,
                'importance' => $indicator->importance
            );
        }

        return json_encode($arrayNotification);
    }

    public function notificationCount()
    {
        $userId = Authorizer::getResourceOwnerId();

        $_notificationsCount = DB::table('notification')
                                ->leftJoin('indicator', 'notification.object_id', '=', 'indicator.id')
                                ->where('subject_id', '=', $userId)
                                ->where('unseen_notification_count', '=', '1')
                                ->select(array(DB::raw('count(*) as count ')))
                                ->get();

        return json_encode($_notificationsCount);
    }

    public function tabsNotificationCount(){

        $userId = Authorizer::getResourceOwnerId();

        $arrayNotificationCount = array();

        $_notificationsCountEvent = DB::table('notification')
                                        ->where('subject_id','=',$userId)
                                        ->where('unseen_notification_count','=',1)
                                        ->whereIn('type',array('events'))
                                        ->select(array(DB::raw('count(*) as count ')))
                                        ->get();

        $_notificationsCountIndicator = DB::table('notification')
                                        ->where('subject_id','=',$userId)
                                        ->where('unseen_notification_count','=',1)
                                        ->whereIn('type',array('indicators'))
                                        ->select(array(DB::raw('count(*) as count ')))
                                        ->get();

        $_notificationsCountStocks = DB::table('notification')
                                        ->where('subject_id','=',$userId)
                                        ->where('unseen_notification_count','=',1)
                                        ->whereIn('type',array('stocks'))
                                        ->select(array(DB::raw('count(*) as count ')))
                                        ->groupBy('notification.object_id')
                                        ->get();

        $_notificationsCountSocial = DB::table('notification')
                                        ->where('subject_id','=',$userId)
                                        ->where('unseen_notification_count','=',1)
                                        ->whereIn('type',array('social'))
                                        ->select(array(DB::raw('count(*) as count ')))
                                        ->get();

        $arrayNotificationCount[] = array(
            'event' => (!empty($_notificationsCountEvent)) ? $_notificationsCountEvent[0]->count : 0,
            'indicator' => (!empty($_notificationsCountIndicator)) ? $_notificationsCountIndicator[0]->count : 0,
            'stock' => (!empty($_notificationsCountStocks)) ? $_notificationsCountStocks[0]->count : 0,
            'social' => (!empty($_notificationsCountSocial)) ? $_notificationsCountSocial[0]->count : 0
        );

        return $arrayNotificationCount;
    }

    public function updateNotificationSeen(){

        $userId = Authorizer::getResourceOwnerId();

        DB::table('notification')
            ->where('subject_id', $userId)
            ->update(array('unseen_notification_count' => 0));

    }

    public function updateNotificationSeenUnseen($notificationId){

        DB::table('notification')
            ->where('id', $notificationId)
            ->update(array('unseen_notification' => 0));

    }

    public function updateSeenToaster($notificationId){

        DB::table('notification')
            ->where('id','=', $notificationId)
            ->update(array('unseen_toaster' => 0));
    }

    public function indicatorNotify()
    {

        $userId = Authorizer::getResourceOwnerId();
        $arrayNotification = array();

        $notificationsNotPercent = DB::table('notification')
                                    ->leftJoin('indicator', 'notification.object_id', '=', 'indicator.id')
                                    ->leftJoin('indicator_tolerance', 'indicator_tolerance.indicator_id', '=', 'indicator.id')
                                    ->where('notification.subject_id', '=', $userId)
                                    ->whereNotIn('indicator.Unit', array('percent'))
                                    ->whereIn('notification.type', array('indicators'))
                                    ->take(10)
                                    ->select(array('notification.id as notId', 'indicator.id as indId', 'indicator.Category', 'indicator.name', 'notification.type', DB::raw('(((indicator.last_value - indicator.previous_value)/(indicator.previous_value))*100.0) as percent '), 'indicator.unit', 'indicator.last_updated_date','notification.unseen_notification'))
                                    ->get();

        $notificationsPercent = DB::table('notification')
                                    ->leftJoin('indicator', 'notification.object_id', '=', 'indicator.id')
                                    ->leftJoin('indicator_tolerance', 'indicator_tolerance.indicator_id', '=', 'indicator.id')
                                    ->where('notification.subject_id', '=', $userId)
                                    ->whereIn('indicator.Unit', array('percent'))
                                    ->whereIn('notification.type', array('indicators'))
                                    ->take(10)
                                    ->select(array('notification.id as notId', 'indicator.id as indId', 'indicator.Category', 'indicator.name', 'notification.type', DB::raw('(indicator.last_value - indicator.previous_value) as percent '), 'indicator.unit', 'indicator.last_updated_date','notification.unseen_notification'))
                                    ->get();

        foreach ($notificationsNotPercent as $indicator) {
            $arrayNotification[] = array(
                'indicatorNotificationText' => ltrim(rtrim($indicator->name)),
                'percentage' => round($indicator->percent, 2),
                'updated' => time() - $indicator->last_updated_date,
                'category' => $indicator->Category,
                'unseen' => $indicator->unseen_notification,
                'notificationId' => $indicator->notId
            );
        }

        foreach ($notificationsPercent as $indicator) {
            $arrayNotification[] = array(
                'indicatorNotificationText' => ltrim(rtrim($indicator->name)),
                'percentage' => round($indicator->percent, 2),
                'updated' => time() - $indicator->last_updated_date,
                'category' => $indicator->Category,
                'unseen' => $indicator->unseen_notification,
                'notificationId' => $indicator->notId
            );
        }

        return json_encode(array_reverse($arrayNotification));

    }

    public function eventsNotify()
    {
        $id = Authorizer::getResourceOwnerId();
        $eventsNotification = DB::table('notification')
                                ->leftJoin('event', 'notification.object_id', '=', 'event.id')
                                ->where('notification.subject_id', '=', $id)
                                ->whereIn('notification.type', array('events'))
                                ->orderBy('notification.id','desc')
                                ->select(array('event.indicator_name', 'event.importance', DB::raw('UNIX_TIMESTAMP() - notification.created_date as created_date') , 'notification.unseen_notification as unseen ' , 'notification.id as notificationId'))
                                ->get();

        return json_encode($eventsNotification);

    }

    public function socialNotify()
    {
        $userId = Authorizer::getResourceOwnerId();
        $socialNotification = DB::table('notification')
                                ->leftJoin('symbol', 'notification.object_id', '=', 'symbol.id')
                                ->leftJoin('twitter_velocity_tolerance', 'symbol.id', '=', 'twitter_velocity_tolerance.symbol_id')
                                ->where('notification.subject_id', '=', $userId)
                                ->whereIn('notification.type', array('social'))
                                ->select(array('notification.id as notId', 'symbol.code', 'symbol.name', 'notification.type', 'notification.created_date', 'notification.unseen_notification'))
                                ->groupBy('symbol.name')
                                ->get();

        $arrayNotification = array();

        foreach ($socialNotification as $social) {

            $arrayNotification[] = array(
                'notificationText' => ltrim(rtrim($social->name)),
                'percentage' => round(getTwitterVelocityPercentageChange($social->code),2), // write up a velocity calculate function
                'updated' => time() - $social->created_date, // last updated time is temprory
                'type' => $social->type,
                'code' => $social->code,
                'notificationId' => $social->notId,
                'unseen' => $social->unseen_notification
            );
        }
        return json_encode(array_reverse($arrayNotification));
    }

    public function socialNewsNotify(){

        $userId = Authorizer::getResourceOwnerId();
        $socialNotification = DB::table('notification')
                                ->leftJoin('symbol', 'notification.object_id', '=', 'symbol.id')
                                ->leftJoin('twitter_velocity_tolerance', 'symbol.id', '=', 'twitter_velocity_tolerance.symbol_id')
                                ->where('notification.subject_id', '=', $userId)
                                ->whereIn('notification.type', array('news'))
                                ->select(array('notification.id as notId', 'symbol.code', 'symbol.name', 'notification.type', 'notification.created_date', 'notification.unseen_notification'))
                                ->groupBy('symbol.name')
                                ->get();

        foreach ($socialNotification as $social) {

            $arrayNotification[] = array(
                'notificationText' => ltrim(rtrim($social->name)),
                'percentage' => round(getNewsVelocityPercentageChange($social->code),2), // write up a velocity calculate function
                'updated' => time() - $social->created_date, // last updated time is temprory
                'type' => $social->type,
                'code' => $social->code,
                'notificationId' => $social->notId,
                'unseen' => $social->unseen_notification
            );
        }
        return array_reverse($arrayNotification);
    }

    // Check this method for correctness.
    // Corrected remove commented line below

    public function stocksNotify()
    {
        $userId = Authorizer::getResourceOwnerId();
        $arrayStockNotification = array();

        $stockNotification = DB::table('notification')
                                ->leftJoin('symbol', 'notification.object_id', '=', 'symbol.id')
                                ->leftJoin('subscribed_stock_info', 'symbol.id', '=', 'subscribed_stock_info.symbol_id')
                                ->leftJoin('stock_tolerance', 'symbol.id', '=', 'stock_tolerance.symbol_id')
                                ->where('notification.subject_id', '=', $userId)
                                //->where('notification.unseen_notification', '=', 1)
                                //->where('stock_tolerance.notify','=',1)
                                ->whereIn('notification.type', array('stocks'))
                                ->whereRaw('notification.created_date > (UNIX_TIMESTAMP(now()) -  24*60*60)')
                                ->select(array('notification.id','notification.created_date', 'symbol.name','symbol.code', 'subscribed_stock_info.PercentChange', 'notification.type', 'notification.unseen_notification'))
                                ->groupBy('symbol.name')
                                ->get();

        foreach ($stockNotification as $stock) {

            $arrayStockNotification[] = array(
                'notificationID' => $stock->id,
                'unseen' => $stock->unseen_notification,
                'stocksNotificationText' => ltrim(rtrim($stock->name)),
                'percentage' => round($stock->PercentChange, 2),
                'updated' => time() - $stock->created_date,
                'code' => $stock->code,
                'type' => $stock->type
            );
        }

        return array_reverse($arrayStockNotification);
    }

    // Indicator Notification insertion make query shorter.
    public function insertNotification()
    {
        $userId = Authorizer::getResourceOwnerId();
        $notInPercent = DB::table('notification')
                        ->leftJoin('indicator', 'notification.object_id', '=', 'indicator.id')
                        ->where('notification.created_date', '>', 'indicator.last_updated_date')
                        ->where('notification.subject_id', '=', $userId)
                        ->where('notification.actor_id', '=', 0)
                        ->where('notification.type', '=', array('indicators'))
                        ->groupBy('notification.object_id')
                        ->select('notification.object_id')
                        ->get();
        $arrayNotInPercent = array('');

        if(!empty($notInPercent)){

            foreach ($notInPercent as $indicator) {

                array_push($arrayNotInPercent, $indicator->object_id);
            }
        }

        $indicators = DB::table('indicator')
                        ->leftJoin('indicator_tolerance', 'indicator.id', '=', 'indicator_tolerance.indicator_id')
                        ->where('indicator_tolerance.user_id','=',$userId)
                        ->whereIn('indicator.unit', array('percent'))
                        ->whereRaw('indicator_tolerance.percentage_change < ABS((indicator.last_value - indicator.previous_value))')
                        ->whereNotIn('indicator.id', $arrayNotInPercent)
                        ->select(array('indicator.name',DB::raw('(indicator.last_value - indicator.previous_value) as percent '),'indicator.id as indId ','indicator_tolerance.notify','indicator_tolerance.sms','indicator_tolerance.email'))
                        ->get();

        $indicators2 = DB::table('indicator')
                        ->leftJoin('indicator_tolerance', 'indicator.id', '=', 'indicator_tolerance.indicator_id')
                        ->where('indicator_tolerance.user_id','=',$userId)
                        ->whereNotIn('indicator.unit', array('percent'))
                        ->whereRaw('indicator_tolerance.percentage_change < ABS((indicator.last_value - indicator.previous_value)/(indicator.previous_value))*100')
                        ->whereNotIn('indicator.id', $arrayNotInPercent)
                        ->select(array( 'indicator.name',DB::raw('(((indicator.last_value - indicator.previous_value)/(indicator.previous_value))*100.0) as percent '),'indicator.id as indId ','indicator_tolerance.notify','indicator_tolerance.sms','indicator_tolerance.email'))
                        ->get();

        $user = User::find($userId);

        if(!empty($indicator)){

            foreach ($indicators as $item) {

                if($item->notify == 1 ){

                    DB::table('notification')->insert(
                        array('actor_id' => 0, 'subject_id' => $userId, 'object_id' => $item->indId, 'type' => 'indicators', 'created_date' => DB::raw('UNIX_TIMESTAMP(now())'), 'unseen_notification' => 1, 'unseen_toaster' => 1, 'unseen_notification_count' => 1)
                    );
                }

                if($item->sms == 1 ){

                    $sms = new Sms;
                    $sms->message = $item->name.' Changed By '.$item->percent;
                    $sms->mobile_number = $user->phone;
                    $sms->for_id = $user->id;
                    $sms->receiver_type_id = 3;

                    $sms->save();
                }

                if($item->email == 1){

                    $email = new Email;
                    $email->subject = 'Hello';
                    $email->message = $item->name.' Changed By '.$item->percent;
                    $email->email_id = $user->email;
                    $email->for_id = $user->id;
                    $email->receiver_type_id = 3;
                    $email->save();
                }
            }
        }

        if(!empty($indicators2)){

            foreach ($indicators2 as $item) {

                if($item->notify == 1 ){

                    DB::table('notification')->insert(
                        array('actor_id' => 0, 'subject_id' => $userId, 'object_id' => $item->indId, 'type' => 'indicators', 'created_date' => DB::raw('UNIX_TIMESTAMP(now())'), 'unseen_notification' => 1, 'unseen_toaster' => 1, 'unseen_notification_count' => 1)
                    );
                }

                if($item->sms == 1 ){

                    $sms = new Sms;
                    $sms->message = $item->name.' Changed By '.$item->percent;
                    $sms->mobile_number = $user->phone;
                    $sms->for_id = $user->id;
                    $sms->receiver_type_id = 3;


                    $sms->save();
                }

                if($item->email == 1){

                    $email = new Email;
                    $email->subject = 'Indicator ';
                    $email->message = $item->name.' Changed By '.$item->percent;
                    $email->email_id = $user->email;
                    $email->for_id = $user->id;
                    $email->receiver_type_id = 3;
                    $email->save();
                }
            }
        }

        //return "Success"; // $arrayNotInPercent;
    }

    public function insertStockNotification()
    {
        $userId = Authorizer::getResourceOwnerId();
        $symbolExist = DB::table('notification')
                           ->leftJoin('symbol','symbol.id','=','notification.object_id')
                           ->leftJoin('subscribed_stock_info','subscribed_stock_info.symbol_id','=','symbol.id')
                           ->where('notification.subject_id','=',$userId)
                           ->where('notification.actor_id','=',0)
                           ->whereIn('notification.type',array('stocks'))
                           ->where('notification.created_date','>','subscribed_stock_info.lastUpdatedTime')
                           ->select('notification.object_id')
                           ->groupBy('notification.object_id')
                           ->get();

        $arrayExistSymbol = array('');

        if(!empty($symbolExist)){

            foreach ($symbolExist as $symbol) {

                array_push($arrayExistSymbol, $symbol->object_id);
            }
        }


        $symbolId = DB::table('subscribed_stock_info')
                    ->leftJoin('symbol', 'symbol.id', '=', 'subscribed_stock_info.symbol_id')
                    ->leftJoin('stock_tolerance', 'symbol.id', '=', 'stock_tolerance.symbol_id')
                    ->where('stock_tolerance.user_id','=',$userId)
                    //->whereRaw('stock_tolerance.percentage_change < ABS(CAST(REPLACE(REPLACE(subscribed_stock_info.PercentChange,'+'," "),'%'," ") AS DECIMAL(10,2)))')
                    ->whereRaw('stock_tolerance.percentage_change < ABS(subscribed_stock_info.PercentChange)')
                    ->whereNotIn('symbol.id', $arrayExistSymbol)
                    ->orderBy('symbol.id','desc')
                    ->select(array('symbol.id', 'symbol.name', 'subscribed_stock_info.PercentChange', 'stock_tolerance.notify' , 'stock_tolerance.sms', 'stock_tolerance.email'))
                    ->get();

        $user = User::find($userId);

        if(!empty($symbolId)){

            foreach ($symbolId as $item) {

                if($item->notify == 1 ){

                    DB::table('notification')->insert(
                        array('actor_id' => 0, 'subject_id' => $userId, 'object_id' => $item->id, 'type' => 'stocks', 'created_date' => DB::raw('UNIX_TIMESTAMP(now())'), 'unseen_notification' => 1, 'unseen_toaster' => 1, 'unseen_notification_count' => 1)
                    );
                }

                if($item->sms == 1 ){

                    $sms = new Sms;
                    $sms->message = $item->name.' Changed By '.$item->PercentChange;
                    $sms->mobile_number = $user->phone;
                    $sms->for_id = $user->id;
                    $sms->receiver_type_id = 3;

                    $sms->save();
                }

                if($item->email == 1 ){

                    $email = new Email;
                    $email->subject = 'Stock ';
                    $email->message = $item->name.' Changed By '.$item->percent;
                    $email->email_id = $user->email;
                    $email->for_id = $user->id;
                    $email->receiver_type_id = 3;
                    $email->save();
                }
            }
        }

        //return $symbolId;
    }

    // Check events insertion ..... getting inserted periodically

    public function insertEventsNotification()
    {
        $userId = Authorizer::getResourceOwnerId();
        $eventIdOnNotification = DB::table('notification')
                                    ->leftJoin('event', 'event.id', '=', 'notification.object_id')
                                    ->whereRaw('notification.created_date >  (UNIX_TIMESTAMP(now()) - (60*60))')
                                    ->where('notification.subject_id', '=', $userId)
                                    ->where('notification.actor_id', '=', 0)
                                    ->whereIn('notification.type', array('events'))
                                    ->groupBy('notification.object_id')
                                    ->select('notification.object_id')
                                    ->get();

        $arrayNotIn = array();

        if(!empty($eventIdOnNotification)){

            foreach ($eventIdOnNotification as $item) {

                array_push($arrayNotIn, $item->object_id);
            }
        }

        $eventId = DB::table('event')
                    ->whereRaw('event.date_time > UNIX_TIMESTAMP(now()) and event.date_time < (UNIX_TIMESTAMP(now()) + (60*60))')
                    ->leftJoin('indicator','indicator.name','=','event.indicator_name')
                    ->leftJoin('event_manager','event_manager.indicator_id','=','indicator.id')
                    ->whereNotNull('indicator.name')
                    ->whereNotNull('event_manager.id')
                    //->where('event_manager.notify','=',1)
                    ->where('event_manager.user_id','=',$userId)
                    ->whereNotIn('event.id', $arrayNotIn)
                    ->orderBy('event.id','desc')
                    ->select(array('event.id','event.indicator_name as name', 'event.importance','event_manager.notify','event_manager.sms','event_manager.email'))
                    ->get();

        $user = User::find($userId);

        foreach ($eventId as $item) {

            if($item->notify == 1 ){

                DB::table('notification')->insert(
                    array('actor_id' => 0, 'subject_id' => $userId, 'object_id' => $item->id, 'type' => 'events', 'created_date' => DB::raw('UNIX_TIMESTAMP(now())'), 'unseen_notification' => 1, 'unseen_toaster' => 1, 'unseen_notification_count' => 1)
                );
            }
            if($item->sms == 1 ){

                $sms = new Sms;
                $sms->message = $item->name.' event is about to begin in one hour - '.$item->importance;
                $sms->mobile_number = $user->phone;
                $sms->for_id = $user->id;
                $sms->receiver_type_id = 3;

                $sms->save();
            }

            if($item->email == 1 ){

                $email = new Email;
                $email->subject = 'Event ';
                $email->message = $item->name.' event is about to begin in one hour - '.$item->importance;
                $email->email_id = $user->email;
                $email->for_id = $user->id;
                $email->receiver_type_id = 3;
                $email->save();
            }
        }

        //return $eventId;
    }


    public function insertTwitterNotification()
    {
        $userId = Authorizer::getResourceOwnerId();
        $getALLNotifySymbols = DB::table('twitter_velocity_tolerance')
                                ->leftJoin('symbol', 'symbol.id', '=', 'twitter_velocity_tolerance.symbol_id')
                                //->where('twitter_velocity_tolerance.notify', '=', 1)
                                ->where('twitter_velocity_tolerance.user_id', '=', $userId)
                                ->select(array('symbol.id','symbol.code'))
                                ->get();

        foreach ($getALLNotifySymbols as $item) {

            $symbolId = $item->id;
            $symbolCode = $item->code;

            $request =  Request::create('/api/v1/twitterVelocity/'.$symbolCode.'/tot', 'GET', array());
            $response = Route::dispatch($request);
            $content = $response->getContent();
            $json = json_decode($content);

            //$json = '[[1416546027000,3.6443148688047],[1416459627000,1.4443148688047],[1416373227000,0],[1416286827000,0.11043378390317]]';

            $obj = $json;

            if ($obj[1][1] != 0) {

                $velocityDifferentPercentage = ($obj[0][1] - $obj[1][1]) * 100 / $obj[1][1];
                $absVelocity = abs($velocityDifferentPercentage);

                $getNotifyId = DB::table('symbol')
                                //->leftJoin('symbol', 'symbol.id', '=', 'subscribed_stock_info.symbol_id')
                                ->leftJoin('twitter_velocity_tolerance', 'twitter_velocity_tolerance.symbol_id', '=', 'symbol.id')
                                ->where('twitter_velocity_tolerance.percentage_change', '<', $absVelocity)
                                ->where('twitter_velocity_tolerance.last_percent', '<>', $velocityDifferentPercentage)
                                ->where(DB::raw($absVelocity.' > 0'))
                                ->where('twitter_velocity_tolerance.user_id','=',$userId)
                                ->where('symbol.id', '=', $symbolId)
                                ->select(array('symbol.id','symbol.code','symbol.name','twitter_velocity_tolerance.notify','twitter_velocity_tolerance.sms','twitter_velocity_tolerance.email'))
                                ->get();

                $idAlreadyInserted = DB::table('notification')
                                        ->leftJoin('symbol','symbol.id','=','notification.object_id')
                                        //->leftJoin('subscribed_stock_info','symbol.id','=','subscribed_stock_info.symbol_id')
                                        ->where('notification.subject_id','=',$userId)
                                        ->where('notification.actor_id','=',0)
                                        ->whereIn('notification.type',array('social'))
                                        ->where('notification.object_id','=',$symbolId)
                                        //->whereRaw('notification.created_date > (UNIX_TIMESTAMP() - (60*60*24))')
                                        ->groupBy('notification.object_id')
                                        ->select('notification.object_id')
                                        ->get();

                //echo getNewsVelocityPercentageChange($symbolCode);

                $user = User::find($userId);

                foreach($getNotifyId as $item){

                    if(empty($idAlreadyInserted[0]->object_id)){

                        if($item->notify == 1 ){

                            DB::table('notification')->insert(
                                array('actor_id' => 0, 'subject_id' => $userId, 'object_id' => $item->id, 'type' => 'social', 'created_date' => DB::raw('UNIX_TIMESTAMP(now())'), 'unseen_notification' => 1, 'unseen_toaster' => 1, 'unseen_notification_count' => 1)
                            );

                            DB::table('twitter_velocity_tolerance')
                                ->where('twitter_velocity_tolerance.user_id','=', $userId)
                                ->where('twitter_velocity_tolerance.symbol_id ','=', $item->id)
                                ->update(array('twitter_velocity_tolerance.last_percent' => $velocityDifferentPercentage));
                        }

                        if($item->sms == 1 ){

                            $sms = new Sms;
                            $velocityPercentage = round(getTwitterVelocityPercentageChange($item->code),2);
                            $sms->message = $item->name.' twitter changed by '.$velocityPercentage.'%';
                            $sms->mobile_number = $user->phone;
                            $sms->for_id = $user->id;
                            $sms->receiver_type_id = 3;

                            $sms->save();
                        }

                        if($item->email == 1 ){

                            $email = new Email;
                            $email->subject = 'Twitter ';
                            $velocityPercentage = round(getTwitterVelocityPercentageChange($item->code),2);
                            $email->message = $item->name.' twitter changed by '.$velocityPercentage.'%';
                            $email->email_id = $user->email;
                            $email->for_id = $user->id;
                            $email->receiver_type_id = 3;
                            $email->save();
                        }
                    }
                }

            }
        }

    }


    public function insertNewsNotification(){

        $userId = Authorizer::getResourceOwnerId();

        $getALLNotifySymbols = DB::table('news_velocity_tolerance')
                                ->leftJoin('symbol', 'symbol.id', '=', 'news_velocity_tolerance.symbol_id')
                                //->where('news_velocity_tolerance.notify', '=', 1)
                                ->where('news_velocity_tolerance.user_id', '=', $userId)
                                ->select(array('symbol.code','symbol.id'))
                                ->get();



        foreach ($getALLNotifySymbols as $item) {



            $symbolId = $item->id;
            $symbolCode = $item->code;
            $request = Request::create('/api/v1/newsVelocity/'.$symbolCode.'/tot', 'GET', array());
            $response = Route::dispatch($request);
            $content = $response->getContent();
            $json = json_decode($content);

            $obj = $json;

            if ($obj[1][1] != 0) {

                $velocityDifferentPercentage = ($obj[0][1] - $obj[1][1]) * 100 / $obj[1][1];
                $absVelocity = abs($velocityDifferentPercentage);

                $getNotifyId = DB::table('symbol')
                    //->leftJoin('symbol', 'symbol.id', '=', 'subscribed_stock_info.symbol_id')
                    ->leftJoin('news_velocity_tolerance', 'news_velocity_tolerance.symbol_id', '=', 'symbol.id')
                    ->where('news_velocity_tolerance.percentage_change', '<', $absVelocity)
                    ->where('news_velocity_tolerance.last_percent', '<>', $velocityDifferentPercentage)
                    ->where(DB::raw($absVelocity.' > 0'))
                    ->where('news_velocity_tolerance.user_id','=',$userId)
                    ->where('symbol.id', '=', $symbolId)
                    ->select(array('symbol.id','symbol.code','symbol.name','twitter_velocity_tolerance.notify','news_velocity_tolerance.sms','news_velocity_tolerance.email'))
                    ->get();

                $idAlreadyInserted = DB::table('notification')
                    ->leftJoin('symbol','symbol.id','=','notification.object_id')
                    //->leftJoin('subscribed_stock_info','symbol.id','=','subscribed_stock_info.symbol_id')
                    ->where('notification.subject_id','=',$userId)
                    ->where('notification.actor_id','=',0)
                    ->whereIn('notification.type',array('news'))
                    ->where('notification.object_id','=',$symbolId)
                    //->whereRaw('notification.created_date > (UNIX_TIMESTAMP() - (60*60*24))')
                    ->groupBy('notification.object_id')
                    ->select('notification.object_id')
                    ->get();

                $user = User::find($userId);

                foreach($getNotifyId as $item){

                    if(empty($idAlreadyInserted[0]->object_id)){

                        if($item->notify == 1 ){

                            DB::table('notification')->insert(
                                array('actor_id' => 0, 'subject_id' => $userId, 'object_id' => $item->id, 'type' => 'news', 'created_date' => DB::raw('UNIX_TIMESTAMP(now())'), 'unseen_notification' => 1, 'unseen_toaster' => 1, 'unseen_notification_count' => 1)
                            );

                            DB::table('news_velocity_tolerance')
                                ->where('news_velocity_tolerance.user_id','=', $userId)
                                ->where('news_velocity_tolerance.symbol_id ','=', $item->id)
                                ->update(array('news_velocity_tolerance.last_percent' => $velocityDifferentPercentage));
                        }
                        if($item->sms == 1 ){

                            $sms = new Sms;
                            $velocityPercentage = round(getNewsVelocityPercentageChange($item->code),2);
                            $sms->message = $item->name.' news changed by '.$velocityPercentage.'%';
                            $sms->mobile_number = $user->phone;
                            $sms->for_id = $user->id;
                            $sms->receiver_type_id = 3;

                            $sms->save();
                        }

                        if($item->email == 1 ){

                            $email = new Email;
                            $email->subject = 'News ';
                            $velocityPercentage = round(getNewsVelocityPercentageChange($item->code),2);
                            $email->message = $item->name.' news changed by '.$velocityPercentage.'%';
                            $email->email_id = $user->email;
                            $email->for_id = $user->id;
                            $email->receiver_type_id = 3;
                            $email->save();
                        }
                    }
                }

            }

            //return $getALLNotifySymbols;
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }


}

function getTwitterVelocityPercentageChange($code){


    $request = Request::create('/api/v1/twitterVelocity/'.$code.'/tot', 'GET', array());
    $response = Route::dispatch($request);
    $content = $response->getContent();
    $obj = json_decode($content, true);

    if ($obj[1][1] != 0) {

        return $velocityDifferentPercentage = (($obj[0][1]) - ($obj[1][1])) * 100 / $obj[1][1];
    }else{
        return 0;
    }
}

function getNewsVelocityPercentageChange($code){


    $request = Request::create('/api/v1/newsVelocity/'.$code.'/tot', 'GET', array());
    $response = Route::dispatch($request);
    $content = $response->getContent();
    $obj = json_decode($content, true);

    if ($obj[1][1] != 0) {

        return $velocityDifferentPercentage = (($obj[0][1]) - ($obj[1][1])) * 100 / $obj[1][1];
    }else{
        return 0;
    }
}
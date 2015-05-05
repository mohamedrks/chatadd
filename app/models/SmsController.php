<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 2/19/15
 * Time: 12:32 PM
 */

class SmsController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {
        return Sms::all();
    }

    public function getClientSmsByUser(){

        $userId = Authorizer::getResourceOwnerId();
        $sms = DB::table('client_user')
                  ->leftJoin('sms','sms.for_id','=','client_user.client_id')
                  ->leftJoin('client','client.id','=','client_user.client_id')
                  ->where('user_id','=',$userId)
                  ->where('sms.receiver_type_id','=',1)
                  ->select(array('sms.id','sms.message','sms.for_id',DB::raw('UNIX_TIMESTAMP(sms.created_at) as created_date'),'client.user_name'))
                  ->get();

        return $sms;
    }

    public function getAccountSmsByUser(){

        $userId = Authorizer::getResourceOwnerId();
        $sms = DB::table('sms')
                ->leftJoin('account_portfolio','account_portfolio.account_id','=','sms.for_id')
                ->leftJoin('portfolio','portfolio.id','=','account_portfolio.portfolio_id')
                ->leftJoin('account','account.id','=','account_portfolio.account_id')
                ->where('portfolio.user_id','=',$userId)
                ->where('sms.receiver_type_id','=',2)
                ->select(array('sms.id','sms.message','sms.for_id',DB::raw('UNIX_TIMESTAMP(sms.created_at) as created_date'),'account.account_name'))
                ->get();

        return $sms;
    }

    public function getSmsByReceiverType($receiverTypeId,$forId){

        $sms = Sms::where('for_id','=',$forId)->where('receiver_type_id','=',$receiverTypeId)->select(array('sms.*',DB::raw('UNIX_TIMESTAMP(sms.created_at) as created_date')))->get();

        return $sms;
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
        $rules = array(

            'message' => 'required',
            'for_id' => 'required',
            'mobile_number' => 'required',
            'receiver_type_id' => 'required'
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500);

        } else {
            $sms = new Sms;

            $mobileNumber = Input::get('mobile_number');

            $sms->message = Input::get('message');
            $sms->for_id = Input::get('for_id');
            $sms->mobile_number = Input::get('mobile_number');
            $sms->receiver_type_id = Input::get('receiver_type_id');

            switch ($sms->receiver_type_id) {
                case 1:
                    $receiver = Client::find($sms->for_id);
                    break;
                case 2:
                    $receiver = Account::find($sms->for_id);
                    break;
            }

            //$receiver->mobile_number;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "http://101.0.70.226/diamatic/?msgtype=bulk&cli=".$mobileNumber."&msg=".urlencode($sms->message)."&originator=MARKETIQ");
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $delivery = curl_exec($ch);

            if (strpos($delivery,'Message Receive correctly') == true) {

                $orderId = explode('=', $delivery);

                $sms->msg_received = 1;
                $sms->order_id = $orderId[1];
            }else{

                $sms->msg_received = 0;
                $sms->order_id = 0;

            }
            curl_close($ch);

            $sms->save();
        }
    }



    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        return Sms::find($id);
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

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {

    }


}


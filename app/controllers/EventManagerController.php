<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 2/12/15
 * Time: 10:20 AM
 */

class EventManagerController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {
        $eventManager = EventManager::with('indicator')->get();
        return $eventManager->toJson();
    }

    public function getEventStockByUser()
    {
        $id = Authorizer::getResourceOwnerId();

        $eventManager = DB::table('event_manager')
                        ->leftJoin('indicator', 'indicator.id', '=', 'event_manager.indicator_id')
                        ->where('event_manager.user_id', '=', $id)
                        ->select(array('event_manager.*', 'indicator.name' , 'indicator.category'))
                        ->get();

        return $eventManager;
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

            'indicator_id' => 'required|numeric',
            'user_id' => 'required|numeric'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return $validator->messages()->toJson();

        } else {

            $_userId = Input::get('user_id'); // rmv
            $_indicatorId = Input::get('indicator_id');

            $existingSymbol = EventManager::where('indicator_id', '=', $_indicatorId)->where('user_id', '=', $_userId)->get();

            if (count($existingSymbol) == 0 ){

                $eventManager = new EventManager;

                $eventManager->notify = 1;
                $eventManager->sms = 1;

                $indicator = Indicator::find($_indicatorId);
                $eventManager->indicator()->associate($indicator);

                $user = User::find($_userId);
                $eventManager->user()->associate($user);

                $eventManager->save();

                return Response::json(array('error' => false, 'eventManager' => $eventManager), 200);

            } else {

                return Response::json(array('error' => false, 'eventManager' => 'Symbol Existing already '), 200);
            }


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
        $eventManager = EventManager::with('indicator')->find($id);
        return $eventManager->toJson();
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
        $rules = array(

            'notify' => 'required|numeric',
            'sms' => 'required|numeric',
            'email' => 'required|numeric'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return $validator->messages()->toJson();

        } else {

            $eventManager = EventManager::find($id);
            $eventManager->notify = Input::get('notify');
            $eventManager->sms = Input::get('sms');
            $eventManager->email = Input::get('email');
            $eventManager->save();
        }
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
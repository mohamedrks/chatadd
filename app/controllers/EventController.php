<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 12/11/14
 * Time: 5:44 PM
 */


class EventController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response

     */


    public function index()
    {
        $results = EventCalendar::all();

        return $results->toJson();
    }

    public function getEventByImportance($importance){

        $eventsArray = array();
        $important = $importance.'%';

        $events = EventCalendar::where('importance','LIKE',$important)->get();

        foreach($events as $item ){

            $e = array();
            $e['id'] = $item->id;
            $e['title'] = $item->indicator_name;
            $time = $item->date_time;
            $e['start'] = date("Y-m-d", $time) . 'T' . date("H:i:s", $time) . '+00:00';
            $e['end'] = date("Y-m-d", $time) . 'T' . date("H:i:s", $time) . '+00:00';
            $e['allDay'] = false;

// Merge the event array into the return array
            array_push($eventsArray, $e);
        }

        return json_encode($eventsArray);
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
            'indicator_name' => 'required',
            'date_time' => 'required',
            'importance' => 'required',
            'country' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500);

        } else {
            $event = new EventCalendar;

            $event->indicator_name = Input::get('indicator_name');
            $event->date_time = strtotime(Input::get('date_time'));
            $event->importance = Input::get('importance');
            $event->country = Input::get('country');

            $event->save();


            return Response::json(array('Success' => true, 'events' => $event ), 200);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $event = EventCalendar::find($id);

        return $event;
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $rules = array(
            'indicator_name' => 'required',
            'date_time' => 'required',
            'importance' => 'required',
            'country' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500);

        } else {

            $event = EventCalendar::find($id);

            $event->indicator_name = Input::get('indicator_name');
            $event->date_time = strtotime(Input::get('date_time'));
            $event->importance = Input::get('importance');
            $event->country = Input::get('country');

            $event->save();

            return Response::json(array('Success' => true, 'events' => $event ), 200);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }


}

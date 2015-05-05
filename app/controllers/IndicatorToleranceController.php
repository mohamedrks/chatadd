<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 12/15/14
 * Time: 12:04 PM
 */



class IndicatorToleranceController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response

     */


    public function index()
    {
        $indicatorTolerance = IndicatorTolerance::with('indicator','user')->get();

        return $indicatorTolerance->toJson();
    }


    public function getIndicatorByUser(){

        $userId = Authorizer::getResourceOwnerId();
        $indicatorTolerance =   DB::table('indicator_tolerance')
                                    ->leftJoin('indicator','indicator.id','=','indicator_tolerance.indicator_id')
                                    ->where('indicator_tolerance.user_id','=',$userId)
                                    ->select(array('indicator_tolerance.*',DB::raw('rtrim(ltrim(indicator.name)) as name '),'indicator.category', DB::raw(' case
                                        when ABS(minimum_range) > ABS(maximum_range)

                                            THEN ROUND((((ABS(minimum_range) + ROUND(ABS(ABS(minimum_range) - ABS(maximum_range))*0.2,2))/(indicator.last_value))*100),3)
                                            ELSE ROUND((((ABS(maximum_range) + ROUND(ABS(ABS(minimum_range) - ABS(maximum_range))*0.2,2))/(indicator.last_value))*100),3)
                                        END as highestValue
                                        ,

                                            ROUND(ABS(ABS(minimum_range) - ABS(maximum_range)),2) as Ranges,
                                            ROUND(ABS(ABS(minimum_range) - ABS(maximum_range))*0.2,2) as percentAdd
                                        ')))
                                    ->get();

        return $indicatorTolerance ;
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
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $indicatorTolerance = IndicatorTolerance::with('indicator')->find($id);

        return $indicatorTolerance->toJson();
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
            'percentage_change' => 'required|numeric',
            'notify' => 'required|numeric',
            'sms' => 'required|numeric',
            'email' => 'required|numeric'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return $validator->messages()->toJson();

        } else {

            $indicatorTolerance = IndicatorTolerance::find($id);
            $indicatorTolerance->percentage_change = Input::get('percentage_change');
            $indicatorTolerance->notify = Input::get('notify');
            $indicatorTolerance->sms = Input::get('sms');
            $indicatorTolerance->email = Input::get('email');
            $indicatorTolerance->save();
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

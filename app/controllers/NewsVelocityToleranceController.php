<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 2/6/15
 * Time: 9:10 AM
 */

class NewsVelocityToleranceController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {
        $newsVelocityTolerance = NewsVelocityTolerance::with('symbol')->get();
        return $newsVelocityTolerance->toJson();
    }

    public function getNewsStockByUser()
    {
        $userId =  Authorizer::getResourceOwnerId();
        $newsVelocityTolerance = DB::table('news_velocity_tolerance')
            ->leftJoin('symbol', 'symbol.id', '=', 'news_velocity_tolerance.symbol_id')
            ->where('news_velocity_tolerance.user_id', '=', $userId)
            ->select(array('news_velocity_tolerance.*', 'symbol.code'))
            ->get();

        return $newsVelocityTolerance;
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
            'symbol_id' => 'required|numeric',
            'user_id' => 'required|numeric'

        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return $validator->messages()->toJson();

        } else {

            $_userId = Input::get('user_id'); // rmv
            $_symbolId = Input::get('symbol_id');

            $existingSymbol = NewsVelocityTolerance::where('symbol_id', '=', $_symbolId)->where('user_id', '=', $_userId)->get();

            if (count($existingSymbol) == 0 ){

                $newsVelocityTolerance = new NewsVelocityTolerance;

                $newsVelocityTolerance->percentage_change = 5;
                $newsVelocityTolerance->notify = 1;
                $newsVelocityTolerance->sms = 1;

                $symbol = Symbol::find($_symbolId);
                $newsVelocityTolerance->symbol()->associate($symbol);

                $user = User::find($_userId);
                $newsVelocityTolerance->user()->associate($user);

                $newsVelocityTolerance->save();

                return Response::json(array('error' => false, 'stockTolerance' => $newsVelocityTolerance), 200);

            } else {

                return Response::json(array('error' => false, 'stockTolerance' => 'Symbol Existing already '), 200);
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
        $newsVelocityTolerance = NewsVelocityTolerance::with('symbol')->find($id);

        return $newsVelocityTolerance->toJson();
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
            'percentage_change' => 'required|numeric',
            'notify' => 'required|numeric',
            'sms' => 'required|numeric',
            'email' => 'required|numeric'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return $validator->messages()->toJson();

        } else {

            $newsVelocityTolerance = NewsVelocityTolerance::find($id);
            $newsVelocityTolerance->percentage_change = Input::get('percentage_change');
            $newsVelocityTolerance->notify = Input::get('notify');
            $newsVelocityTolerance->sms = Input::get('sms');
            $newsVelocityTolerance->email = Input::get('email');
            $newsVelocityTolerance->save();
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

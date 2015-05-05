<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 2/6/15
 * Time: 9:11 AM
 */

class TwitterVelocityToleranceController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {
        $twitterVelocityTolerance = TwitterVelocityTolerance::with('symbol')->get();
        return $twitterVelocityTolerance->toJson();
    }

    public function getTwitterStockByUser()
    {
        $userId = Authorizer::getResourceOwnerId();
        $twitterVelocityTolerance = DB::table('twitter_velocity_tolerance')
            ->leftJoin('symbol', 'symbol.id', '=', 'twitter_velocity_tolerance.symbol_id')
            ->where('twitter_velocity_tolerance.user_id', '=', $userId)
            ->select(array('twitter_velocity_tolerance.*', 'symbol.code'))
            ->get();

        return $twitterVelocityTolerance;
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

            $existingSymbol = TwitterVelocityTolerance::where('symbol_id', '=', $_symbolId)->where('user_id', '=', $_userId)->get();

            if (count($existingSymbol) == 0 ){

                $twitterVelocityTolerance = new TwitterVelocityTolerance;

                $twitterVelocityTolerance->percentage_change = 5;
                $twitterVelocityTolerance->notify = 1;
                $twitterVelocityTolerance->sms = 1;

                $symbol = Symbol::find($_symbolId);
                $twitterVelocityTolerance->symbol()->associate($symbol);

                $user = User::find($_userId);
                $twitterVelocityTolerance->user()->associate($user);

                $twitterVelocityTolerance->save();

                return Response::json(array('error' => false, 'stockTolerance' => $twitterVelocityTolerance), 200);

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
        $twitterVelocityTolerance = TwitterVelocityTolerance::with('symbol')->find($id);

        return $twitterVelocityTolerance->toJson();
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

            $twitterVelocityTolerance = TwitterVelocityTolerance::find($id);
            $twitterVelocityTolerance->percentage_change = Input::get('percentage_change');
            $twitterVelocityTolerance->notify = Input::get('notify');
            $twitterVelocityTolerance->sms = Input::get('sms');
            $twitterVelocityTolerance->email = Input::get('email');
            $twitterVelocityTolerance->save();
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

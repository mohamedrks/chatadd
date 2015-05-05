<?php

/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 12/11/14
 * Time: 6:30 PM
 */
class StockToleranceController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {
        $stockTolerance = StockTolerance::with('symbol')->get();
        return $stockTolerance->toJson();
    }

    public function getStockByUser()
    {
        $userId = Authorizer::getResourceOwnerId();
        $stockTolerance = DB::table('stock_tolerance')
                            ->leftJoin('symbol', 'symbol.id', '=', 'stock_tolerance.symbol_id')
                            ->where('stock_tolerance.user_id', '=', $userId)
                            ->select(array('stock_tolerance.*', 'symbol.code'))
                            ->get();

        return $stockTolerance;
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

             $existingSymbol = StockTolerance::where('symbol_id', '=', $_symbolId)->where('user_id', '=', $_userId)->get();

            if (count($existingSymbol) == 0 ){

                $stockTolerance = new StockTolerance;

                $stockTolerance->percentage_change = 5;
                $stockTolerance->notify = 1;
                $stockTolerance->sms = 1;

                $symbol = Symbol::find($_symbolId);
                $stockTolerance->symbol()->associate($symbol);

                $user = User::find($_userId);
                $stockTolerance->user()->associate($user);

                $stockTolerance->save();

                return Response::json(array('error' => false, 'stockTolerance' => $stockTolerance), 200);

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
        $stockTolerance = StockTolerance::with('symbol')->find($id);

        return $stockTolerance->toJson();
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

            $stockTolerance = StockTolerance::find($id);
            $stockTolerance->percentage_change = Input::get('percentage_change');
            $stockTolerance->notify = Input::get('notify');
            $stockTolerance->sms = Input::get('sms');
            $stockTolerance->email = Input::get('email');
            $stockTolerance->save();
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

<?php

/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 1/20/15
 * Time: 3:06 PM
 */
class StatsInputController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {
        $stats = StatsInput::with('portfolio')->get();
        return $stats;
    }

    public function getStatsByUser(){

        $userId = Authorizer::getResourceOwnerId();
        $stats = StatsInput::with('portfolio')->where('user_id','=',$userId)->get();
        return $stats;
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
        $con = mysqli_connect(DB_SERVER_IP,DB_SERVER_NAME,DB_SERVER_PASSWORD,DB_SERVER_USER_NAME);

        $rules = array(

            'statsId' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Response::json($validator->messages(), 500);
        } else {

            $id = Input::get('statsId');

            mysqli_query($con, "UPDATE stats_input SET average_div_yield_asx200 =
                ( case
                when  (select avg(yld200) as average from stock_price) > 0.0001 then (select avg(yld200) as average from stock_price)
                else 0
                end
                ) where id = '$id'");

            mysqli_query($con, "UPDATE stats_input SET average_gross_devidend_yield_asx200 =
                (
                case
                when  (select avg(gyld200) as average from stock_price) > 0.0001 then (select avg(gyld200) as average from stock_price)
                else 0
                end
                ) where id = '$id'");

            mysqli_query($con, "UPDATE stats_input SET minimum_ff_devidend_yield_asx200 =
                (
                case input
                when 1 then 18
                when 2 then 19
                when 3 then portfolio_model3_moderate_yield
                when 4 then portfolio_model4_low_yield
                else portfolio_desired_yield
                end
                ) where id = '$id'");

            mysqli_query($con, "UPDATE stats_input SET minimum_gross_yield_asx200 = ( minimum_ff_devidend_yield_asx200 * 1.42857 ) where id = '$id'");

            mysqli_query($con, "UPDATE stats_input SET average_div_yield_asx300 =
                ( case
                when  (select avg(net) as average from stock_price) > 0.0001 then (select avg(net) as average from stock_price)
                else 0
                end
                ) where id = '$id'");

            mysqli_query($con, "UPDATE stats_input SET average_gross_devidend_yield_asx300 =
                (
                case
                when  (select avg(gross) as average from stock_price) > 0.0001 then (select avg(gross) as average from stock_price)
                else 0
                end
                ) where id = '$id'");


            mysqli_query($con, "UPDATE stats_input SET minimum_ff_devidend_yield_asx300 =
                (
                case input
                when 1 then 18
                when 2 then 19
                when 3 then portfolio_model3_moderate_yield
                when 4 then portfolio_model4_low_yield
                else portfolio_desired_yield
                end
                ) where id = '$id'");

            mysqli_query($con, "UPDATE stats_input SET minimum_gross_yield_asx300 = ( minimum_ff_devidend_yield_asx300 * 1.42857 ) where id = '$id'");



            return Response::json(array('error' => false, 'statsInput' => $id ), 200);
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
        $statsInput = StatsInput::find($id);
        return $statsInput;
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

            'user_id' => 'required|numeric',
            'minimum_div_cover_200' => 'required',
            'current_min_capitalization_200' => 'required',
            'minimum_trading_period_200' => 'required',
            'minimum_div_cover_300' => 'required',
            'portfolio_id' => 'required',
            'current_min_capitalisation_300' => 'required',
            'minimum_trading_period_300' => 'required',
            'portfolio_funds_available' => 'required',
            'minimum_capital' => 'required',
            'minimum_pe_ratio' => 'required',
            'portfolio_model1_max_yield' => 'required',
            'portfolio_model2_high_yield' => 'required',
            'portfolio_model3_moderate_yield' => 'required',
            'portfolio_model4_low_yield' => 'required',
            //'input' => 'required',
            'portfolio_desired_yield' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Response::json($validator->messages(), 500);
        } else {
            $statsInput = StatsInput::find($id);

            $statsInput->user_id = Input::get('user_id'); // rmv
            $statsInput->minimum_div_cover_200 = Input::get('minimum_div_cover_200');
            $statsInput->current_min_capitalization_200 = Input::get('current_min_capitalization_200');
            $statsInput->minimum_trading_period_200 = Input::get('minimum_trading_period_200');
            $statsInput->minimum_div_cover_300 = Input::get('minimum_div_cover_300');
            $statsInput->current_min_capitalisation_300 = Input::get('current_min_capitalisation_300');
            $statsInput->minimum_trading_period_300 = Input::get('minimum_trading_period_300');
            $statsInput->portfolio_funds_available = Input::get('portfolio_funds_available');
            $statsInput->minimum_capital = Input::get('minimum_capital');
            $statsInput->minimum_pe_ratio = Input::get('minimum_pe_ratio');
            $statsInput->portfolio_model1_max_yield = Input::get('portfolio_model1_max_yield');
            $statsInput->portfolio_model2_high_yield = Input::get('portfolio_model2_high_yield');
            $statsInput->portfolio_model3_moderate_yield = Input::get('portfolio_model3_moderate_yield');
            $statsInput->portfolio_model4_low_yield = Input::get('portfolio_model4_low_yield');
            $statsInput->input = Input::get('input');
            $statsInput->portfolio_desired_yield = Input::get('portfolio_desired_yield');

            $portfolioId = Input::get('portfolio_id');

            $portfolio = Portfolio::find($portfolioId);
            $statsInput->portfolio()->associate($portfolio);

            $statsInput->save();

            return Response::json(array('error' => false, 'statsInput' => $statsInput->toArray()), 200);
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

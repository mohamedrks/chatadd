<?php

/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 12/31/14
 * Time: 10:47 AM
 */


class PortfolioController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function index()
    {

    }

    public function getPortfolioPerformance($portfolioId)
    {
        $weekDays = array('1', '2', '3', '4', '5');

        $minimumDate = DB::table('transaction')
                            ->where('portfolio_id', '=', $portfolioId)
                            ->where('balance_shares', '>', 0)
                            ->select(array(DB::raw('min(date) as minDate')))
                            ->get();

        $begin = ROUND(($minimumDate[0]->minDate) / 1000, 0);
        $startDate = date('Y-m-d', $begin);
        $endDate = date('Y-m-d', strtotime("-1 days"));

        $period = new DatePeriod(DateTime::createFromFormat('Y-m-d', $startDate), new DateInterval('P1D'), DateTime::createFromFormat('Y-m-d', $endDate));

        $transactions = Transaction::where('portfolio_id', '=', $portfolioId)->where('balance_shares', '>', 0)->where('transaction_type', '=', 'buy')->get();
        $transactionSymbols = DB::table('transaction')
                                ->leftJoin('symbol', 'symbol.id', '=', 'transaction.symbol_id')
                                ->where('portfolio_id', '=', $portfolioId)
                                ->where('balance_shares', '>', 0)
                                ->where('transaction_type', '=', 'buy')
                                ->groupBy('symbol_id')
                                ->select(array('symbol.id', 'symbol.code'))
                                ->get();

        $arraySymbolPrices = array();

        foreach ($transactionSymbols as $transaction) {

            $yql = "http://query.yahooapis.com/v1/public/yql?q=select+*+from+yahoo.finance.historicaldata+where+symbol+%3d+%22" . $transaction->code . "%22+and+startDate+%3d+%22" . $startDate . "%22+and+endDate+%3d+%22" . $endDate . "%22&diagnostics=true&env=store://datatables.org/alltableswithkeys&format=json";

            $session = curl_init($yql);
            curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
            $json = curl_exec($session);
            $phpObj = json_decode($json);


            $arraySymbolPrices[] = array(
                'code' => $transaction->code,
                'prices' => $phpObj->query->results
            );
        }

        foreach ($period as $dt) {
            $dayGain = 0.0;

            if (in_array($dt->format('N'), $weekDays)) {

                $day = $dt->format("Y-m-d");
                foreach ($transactions as $trans) {

                    $transactionDate = date('Y-m-d', ROUND($trans->date / 1000, 0));
                    $symbolCode = DB::table('symbol')->where('id', '=', $trans->symbol_id)->select('code')->get();

                    if (strcmp($transactionDate, $day) <= 0) {

                        foreach ($arraySymbolPrices as $arr) {

                            if (strcmp($arr['code'], $symbolCode[0]->code) == 0) {

                                $pr = $arr['prices'];

                                if(!empty($pr)){

                                    foreach ($pr as $dayprice) {

                                        if(!empty($dayprice)){

                                            foreach ($dayprice as $res) {

                                                if (strcmp($res->Date, $day) == 0) {

                                                    $price = $res->Adj_Close;
                                                    $dayGain = $dayGain + ($price - $trans->price) * $trans->balance_shares;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                $arrayDailyGain[] = array(

                    strtotime($day) * 1000,
                    $dayGain

                );
            }
        }
        return $arrayDailyGain;

    }

    public function getPortfolioByUser()
    {

        $userId = Authorizer::getResourceOwnerId();
        $portfolioArray = array();

        $portfolio = Portfolio::where('user_id', '=', $userId)->get(); //with('client')->get();

        if(!empty($portfolio)){

            foreach ($portfolio as $itemPortfolio) {

                $portfolio_value = 0.0;

                //$transaction = Transaction::with('symbol','portfolio')->where('portfolio_id','=',$itemPortfolio->id)->where('user_id','=',$userId)->where('transaction_type','=','Buy')->where('balance_shares','>',0)->get(array('*'));

                $symbol = DB::table('transaction')
                            ->leftJoin('symbol', 'symbol.id', '=', 'transaction.symbol_id')
                            ->leftJoin('subscribed_stock_info', 'symbol.id', '=', 'subscribed_stock_info.symbol_id')
                            ->where('portfolio_id', '=', $itemPortfolio->id)
                            ->where('user_id', '=', $userId)
                            ->where('transaction_type', '=', 'Buy')
                            ->where('balance_shares', '>', 0)
                            ->groupBy('transaction.symbol_id')
                            ->select(array('symbol.code', 'subscribed_stock_info.bidRealtime', 'subscribed_stock_info.askRealtime', 'subscribed_stock_info.ask', 'transaction.balance_shares'))
                            ->get();

                foreach ($symbol as $item) {

                    if ($item->bidRealtime > 0) {
                        $lastprice = floatval($item->bidRealtime);

                    } else if ($item->askRealtime > 0) {
                        $lastprice = floatval($item->askRealtime);

                    } else {
                        $lastprice = floatval($item->ask);

                    }

                    $portfolio_value += $lastprice * $item->balance_shares;
                }

                $portfolioArray[] = array(
                    'id' => $itemPortfolio->id,
                    'name' => $itemPortfolio->name,
                    'created_at' => $itemPortfolio->created_at,
                    'portfolioValue' => $portfolio_value

                );

            }
        }

        return json_encode($portfolioArray);
    }

    public function getPortfolioSearch($req)
    {

        $like = '%' . $req . '%';

        $portfolio = Portfolio::where('name', 'LIKE', $like)->get();

        return $portfolio->toJson();
    }

    public function getPortfolioSearchByClient($clientId,$req){


            $portfolios = DB::table('client_portfolio')
                        ->where('client_id','=',$clientId)
                        ->select(array('portfolio_id'))
                        ->get();

            $arrayIn = array('');

            if(!empty($portfolios)){

                foreach ($portfolios as $item) {

                    array_push($arrayIn, $item->portfolio_id);
                }
            }

            $like = '%'.$req.'%';

            $portfolio = Portfolio::where('name', 'LIKE', $like)->whereNotIn('id',$arrayIn)->get();

            return $portfolio;


    }

    public function getPortfolioSearchByAccount($accountId,$req){


        $portfolios = DB::table('account_portfolio')
                        ->where('account_id','=',$accountId)
                        ->select(array('portfolio_id'))
                        ->get();

        $arrayIn = array('');

        if(!empty($portfolios)){

            foreach ($portfolios as $item) {

                array_push($arrayIn, $item->portfolio_id);
            }
        }

        $like = '%'.$req.'%';

        $portfolio = Portfolio::where('name','LIKE',$like)->whereNotIn('id',$arrayIn)->get();

        return $portfolio;

    }

    public function getAccountByPortfolio($portfolio_id)
    {

        $account_portfolio  = DB::table('account_portfolio')
                                ->leftJoin('account','account.id','=','account_portfolio.account_id')
                                ->where('portfolio_id','=',$portfolio_id)
                                ->select('account.*')
                                ->get();

        $arrayAccount = array(
            'account' => $account_portfolio
        );

        return $arrayAccount;
    }

    public function getPortfolioClient($portfolio_id)
    {

//        $account_portfolio = Portfolio::where('id','=',$portfolio_id)->account->get();
        $account_portfolio = Portfolio::find($portfolio_id)->client;
        return $account_portfolio;
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
            'name' => 'required|unique:portfolio',
            'user_id' => 'required|numeric'

        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {
            $portfolio = new Portfolio;

//           $id = Authorizer::getResourceOwnerId();

            $portfolio->name = Input::get('name');
            $portfolio->user_id = Input::get('user_id'); // rmv

            $portfolio->save();

            $user = Users::find(Input::get('user_id')); // rmv
            $statsInput = new StatsInput;

            $statsInput->portfolio()->associate($portfolio);
            $statsInput->user()->associate($user);
            $statsInput->save();

            //return Response::json(array('Success' => true, 'portfolio' => $portfolio), 200);
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
        $portfolio = Portfolio::find($id);
        return $portfolio->toJson();
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
            'name' => 'required|unique:portfolio,name,' . $id //,
            //'user_id' => 'required|numeric'

        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            $portfolio = Portfolio::find($id);

            $portfolio->name = Input::get('name');
            //$portfolio->user_id = Input::get('user_id');

            $portfolio->save();

            return Response::json(array('Success' => true, 'portfolio' => $portfolio->toArray()), 200);
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
        $portfolio = Portfolio::find($id);
        $portfolio->delete();

        $stats = Stats::where('portfolio_id', '=', $id)->first();
        $stats->delete();
    }


}

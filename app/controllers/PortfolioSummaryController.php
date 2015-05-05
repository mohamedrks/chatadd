<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 3/6/15
 * Time: 9:59 AM
 */


class PortfolioSummaryController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function index(){


    }


    public function portfolioSummaryPDF($portfolioId)
    {
        $inceptiondate = date('l jS \of F Y ',time());

        $sales = DB::table('transaction as tr1')
                    ->leftJoin('transaction as tr2','tr1.parent_transaction','=','tr2.id')
                    ->where('tr1.transaction_type','=','sell')
                    ->where('tr1.portfolio_id','=',$portfolioId)
                    ->select('*')
                    ->get();

        $purchaseAndSales = DB::table('transaction as tr1')
                            ->leftJoin('transaction as tr2','tr1.parent_transaction','=','tr2.id')
                            ->where('tr1.transaction_type','=','sell')
                            ->where('tr1.portfolio_id','=',$portfolioId)
                            ->select(array(DB::raw('sum(tr1.shares*tr2.price) as PurchaseFund, sum(tr1.shares*tr1.price) as TotalSales , sum(tr1.shares*(tr1.price - tr2.price)) as ProfitOrLoss')))
                            ->get();


        $currentSituation = DB::table('transaction as tr1')
                            ->where('tr1.transaction_type','=','buy')
                            ->where('tr1.portfolio_id','=',$portfolioId)
                            ->select(array(DB::raw('sum(tr1.balance_shares * tr1.balance_shares) as totalPurchasesWithReinvestment')))
                            ->get();

        $currentValueOfTheShares = $currentSituation[0]->totalPurchasesWithReinvestment;
        $cashHoldingsInTheAccount = 0.00;
        $totalPortfolioValue = $cashHoldingsInTheAccount + $currentValueOfTheShares;

        $profitOrLossToDateBuy = DB::table('transaction as tr')
                                    ->leftJoin('subscribed_stock_info as si','si.symbol_id','=','tr.symbol_id')
                                    ->where('tr.transaction_type','=','buy')
                                    ->where('tr.portfolio_id','=',$portfolioId)
                                    ->select(array(DB::raw('sum(tr.balance_shares*si.bidRealtime) as totalProfitOrLossToDate')))
                                    ->get();


        $totalProfitOrLossToDateSell = DB::table('transaction as tr')
                                        ->where('tr.transaction_type','=','sell')
                                        ->where('tr.portfolio_id','=',$portfolioId)
                                        ->select(array(DB::raw('sum(tr.shares * tr.price) as totalProfitOrLossToDate')))
                                        ->get();



        $totalProfitOrLossToDate = $profitOrLossToDateBuy[0]->totalProfitOrLossToDate + $totalProfitOrLossToDateSell[0]->totalProfitOrLossToDate;

        $currentPortfolioChange  = $totalProfitOrLossToDate/$currentSituation[0]->totalPurchasesWithReinvestment;


        $amountApproved = 1000000.00;
        $amountDrawn = 127500.00;
        $equityStr = "MARGIN LOAN";
        $interestRate = 7.00;

        $currentLVR = ($totalPortfolioValue >= 0.001 )  ?  $amountDrawn/$totalPortfolioValue : 0.00 ;

        $ownersEquity = (strcmp($equityStr,'MARGIN LOAN') == 0 )  ?  $totalPortfolioValue - $amountDrawn : 0.00 ;
        $lvr = (strcmp($equityStr,'MARGIN LOAN') == 0 )  ?  $amountDrawn/$totalPortfolioValue : 0.00 ;

        $netDividentYield = 13595.00; // possibly can calculate
        $netDividentYieldPercentage = 7.08;
        $grossDividentYield = 19012;  // can calculate
        $grossDividentYieldPercentage = 9.90;


        $currentNetDevidentYieldForCurrentPrice = 4.85;
        $currentGrossDevidentYieldForCurrentPrice = 6.79;

        $increaseInGrossIncome = 4772;
        $incresedDevidentYield = 68.72;

        $estimatedAnualIncome = $netDividentYield;
        $estinatedTaxationCredits = 5417;

        $averageImputationCredits = 96.99;

        $arr = array(
            'inceptionDate' => $inceptiondate,
            'portfolio' => $portfolioId,
            'purchaseFund' => $purchaseAndSales[0]->PurchaseFund,
            'totalSales' => $purchaseAndSales[0]->TotalSales,
            'profitOrLoss' => $purchaseAndSales[0]->ProfitOrLoss,
            'totalPurchasesWithReinvestment' => $currentValueOfTheShares,
            'currentValueOfShares' => $currentValueOfTheShares,
            'cashHoldingInTheAccount' => $cashHoldingsInTheAccount,
            'totalPortfolioValue' => $totalPortfolioValue,

            'totalProfitOrLossToDate' => ROUND($totalProfitOrLossToDate,3),
            'capitalProfitOrLoss' => ROUND($totalProfitOrLossToDate,3),
            'currentPortfolioChange' => ROUND($currentPortfolioChange,5),
            'amountApproved' => $amountApproved,
            'amountDrawn' => $amountDrawn,
            'ownersEquity' => $ownersEquity,
            'interestRate' => $interestRate,
            'lvr' => ROUND($lvr,4),

            'netDividentYield' => $netDividentYield,
            'netDividentYieldPercentage' => $netDividentYieldPercentage,
            'grossDividentYield' => $grossDividentYield,
            'grossDividentYieldPercentage' => $grossDividentYieldPercentage,

            'currentNetDevidentYieldForCurrentPrice' =>$currentNetDevidentYieldForCurrentPrice,
            'currentGrossDevidentYieldForCurrentPrice' => $currentGrossDevidentYieldForCurrentPrice,

            'increaseInGrossIncome' => $increaseInGrossIncome,
            'incresedDevidentYield' => $incresedDevidentYield,

            'estimatedAnualDevidentIncome' => $estimatedAnualIncome,
            'estinatedTaxationCredits' => $estinatedTaxationCredits,
            'averageImputationCredits' => $averageImputationCredits

        );

        $sales = DB::table('transaction as tr1')
                        ->leftJoin('transaction as tr2','tr1.parent_transaction','=','tr2.id')
                        ->leftJoin('symbol as s','s.id','=','tr1.symbol_id')
                        ->leftJoin('account_portfolio as ap','ap.portfolio_id','=','tr1.portfolio_id')
                        ->where('tr1.transaction_type','=','sell')
                        ->where('tr1.portfolio_id','=',$portfolioId)
                        ->whereNotNull('tr2.price')
                        ->groupBy('s.code')
                        ->select(array('tr1.created_at','s.code','tr1.transaction_type',DB::raw('sum(tr1.shares) as Quantity ,avg(tr1.price) as AveragePrice ,tr1.shares*tr2.price as PurchaseCost,tr1.shares*tr1.price as NetProceeds,tr1.shares*(tr1.price - tr2.price) as ProfitOrLoss')))
                        ->get();

        $totalNetProceeds  = 0.0;
        $totalPurchaseCost = 0.0;
        $totalProfitOrLoss = 0.0;

        $portfolio = Portfolio::find($portfolioId);


        foreach($sales as $item ){

            $totalNetProceeds += $item->NetProceeds;
            $totalPurchaseCost += $item->PurchaseCost;
            $totalProfitOrLoss += $item->ProfitOrLoss;

        }

        $arrySales = array(
            'sales' => $sales,
            'portfolio' => $portfolio,
            'totalNetProceeds' => $totalNetProceeds,
            'totalPurchaseCost' => $totalPurchaseCost,
            'totalProfitOrLoss' => $totalProfitOrLoss
        );

        $currentPortfolio = DB::table('transaction as tr1')
                                ->leftJoin('symbol as s','s.id','=','tr1.symbol_id')
                                ->leftJoin('stock_price as sp','sp.symbol_id','=','s.id')
                                ->where('tr1.transaction_type','=','buy')
                                ->where('tr1.portfolio_id','=',$portfolioId)
                                ->where('tr1.balance_shares','>',0)
                                ->select(array('s.code','s.name','tr1.balance_shares','sp.last_val as closingPrice','sp.div_val as NetDiv','sp.franked',DB::raw(' tr1.balance_shares * sp.last_val as MarketValue,sp.div_val /sp.last_val as DivYield ,tr1.balance_shares*sp.div_val as AnnualIncome ,( (tr1.balance_shares*sp.gross ) - (tr1.balance_shares*sp.div_val) ) as FrankedCredits , ((tr1.balance_shares*sp.div_val ) + ( (tr1.balance_shares*sp.gross ) - (tr1.balance_shares*sp.div_val))) as GrossIncome , sp.gross/sp.last_val as CostBaseGrossYield')))
                                ->get();

        $portfolioValue = DB::table('transaction as tr1')
                            ->leftJoin('symbol as s','s.id','=','tr1.symbol_id')
                            ->leftJoin('stock_price as sp','sp.symbol_id','=','s.id')
                            ->where('tr1.transaction_type','=','buy')
                            ->where('tr1.portfolio_id','=',$portfolioId)
                            ->where('tr1.balance_shares','>',0)
                            ->select(array(DB::raw(' sum(tr1.balance_shares * sp.last_val) as totalPortfolioValue ')))
                            ->get();

        $arraycurrentPortfolio = array(
            'currentPortfolio' => $currentPortfolio,
            'portfolioValue'  => $portfolioValue,
            'portfolio' => $portfolio
        );

        $mortgageObj = Mortgage::where('portfolio_id','=',$portfolioId)->first();
        $mortgageId = (!empty($mortgageObj)) ? $mortgageObj->id : 0;
        $debits = 0.0;
        $credits = 0.0;
        $open = 0.0;
        $close = 0.0;

        $purchase = 0.0;
        $sell = 0.0;
        $interest  = 0.0;
        $payment = 0.0;
        $withdrawal = 0.0;
        $dividend = 0.0;

        $portfolio = Portfolio::find($portfolioId);

        $mortgage = DB::table('margin_loan')
                        ->leftJoin('margin_loan_type','margin_loan_type.id','=','margin_loan.marginloantype_id')
                        ->where('margin_loan.mortgage_id','=',$mortgageId)
                        ->groupBy('margin_loan.marginloantype_id')
                        ->select(array(DB::raw('sum(debit) as debit , sum(credit) as credit, margin_loan_type.name  ')))
                        ->get();



        $marginLoanAmountBorrowed = DB::table('margin_loan')
                                    ->leftJoin('margin_loan_type','margin_loan_type.id','=','margin_loan.marginloantype_id')
                                    ->where('margin_loan.mortgage_id','=',$mortgageId)
                                    ->orderBy('margin_loan.id','desc')
                                    ->select(array(DB::raw('margin_loan.id,date ,debit as debit , credit as credit, description  ')))
                                    ->get();

        $marginloans = array();


        for( $i= 0 ; $i < count($marginLoanAmountBorrowed) ; $i++ ){

            $amountBorrowed = 0.0;
            $date = intval($marginLoanAmountBorrowed[$i]->date);
            $debit = $marginLoanAmountBorrowed[$i]->debit;
            $credit = $marginLoanAmountBorrowed[$i]->credit;
            $description = $marginLoanAmountBorrowed[$i]->description;

            foreach($marginLoanAmountBorrowed as $loan ){

                if(intval($loan->id) <= $marginLoanAmountBorrowed[$i]->id ){

                    $amountBorrowed = ($amountBorrowed + $loan->debit ) - ($loan->credit);

                }
            }

            $arrayAmmountBorrowed = array(

                'borrowed' => $amountBorrowed,
                'date' => $date,
                'debit' => $debit,
                'credit' => $credit,
                'description' => $description
            );


            $marginloans[] = array(
                'mloan' => $arrayAmmountBorrowed
            );
        }

        foreach ($mortgage as $item) {

            $debits +=  floatval($item->debit) ;
            $credits +=  floatval($item->credit);
            $close += (($item->credit) - ($item->debit));

            $purchase += (strcmp(rtrim($item->name),'Purchase') == 0) ? $item->debit : 0;
            $sell += (strcmp(rtrim($item->name),'Sell') == 0) ? $item->credit : 0;
            $interest  += (strcmp(rtrim($item->name),'Interest') == 0) ? $item->debit : 0;
            $payment += (strcmp(rtrim($item->name),'Payment') == 0) ? $item->credit : 0;
            $withdrawal += (strcmp(rtrim($item->name),'Withdrawal') == 0) ? $item->debit : 0;
            $dividend += (strcmp(rtrim($item->name),'Dividend') == 0) ? $item->credit : 0;

        }

        $arrayMortgageAccount = array(
            'marginLoans' => $marginloans,
            'purchase' => ROUND($purchase,2),
            'sell' =>  ROUND($sell,2),
            'interest'  =>  ROUND($interest,2),
            'payment' =>  ROUND($payment,2),
            'withdrawal' =>  ROUND($withdrawal,2),
            'dividend' =>  ROUND($dividend,2),
            'debitTotal' => ROUND($debits,2),
            'creditTotal' => ROUND($credits,2),
            'change' => ROUND($credits - $debits,2),
            'open' => ROUND($open,2),
            'close' => ROUND($close,2),
            'difference' => ROUND(abs($open - $close),2),
            'portfolio' => $portfolio
        );

        $con = mysqli_connect(DB_SERVER_IP,DB_SERVER_NAME,DB_SERVER_PASSWORD,DB_SERVER_USER_NAME);

        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();

        } else {
            //echo "Succesfully connected to DB ";
        }

        $query = "SELECT SUM(x.marketcap) as marketcap, t.name

                    FROM (select sp.MarketCapitalization  as marketcap, sp.symbol_id, sy.code from stock_price sp
                    left join symbol sy on sy.id = sp.symbol_id
                    and sy.code LIKE '%.AX'
                    ORDER BY sp.MarketCapitalization
                    DESC
                    LIMIT 0, 200) x
                    LEFT JOIN industry_symbol i on i.symbol_id = x.symbol_id
                    LEFT JOIN industry_sector y on y.industry_id = i.industry_id
                    LEFT JOIN sector t on t.id = y.sector_id
                    GROUP BY t.name
                    ORDER BY marketcap DESC
                    ";

        $resultsMarketCap = mysqli_query($con, $query);
        $totalMarketCap = 0.0;

        while ($_rowMarketCap = mysqli_fetch_array($resultsMarketCap)) {

            $arrayMarketCap[] = array(
                'name' => $_rowMarketCap['name'],
                'marketCap' => $_rowMarketCap['marketcap']
            );

        }

        foreach ($arrayMarketCap as $item) {

            $totalMarketCap += $item['marketCap'];
        }

        $arrayMarketCapPercentage = array();
        $portfolio = Portfolio::find($portfolioId);

        foreach ($arrayMarketCap as $item) {

            $percentageSector = $item['marketCap'] * 100 / $totalMarketCap;

            $arrayMarketCapPercentage[] = array(

                'name' => $item['name'],
                'marketCap' => $item['marketCap'],
                'percentage' => $percentageSector

            );
        }

        $arrayIndexWeight = array(

            'portfolio' => $portfolio,
            'indexWeight' => $arrayMarketCapPercentage
        );

        $query2 = "	SELECT  SUM(x.marketcap) as marketcap, t.name
                    FROM (select sp.MarketCapitalization  as marketcap, sp.symbol_id, sy.code , tr.portfolio_id from stock_price sp
                    left join symbol sy on sy.id = sp.symbol_id
                    left join transaction tr on tr.symbol_id = sp.symbol_id
  					where tr.portfolio_id = '$portfolioId'
                    and sy.code LIKE '%.AX'
                    ORDER BY sp.MarketCapitalization
                    DESC
                    LIMIT 0, 300) x
                    LEFT JOIN industry_symbol i on i.symbol_id = x.symbol_id
                    LEFT JOIN industry_sector y on y.industry_id = i.industry_id
                    LEFT JOIN sector t on t.id = y.sector_id
                    GROUP BY t.name
                    ORDER BY marketcap DESC
                    ";
        $resultsMarketCap2 = mysqli_query($con, $query2);
        $totalMarketCap2 = 0.0;

        while ($_rowMarketCap2 = mysqli_fetch_array($resultsMarketCap2)) {

            $arrayMarketCap2[] = array(
                'name' => $_rowMarketCap2['name'],
                'marketCap' => $_rowMarketCap2['marketcap']
            );

        }

        foreach ($arrayMarketCap2 as $item) {

            $totalMarketCap2 += $item['marketCap'];
        }

        foreach ($arrayMarketCap2 as $item) {

            $percentageSector2 = $item['marketCap'] * 100 / $totalMarketCap2;

            $arryMarketCapPercentage2[] = array(

                'name' => $item['name'],
                'marketCap' => $item['marketCap'],
                'percentage' => $percentageSector2

            );
        }

        $arrayPortfolioWeighting = array(

            'portfolio' => $portfolio,
            'portfolioWeight' => $arryMarketCapPercentage2
        );

        $html =  View::make('PortfolioSummary',$arr)
                    ->nest('childSales', 'PortfolioSales',$arrySales)
                    ->nest('childCurrentPortfolio', 'CurrentPortfolio',$arraycurrentPortfolio)
                    ->nest('childMarginLoan', 'MarginLoan',$arrayMortgageAccount)
                    ->nest('childIndexWeightings', 'IndexWeightings',$arrayIndexWeight)
                    ->nest('childPortfolioWeightings', 'PortfolioWeightings',$arrayPortfolioWeighting);

        return PDF::load($html, 'A3', 'portrait')->download($portfolio->name);
    }


    public function portfolioSales(){

        return View::make('PortfolioSales');
    }

    public function currentPortfolio(){

        return View::make('CurrentPortfolio');
    }

    public function marginLoanReport(){

        return View::make('MarginLoan');
    }

    public function indexWeightings(){

        return View::make('IndexWeightings');
    }

    public function portfolioWeighting(){

        return View::make('PortfolioWeightings');
    }


    public function testView(){

        $data = array(

            'portfolio' => 'Berts Investment PF ',
            'portfolioWeight' => '43%'
        );

        $data2 = array(

            'portfolioWeight' => '43%'
        );

        $view =  View::make('testVW',$data);
        $view->nest('child', 'partialVW',$data2);

        return $view;
    }

    public function partialView(){

        return  View::make('partialVW');
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

    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {

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

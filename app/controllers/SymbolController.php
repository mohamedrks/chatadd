<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 12/18/14
 * Time: 7:13 PM
 */


class SymbolController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response

     */


    public function index()
    {

        $symbols = Symbol::all();//->groupBy('category');

        return $symbols->toJson();

        //
    }

    public function getSymbolNews($symbol,$page){

        $news = Symbol::with('news')->where('code','=',$symbol)->get();

        return Paginator::make($news->toArray(), $news->count(), 10);

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
        $symbols = Symbol::where('id', $id)->get();
        return $symbols->toJson();
    }

    public function getSymbolSearch($req){

        $like = '%'.$req.'%';

        $symbols = DB::table('symbol')
                        ->where('code','LIKE',$like)
                        ->orderBy('isASX','DESC')
                        ->select(array('code',DB::raw('SUBSTRING(concat( code," " , name),1, 20) as name')))
                        ->get();

        return $symbols;
    }

    public function getSymbol($req){

        $like = '%'.$req.'%';
        $symbols = Symbol::where('code','LIKE',$like)->orderBy('isASX','DESC')->get();
        return $symbols->toJson();
    }

    public function getASX200(){

        // $symbols = StockPrice::with('symbol')->orderBy('MarketCapitalization', 'DESC')->take(200)->get();
        $symbols = DB::table('stock_price')
                        ->leftJoin('symbol','symbol.id','=','stock_price.symbol_id')
                        ->leftJoin('industry_symbol','industry_symbol.symbol_id','=','symbol.id')
                        ->leftJoin('industry','industry.id','=','industry_symbol.industry_id')
                        ->orderBy('MarketCapitalization', 'DESC')
                        ->take(200)
                        ->select(array('stock_price.*','symbol.code as symbol_code','industry.name as industry_name'))
                        ->get();

        return $symbols;
    }

    public function getASX300(){

        $symbols = DB::table('stock_price')
                        ->leftJoin('symbol','symbol.id','=','stock_price.symbol_id')
                        ->leftJoin('industry_symbol','industry_symbol.symbol_id','=','symbol.id')
                        ->leftJoin('industry','industry.id','=','industry_symbol.industry_id')
                        ->orderBy('MarketCapitalization', 'DESC')
                        ->take(300)
                        ->select(array('stock_price.*','symbol.code as symbol_code','industry.name as industry_name'))
                        ->get();

        return $symbols;
    }

    public function getSumMarketCapitalASX200(){

        $marketCapitalSum = DB::table('stock_price')
                                ->leftJoin('symbol','symbol.id','=','stock_price.symbol_id')
                                ->leftJoin('industry_symbol','industry_symbol.symbol_id','=','symbol.id')
                                ->leftJoin('industry','industry.id','=','industry_symbol.industry_id')
                                ->whereRaw('industry.name is not null ')
                                ->orderBy('sumCapital', 'DESC')
                                ->groupBy('industry.name')
                                ->take(200)
                                ->select(array('industry.name as industry_name',DB::raw('sum(stock_price.MarketCapitalization) as sumCapital')))
                                ->get();

        return $marketCapitalSum;
    }

    public function getSumMarketCapitalASX300(){

        $marketCapitalSum = DB::table('stock_price')
            ->leftJoin('symbol','symbol.id','=','stock_price.symbol_id')
            ->leftJoin('industry_symbol','industry_symbol.symbol_id','=','symbol.id')
            ->leftJoin('industry','industry.id','=','industry_symbol.industry_id')
            ->whereRaw('industry.name is not null ')
            ->orderBy('MarketCapitalization', 'DESC')
            ->take(300)
            ->groupBy('industry.name')
            ->select(array('stock_price.*','symbol.code as symbol_code','industry.name as industry_name',DB::raw('sum(stock_price.MarketCapitalization) as sumCapital')))
            ->get();

        return $marketCapitalSum;
    }

    public function getASX200Pagination($page){

        // $symbols = StockPrice::with('symbol')->orderBy('MarketCapitalization', 'DESC')->take(200)->get();
        $symbols = DB::table('stock_price')
            ->leftJoin('symbol','symbol.id','=','stock_price.symbol_id')
            ->leftJoin('industry_symbol','industry_symbol.symbol_id','=','symbol.id')
            ->leftJoin('industry','industry.id','=','industry_symbol.industry_id')
            ->select(array('stock_price.*','symbol.code as symbol_code','industry.name as industry_name'))
            ->paginate(15);

        return $symbols;
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
        //
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

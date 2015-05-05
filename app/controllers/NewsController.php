<?php

class NewsController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function  index()
    {
        return News::all();

    }


    public function getAllSubscribedSymbol()
    {
        $userId = Authorizer::getResourceOwnerId();
        $arraySymbols = array();

        $symbolSubscribed = Transaction::where('user_id', '=', $userId)->select('symbol_id')->groupBy('symbol_id')->get();

        foreach ($symbolSubscribed as $symbol) {

            $code = Symbol::find($symbol->symbol_id);
            array_push($arraySymbols, $code->code);
        }

        return $arraySymbols;
    }

    public function getNews($symbol)
    {

        if ($symbol != 'all') {

            $code = Symbol::where('code', '=', $symbol)->first();
            $news = News::with('symbol', 'sentiment')->where('symbol_id', '=', $code->id)->get();
            return Paginator::make($news->toArray(), $news->count(), 10);

        } else {

            $news = News::with('symbol', 'sentiment')->get();
            return Paginator::make($news->toArray(), $news->count(), 10);
        }

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
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $news = News::find($id);
        return $news;
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
        //
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

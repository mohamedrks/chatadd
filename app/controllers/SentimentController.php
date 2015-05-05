<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 12/11/14
 * Time: 4:50 PM
 */


class SentimentController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response

     */


    public function index()
    {

    }

    public function getTwitterBySymbol($symbol){

        $codeId = Symbol::where('code','=',$symbol)->first(array('id'));
        $results = Sentiment::with('symbol')->where('source','=','twitter')->where('symbol_id','=',$codeId->id)->orderBy('id', 'asc')->take(10)->get();

        return  $results->toJson();

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


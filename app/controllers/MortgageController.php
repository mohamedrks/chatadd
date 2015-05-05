<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 2/10/15
 * Time: 4:58 PM
 */



class MortgageController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response

     */

    public function index()
    {
        $mortgage = Mortgage::with('portfolio')->get();
        return $mortgage;
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

            'name' => 'required',
            'portfolio_id' => 'required'

        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500);

        } else {
            $mortgage = new Mortgage;

            $mortgage->name = Input::get('name');
            $portfolio_id = Input::get('portfolio_id');

            $portfolio = Portfolio::find($portfolio_id);


            if (!empty($portfolio_id)) {

                $mortgage->portfolio()->associate($portfolio);
            }

            $mortgage->save();


            //return Response::json(array('error' => false, 'mortgage' => $mortgage), 200);
        }


    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $mortgage = Mortgage::find($id);
        return $mortgage;
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
        $mortgage = Mortgage::find($id);
        $mortgage->delete();
    }


}

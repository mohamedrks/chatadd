<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 2/11/15
 * Time: 12:21 PM
 */

class StatsController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response

     */


    public function index()
    {

        //
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

            'name' => 'required|unique:stats_input',
            'portfolio_id' => 'required'

        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500);//$validator->messages()->toJson();

        } else {
            $group = new Group;

            $group->name = Input::get('name');
            $portfolioId = Input::get('portfolio_id');

            $portfolio = Portfolio::find($portfolioId);

            if(!empty($portfolio)){

            }
            $group->save();
            return Response::json(array('error' => false,'group' => $group->toArray()),200);
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
        $group = Group::where('id', $id)->get();
        return $group->toJson();
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
        $rules = array(

            'name' => 'required|unique:group,name,'.$id,
            'description' => 'required',
            'level' => 'required|unique:group,level,'.$id
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500);//$validator->messages()->toJson();

        } else {

            $group = Group::find($id);

            $group->name = Input::get('name');
            $group->description = Input::get('description');
            $group->level = Input::get('level');
            $group->save();


            return Response::json(array('error' => false,'group' => $group->toArray()),200);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $group = Group::find($id);
        $group->delete();
    }


}
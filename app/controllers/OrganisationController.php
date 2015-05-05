<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 12/24/14
 * Time: 12:11 PM
 */


class OrganisationController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response

     */


    public function index()
    {
        $organisations = Organisation::all();
        return $organisations->toJson();
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

            'name' => 'required|unique:organisation'

        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500);//$validator->messages()->toJson();

        } else {
            $organisation = new Organisation;

            $organisation->name = Input::get('name');
            $organisation->save();


            //return Response::json(array('error' => false,'organisation' => $organisation->toArray()),200);
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
        $organisation = Organisation::find($id);
        return $organisation;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $req
     * @return Response
     */
    public function getOrganisation($req){

        $like = '%'.$req.'%';

        $organisation = Organisation::where('name','LIKE',$like)->get();

        return $organisation->toJson();
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

            'name' => 'required|unique:organisation,name,'.$id,

        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500);//$validator->messages()->toJson();

        } else {

            $organisation = Organisation::find($id);

            $organisation->name = Input::get('name');

            $organisation->save();

            //return Response::json(array('error' => false,'organisation' => $organisation->toArray()),200);
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
        $organisation = Organisation::find($id);
        $organisation->delete();
    }


}

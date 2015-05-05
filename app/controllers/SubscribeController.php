<?php
/**
 * Created by PhpStorm.
 * User: RikiJoe
 * Date: 4/26/2015
 * Time: 8:08 AM
 */


class SubscribeController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {
        return Subscribe::all();
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

            'category_id' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            $subscribe = new Subscribe;

            $id = Authorizer::getResourceOwnerId();
            $user = \Cartalyst\Sentry\Users\Eloquent\User::find($id);
            $category = Category::find(Input::get('category_id'));

            $subscribe->users()->associate($user);
            $subscribe->category()->associate($category);
            $subscribe->save();

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
        $subscribe = Subscribe::find($id);
        return $subscribe->toJson();
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

            'category_id' => 'required'

        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            $subscribe = Subscribe::find($id);
            $category = Category::find(Input::get('category_id'));

            $subscribe->category()->associate($category);
            $subscribe->save();
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
        $subscribe = Subscribe::find($id);
        $subscribe->delete();
    }


}
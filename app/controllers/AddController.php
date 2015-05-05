<?php
/**
 * Created by PhpStorm.
 * User: RikiJoe
 * Date: 4/26/2015
 * Time: 7:37 AM
 */


class AddController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {
        return Add::all();
    }

    public function getSubscribedAdds(){

        $id = Authorizer::getResourceOwnerId();
        $subscribedCategory = Subscribe::where('user_id','=',$id)->select(array('category_id'))->get();

        $arraySubscribedCategory = array();

        foreach($subscribedCategory as $item ){

            array_push($arraySubscribedCategory,$item->category_id);
        }

        $adds = DB::table('add')
                    ->where('user_id','!=',$id)
                    ->whereIn('category_id', $arraySubscribedCategory )->get();

        return $adds;
    }

    public function getAllAddsByUser(){

        $id     = 1;//Authorizer::getResourceOwnerId();
        $user   = \Cartalyst\Sentry\Users\Eloquent\User::find($id);
        $adds   = Add::where('user_id','=',$id)->get();

        return $adds;
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

            'category_id' => 'required',
            'country_id'  => 'required',
            'suburb_id'   => 'required',
            'description' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            $add = new Add;

            $category = Category    ::  find(Input::get('category_id'));
            $country  = Country     ::  find(Input::get('country_id'));
            $suburb   = Suburb      ::  find(Input::get('suburb'));

            $add->description = Input::get('description');
            $add->category()->associate($category);
            $add->country()->associate($country);
            $add->suburb()->associate($suburb);
            $add->save();

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
        $add = Add::find($id);
        return $add->toJson();
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

            'category_id' => 'required',
            'country_id'  => 'required',
            'suburb_id'   => 'required',
            'description' => 'required'

        );
        $validator = Validator::make(Input::all(), $rules);


        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            $add = Add::find($id);

            $category = Category    ::  find(Input::get('category_id'));
            $country  = Country     ::  find(Input::get('country_id'));
            $suburb   = Suburb      ::  find(Input::get('suburb'));
            $id       = Authorizer::getResourceOwnerId();
            $user     = \Cartalyst\Sentry\Users\Eloquent\User::find($id);

            $add->description = Input::get('description');
            $add->category()->associate($category);
            $add->country()->associate($country);
            $add->suburb()->associate($suburb);
            $add->users()->associate($user);
            $add->save();
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
        $add = Add::find($id);
        $add->delete();
    }


}
<?php

/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 12/12/14
 * Time: 11:49 AM
 */
class UsersController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {
//        $users = User::with('groups')->get(); // chk
        $users = DB::table('users')
                    ->leftJoin('users_groups', 'users_groups.user_id', '=', 'users.id')
                    ->leftJoin('groups', 'groups.id', '=', 'users_groups.group_id')
                    ->select(array('users.*', 'groups.name as gname', 'groups.id as gid'))
                    ->get();

        return $users;
    }


    public function getPermissions($req)
    {

        try {
            // Find the user using the user id
            $user = Sentry::findUserByID(Authorizer::getResourceOwnerId());

            // Get the user permissions
            //$permissions = $user->getPermissions();

            if (Sentry::getUser()->hasAnyAccess([$req])) {

                return 'true';

            } else {

                return 'false';
            }

        } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
            return 'User was not found.';
        }

    }

    public function getClient()
    {

        $id = Authorizer::getResourceOwnerId();

        if ($id) {

            $client = DB::table('client_user')
                ->leftJoin('client', 'client.id', '=', 'client_user.client_id')
                ->leftJoin('account', 'account.id', '=', 'client.account_id')
                ->leftJoin('country', 'country.id', '=', 'client.country_id')
                ->where('client_user.user_id', '=', $id)
                ->select(array('client.*', 'country.name as country_name', 'account.account_name as account_name'))
                ->get();

            return $client;

        } else {

            App::abort(403, 'User not authenticated.');
        }
    }

    public function getCurrentUserDetails()
    {

        $id = Authorizer::getResourceOwnerId();

        if ($id) {
            //$user = User::find($id)->with('group')->first();//->get();

            $user = DB::table('users')
                ->leftJoin('users_groups', 'users.id', '=', 'users_groups.user_id')
                ->leftJoin('groups', 'groups.id', '=', 'users_groups.group_id')
                ->leftJoin('organisation', 'organisation.id', '=', 'users.organisation_id')
                ->where('users.id', '=', $id)
                ->select(array('users.*', 'groups.name as gname', 'organisation.name as orgname'))
                ->get();

            return $user;
        } else {

            App::abort(403, 'User not authenticated.');
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
        $rules = array(
            'group_id' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required|min:6',
            'email' => 'required|email|unique:users',
            'first_name' => 'required',
            'last_name' => 'required'
            //'active' => 'required|numeric'

        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {
            $user = new Cartalyst\Sentry\Users\Eloquent\User;

            //$user->group_id = Input::get('group_id');
            $user->username = Input::get('username');
            $user->password = Hash::make(Input::get('password'));
            $user->email = Input::get('email');
            $user->phone = Input::get('phone');
            $user->first_name = Input::get('first_name');
            $user->last_name = Input::get('last_name');
            //$user->active = Input::get('active');

            $organisation = Organisation::find(Input::get('organisation_id'));
            $group = Groups::find(Input::get('group_id'));

            if (!empty($organisation)) {

                $user->organisation()->associate($organisation);
            }

            $user->save();

            if (!empty($group)) {

                $user->groups()->attach($group);

            }

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
        $user = Users::find($id);
        return $user->toJson();
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
            //'group_id' => 'required', // chk
            //'username' => 'required|unique:user,username,' . $id,
            'email' => 'required|unique:users,email,' . $id,
            'first_name' => 'required',
            'last_name' => 'required',
            'password' => 'sometimes|required|min:6'
            //'active' => 'required|numeric'

        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            $user = Users::find($id);
            //$user->group_id = Input::get('group_id');
            //$user->username = Input::get('username');
            $user->email = Input::get('email');
            $user->first_name = Input::get('first_name');
            $user->last_name = Input::get('last_name');
            $user->phone = Input::get('phone');
            $user->password = Hash::make(Input::get('password'));
            //$user->active = Input::get('active');

            $organisation = Organisation::find(Input::get('organisation_id'));
            $group = Groups::find(Input::get('group_id'));

            if (!empty($organisation)) {

                $user->organisation()->associate($organisation);
            }

            $user->save();
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
        $user = Users::find($id);
        $user->delete();
    }


}


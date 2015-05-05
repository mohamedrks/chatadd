<?php

/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 1/6/15
 * Time: 9:29 AM
 */
class AccountController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {
        $account = Account::all();
        return $account;
    }


    public function getPortfolioByAccount($account_id){

        $portfolio_account = Account::find($account_id)->portfolio;

        return $portfolio_account;
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

            'account_name' => 'required',
            'account_number' => 'required|unique:account',
            'parent_account' => 'required',
            //'primary_contact' => 'required',
            //'relationship_type' => 'required',
            //'currency' => 'required',
            //'main_phone' => 'required',
            //'other_phone' => 'required',
            //'fax' => 'required',
            //'website' => 'required',
            'email' => 'required|email|unique:account',
            'address_name' => 'required'
            //'street' => 'required',
            //'city' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500);

        } else {
            $account = new Account;

            $account->account_name = Input::get('account_name');
            $account->account_number = Input::get('account_number');
            $account->parent_account = Input::get('parent_account');
            $account->primary_contact = Input::get('primary_contact');
            $account->relationship_type = Input::get('relationship_type');
            $account->currency = Input::get('currency');
            $account->main_phone = Input::get('main_phone');
            $account->other_phone = Input::get('other_phone');
            $account->fax = Input::get('fax');
            $account->website = Input::get('website');
            $account->email = Input::get('email');
            $account->address_name = Input::get('address_name');
            $account->street = Input::get('street');
            $account->city = Input::get('city');
            $account->save();


//            $user = User::find(1);
//            $user->client()->attach($client->id);

//            $portfolioId = Input::get('portfolio_id');
//            $portfolio = Portfolio::find($portfolioId);
//            $portfolio->portfolio()->attach($client->id);


            //return Response::json(array('error' => true, 'account' => $account->toArray()), 200);
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
        $account = Account::find($id);
        return $account;

    }

    public function getAccount($req){

        $like = '%'.$req.'%';

        $account = Account::where('account_name','LIKE',$like)->get();

        return $account;
    }

    public function getSearchAccount($portfolioId,$req){

        $accounts = DB::table('account_portfolio')
                     ->where('portfolio_id','=',$portfolioId)
                     ->select(array('account_id'))
                     ->get();

        $arrayIn = array('');

        if(!empty($accounts)){

            foreach ($accounts as $item) {

                array_push($arrayIn, $item->account_id);
            }
        }

        $like = '%'.$req.'%';

        $account = Account::where('account_name','LIKE',$like)->whereNotIn('id',$arrayIn)->get();

        return $account;
    }

    public function addPortfolioAccount(){

        $account_id = Input::get('account_id');
        $portfolio_id = Input::get('portfolio_id');

        $portfolio = Portfolio::find($portfolio_id);
        $portfolio->account()->attach($account_id);

        //return $portfolio_id.' '.$account_id;
    }

    public function removePortfolioAccount(){

        $account_id = Input::get('account_id');
        $portfolio_id = Input::get('portfolio_id');

        $portfolio = Portfolio::find($portfolio_id);
        $portfolio->account()->detach($account_id);

        //return $portfolio_id.' '.$account_id;
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

            'account_name' => 'required',
            'account_number' => 'required|unique:account,account_number,'.$id,
            'parent_account' => 'required',
            'primary_contact' => 'required',
            'relationship_type' => 'required',
            'currency' => 'required',
            'main_phone' => 'required',
            'other_phone' => 'required',
            'fax' => 'required',
            'website' => 'required',
            'email' => 'required|email|unique:account,email,'.$id,
            'address_name' => 'required',
            'street' => 'required',
            'city' => 'required'
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500);

        } else {

            $account = Account::find($id);

            $account->account_name = Input::get('account_name');
            $account->account_number = Input::get('account_number');
            $account->parent_account = Input::get('parent_account');
            $account->primary_contact = Input::get('primary_contact');
            $account->relationship_type = Input::get('relationship_type');
            $account->currency = Input::get('currency');
            $account->main_phone = Input::get('main_phone');
            $account->other_phone = Input::get('other_phone');
            $account->fax = Input::get('fax');
            $account->website = Input::get('website');
            $account->email = Input::get('email');
            $account->address_name = Input::get('address_name');
            $account->street = Input::get('street');
            $account->city = Input::get('city');
            $account->save();
            return Response::json(array('error' => true, 'account' => $account->toArray()), 200);
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
        $account = Account::find($id);
        $account->delete();
    }


}

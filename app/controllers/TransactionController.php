<?php

/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 12/11/14
 * Time: 7:56 PM
 */
class TransactionController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */



    public function index()
    {
        $transactions = Transaction::with('symbol')->get();
        return $transactions->toJson();
    }

    public function getTransactionByUserAll(){

        $userId = Authorizer::getResourceOwnerId();
        $transaction = Transaction::with('symbol','portfolio')->where('user_id','=',$userId)->orderBy('created_at','desc')->get();

        return $transaction->toJson();
    }

    public function getTransactionByUserBuy(){

        $userId = Authorizer::getResourceOwnerId();
        $transaction = Transaction::with('symbol','portfolio')->where('user_id','=',$userId)->where('transaction_type','=','Buy')->orderBy('created_at','desc')->get();

        return $transaction->toJson();
    }

    public function getTransactionByUserSell(){

        $userId = Authorizer::getResourceOwnerId();
        $transaction = Transaction::with('symbol','portfolio')->where('user_id','=',$userId)->where('transaction_type','=','Sell')->orderBy('created_at','desc')->get();

        return $transaction->toJson();
    }

    public function getPortfolio($portfolioId){

        $transaction = Transaction::with('symbol','portfolio')->where('portfolio_id','=',$portfolioId)->where('transaction_type','=','Buy')->where('balance_shares','>',0)->get();

        return $transaction->toJson();
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
            'symbol_id' => 'required',
            'user_id' => 'required|numeric',
            'transaction_type' => 'required',
            //'date' => 'required|numeric',
            'shares' => 'required|numeric',
            //'balance_shares' => 'required|numeric',
            'price' => 'required|numeric',
            'portfolio_id' => 'required'

        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500);//$validator->messages()->toJson();

        } else {
            $transaction = new Transaction;

            $transaction->symbol_id = Input::get('symbol_id');
            $transaction->user_id =  Input::get('user_id'); //Authorizer::getResourceOwnerId(); rmv
            $transaction->transaction_type = Input::get('transaction_type');
            $transaction->date = time();//Input::get('date');
            $transaction->shares = Input::get('shares');
            $transaction->balance_shares = Input::get('balance_shares');
            $transaction->price = Input::get('price');
            $transaction->commission = Input::get('commission');
            $transaction->parent_transaction = Input::get('parent_transaction');

            $portfolioId = Input::get('portfolio_id');
            $portfolio = Portfolio::find($portfolioId);

            if(!empty($portfolio)){

                $transaction->portfolio()->associate($portfolio);
            }
            $transaction->save();


        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    // remove user_id field if not using and can remove TransactionItem
    public function show($id)
    {
        //rmv
        $transaction = Transaction::where('user_id', $id)->get();
        return $transaction->toJson();
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {

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
            'symbol_id' => 'required',
            'user_id' => 'required|numeric',
            'transaction_type' => 'required',
            //'date' => 'required',
            'shares' => 'required|numeric',
            'balance_shares' => 'required|numeric',
            'price' => 'required|numeric'

        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500);//$validator->messages()->toJson();

        } else {

            $transaction = Transaction::find($id);


            $transaction->commission = Input::get('commission');
            $transaction->symbol_id = Input::get('symbol_id');
            $transaction->user_id = Input::get('user_id'); // rmv
            $transaction->transaction_type = Input::get('transaction_type');
            //$transaction->date = Input::get('date');
            $transaction->shares = Input::get('shares');
            $transaction->balance_shares = Input::get('balance_shares');
            $transaction->price = Input::get('price');
            $transaction->commission = Input::get('commission');
            $transaction->save();

            return Response::json(array('error' => false,'transaction' => $transaction->toArray()),200);
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
        $transaction = Transaction::find($id);
        $transaction->delete();
    }


}

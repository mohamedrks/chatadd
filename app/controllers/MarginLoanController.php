<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 2/6/15
 * Time: 10:30 AM
 */

class MarginLoanController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response

     */

    public function index()
    {
        $mortgage = MarginLoan::with('marginloantype')->get();
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

    public function getMarginLoanByMortgage($mortgageId){

        $marginloans = MarginLoan::with('marginloantype')->where('mortgage_id','=',$mortgageId)->get();

        return $marginloans;

    }

    public  function getMarginLoanDetailByMortgage($mortgageId){

        $arrayMortgageAccount = array();
        $debits = 0.0;
        $credits = 0.0;
        $open = 0.0;
        $close = 0.0;

        $mortgage = DB::table('margin_loan')
                    ->leftJoin('margin_loan_type','margin_loan_type.id','=','margin_loan.marginloantype_id')
                    ->where('margin_loan.mortgage_id','=',$mortgageId)
                    ->groupBy('margin_loan.marginloantype_id')
                    ->select(array(DB::raw('sum(debit) as debit , sum(credit) as credit, margin_loan_type.name  ')))
                    ->get();

        foreach ($mortgage as $item) {

            $debits +=  floatval($item->debit) ;
            $credits +=  floatval($item->credit);
            $close += (($item->credit) - ($item->debit));
        }



        $arrayMortgageAccount[] = array(
            'mortgage' => $mortgage,
            'debitTotal' => $debits,
            'creditTotal' => $credits,
            'change' => $credits - $debits,
            'open' => $open,
            'close' => $close,
            'difference' => abs($open - $close)
        );

        return $arrayMortgageAccount;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $rules = array(

            'date' => 'required',
            'description' => 'required',
            'debit' => 'required',
            'credit' => 'required',
            'type' => 'required',
            'mortgage_id' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500);

        } else {
            $marginloan = new MarginLoan;

            $marginloan->date = strtotime(Input::get('date'));
            $marginloan->description = Input::get('description');
            $marginloan->debit = Input::get('debit');
            $marginloan->credit = Input::get('credit');
            $marginLoanTypeId = Input::get('type');
            $mortgageId = Input::get('mortgage_id');

            $margin_loan_type = MarginLoanType::where('name','=',$marginLoanTypeId)->first();
            if (!empty($margin_loan_type)) {

                $marginloan->marginloantype()->associate($margin_loan_type);
            }

            $mortgage = Mortgage::find($mortgageId);

            if (!empty($mortgage)) {

                $marginloan->mortgage()->associate($mortgage);
            }

            $marginloan->save();


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
        $marginloan = MarginLoan::find($id);
        return $marginloan;
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
        $marginloan = MarginLoan::find($id);
        $marginloan->delete();
    }


}

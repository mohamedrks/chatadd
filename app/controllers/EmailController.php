<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 2/19/15
 * Time: 3:39 PM
 */


class EmailController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {
        return Email::all();
    }

    public function getClientEmailByUser(){

        $id = Authorizer::getResourceOwnerId();
        $sms = DB::table('client_user')
                    ->leftJoin('email','email.for_id','=','client_user.client_id')
                    ->leftJoin('client','client.id','=','client_user.client_id')
                    ->where('user_id','=',$id)
                    ->where('email.receiver_type_id','=',1)
                    ->select(array('email.*',DB::raw('UNIX_TIMESTAMP(email.created_at) as created_date'),'client.user_name'))
                    ->get();

        return $sms;
    }

    public function getAccountEmailByUser(){


        $id = Authorizer::getResourceOwnerId();
        $sms = DB::table('email')
                ->leftJoin('account_portfolio','account_portfolio.account_id','=','email.for_id')
                ->leftJoin('portfolio','portfolio.id','=','account_portfolio.portfolio_id')
                ->leftJoin('account','account.id','=','account_portfolio.account_id')
                ->where('portfolio.user_id','=',$id)
                ->where('email.receiver_type_id','=',2)
                ->select(array('email.*',DB::raw('UNIX_TIMESTAMP(email.created_at) as created_date'),'account.account_name'))
                ->get();

        return $sms;
    }

    public function getEmailByReceiverType($receiverTypeId,$forId){

        $email = Email::where('for_id','=',$forId)->where('receiver_type_id','=',$receiverTypeId)->select(array('email.*',DB::raw('UNIX_TIMESTAMP(email.created_at) as created_date')))->get();

        return $email;
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

            'subject' => 'required',
            'email_id' => 'required',
            'message' => 'required',
            'for_id' => 'required',
            'receiver_type_id' => 'required'
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500);

        } else {
            $email = new Email;

            $email->subject = Input::get('subject');
            $email->email_id = Input::get('email_id');
            $email->message = Input::get('message');
            $email->for_id = Input::get('for_id');
            $email->receiver_type_id = Input::get('receiver_type_id');

            switch ($email->receiver_type_id) {
                case 1:
                    $receiver = Client::find($email->for_id);
                    break;
                case 2:
                    $receiver = Account::find($email->for_id);
                    break;
            }

            $emailId = $receiver->email;
            $emailRecipients = array( 'email' => $emailId, 'first_name' => 'John Smith', 'from' => 'admin@diamatic.com.au', 'from_name' => 'Admin' , 'subject' => $email->subject);

            Mail::send('emails.test', array('msg' => $email->message), function($message) use ($emailRecipients)
            {
                $message->from($emailRecipients['from'],$emailRecipients['from_name']);

                $message->to($emailRecipients['email'], $emailRecipients['first_name'])->subject($emailRecipients['subject']); // tony.t.lucas@gmail.com
            });

            $email->save();



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
        return Email::find($id);
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

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $email = Email::find($id);
        $email->delete();
    }


}


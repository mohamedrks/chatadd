<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 2/17/15
 * Time: 4:02 PM
 */

class NoteController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {

    }

    public function getNoteByReceiverType($receiverTypeId,$clientId){

        $notes = Note::where('for_id','=',$clientId)->where('receiver_type_id','=',$receiverTypeId)->select(array('note.*',DB::raw('UNIX_TIMESTAMP(note.created_at) as created_date')))->get();

        return $notes;
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

            'message' => 'required',
            'for_id' => 'required',
            'receiver_type_id' => 'required'
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500);

        } else {
            $note = new Note;

            $note->message = Input::get('message');
            $note->for_id = Input::get('for_id');
            $note->receiver_type_id = Input::get('receiver_type_id');

            $note->save();
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
        return Note::find($id);
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

            'message' => 'required',
            'for_id' => 'required',
            'receiver_type_id' => 'required'
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500);

        } else {
            $note = Note::find($id);

            $note->message = Input::get('message');
            $note->for_id = Input::get('for_id');
            $note->receiver_type_id = Input::get('receiver_type_id');

            $note->save();
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
        $note = Note::find($id);
        $note->delete();

    }


}


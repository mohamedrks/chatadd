<?php

/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 12/15/14
 * Time: 3:43 PM
 */
class VelocityController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {

    }

    public function getTwitterVelocity($symbol, $sentiment)
    {

        $codeId = Symbol::where('code', '=', $symbol)->first(array('id'));
        $source = 'twitter';

        $results = DB::table('sentiment')->where('source', '=', $source)->select(array(DB::raw('CEIL(( max(date_time) - min(date_time) )/(60*60*24)) as Days '), DB::raw('count(*)/(( max(date_time) - min(date_time) )/(60*60*24)) as AverageTweets '), DB::raw('max(date_time) as maximum_date'), DB::raw('min(date_time) as minimum_date')))->get();

        $maxdate = $results[0]->maximum_date;
        $average = $results[0]->AverageTweets;

        for ($i = 1; $i <= $results[0]->Days; $i++) {

            $currentDay = ($maxdate) - (($i - 1) * 60 * 60 * 24);
            $beforeCurrentDay = ($maxdate) - (($i) * 60 * 60 * 24);

// Need to use group by to the blow query group by sentiment_status column.
            $varSentiment = $sentiment . '%';

            if ($sentiment != 'tot') {

                $res = DB::table('sentiment')->where('source', '=', $source)->where('symbol_id', '=', $codeId->id)->where('date_time', '>=', $beforeCurrentDay)->where('date_time', '<=', $currentDay)->where('sentiment_status', 'like', $varSentiment)->select(array(DB::raw('count(*) as count ')))->get();
            } else {

                $res = DB::table('sentiment')->where('source', '=', $source)->where('symbol_id', '=', $codeId->id)->where('date_time', '>=', $beforeCurrentDay)->where('date_time', '<=', $currentDay)->select(array(DB::raw('count(*) as count ')))->get();
            }
            $countTweetsPerDayPerSymbol = $res[0]->count;

            $arrayAverageTwetsPerDay[] = array(
                $currentDay * 1000,
                $countTweetsPerDayPerSymbol / $average
            );

        }

        return array_reverse($arrayAverageTwetsPerDay);

    }

    public function getNewsVelocity($symbol, $sentiment)
    {


        $codeId = Symbol::where('code', '=', $symbol)->first(array('id'));
        //$sentiment = 'pos';
        $source = 'news';

        $results = DB::table('sentiment')->where('source', '=', $source)->select(array(DB::raw('CEIL(( max(date_time) - min(date_time) )/(60*60*24)) as Days '), DB::raw('count(*)/(( max(date_time) - min(date_time) )/(60*60*24)) as AverageNews '), DB::raw('max(date_time) as maximum_date'), DB::raw('min(date_time) as minimum_date')))->get();

        $maxdate = $results[0]->maximum_date;
        $average = $results[0]->AverageNews;

        for ($i = 1; $i <= $results[0]->Days; $i++) {

            $currentDay = ($maxdate) - (($i - 1) * 60 * 60 * 24);
            $beforeCurrentDay = ($maxdate) - (($i) * 60 * 60 * 24);

// Need to use group by to the blow query group by sentiment_status column.
            $varSentiment = $sentiment . '%';

            if ($sentiment != 'tot') {
                $res = DB::table('sentiment')->where('source', '=', $source)->where('symbol_id', '=', $codeId->id)->where('date_time', '>=', $beforeCurrentDay)->where('date_time', '<=', $currentDay)->where('sentiment_status', 'like', $varSentiment)->select(array(DB::raw('count(*) as count ')))->get();
            } else {
                $res = DB::table('sentiment')->where('source', '=', $source)->where('symbol_id', '=', $codeId->id)->where('date_time', '>=', $beforeCurrentDay)->where('date_time', '<=', $currentDay)->select(array(DB::raw('count(*) as count ')))->get();
            }
            $countNewsPerDayPerSymbol = $res[0]->count;

            $arrayAverageNewsPerDay[] = array(
                $currentDay * 1000,
                $countNewsPerDayPerSymbol / $average
            );

        }

        return array_reverse($arrayAverageNewsPerDay); //array_reverse($arrayAverageNewsPerDay);
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
        //
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        //
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
        //
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }


}


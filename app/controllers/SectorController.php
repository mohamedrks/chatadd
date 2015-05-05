<?php

/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 1/16/15
 * Time: 2:43 PM
 */


class SectorController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {
        $sector = Sector::all();
        return $sector->toJson();
    }

    public function getASXStockBySector($sectorId)
    {


        $con = mysqli_connect(DB_SERVER_IP,DB_SERVER_NAME,DB_SERVER_PASSWORD,DB_SERVER_USER_NAME);

        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();

        } else {
            //echo "Succesfully connected to DB ";
        }

        $stockPriceBySector = "select x.* from
                                    (select  sp.*,s.code  from stock_price sp
                                    left join symbol s on s.id = sp.symbol_id
                                    order by sp.MarketCapitalization desc
                                    LIMIT 0, 300) x
                                    left join industry_symbol insy on insy.symbol_id = x.symbol_id
                                    left join industry_sector inse on inse.industry_id = insy.industry_id
                                    where inse.sector_id = '$sectorId'";

        $resultsMarketCap = mysqli_query($con, $stockPriceBySector);

        while ($row = mysqli_fetch_array($resultsMarketCap)) {

            $arrayStockPriceBySector[] = array(
                'symbol_id' => $row['symbol_id'],
                'code' => $row['code'],
                'MarketCapitalization' => $row['MarketCapitalization']
            );

        }

        return $arrayStockPriceBySector;
    }

    public function getSumMarketCapitalBySectorASX200()
    {

        $con = mysqli_connect(DB_SERVER_IP,DB_SERVER_NAME,DB_SERVER_PASSWORD,DB_SERVER_USER_NAME);

        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();

        } else {
            //echo "Succesfully connected to DB ";
        }

        $query = "SELECT SUM(x.marketcap) as marketcap, t.name

                    FROM (select sp.MarketCapitalization  as marketcap, sp.symbol_id, sy.code from stock_price sp
                    left join symbol sy on sy.id = sp.symbol_id
                    and sy.code LIKE '%.AX'
                    ORDER BY sp.MarketCapitalization
                    DESC
                    LIMIT 0, 200) x
                    LEFT JOIN industry_symbol i on i.symbol_id = x.symbol_id
                    LEFT JOIN industry_sector y on y.industry_id = i.industry_id
                    LEFT JOIN sector t on t.id = y.sector_id
                    GROUP BY t.name
                    ORDER BY marketcap DESC
                    ";
        $resultsMarketCap = mysqli_query($con, $query);
        $totalMarketCap = 0.0;

        while ($_rowMarketCap = mysqli_fetch_array($resultsMarketCap)) {

            $arrayMarketCap[] = array(
                'name' => $_rowMarketCap['name'],
                'marketCap' => $_rowMarketCap['marketcap']
            );

        }

        foreach ($arrayMarketCap as $item) {

            $totalMarketCap += $item['marketCap'];
        }

        foreach ($arrayMarketCap as $item) {

            $percentageSector = $item['marketCap'] * 100 / $totalMarketCap;

            $arryMarketCapPercentage[] = array(

                'name' => $item['name'],
                'marketCap' => $item['marketCap'],
                'percentage' => $percentageSector

            );
        }

        return $arryMarketCapPercentage;
    }

    public function getSumMarketCapitalBySectorASX300()
    {

        $con = mysqli_connect(DB_SERVER_IP,DB_SERVER_NAME,DB_SERVER_PASSWORD,DB_SERVER_USER_NAME);

        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();

        } else {
            //echo "Succesfully connected to DB ";
        }

        $query = "SELECT SUM(x.marketcap) as marketcap, t.name

                    FROM (select sp.MarketCapitalization  as marketcap, sp.symbol_id, sy.code from stock_price sp
                    left join symbol sy on sy.id = sp.symbol_id
                    and sy.code LIKE '%.AX'
                    ORDER BY sp.MarketCapitalization
                    DESC
                    LIMIT 0, 300) x
                    LEFT JOIN industry_symbol i on i.symbol_id = x.symbol_id
                    LEFT JOIN industry_sector y on y.industry_id = i.industry_id
                    LEFT JOIN sector t on t.id = y.sector_id
                    GROUP BY t.name
                    ORDER BY marketcap DESC
                    ";
        $resultsMarketCap = mysqli_query($con, $query);
        $totalMarketCap = 0.0;

        while ($_rowMarketCap = mysqli_fetch_array($resultsMarketCap)) {

            $arrayMarketCap[] = array(
                'name' => $_rowMarketCap['name'],
                'marketCap' => $_rowMarketCap['marketcap']
            );

        }

        foreach ($arrayMarketCap as $item) {

            $totalMarketCap += $item['marketCap'];
        }

        foreach ($arrayMarketCap as $item) {

            $percentageSector = $item['marketCap'] * 100 / $totalMarketCap;

            $arryMarketCapPercentage[] = array(

                'name' => $item['name'],
                'marketCap' => $item['marketCap'],
                'percentage' => $percentageSector

            );
        }

        return $arryMarketCapPercentage;
    }

    public function getSumMarketCapitalBySectorASXforPortfolio($portfolioId)
    {

        $con = mysqli_connect(DB_SERVER_IP,DB_SERVER_NAME,DB_SERVER_PASSWORD,DB_SERVER_USER_NAME);

        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();

        } else {
            //echo "Succesfully connected to DB ";
        }

        $query = "	SELECT  SUM(x.marketcap) as marketcap, t.name
                    FROM (select sp.MarketCapitalization  as marketcap, sp.symbol_id, sy.code , tr.portfolio_id from stock_price sp
                    left join symbol sy on sy.id = sp.symbol_id
                    left join transaction tr on tr.symbol_id = sp.symbol_id
  					where tr.portfolio_id = '$portfolioId'
                    and sy.code LIKE '%.AX'
                    ORDER BY sp.MarketCapitalization
                    DESC
                    LIMIT 0, 300) x
                    LEFT JOIN industry_symbol i on i.symbol_id = x.symbol_id
                    LEFT JOIN industry_sector y on y.industry_id = i.industry_id
                    LEFT JOIN sector t on t.id = y.sector_id
                    GROUP BY t.name
                    ORDER BY marketcap DESC
                    ";
        $resultsMarketCap = mysqli_query($con, $query);
        $totalMarketCap = 0.0;

        while ($_rowMarketCap = mysqli_fetch_array($resultsMarketCap)) {

            $arrayMarketCap[] = array(
                'name' => $_rowMarketCap['name'],
                'marketCap' => $_rowMarketCap['marketcap']
            );

        }

        foreach ($arrayMarketCap as $item) {

            $totalMarketCap += $item['marketCap'];
        }

        foreach ($arrayMarketCap as $item) {

            $percentageSector = $item['marketCap'] * 100 / $totalMarketCap;

            $arryMarketCapPercentage[] = array(

                'name' => $item['name'],
                'marketCap' => $item['marketCap'],
                'percentage' => $percentageSector

            );
        }

        return $arryMarketCapPercentage;
    }

    public function marketCapitalDistribution($portfolioId)
    {

        $con = mysqli_connect(DB_SERVER_IP,DB_SERVER_NAME,DB_SERVER_PASSWORD,DB_SERVER_USER_NAME);

        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();

        } else {
            //echo "Succesfully connected to DB ";
        }

        $query = "SELECT SUM(x.marketcap) as marketcap, t.name

                    FROM (select sp.MarketCapitalization  as marketcap, sp.symbol_id, sy.code from stock_price sp
                    left join symbol sy on sy.id = sp.symbol_id
                    and sy.code LIKE '%.AX'
                    ORDER BY sp.MarketCapitalization
                    DESC
                    LIMIT 0, 300) x
                    LEFT JOIN industry_symbol i on i.symbol_id = x.symbol_id
                    LEFT JOIN industry_sector y on y.industry_id = i.industry_id
                    LEFT JOIN sector t on t.id = y.sector_id
                    GROUP BY t.name
                    ORDER BY marketcap DESC
                    ";
        $resultsMarketCap = mysqli_query($con, $query);
        $totalMarketCap = 0.0;

        while ($_rowMarketCap = mysqli_fetch_array($resultsMarketCap)) {

            $arrayMarketCap[] = array(
                'name' => $_rowMarketCap['name'],
                'marketCap' => $_rowMarketCap['marketcap']
            );

        }

//        foreach($arrayMarketCap as $item ){
//
//            $totalMarketCap += $item['marketCap'];
//        }
//
//        foreach($arrayMarketCap as $item ){
//
//            $percentageSector = $item['marketCap']*100/$totalMarketCap;
//
//            $arryMarketCapPercentage[] = array(
//
//                'name' => $item['name'],
//                'marketCap' => $item['marketCap'],
//                'percentage' => $percentageSector
//
//            );
//        }

        $query2 = "	SELECT  SUM(x.marketcap) as marketcap, t.name
                    FROM (select sp.MarketCapitalization  as marketcap, sp.symbol_id, sy.code , tr.portfolio_id from stock_price sp
                    left join symbol sy on sy.id = sp.symbol_id
                    left join transaction tr on tr.symbol_id = sp.symbol_id
  					where tr.portfolio_id = '$portfolioId'
                    and sy.code LIKE '%.AX'
                    ORDER BY sp.MarketCapitalization
                    DESC
                    LIMIT 0, 300) x
                    LEFT JOIN industry_symbol i on i.symbol_id = x.symbol_id
                    LEFT JOIN industry_sector y on y.industry_id = i.industry_id
                    LEFT JOIN sector t on t.id = y.sector_id
                    GROUP BY t.name
                    ORDER BY marketcap DESC
                    ";
        $resultsMarketCapPortfolio = mysqli_query($con, $query2);
        $totalMarketCapPortfolio = 0.0;

        while ($_rowMarketCapPortfolio = mysqli_fetch_array($resultsMarketCapPortfolio)) {

            $arrayMarketCapPortfolio[] = array(
                'name' => $_rowMarketCapPortfolio['name'],
                'marketCap' => $_rowMarketCapPortfolio['marketcap']
            );

        }

//        foreach($arrayMarketCapPortfolio as $item ){
//
//            $totalMarketCapPortfolio += $item['marketCap'];
//        }

//        $count = 0;

        foreach ($arrayMarketCap as $item) {

            //$percentageSectorPortfolio = $item['marketCap']*100/$totalMarketCapPortfolio;
            foreach ($arrayMarketCapPortfolio as $itemPortfolio) {

                if (!strcmp($itemPortfolio['name'], $item['name'])) {

//                    if ($count < 5) {

                        $arryMarketCapPercentagePortfolio[] = array(

                            'name' => $item['name'],
                            'marketCapASX300' => $item['marketCap'],
                            'marketCapPortfolio' => $itemPortfolio['marketCap']
                            //'percentage' => $percentageSectorPortfolio

                        );
//                        $count++;
//                    }
                }

            }


        }

        return $arryMarketCapPercentagePortfolio;
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
        $sector = Sector::find($id);

        return $sector->toJson();
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

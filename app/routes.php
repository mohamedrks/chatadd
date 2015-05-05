<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

//$router->get('news/{id}','NewsController@index');
header('Access-Control-Allow-Origin: *');
//header('Access-Control-All-Headers: Content-Type, Authorization, X-Requested-With, Cache-Control, Accept, Origin, X-Session-ID');
//header('Access-Control-Allow-Methods', 'GET,POST,PUT,HEAD,DELETE,TRACE,COPY,LOCK,MKCOL,MOVE,PROPFIND,PROPPATCH,UNLOCK,REPORT,MKACTIVITY,CHECKOUT,MERGE,M-SEARCH,NOTIFY,SUBSCRIBE,UNSUBSCRIBE,PATCH');
//header('Access-Control-Allow-Credentials', 'false');
//header('Access-Control-Max-Age', '1000');


Route::group(array('prefix' => 'api/v1'), function()
{


    Route::resource('subscribe','SubscribeController');


    Route::get('getSubscribedAdds','AddController@getSubscribedAdds');
    Route::get('getAllAddsByUser','AddController@getAllAddsByUser');
    Route::resource('add','AddController');


    Route::resource('category','CategoryController');

    Route::group(['before' => 'oauth'], function()
    {
        Route::resource('menu','MenuController');
        Route::resource('user',  'UsersController');
        Route::resource('group', 'GroupsController');
    });



    Route::get('updateSeenToaster/{notificationId}', ['before' => 'oauth', 'uses' => 'NotificationController@updateSeenToaster']);
    //Route::get('updateSeenToaster/{notificationId}','NotificationController@updateSeenToaster');
    Route::get('updateNotificationSeenUnseen', ['before' => 'oauth', 'uses' => 'NotificationController@updateNotificationSeenUnseen']);
    //Route::get('updateNotificationSeenUnseen/{id}','NotificationController@updateNotificationSeenUnseen');
    Route::get('updateNotificationSeen', ['before' => 'oauth', 'uses' => 'NotificationController@updateNotificationSeen']);
    //Route::get('updateNotificationSeen/{id}','NotificationController@updateNotificationSeen');
    Route::get('notificationCount', ['before' => 'oauth', 'uses' => 'NotificationController@notificationCount']);
    //Route::get('notificationCount/{id}', 'NotificationController@notificationCount');
    Route::get('tabsNotificationCount', ['before' => 'oauth', 'uses' => 'NotificationController@tabsNotificationCount']);
    //Route::get('tabsNotificationCount/{id}', 'NotificationController@tabsNotificationCount');
    //Route::get('toastNotifications', ['before' => 'oauth', 'uses' => 'NotificationController@toastNotifications']);
    Route::get('toastNotifications/{id}', 'NotificationController@toastNotifications');
    Route::get('indicatorNotify', ['before' => 'oauth', 'uses' => 'NotificationController@indicatorNotify']);
    //Route::get('indicatorNotify/{id}', 'NotificationController@indicatorNotify');
    Route::get('eventsNotify', ['before' => 'oauth', 'uses' => 'NotificationController@eventsNotify']);
    //Route::get('eventsNotify/{id}', 'NotificationController@eventsNotify');
    Route::get('socialNotify', ['before' => 'oauth', 'uses' => 'NotificationController@socialNotify']);
    //Route::get('socialNotify/{id}', 'NotificationController@socialNotify');
    Route::get('socialNewsNotify', ['before' => 'oauth', 'uses' => 'NotificationController@socialNewsNotify']);
    //Route::get('socialNewsNotify/{id}','NotificationController@socialNewsNotify');
    Route::get('stocksNotify', ['before' => 'oauth', 'uses' => 'NotificationController@stocksNotify']);
    //Route::get('stocksNotify/{id}', 'NotificationController@stocksNotify');
    Route::get('insertNotification', ['before' => 'oauth', 'uses' => 'NotificationController@insertNotification']);
    //Route::get('insertNotification/{id}', 'NotificationController@insertNotification');
    Route::get('insertStockNotification', ['before' => 'oauth', 'uses' => 'NotificationController@insertStockNotification']);
    //Route::get('insertStockNotification/{id}', 'NotificationController@insertStockNotification');
    Route::get('insertEventsNotification', ['before' => 'oauth', 'uses' => 'NotificationController@insertEventsNotification']);
    //Route::get('insertEventsNotification/{id}', 'NotificationController@insertEventsNotification');
    Route::get('insertTwitterNotification', ['before' => 'oauth', 'uses' => 'NotificationController@insertTwitterNotification']);
    //Route::get('insertTwitterNotification/{id}', 'NotificationController@insertTwitterNotification');
    Route::get('insertNewsNotification', ['before' => 'oauth', 'uses' => 'NotificationController@insertNewsNotification']);
    //Route::get('insertNewsNotification/{id}', 'NotificationController@insertNewsNotification');
    Route::resource('notification', 'NotificationController'); // rmv



    Route::group(['before' => 'oauth|superadmin'], function()
    {

        Route::get('createGroupPermission','CreateGroupController@createGroupPermission');
        Route::resource('groupPermission','CreateGroupController');

        Route::post('removeGroupMenu','MenuController@removeGroupMenu');
        Route::post('addGroupMenu','MenuController@addGroupMenu');
        Route::get('getMenuStatus/{groupId}/{menuId}','MenuController@getMenuStatus');

//    Route::get('getMenuByUser', 'MenuController@getMenuByUser');

    });

    Route::group(['before' => 'oauth|admin,superadmin'], function()
    {


        Route::resource('event', 'EventController');

        Route::resource('sentiment', 'SentimentController');

        Route::resource('indicator', 'IndicatorController');

        Route::resource('news', 'NewsController');

        Route::resource('portfolio','PortfolioController');

        Route::resource('sector','SectorController');

        Route::resource('statsInput','StatsInputController');

        Route::resource('marginloan','MarginLoanController');

        Route::resource('mortgage','MortgageController');

        Route::resource('sms','SmsController');

        Route::resource('email','EmailController');

        Route::resource('symbol', 'SymbolController');

        Route::get('getTest','DocumentController@getTest');
        Route::post('uploadDocument','DocumentController@uploadDocument');
        Route::resource('document','DocumentController');


        Route::get('getNoteByReceiverType/{receiverTypeId}/{clientId}','NoteController@getNoteByReceiverType');
        Route::resource('note','NoteController');

        Route::resource('industry','IndustryController');

        Route::get('getPortfolioByAccount/{accountId}','AccountController@getPortfolioByAccount');
        Route::post('removePortfolioAccount','AccountController@removePortfolioAccount');
        Route::post('addPortfolioAccount','AccountController@addPortfolioAccount');
        Route::get('getAccount/{account}','AccountController@getAccount');
        Route::get('getSearchAccount/{portfolioId}/{account}','AccountController@getSearchAccount');
        Route::resource('account','AccountController');


        Route::get('sell', ['before' => 'oauth', 'uses' => 'TransactionController@sell']);
        //Route::post('sell','TransactionController@sell');
        Route::get('getTransactionByUserAll', ['before' => 'oauth', 'uses' => 'TransactionController@getTransactionByUserAll']);
        //Route::get('getTransactionByUserAll/{userId}','TransactionController@getTransactionByUserAll');
        Route::get('getTransactionByUserBuy', ['before' => 'oauth', 'uses' => 'TransactionController@getTransactionByUserBuy']);
        //Route::get('getTransactionByUserBuy/{userId}','TransactionController@getTransactionByUserBuy');
        Route::get('getTransactionByUserSell', ['before' => 'oauth', 'uses' => 'TransactionController@getTransactionByUserSell']);
        //Route::get('getTransactionByUserSell/{userId}','TransactionController@getTransactionByUserSell');
        Route::get('getPortfolio/{portfolioId}', ['before' => 'oauth', 'uses' => 'TransactionController@getPortfolio']);
        //Route::get('getPortfolio/{portfolioId}','TransactionController@getPortfolio');
//  Route::get('getPortfolioByPortfolioId/{portfolioId}/{userId}','TransactionController@getPortfolioByPortfolioId');
        Route::resource('transaction', 'TransactionController');

        Route::resource('transactionItem', 'TransactionItemController');


        Route::get('getEventStockByUser', ['before' => 'oauth', 'uses' => 'EventManagerController@getEventStockByUser']);
        //Route::get('getEventStockByUser/{userId}','EventManagerController@getEventStockByUser');
        Route::resource('eventManager','EventManagerController');

        Route::get('getNewsStockByUser', ['before' => 'oauth', 'uses' => 'NewsVelocityToleranceController@getNewsStockByUser']);
        //Route::get('getNewsStockByUser/{userId}','NewsVelocityToleranceController@getNewsStockByUser');
        Route::resource('newsVelocityTolerance','NewsVelocityToleranceController');

        Route::get('getTwitterStockByUser', ['before' => 'oauth', 'uses' => 'TwitterVelocityToleranceController@getTwitterStockByUser']);
        //Route::get('getTwitterStockByUser/{userId}','TwitterVelocityToleranceController@getTwitterStockByUser');
        Route::resource('twitterVelocityTolerance','TwitterVelocityToleranceController');

        Route::get('getStockByUser', ['before' => 'oauth', 'uses' => 'StockToleranceController@getStockByUser']);
        //Route::get('getStockByUser/{userId}','StockToleranceController@getStockByUser');
        Route::resource('stockTolerance', 'StockToleranceController');

        Route::get('getIndicatorByUser', ['before' => 'oauth', 'uses' => 'IndicatorToleranceController@getIndicatorByUser']);
        //Route::get('getIndicatorByUser/{userId}','IndicatorToleranceController@getIndicatorByUser');
        Route::resource('indicatorTolerance', 'IndicatorToleranceController');


        Route::get('getClientWithAccount/{clientId}', ['before' => 'oauth', 'uses' => 'ClientController@getClientWithAccount']);
        //Route::get('getClientWithAccount/{clientId}','ClientController@getClientWithAccount');
        Route::get('getAllTransactionByClient/{clientId}', ['before' => 'oauth', 'uses' => 'ClientController@getAllTransactionByClient']);
        //Route::get('getAllTransactionByClient/{clientId}','ClientController@getAllTransactionByClient');
        Route::get('getBuyTransactionByClient/{clientId}', ['before' => 'oauth', 'uses' => 'ClientController@getBuyTransactionByClient']);
        //Route::get('getBuyTransactionByClient/{clientId}','ClientController@getBuyTransactionByClient');
        Route::get('getSellTransactionByClient/{clientId}', ['before' => 'oauth', 'uses' => 'ClientController@getSellTransactionByClient']);
        //Route::get('getSellTransactionByClient/{clientId}','ClientController@getSellTransactionByClient');
        Route::get('getPortfolioByClient/{clientId}', ['before' => 'oauth', 'uses' => 'ClientController@getPortfolioByClient']);
        //Route::get('getPortfolioByClient/{clientId}','ClientController@getPortfolioByClient');
        Route::get('removePortfolioClient/{clientId}', ['before' => 'oauth', 'uses' => 'ClientController@removePortfolioClient']);
        //Route::post('removePortfolioClient','ClientController@removePortfolioClient');
        Route::get('addPortfolioClient/{clientId}', ['before' => 'oauth', 'uses' => 'ClientController@addPortfolioClient']);
        //Route::post('addPortfolioClient','ClientController@addPortfolioClient');
        Route::get('getClientSearch/{req}', ['before' => 'oauth', 'uses' => 'ClientController@getClientSearch']);
        //Route::get('getClientSearch/{req}','ClientController@getClientSearch');
        Route::get('searchClient/{portfolioId}/{req}', ['before' => 'oauth', 'uses' => 'ClientController@searchClient']);
        //Route::get('searchClient/{portfolioId}/{req}','ClientController@searchClient');
        Route::resource('client', 'ClientController');

        Route::resource('country','CountryController');


    });

    Route::group(['before' => 'oauth|user,superadmin,admin'], function()
    {



        Route::get('getPermissions/{req}','UsersController@getPermissions');

        Route::get('getMenuByUser', ['before' => 'oauth', 'uses' => 'MenuController@getMenuByUser']);

        Route::get('partialView','PortfolioSummaryController@partialView');
        Route::get('testView','PortfolioSummaryController@testView');
        Route::get('portfolioWeighting','PortfolioSummaryController@portfolioWeighting');
        Route::get('indexWeightings','PortfolioSummaryController@indexWeightings');
        Route::get('marginLoanReport','PortfolioSummaryController@marginLoanReport');
        Route::get('currentPortfolio','PortfolioSummaryController@currentPortfolio');
        Route::get('portfolioSales','PortfolioSummaryController@portfolioSales');
        Route::get('portfolioSummaryPDF/{portfolioId}', 'PortfolioSummaryController@portfolioSummaryPDF');


        Route::get('getAccountEmailByUser', ['before' => 'oauth', 'uses' => 'EmailController@getAccountEmailByUser']);
        //Route::get('getAccountEmailByUser/{userId}','EmailController@getAccountEmailByUser');
        Route::get('getClientEmailByUser', ['before' => 'oauth', 'uses' => 'EmailController@getClientEmailByUser']);
        //Route::get('getClientEmailByUser/{userId}','EmailController@getClientEmailByUser');
        Route::get('getEmailByReceiverType/{receiverTypeId}/{forId}', ['before' => 'oauth', 'uses' => 'EmailController@getEmailByReceiverType']);
//  Route::get('getEmailByReceiverType/{receiverTypeId}/{forId}','EmailController@getEmailByReceiverType');
        //Route::resource('email', ['before' => 'oauth', 'uses' => 'EmailController']);


        Route::get('test','SmsController@test');
        Route::get('getAccountSmsByUser', ['before' => 'oauth', 'uses' => 'SmsController@getAccountSmsByUser']);
        //Route::get('getAccountSmsByUser/{userId}','SmsController@getAccountSmsByUser');
        Route::get('getClientSmsByUser', ['before' => 'oauth', 'uses' => 'SmsController@getClientSmsByUser']);
        //Route::get('getClientSmsByUser/{userId}','SmsController@getClientSmsByUser');
        Route::get('getSmsByReceiverType/{receiverTypeId}/{forId}','SmsController@getSmsByReceiverType');




        Route::get('getMarginLoanByMortgage/{mortgageId}','MarginLoanController@getMarginLoanByMortgage');
        Route::get('getMarginLoanDetailByMortgage/{mortgageId}','MarginLoanController@getMarginLoanDetailByMortgage');


        Route::get('getStatsByUser', ['before' => 'oauth', 'uses' => 'StatsInputController@getStatsByUser']);
        //    Route::get('getStatsByUser/{userId}','StatsInputController@getStatsByUser');



        Route::get('getASXStockBySector/{sectorId}','SectorController@getASXStockBySector');
        Route::get('getSumMarketCapitalBySectorASX300','SectorController@getSumMarketCapitalBySectorASX300');
        Route::get('getSumMarketCapitalBySectorASX200','SectorController@getSumMarketCapitalBySectorASX200');


        Route::get('getPortfolioSearchByAccount/{accountId}/{req}','PortfolioController@getPortfolioSearchByAccount');
        Route::get('getPortfolioSearchByClient/{clientId}/{req}','PortfolioController@getPortfolioSearchByClient');
        Route::get('getPortfolioPerformance/{portfolioId}','PortfolioController@getPortfolioPerformance');
        Route::get('getPortfolioClient/{id}','PortfolioController@getPortfolioClient');
        Route::get('getAccountByPortfolio/{id}','PortfolioController@getAccountByPortfolio');
        Route::get('getPortfolioSearch/{portfolio}','PortfolioController@getPortfolioSearch');
        Route::get('getPortfolioByUser', ['before' => 'oauth', 'uses' => 'PortfolioController@getPortfolioByUser']);
        //Route::get('getPortfolioByUser/{id}','PortfolioController@getPortfolioByUser');


        Route::get('getAllSubscribedSymbol', ['before' => 'oauth', 'uses' => 'NewsController@getAllSubscribedSymbol']);
        //Route::get('getAllSubscribedSymbol/{userId}','NewsController@getAllSubscribedSymbol');
        Route::get('getNews/{id}', ['before' => 'oauth', 'uses' => 'NewsController@getNews']);
        //Route::get('getNews/{id}','NewsController@getNews');




        Route::get('getTwitterBySymbol/{code}', ['before' => 'oauth', 'uses' => 'SentimentController@getTwitterBySymbol']);
        //Route::get('getTwitterBySymbol/{code}','SentimentController@getTwitterBySymbol');


        Route::get('getEventByImportance/{importance}', ['before' => 'oauth', 'uses' => 'EventController@getEventByImportance']);
        //Route::get('getEventByImportance/{importance}', 'EventController@getEventByImportance');


        Route::get('getSumMarketCapitalASX300', ['before' => 'oauth', 'uses' => 'SymbolController@getSumMarketCapitalASX200']);
        //Route::get('getSumMarketCapitalASX300','SymbolController@getSumMarketCapitalASX200');
        Route::get('getSumMarketCapitalASX200', ['before' => 'oauth', 'uses' => 'SymbolController@getSumMarketCapitalASX200']);
        //Route::get('getSumMarketCapitalASX200','SymbolController@getSumMarketCapitalASX200');
        Route::get('getASX200Pagination/{page}', ['before' => 'oauth', 'uses' => 'SymbolController@getASX200Pagination']);
        //Route::get('getASX200Pagination/{page}','SymbolController@getASX200Pagination');
        Route::get('getASX200', ['before' => 'oauth', 'uses' => 'SymbolController@getASX200']);
        //Route::get('getASX200','SymbolController@getASX200');
        Route::get('getASX300', ['before' => 'oauth', 'uses' => 'SymbolController@getASX300']);
        //Route::get('getASX300','SymbolController@getASX300');
        Route::get('getSymbolNews/{id}/{page}', ['before' => 'oauth', 'uses' => 'SymbolController@getSymbolNews']);
        //Route::get('getSymbolNews/{id}/{page}','SymbolController@getSymbolNews');
        Route::get('symbolSearch/{str}', ['before' => 'oauth', 'uses' => 'SymbolController@getSymbolSearch']);
        //Route::get('symbolSearch/{str}','SymbolController@getSymbolSearch');
        Route::get('symbol/{getSymbol}', ['before' => 'oauth', 'uses' => 'SymbolController@getSymbol']);
        //Route::get('symbol/{getSymbol}','SymbolController@getSymbol');



        //Route::get('twitterVelocity/{code}/{sentiment}', ['before' => 'oauth', 'uses' => 'VelocityController@getTwitterVelocity']);
        Route::get('twitterVelocity/{code}/{sentiment}','VelocityController@getTwitterVelocity');
        //Route::get('newsVelocity/{code}/{sentiment}', ['before' => 'oauth', 'uses' => 'VelocityController@getNewsVelocity']);
        Route::get('newsVelocity/{code}/{sentiment}','VelocityController@getNewsVelocity');
        Route::resource('twitterVelocity', 'VelocityController');

        Route::get('getCountrySearch/{req}', ['before' => 'oauth', 'uses' => 'CountryController@getCountrySearch']);

        Route::get('getClient',['before' => 'oauth', 'uses' => 'UsersController@getClient']);
        Route::get('getCurrentUserDetails', ['before' => 'oauth', 'uses' => 'UsersController@getCurrentUserDetails']);
        //Route::resource('user', 'UsersController',['only' => ['show']]);

        Route::get('getGroupMenu/{groupId}', ['before' => 'oauth', 'uses' => 'GroupsController@getGroupMenu']);

        Route::resource('organisation', 'OrganisationController',['only' => ['index', 'show', 'edit', 'update', 'destroy']]);


    });

    Route::get('marketCapitalDistribution/{portfolioId}','SectorController@marketCapitalDistribution');
    Route::get('getSumMarketCapitalBySectorASXforPortfolio/{portfolioId}','SectorController@getSumMarketCapitalBySectorASXforPortfolio');


    Route::get('testMail', function() {

        Mail::send('emails.test', array('msg' => 'This is the body of my email'), function($message)
        {
            $message->from('admin@diamatic.com.au', 'admin');
            $message->to('mohamedrks@gmail.com', 'John Smith')->subject('Welcome!'); // tony.t.lucas@gmail.com
        });

    });

    Route::post('oauth/access_token', function() {
        return Response::json(Authorizer::issueAccessToken());
    });

    Route::get('testing', function() {

       return Response::json(array('name' => 'Steve', 'state' => 'CA'));
    });
});
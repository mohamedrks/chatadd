<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 1/13/15
 * Time: 11:07 AM
 */

class SubscribedStockInfo extends BaseModel  {

    protected $table = 'subscribed_stock_info';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();

    }

    public static function querySelect(  ){


        return "  SELECT subscribed_stock_info.* FROM subscribed_stock_info  ";
    }
    public static function queryWhere(  ){

        return " ";
    }

    public static function queryGroup(){
        return "  ";
    }


}

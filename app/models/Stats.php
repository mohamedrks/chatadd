<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 2/11/15
 * Time: 12:35 PM
 */

class Stats extends BaseModel  {

    protected $table = 'stats_input';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();

    }

    public static function querySelect(  ){


        return " SELECT  stats_input.* FROM stats_input ";
    }
    public static function queryWhere(  ){

        return " ";
    }

    public static function queryGroup(){
        return "      ";
    }

    public function portfolio(){

        return $this->belongsTo('Portfolio','portfolio_id');
    }
}

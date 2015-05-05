<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 1/20/15
 * Time: 3:03 PM
 */


class StatsInput extends BaseModel  {

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

    public function user(){

        return $this->belongsTo('User','user_id');
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 2/10/15
 * Time: 4:24 PM
 */

class Mortgage extends BaseModel  {

    protected $table = 'mortgage';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();

    }

    public static function querySelect(  ){


        return " SELECT  mortgage.* FROM mortgage ";
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

    public function marginloan(){

        return $this->belongsToMany('MarginLoan');
    }
}

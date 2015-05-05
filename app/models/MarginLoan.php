<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 2/6/15
 * Time: 10:23 AM
 */


class MarginLoan extends BaseModel  {

    protected $table = 'margin_loan';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();

    }

    public static function querySelect(  ){


        return " SELECT  margin_loan.* FROM margin_loan ";
    }
    public static function queryWhere(  ){

        return " ";
    }

    public static function queryGroup(){
        return "      ";
    }

    public function marginloantype(){

        return $this->belongsTo('MarginLoanType');
    }

    public function mortgage(){

        return $this->belongsTo('Mortgage','mortgage_id');
    }
}

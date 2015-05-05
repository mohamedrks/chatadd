<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 2/6/15
 * Time: 10:26 AM
 */

class MarginLoanType extends BaseModel  {

    protected $table = 'margin_loan_type';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();

    }

    public static function querySelect(  ){


        return " SELECT  margin_loan_type.* FROM margin_loan_type ";
    }
    public static function queryWhere(  ){

        return " ";
    }

    public static function queryGroup(){
        return "      ";
    }

    public function marginloan(){

        return $this->belongsToMany('MarginLoan');
    }
}

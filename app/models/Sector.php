<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 1/16/15
 * Time: 2:39 PM
 */

class Sector extends BaseModel  {

    protected $table = 'sector';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();

    }

    public static function querySelect(  ){


        return " SELECT  industry.* FROM industry ";
    }
    public static function queryWhere(  ){

        return " ";
    }

    public static function queryGroup(){
        return "      ";
    }

    public function industry(){

        return $this->belongsToMany('Industry');
    }
}

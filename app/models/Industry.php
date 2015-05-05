<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 1/15/15
 * Time: 12:57 PM
 */

class Industry extends BaseModel  {

    protected $table = 'industry';
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

    public function symbol(){

        return $this->belongsToMany('Symbol');
    }

    public function sector(){

        return $this->belongsToMany('Sector');
    }
}


<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 12/11/14
 * Time: 1:15 PM
 */

class Symbol extends BaseModel  {

    protected $table = 'symbol';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();

    }

    public static function querySelect(  ){


        return "  SELECT symbol.* FROM symbol  ";
    }
    public static function queryWhere(  ){

        return "";
    }

    public static function queryGroup(){
        return "  ";
    }

    public function news()
    {
        return $this->hasMany('News');
    }

    public function industry(){

        return $this->belongsToMany('Industry');
    }
}
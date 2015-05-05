<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 12/10/14
 * Time: 2:50 PM
 */

class Sentiment extends BaseModel  {

    protected $table = 'sentiment';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();

    }

    public static function querySelect(  ){


        return "  SELECT sentiment.* FROM sentiment  ";
    }
    public static function queryWhere(  ){

        return "";
    }

    public static function queryGroup(){
        return "  ";
    }

    public function symbol()
    {
        return $this->belongsTo('Symbol','symbol_id');
    }

}
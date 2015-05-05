<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 12/10/14
 * Time: 12:26 PM
 */
class NewsSymbols extends BaseModel  {

    protected $table = 'news_symbol';

    public function __construct() {
        parent::__construct();

    }

    public static function querySelect(  ){


        return "  SELECT news_symbol.* FROM news_symbol  ";
    }
    public static function queryWhere(  ){

        return "";
    }

    public static function queryGroup(){
        return "  ";
    }

    public function news_id()
    {
        return $this->hasMany('News','id');
    }

    public function symbol_id()
    {
        return $this->hasMany('Symbol','id');
    }

}
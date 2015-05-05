<?php

class News extends BaseModel
{

    protected $table = 'news';
    protected $primaryKey = 'id';

    public function __construct()
    {
        parent::__construct();

    }

    public static function querySelect()
    {


        return "  SELECT news.* FROM news  ";
    }

    public static function queryWhere()
    {

        return " WHERE news.id IS NOT NULL   ";
    }

    public static function queryGroup()
    {
        return "  ";
    }


    public function symbol()
    {

        return $this->belongsTo('Symbol', 'symbol_id');

    }

    public function sentiment()
    {
        return $this->belongsTo('Sentiment', 'sentiment_id');
    }

    public function subscribe()
    {
        return Subscribe ::where('user_id', '=', 1)->distinct()->get(array('stock_symbol'));
    }

}

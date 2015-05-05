<?php

class StockTolerance extends BaseModel
{

    protected $table = 'stock_tolerance';
    protected $primaryKey = 'id';

    public function __construct()
    {
        parent::__construct();

    }

    public static function querySelect()
    {


        return "  SELECT stock_tolerance.* FROM stock_tolerance ";

    }

    public static function queryWhere()
    {

        return " ";
    }

    public static function queryGroup()
    {
        return "  ";
    }

    public function symbol()
    {
        return $this->belongsTo('Symbol', 'symbol_id');
    }

    public function user()
    {
        return $this->belongsTo('User', 'user_id');
    }
}

<?php

class IndicatorTolerance extends BaseModel
{

    protected $table = 'indicator_tolerance';
    protected $primaryKey = 'id';

    public function __construct()
    {
        parent::__construct();

    }

    public static function querySelect()
    {


        return "SELECT * FROM indicator_tolerance";
    }

    public static function queryWhere()
    {

        return " ";
    }

    public static function queryGroup()
    {
        return "  ";
    }

    public function indicator()
    {
        return $this->belongsTo('Indicator','indicator_id');
    }

    public function user()
    {
        return $this->belongsTo('User','user_id');
    }

}

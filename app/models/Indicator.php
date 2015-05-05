<?php

class Indicator extends BaseModel
{

    protected $table = 'indicator';
    protected $primaryKey = 'id';

    public function __construct()
    {
        parent::__construct();

    }

    public static function querySelect()
    {


        return "  SELECT indicator.* FROM indicator  ";
    }

    public static function queryWhere()
    {

        return "";
    }

    public static function queryGroup()
    {
        return "  ";
    }

}
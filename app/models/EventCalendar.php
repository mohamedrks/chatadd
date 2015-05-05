<?php

class EventCalendar extends BaseModel
{

    protected $table = 'event';
    protected $primaryKey = 'id';

    public function __construct()
    {
        parent::__construct();

    }

    public static function querySelect()
    {

        return "  SELECT event.* FROM event  ";
    }

    public static function queryWhere()
    {

        return " ";
    }

    public static function queryGroup()
    {
        return "  ";
    }


}

<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 2/12/15
 * Time: 10:19 AM
 */

class EventManager extends BaseModel
{

    protected $table = 'event_manager';
    protected $primaryKey = 'id';

    public function __construct()
    {
        parent::__construct();

    }

    public static function querySelect()
    {


        return "  SELECT event_manager.* FROM event_manager ";

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
        return $this->belongsTo('Indicator', 'indicator_id');
    }

    public function user()
    {
        return $this->belongsTo('User', 'user_id');
    }
}

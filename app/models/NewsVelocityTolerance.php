<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 2/6/15
 * Time: 9:16 AM
 */

class NewsVelocityTolerance extends BaseModel
{

    protected $table = 'news_velocity_tolerance';
    protected $primaryKey = 'id';

    public function __construct()
    {
        parent::__construct();

    }

    public static function querySelect()
    {


        return "  SELECT news_velocity_tolerance.* FROM news_velocity_tolerance ";

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

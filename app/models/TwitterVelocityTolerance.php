<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 2/11/15
 * Time: 4:44 PM
 */

class TwitterVelocityTolerance extends BaseModel
{

    protected $table = 'twitter_velocity_tolerance';
    protected $primaryKey = 'id';

    public function __construct()
    {
        parent::__construct();

    }

    public static function querySelect()
    {


        return "  SELECT twitter_velocity_tolerance.* FROM twitter_velocity_tolerance ";

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

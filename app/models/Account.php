<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 1/6/15
 * Time: 9:23 AM
 */

class Account extends BaseModel  {

    protected $table = 'account';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();

    }

    public static function querySelect(  ){


        return "  SELECT account.* FROM account  ";
    }
    public static function queryWhere(  ){

        return "";
    }

    public static function queryGroup(){
        return "  ";
    }

    public function portfolio(){

        return $this->belongsToMany('Portfolio');
    }

    public function account()
    {
        return $this->hasMany('Client');
    }

    public function note()
    {
        return $this->hasMany('Note');
    }

    public function receivertype(){

        return $this->belongsTo('ReceiverType','receiver_type_id');
    }
}
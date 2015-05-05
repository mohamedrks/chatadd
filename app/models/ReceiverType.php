<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 2/17/15
 * Time: 3:59 PM
 */


class ReceiverType extends BaseModel  {

    protected $table = 'receiver_type';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();

    }

    public static function querySelect(  ){


        return "  SELECT receiver_type.* FROM receiver_type  ";
    }
    public static function queryWhere(  ){

        return "";
    }

    public static function queryGroup(){
        return "  ";
    }

}
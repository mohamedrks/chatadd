<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 12/23/14
 * Time: 2:44 PM
 */
class Organisation extends BaseModel  {

    protected $table = 'organisation';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();

    }

    public static function querySelect(  ){


        return "  SELECT organisation.* FROM organisation  ";
    }
    public static function queryWhere(  ){

        return "";
    }

    public static function queryGroup(){
        return "  ";
    }

}
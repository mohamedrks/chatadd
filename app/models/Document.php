<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 2/23/15
 * Time: 12:04 PM
 */


class Document extends Eloquent  {


    protected $table = 'document';
    protected $primaryKey = 'id';


    public function __construct() {
        parent::__construct();

    }

    public static function querySelect(  ){


        return "  SELECT document.* FROM document  ";
    }
    public static function queryWhere(  ){

        return "";
    }

    public static function queryGroup(){
        return "  ";
    }


}
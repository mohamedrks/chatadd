<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 2/17/15
 * Time: 3:53 PM
 */

class Note extends BaseModel  {

    protected $table = 'note';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();

    }

    public static function querySelect(  ){


        return "  SELECT note.* FROM note  ";
    }
    public static function queryWhere(  ){

        return "";
    }

    public static function queryGroup(){
        return "  ";
    }

}
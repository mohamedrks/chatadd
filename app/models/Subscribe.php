<?php
/**
 * Created by PhpStorm.
 * User: RikiJoe
 * Date: 4/26/2015
 * Time: 8:07 AM
 */


class Subscribe extends BaseModel  {

    protected $table = 'subscribe';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();

    }

    public static function querySelect(  ){


        return " SELECT  subscribe.* FROM subscribe ";
    }
    public static function queryWhere(  ){

        return " ";
    }

    public static function queryGroup(){
        return "      ";
    }

    public function category(){

        return $this->belongsTo('Category');
    }

    public function users(){

        return $this->belongsTo('User');
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: RikiJoe
 * Date: 4/26/2015
 * Time: 7:35 AM
 */

class Add extends BaseModel  {

    protected $table = 'add';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();

    }

    public static function querySelect(  ){


        return " SELECT  add.* FROM add ";
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

    public function country(){

        return $this->belongsTo('Country');
    }

    public function suburb(){

        return $this->belongsTo('Suburb');
    }

    public function users(){

        return $this->belongsTo('User');
    }
}
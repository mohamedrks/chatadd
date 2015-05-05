<?php
class Portfolio extends BaseModel  {
	
	protected $table = 'portfolio';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){

		return "  SELECT portfolio.* FROM portfolio  ";
	}
	public static function queryWhere(  ){
		
		return "  ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	
    public function client(){

        return $this->belongsToMany('Client');
    }

    public function account(){

        return $this->belongsToMany('Account');
    }

//    public function mortgage(){
//
//        return $this->belongsToMany('Mortgage');
//    }

    public function user(){

        return $this->belongsTo('Users');
    }
}

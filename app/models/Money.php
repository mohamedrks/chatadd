<?php
class Money extends BaseModel  {
	
	protected $table = 'indicators';
	protected $primaryKey = 'Id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		
		return " SELECT * FROM  indicators   ";
	}
	public static function queryWhere(  ){
		
		return "where rtrim(ltrim(category)) = 'Money'   ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

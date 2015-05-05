<?php
class Government extends BaseModel  {
	
	protected $table = 'indicators';
	protected $primaryKey = 'Id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		
		return " SELECT * FROM  indicators   ";
	}
	public static function queryWhere(  ){
		
		return "where rtrim(ltrim(category)) = 'Government'   ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

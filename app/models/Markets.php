<?php
class Markets extends BaseModel  {
	
	protected $table = 'indicators';
	protected $primaryKey = 'Id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		
		return " select * from indicators ";
	}
	public static function queryWhere(  ){
		
		return "where rtrim(ltrim(category)) = 'Markets'   ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}

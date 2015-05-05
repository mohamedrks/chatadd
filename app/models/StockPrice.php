<?php
class StockPrice extends BaseModel  {
	
	protected $table = 'stock_price';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		
		return "  SELECT stock_price.* FROM stock_price  ";
	}
	public static function queryWhere(  ){
		
		return " ";
	}
	
	public static function queryGroup(){
		return "  ";
	}

    public function symbol()
    {
        return $this->belongsTo('Symbol','symbol_id');
    }
}

<?php
class Transaction extends BaseModel  {
	
	protected $table = 'transaction';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		
		return "  SELECT transaction.* FROM transaction  ";
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

    public function portfolio(){

        return $this->belongsTo('Portfolio','portfolio_id');
    }

    public function transaction()
    {
        return $this->belongsTo('Transaction','parent_transaction');
    }
}

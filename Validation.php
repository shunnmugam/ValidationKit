<?php
//error_reporting(1);
class Validation
{
    const V_DB_SERVER = "localhost";
    const V_DB_USER = "redwebde_rwsrest";
    const V_DB_PASSWORD = "N!*eiGO_tQz7";
    const V_DB = "redwebde_rws_restaurant";
    //public $validationdb = NULL;

	public function __construct()
    {
    	$this->validationdb = "";
    	self::validation_dbConnect(); // Initiate Database connection
        $this->errorArray = []; // Initiate errorArray
    }
    //Database connection
    private function validation_dbConnect()
    {
        $this->validationdb = mysql_connect(self::V_DB_SERVER, self::V_DB_USER, self::V_DB_PASSWORD);
        if ($this->validationdb)
            mysql_select_db(self::V_DB, $this->validationdb);
    }
	public function validater($request,$rule)
	{
		if($rule =="" || count($rule)==0 )
			return 0;
		foreach ($rule as $rulevalue => $rules) {
			$ruleArray = explode("|", $rules);
			if(isset($request[$rulevalue]))
				self::ruleCheck($ruleArray,$request[$rulevalue]);
			else
				$this->errorArray[] = 1;
		}
		//return 1 means no error
		if (in_array(1, $this->errorArray))
			return 0;
		else
			return 1;
		
	}
	public function ruleCheck($ruleArray,$rulevalue)
	{
		foreach ($ruleArray as $key => $value) {
			if($value == "required" )
				$this->errorArray[] = self::requiredCheck($rulevalue);
			elseif (substr($value, 0, 4) == "min:") 
				$this->errorArray[] = self::minCheck($rulevalue,$value);
			elseif (substr($value, 0, 4) == "max:") 
				$this->errorArray[] = self::maxCheck($rulevalue,$value);
			elseif($value == "email")
				$this->errorArray[] = self::emailCheck($rulevalue);
			elseif (substr($value, 0, 7) == "exists:")
				 $this->errorArray[] = self::existsCheck($rulevalue,$value);
		}

	}
	public function requiredCheck($value)
	{
		if($value == "" || count($value)==0)
			return 1;
		else
			return 0;
	}
	public function minCheck($value,$min)
	{
		$min = substr($min, strpos($min, ":") + 1);
		if(strlen($value) >= $min)
			return 0;
		else
			return 1;
	}
	public function maxCheck($value,$max)
	{
		$max = substr($max, strpos($max, ":") + 1);
		if(strlen($value) <= $max)
			return 0;
		else
			return 1;
	}
	public function emailCheck($value)
	{
		if(filter_var($value, FILTER_VALIDATE_EMAIL))
			return 0;
		else
			return 1;
	}
	public function existsCheck($value,$data)
	{
		$data       = substr($data, strpos($data, ":") + 1);
		$table      = substr($data, 0, strpos($data, '.'));
		$field = substr($data, strpos($data, ".") + 1);
		$sql = mysql_query("SELECT * FROM $table WHERE $field = '$value'", $this->validationdb);
		if (mysql_num_rows($sql) > 0)
			return 0;
		else
			return 1;
	}
}
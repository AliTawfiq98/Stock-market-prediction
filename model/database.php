

<?php

class dbh
{
    /*
   private static $_instance;

    
    
    	
    	public function __construct()
    	{
    		# code...
             $this->servername="localhost";
                $this->username="root";
        $this->password="";
        $this->dbname="erp";
        $conn=new mysqli($this->servername,$this->username,$this->password, $this->dbname);

        return $conn;
        
    		
    	}
    

    

    public static function getInstance() {

		if(!self::$_instance) { // If no instance then make one
			self::$_instance = new self();
		}
		return self::$_instance;
	}
   
    public function connect()

	{
		  $this->servername="localhost";
                $this->username="root";
        $this->password="";
        $this->dbname="erp";
		$conn=new mysqli($this->servername,$this->username,$this->password,	$this->dbname);

		return $conn;
						

	}

  
    final public function __clone()
    {
        throw new Exception('Feature disabled.');
    }

    final public function __wakeup()
    {
        throw new Exception('Feature disabled.');
    }
*/
   private static $connect;


function __construct()
{

    $this->db_connect();
}


function db_connect()
{

if(!self::$connect)
{

self::$connect=new mysqli("localhost","root","","smp");

if(self::$connect->connect_error)
{
    die("conn failed".$connect->connect_error);
}

}
return self::$connect;

}


function db_query($query)
{

    $result=mysqli_query($this->db_connect(),$query);
    if(!$result)
    {
        echo ("error".mysqli_error($this->db_connect()));
    }
    return $result;
}


}





  ?>


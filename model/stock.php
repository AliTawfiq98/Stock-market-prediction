<?php

include("../model/database.php") ;

class stock 
{
function __construct()
{
    $this->db=new dbh();
}
function search($tickerName)
{
	$sql = "SELECT stockName FROM stock where stockTicker='$tickerName'";
	$result=$this->db->db_query($sql);
	return $result;
}
function show()
{
	$sql = "SELECT  * FROM stock ";
	$result=$this->db->db_query($sql);
	return $result;
}
}
?>
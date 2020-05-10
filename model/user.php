<?php

	include("../model/database.php") ;

class user  
{
function __construct()
{

    $this->db=new dbh();
}

function insert($username,$email,$password,$telephone,$usertypeid)
{
$sql = "INSERT INTO user(username,email,password,telephone,usertypeid)
VALUES('$username','$email','$password','$telephone','$usertypeid')";	 
//$result = $x->connect()->query($sql);  
$result=$this->db->db_query($sql);
}

function show()
{
	$sql = "SELECT * FROM user";
	$result=$this->db->db_query($sql);
	return $result;
}
function update($id,$usertype)
{
	$sql = "UPDATE user SET usertypeid='$usertype' WHERE id=$id";
	$result=$this->db->db_query($sql);
}
function delete($id)
{
	$sql = "DELETE FROM user WHERE id='$id'";
	$result=$this->db->db_query($sql);
}	
function show1($username,$email)
{
	$sql = "SELECT * FROM user WHERE userName='$username' OR email='$email' LIMIT 1";
	$result=$this->db->db_query($sql);
	return $result;
}
function show2($username,$password)
{
    $sql = "SELECT * FROM user WHERE userName='$username' AND password='$password' ";
	$result=$this->db->db_query($sql);
	return $result;
}
function showid($name)
{
	$sql = "SELECT id FROM usertype WHERE name='$name' LIMIT 1";
	$result=$this->db->db_query($sql);
	$row=mysqli_fetch_array($result);
	return $row[0];
}


}



 ?>

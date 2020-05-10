<?php

include("../model/database.php") ;

class contact 
{
function __construct()
{
    $this->db=new dbh();
}
function insert($name,$email,$number,$description)
{
$sql = "INSERT INTO requests(name,email,number,description)
        VALUES('$name','$email','$number','$description')";	 
$result=$this->db->db_query($sql);
}

function show()
{
	$sql = "SELECT * FROM requests";
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
	$sql = "DELETE * FROM requests WHERE id='$id'";
	$result=$this->db->db_query($sql);
}	


}



 ?>

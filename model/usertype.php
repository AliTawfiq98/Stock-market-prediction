<?php
require_once 'database.php';

class usertype extends dbh
{
	function __construct()
{

    $this->db=new dbh();
}


function insert($name)
	{
		
    $sql = "INSERT INTO usertype(name)
    VALUES('$name')";	 
    $result=$this->db->db_query($sql);

	}



function show()
{
	
	$sql = "SELECT * FROM usertype";
$result=$this->db->db_query($sql);

if ($result->num_rows > 0) 
{ 
  	 		
    while($row = $result->fetch_assoc())
     {
  echo "<tr>";
  echo "<td>". $row['name'] ."</td>";
  echo "<td><a class=edit data-toggle=modal href=editusertype.php?id=".$row['id']."><i class=material-icons data-toggle=tooltip title=Edit>&#xE254;</i></a></td>";  
  echo "<td><a class=delete data-toggle=modal href=../controller/cusertype.php?id=" . $row['id'] . "><i class=material-icons data-toggle=tooltip title=Delete>&#xE872;</i></a></td>";  
}
}
 else
 {
    echo "0 results";
}
}

 	
function update($name,$id)
{
$sql = "UPDATE usertype SET name='$name' WHERE id=$id";
$result=$this->db->db_query($sql);

}
function delete($id)
{


$sql = "DELETE FROM usertype WHERE id='$id'";
$result=$this->db->db_query($sql);

}	
function show1($id)
{

$sql = "SELECT * FROM usertype WHERE id='$id'";
$result=$this->db->db_query($sql);
return $result;

}
function permission()
{

$sql = "SELECT * FROM pages ";
$result=$this->db->db_query($sql);
if ($result->num_rows > 0) 
{ 
  while($row = $result->fetch_assoc())
     {
  echo "<tr>";
  echo "<td>". $row['name'] ."</td>";
  echo "<td> <input type='checkbox' value=".$row['id']." name='ch[]'> </td>";
   
}
}
 else
 {
    echo "0 results";
}

}


function  link($usertypeid,$pageid)
{
 $sql = "INSERT INTO links(usertypeid,pageid)
VALUES('$usertypeid',$pageid)";  
$result=$this->db->db_query($sql);



}


}




  ?>
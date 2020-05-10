<?php

//include("../model/database.php") ;
require_once "../model/facade.php";

$myobject= new facade();

if(isset($_POST['submit']))
{
$name = test_input($_POST["name"]);
$myobject->Insertusertype($name); 
header( 'Location: ../viewer/usertypehome.php' );
}

if(isset($_POST['edittype']))
  {
  $id=test_input($_POST['id']);
  $name=test_input($_POST['name']); 

$count = count($_POST['ch']);
for($i=0;$i<$count;$i++)
{

$value=test_input($_POST['ch'][$i]);
$myobject->linkusertype($id,$value);

}
$myobject->Updateusertype($name,$id);

header( 'Location: ../viewer/usertypehome.php' );

  }


  if(isset($_GET['id']))
{
  $id=$_GET['id'];
  $myobject->Deleteusertype($id);
  header( 'Location: ../viewer/usertypehome.php' );
}

function test_input($data)
   {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
  }

  ?>
<?php

require_once "../model/contact.php";
$myobject = new contact();

if(isset($_POST['submit']))
{
    $name = test_input($_POST['name']);
    $email = test_input($_POST['email']);
    $number = test_input($_POST['num']);
    $description=test_input($_POST['description']);

    $myobject->insert($name,$email,$number,$description);
    
    header( 'Location: ../viewer/index2.html' );
    
}
function test_input($data)
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
if(isset($_GET['id']))
{
  $id=$_GET['id'];
  $myobject->delete($id);
  header( 'Location: ../viewer/requestshome.php' );
}
?>
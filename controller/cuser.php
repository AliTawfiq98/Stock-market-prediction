<?php

require_once "../model/user.php";
$myobject = new user();

if(isset($_POST['submit']))
{
    $username = test_input($_POST["username"]);
    $email = test_input($_POST["email"]);
    $ps = test_input($_POST["password"]);
    $number = test_input($_POST["num"]);
    $utid= test_input($_POST["type"]);

    $usertype=$myobject->showid($utid);

    $password=md5($ps);

    $myobject->insert($username,$email,$password,$number,$usertype);
    header( 'Location: ../viewer/userhome.php' );
    return true;
}
function test_input($data)
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

?>
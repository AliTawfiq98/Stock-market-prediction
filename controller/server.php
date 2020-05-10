
<?php

include_once("index.php");
 // include("../model/database.php") ;
     include("../model/user.php") ;
   //  $myobject=   user::getInstance();
     $myobject=new user();
  session_start(); 

$errors = array();

if(isset($_POST['submit'])){



$fn = test_input($_POST["username"]);
$em = test_input($_POST["email"]);
$ps = test_input($_POST["psw"]);
$ps1 = test_input($_POST["psw1"]);
$mb = test_input($_POST["mobilenumber"]);



  if (empty($fn)) { array_push($errors, "Username is required"); }
  if (empty($em)) { array_push($errors, "Email is required"); }
  if (empty($ps)) { array_push($errors, "Password is required"); }

  
   if (!preg_match("/^[a-zA-Z ]*$/",$fn)) 
    {
            array_push($errors, "Only letters and space allowed");
    }
    if (!filter_var($em, FILTER_VALIDATE_EMAIL))
     {
            array_push($errors, "invalid email format");
    }
  /*  if(!preg_match("/^.*(?=.{8,})(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).*$/", $ps))
    {
            array_push($errors, "Password must be at least 8 characters and must contain at least one lower case letter,one upper case letter and one digit");

    }*/
if(!preg_match("/^[0-9]*$/",$mb))
{
array_push($errors,"mobile number must be only numbers");
}
if ($ps != $ps1) 
{
  array_push($errors, "The two passwords do not match");
  }

 // $user_check_query = "SELECT * FROM user WHERE userName='$fn' OR email='$em' LIMIT 1";
  //$result = mysqli_query($connn, $user_check_query);
  //$user = mysqli_fetch_assoc($result);
$row=$myobject->show1($fn,$em);

        
if($row)
{
foreach ($row as $user) {

 
    if ($user['username'] === $fn) {
      array_push($errors, "Username already exists");
    }

    if ($user['email'] === $em) {
      array_push($errors, "email already exists");
    }
}
}

if (count($errors) == 0) {
	  	$password = md5($ps);
/*
  $sql = "INSERT INTO user(userName, email,password,mobile,type)
VALUES('$fn','$em','$password','$mb','$type')";
$query = mysqli_query($connn, $sql); 


  $_SESSION['username'] = $fn;
    $_SESSION['success'] = "You are now logged in";
   header('location: welcome.php');
   */

 

   $myobject->insert($fn,$em,$password,$mb,$type1);

  }




}
if (isset($_POST['login_user'])) {
  $username =test_input($_POST["username"]); 
  $password = test_input($_POST['password']);

  if (empty($username)) {
    array_push($errors, "Username is required");
  }
  if (empty($password)) {
    array_push($errors, "Password is required");
}



 if (count($errors) == 0) {
    $password = md5($password);
   

    $row=$myobject->show2($username,$password);
    if (mysqli_num_rows($row)==0)  {
      array_push($errors, "Invalid username or password");
    }
    
foreach ($row as $user) {


   $_SESSION['username'] = $user['username'];
      $_SESSION['id'] = $user['id'];


$typeid=$user['usertypeid'];

$sql="SELECT * from links where usertypeid='$typeid'";
$result= mysqli_query($connn, $sql);
     while($row = $result->fetch_assoc())
     {
      $pageid=$row['pageid'];
      $sql1="SELECT * from pages where id='$pageid'";
      $result1= mysqli_query($connn, $sql1);
      while($row1 = $result1->fetch_assoc())
      {
        $array[]=$row1['name'];
        $array1[]=$row1['physicallink'];
        
      }
      $_SESSION['pagename']=$array;
      $_SESSION['link']=$array1;  
       
     }
      header('location:panel.php');




    
      
  }
  }
 
}

if (isset($_GET['logout']))

 {
    session_destroy();
    unset($_SESSION['username']);
    header("location: signin.php");
  }


  function test_input($data)
   {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
  }


  if(isset($_POST['edituser']))
  {

  $id=test_input($_POST['id']);
    
  $type=test_input($_POST['type']);

$myobject->update($id,$type);
header( "Location: ../viewer/userhome.php" );


  }
  if(isset($_GET['id']))
{
  $id=$_GET['id'];
  $myobject->delete($id);
  header( 'Location: ../viewer/userhome.php' );
}


?>		
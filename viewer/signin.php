<?php include('../controller/server.php') ?>
    
<!DOCTYPE html>
<html>
<head>
  <title></title>
  <link rel="stylesheet" type="text/css" href="signin.css">
  <link rel="shortcut icon" href="img/stock.png">


</head>
<body>   
  <form method="post" action="">  
    <div class="login-box">
    <?php include('../controller/error.php'); ?>

    <h1>Login</h1>
          <div class="textbox">
        <i class="fa fa-user" aria-hidden="true"></i>
      <input type="text" name="username" placeholder="Username">  
          </div>
<div class="textbox">
<i class="fa fa-lock  " aria-hidden="true"></i>

      <input type="password" name="password" placeholder="Password">
</div>
      <button type="submit" class="btn" name="login_user" >Sign in</button>
   
    </div>
    </div>
    <a href="index2.html" class="button">Go back home</a>
   
    </div>
  </form>
</body>
</html>
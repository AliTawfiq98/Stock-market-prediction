
<?php
include('panel.php');

$servername = "localhost";
$username = "root";
$password = "";
$db = "smp";
// Create connection
$connn = new mysqli($servername, $username, $password,$db);

  $id = $_GET['id'];
$sql = "SELECT id FROM user";
$result = mysqli_query($connn,$sql);
while($row = mysqli_fetch_array($result))
{
  if($id==(md5($row['id'])))
  {
    $nid=$row['id'];
  }
}



$sql = "SELECT * FROM user where id='$nid'";
$result = mysqli_query($connn,$sql);

while($row = mysqli_fetch_array($result))
{
	$type=$row['usertypeid'];
}


$sql = "SELECT * FROM usertype";
$result2 = mysqli_query($connn,$sql);
	

	


?>
<!DOCTYPE html>
<html>
<head>

</head>
<body>
 <div class="container">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-6">
						<h2>Edit <b>users</b></h2>
					</div>
					
                </div>
            </div>
            <form action="../controller/server.php " method="post">
 <div class="form-group">
							<label>Type:</label>
							<select class="form-control" name="type" ">
								<?php  while($row1=mysqli_fetch_array($result2)):; ?>
									<option value="<?php echo $row1[0]; ?> "  <?php if($type=="$row1[0]") echo 'selected="selected"'; ?>><?php echo $row1[1];  ?>
								
	</option>
								<?php endwhile;  ?>
							</select>
						</div>	

  <div class="form-group">
    <input type="hidden" name="id"   value="<?php echo $nid;?>">

  </div>
  <button type="submit" class="btn btn-primary" name="edituser">Submit</button>
</form>
        </div>
    </div>
</body>
</html>

<?php

include('panel.php');


$servername = "localhost";
$username = "root";
$password = "";
$db = "smp";
// Create connection
$connn = new mysqli($servername, $username, $password,$db);


 ?>




<!DOCTYPE html>
<html lang="en">
<head>
<link href="css/addons/datatables.min.css" rel="stylesheet">
</head>
<!-- MDBootstrap Datatables  -->
<script type="text/javascript" src="js/addons/datatables.min.js"></script>

    <div class="container" >
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-6">
						<h2><b>Manage Users</b></h2>
					</div>
					<div class="col-sm-6">
						<a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Add New Client</span></a>
					</div>
                </div>
            </div>
            <table width="100%" id="dtBasicExample"class="table table-striped table-hover">
                <thead>
                    <tr>
					    <th>Username</th>
					    <th>Email</th>
				        <th>Telephone</th>
					    <th>Type</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                                <tbody>
                    <?php

     include("../model/user.php") ;
$myobject=  new user();
$rows=$myobject->show();
if($rows)
{
foreach ($rows as $row) {

	# code...
	?>

<tr>
	<td><?php echo $row['username'] ?></td>
		<td><?php echo $row['email'] ?></td>	
				<td><?php echo $row['telephone'] ?></td>	


	 <td>
	<?php 
$usertypeid=$row['usertypeid'];
$sql= "SELECT * FROM usertype WHERE id='$usertypeid' limit 1";
$result = $connn->query($sql);  
if ($result->num_rows > 0) 
{ 
$value = mysqli_fetch_assoc($result);
echo $value['name'];
}
else
{
	echo "Null";
}
	 ?>
	 	
	 </td>	

<td><a class=edit data-toggle=modal href=edituser.php?id=<?php echo md5($row['id']); ?>><i class=material-icons data-toggle=tooltip title=Edit>&#xE254;</i></a></td>  
<td><a class=delete data-toggle=modal href=../controller/server.php?id=<?php echo $row['id']; ?>><i class=material-icons data-toggle=tooltip title=Delete>&#xE872;</i></a></td> 	
</tr>


<?php 
}
}
  ?>
    <div id="addEmployeeModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form action="../controller/cuser.php" method="post">
					<div class="modal-header">						
						<h4 style="font-size:24px;"class="modal-title">Add Client</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">					
						<div class="form-group">
							<label>Username</label>
                            <input type="text" class="form-control" required name="username" >
                            <label>Email</label>
							<input type="email" class="form-control" required name="email" >
                            <label>Password</label>
                            <input type="password" class="form-control" required name="password" >
                            
                            <label>Phone Number</label>
                            <input type="number" class="form-control" required name="num" >
                            
                            <label>User Access</label>
                            <select class="form-control" name="type" id="type">
                            <?php  
                                $que="SELECT name from usertype";
								$result1=mysqli_query($connn,$que);
								while($row=mysqli_fetch_array($result1)):;?>	
								<option value="<?php echo $row[0];?>"><?php echo $row[0];?></option>
								<?php endwhile;?> 
                            </select>

                        
                        </div>


						
							
					</div>
					<div class="modal-footer">
						<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
						<input type="submit" class="btn btn-success" value="Add" name="submit">
					</div>
				</form>
			</div>
		</div>
	</div>
</body>
<script>
$(document).ready(function () {
  $('#dtBasicExample').DataTable();
  $('.dataTables_length').addClass('bs-select');
});
</script>
</html>           
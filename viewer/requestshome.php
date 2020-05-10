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
						<h2><b>Manage Requests</b></h2>
					</div>
					
                </div>
            </div>
            <table width="100%" id="dtBasicExample" class="table table-striped table-hover">
                <thead>
                    <tr>
					    <th>Name</th>
					     <th>Email</th>
				        <th>Number</th>
					     <th>Request</th>
                         <th>Delete</th>

                    </tr>
                </thead>
                                <tbody>
                    <?php

     include("../model/contact.php") ;
$myobject=  new contact();
$rows=$myobject->show();
if($rows)
{
foreach ($rows as $row) {

	# code...
	?>

<tr>
    <td><?php echo $row['name'] ?></td>
    <td><?php echo $row['email'] ?></td>	
    <td><?php echo $row['number'] ?></td>	
    <td><?php echo $row['description'] ?></td>	
  	<td><a class=delete data-toggle=modal href=../controller/ccontact.php?id=<?php echo $row['id']; ?>><i class=material-icons data-toggle=tooltip title=Delete>&#xE872;</i></a></td> 	
</tr>


<?php 
}
}



  ?>
                   
</body>
<script>
$(document).ready(function () {
  $('#dtBasicExample').DataTable();
  $('.dataTables_length').addClass('bs-select');
});
</script>
</html>           
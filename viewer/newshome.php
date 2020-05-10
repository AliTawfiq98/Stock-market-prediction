<?php

include('panel.php');
include("../model/stock.php");


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
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
</head>
<!-- MDBootstrap Datatables  -->
<script type="text/javascript" src="js/addons/datatables.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>

    <div class="container" >
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-6">
						<h2><b>Analyze Stocks</b></h2>
					</div>
			    </div>
            </div>
            
            <table width="100%" id="dtBasicExample"class="table table-striped table-hover">
                <thead>
                    <tr  align= "center" style="font-size: 21px;">
                        <th >Stock ID</th>
					    <th>Stock Name</th>
					     <th>Stock Symbol</th>
				        <th>Analyze</th>

					</tr>
                </thead>

                               
                    <?php
$myobject= new stock();
$rows=$myobject->show();
if($rows)
{
foreach ($rows as $row) {

	# code...
	?>

<tr align= "left"style="font-size: 17px;" >
	<td><?php echo $row['id'] ?></td>
	<td><?php echo $row['stockName'] ?></td>	
	<td><?php echo $row['stockTicker'] ?></td>	
    <td><a class=edit data-toggle=modal href=news.php?ticker=<?php echo $row['stockTicker']; ?>><i class=material-icons data-toggle=tooltip title=Analyze>&#xe5c8;</i></a></td>  
</tr>
</div>
    </div>
    </div>

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
<!-- <script>
$(document).ready( function () {
    $('#dtBasicExample').DataTable();
} );
</script> -->
</html>           

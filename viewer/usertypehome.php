<?php include 'panel.php';  ?>
<!DOCTYPE html>
<html lang="en">
<head>


</head>
<body>


    <div class="container">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-6">
						<h2><b>Manage User Types</b></h2>
					</div>
					<div class="col-sm-6">
						<a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Add New Type</span></a>
					</div>
                </div>
            </div>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
					      <th>Name</th>
                          <th>Edit</th>
                        <th>delete</th>

                    </tr>
                </thead>
                                <tbody>
                    <?php

              //include("../model/database.php") ;
     require_once "../model/facade.php";
$myobject= new facade();
$myobject->Showusertype();


  ?>
                    <tr>
						
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit Modal HTML -->
	<div id="addEmployeeModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form action="../controller/cusertype.php" method="post">
					<div class="modal-header">						
						<h4 class="modal-title">Add Type</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">					
						<div class="form-group">
							<label>Name</label>
							<input type="text" class="form-control" required name="name" >
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
	
				</form>
			</div>
		</div>
	</div>
</body>
</html>
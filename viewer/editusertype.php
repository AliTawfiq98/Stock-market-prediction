<?php
include 'panel.php';
  $id = $_GET['id'];
  include("../model/database.php") ;
     include("../model/facade.php") ;
$myobject= new facade();
$rows=$myobject->Show1usertype($id);
foreach ($rows as $row ) {
  # code...

  $name=$row['name']; 

}

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
            <h2>Edit <b>Type</b></h2>
          </div>
          
                </div>
            </div>
            <form action="../controller/cusertype.php" method="post">
  <div class="form-group">
    <label for="name">Name</label>
    <input type="text" class="form-control" id="name" aria-describedby="emailHelp" name="name" required value="<?php echo $name; ?>">
  </div>
  <div class="form-group">
    <input type="hidden" name="id"   value="<?php echo $id;?>">

  </div>


  <div class="form-group">

<table class="table table-striped table-hover">
                <thead>
                    <tr>
                <th>Name</th>
                                        <th>views</th>


                    </tr>
                </thead>
                                <tbody>
                    <?php

        
$myobject->usertype->permission();


  ?>
                    <tr>
            
                </tbody>
            </table>
  </div>
  <button type="submit" class="btn btn-primary" name="edittype">Submit</button>
</form>
        </div>
    </div>
</body>
</html>
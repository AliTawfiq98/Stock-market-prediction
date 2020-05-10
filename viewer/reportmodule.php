<?php
include_once 'panel.php'; 
include_once "../model/database.php";
include_once "ireports.php";

class reports extends abstractreport
{
    public function __construct()
    {
      $this->js = $this->getproductsstate ();
      $this->ireport = new donutview();

    }

function getproductsstate()
{
$db = new dbh();
$con=$db->connect();
$sql = "select name , quantity from product";

$result = mysqli_query($con , $sql );
$str='';
while ($row = mysqli_fetch_assoc($result))
$str .= ' ,["' . $row["name"] . '" , '  .$row['quantity'] . '] ';


$js = "var data = google.visualization.arrayToDataTable([
    ['name', 'quantity']".$str." ]);";
 
return $js;
}

}


$reports = new reports();
$reports->display();
if(isset($_POST['barbtn']))
{
    $reports->setview(new barview());
    $reports->display();
}
if(isset($_POST['dountbtn']))
{
    $reports->setview(new donutview());
    $reports->display();
}

?>

<html>
  <head>
  </head>
  <body>
    <div id="chart" style="margin-left:300px; width: 900px; height: 500px; background-color:none;" ></div>
    <form method="POST">
<button name="dountbtn" type ="submit" style="margin-left:300px;">Donut Chart</button>
<button name="barbtn" type ="submit">Bar Chart</button>
</form>
  </body>
</html>
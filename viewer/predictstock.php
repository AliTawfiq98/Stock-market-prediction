
<?php
include('panel.php');

$servername = "localhost";
$username = "root";
$password = "";
$db = "smp";
// Create connection
$connn = new mysqli($servername, $username, $password,$db);

$ticker = $_GET['ticker'];

?>
<!DOCTYPE html>
<html>
<head>

</head>
<body>
 <div class="container">
    <?php 
        $output = shell_exec('conda run C:\Users\future\PycharmProjects\StockPrediction\forecast.py "'.$ticker.'"');
        echo '<div>';
        echo '<p style="margin-top:3%; font-size:300%">The predictions of '.$_GET['ticker'].' for the following week : </p>';
        echo '<img src="predictions/'.$_GET['ticker'].'.png">'; 
        echo '</div>';
    ?>
    
    
 </div>
 
</body>
</html>

<?php

include('panel.php');

$ticker=$_GET['ticker'];
$output = shell_exec('conda run C:\Users\future\PycharmProjects\StockPrediction\news.py "'.$ticker.'" ');

?>
<!DOCTYPE html>
<html>
<style>
#zeyad{
    position: relative;
    left:200px;
    top:170px;
    padding-top:50px;
}
#imag{
    width:200px;
    height:200px;
    position: relative;
    left:10px;
    top:100px;
}
#auth{
    position: absolute;
    left:210px;
    top:250px;
}
#tit{
    position: absolute;
    left:210px;
    top:150px;
}
#up{
    position: absolute;
    left:1020px;
    top:130px;  
    width:80px;  
}
.grid-container {
    display:grid;
    grid-gap:100px;
    grid-template-rows:auto;
    background-color:white;
    padding: 50px;
    
}
 .grid-item {
    background-color:black;
    border:1px solid rgba(0, 0, 0, 0.8);
    padding:200px;
    padding-bottom:0px;
    width:1000px;
    height:315px;
    font-size:30px;
    justify-items: center;
    position:relative;
    top:40px;
}
#zeyadd{
    text-align: center;
  text-transform: uppercase;
  color: blue;
  border: 1px solid gray;
  position:relative;
    top:180px;
  line-height:0.1px;
  font-family: Impact, Charcoal, sans-serif;

}
#zeyaddd{
    text-align: center;
  text-transform: uppercase;
  color: blue;
  border: 1px solid gray;
  position:relative;
    top:50px;


}
#zeyad3{
    text-align: center;
  text-transform: uppercase;
  color: blue;
  border: 1px solid gray;
  position:relative;
    top:30px;


}
#hh{
    text-align: center;
  text-transform: uppercase;
  color: blue;
  border: 1px solid gray;
  position:relative;
    top:70px;


}
</style>

<div id="zeyad3">
<h1>The selected stock :
     <?php echo $_GET['ticker'] ?>
</h1>
</div>
<?php
$list=array();
$i=0;
$p=0;
$neg=0;
$nut=0;
$file2 = fopen('C:\\xampp\\htdocs\\SMP\\viewer\\sentiments\\sentiment.csv',"r");
while (($row = fgetcsv($file2, 0, ",")) !== FALSE) {
    //Dump out the row for the sake of clarity.
    $list[$i]=$row[0];
    if ($list[$i]=="positive")
    {
        $p=$p+1;
    }
    elseif ($list[$i]=="negative")
    {
        $neg=$neg+1;
    }
    else 
    {
        $nut=$nut+1;
    }
    $i=$i+1;
}
$total=$p+$neg+$nut;
$_SESSION['avp']=$p/$total*100;
$_SESSION['avneg']=$neg/$total*100;
$_SESSION['avnut']=$nut/$total*100;
echo'<div id="container"><div id="zeyaddd">'.'<h2> Positive announced news : '.$_SESSION['avp']."%".'</h2>'.'<br>'
.'<h2> Negative announced news : '.$_SESSION['avneg']."%".'</h2>'.'<br>'
.'<h2> Neutral announced news : '.$_SESSION['avnut']."%".'</h2>'
.'</div>'
.'</div>';



$file = fopen('C:\\xampp\\htdocs\\SMP\\viewer\\sentiments\\articles.csv',"r");
$i=0;
$count = 0;
while (($row = fgetcsv($file, 0, ",")) !== FALSE) {
    //Dump out the row for the sake of clarity.
    $count++;
    if ($count == 1) { continue; }
    $author=$row[2];
    $title=$row[3];
    $link=$row[5];
    $image=$row[6]; 
    $date=$row[7];
    if($image==""){
        $image="https://www.investors.com/wp-content/uploads/2018/12/Stock-DowntrendBoard-01-shut.jpg";
    }
    $imageData = base64_encode(file_get_contents($image));
    
    echo '<div id="zeyad">'.'<div class="grid-container>'.'<div class="grid-item>'.
    '<img id="imag" src="data:image/jpeg;base64,'.$imageData.'">'.'<a href='.$link.'>"'.'<div id="tit">'.$title.'</div></a>'.
    '<div id="auth">'."$author".' | '."$date".' | '."$list[$i]".'</div>'.
    '</div>'.'</div>'.'</div>';
    $i=$i+1;

}

include_once 'piechart.php';

?>

</body>
</html>
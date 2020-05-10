<!DOCTYPE html>
<html>
<style>
  #piechart{
  

  position:absolute;
    top:400px; 
    left:450px;
  }
  </style>


    <div id="piechart"></div>
    
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    
    <script type="text/javascript">
    // Load google charts
    
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    // Draw the chart and set the chart values
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        <?php
            $x=$_SESSION['avp'];
            $y=$_SESSION['avneg'];
            $z=$_SESSION['avnut'];
     echo' ["Task", "Hours per Day"],';
     echo '["POSITIVE",'.$x.'],
           ["NEGATIVE",'.$y.'],
           ["NEUTRAL",'.$z.']';
     ?>
    ]);
   
      // Optional; add a title and set the width and height of the chart
      var options = {'title':'sentiment anlysis'};
    
      // Display the chart inside the <div> element with id="piechart"
      var chart = new google.visualization.PieChart(document.getElementById('piechart'));
      chart.draw(data, options);
    }
    </script>
    <body>
    <div id="piechart" style="width: 200px; height: 30; top: 5px;"></div>
</body>
</html>

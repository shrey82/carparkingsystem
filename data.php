<?php
if(count(get_included_files()) == 1 && $_SESSION['admin'] != 1) exit();
$connection = connect();
$tp_from = $inc = 0;
$val = array();
if(isset($_POST['view'])){
    $uid = $_SESSION['id'];
    $interval = explode("-", $_POST['daterange']);
    $from = str_replace("/", "-", $interval[0]);
    $till = str_replace("/", "-", $interval[1]);
    $tp_from = strtotime($from);
    $tp_till = strtotime($till);
    $inc = ($tp_till - $tp_from)/14; 
    $cfrom = $tp_from;
    $ctill = $cfrom + $inc;
    for($i = 1; $i <= 14; $i++){
        $cfrom += $inc;
        $ctill += $inc;
        $q1 = "SELECT area_no FROM `bill` WHERE (`from` <= from_unixtime('$cfrom') AND from_unixtime('$cfrom') <= `till`) OR (`from` <= from_unixtime('$ctill') AND from_unixtime('$ctill') <= `till`) OR "
                . "(from_unixtime('$cfrom') <= `from`   AND `till` <= from_unixtime('$ctill')) OR (`from` <= from_unixtime('$cfrom')   AND from_unixtime('$ctill') <= `till`)";
        $res = mysqli_query($connection, $q1);
        array_push($val, mysqli_num_rows($res));
    }
    $q1 = "SELECT area_no FROM `bill` WHERE (`from` <= from_unixtime('$tp_from') AND from_unixtime('$tp_from') <= `till`) OR (`from` <= from_unixtime('$tp_till') AND from_unixtime('$tp_till') <= `till`) OR "
                . "(from_unixtime('$tp_from') <= `from`   AND `till` <= from_unixtime('$tp_till')) OR (`from` <= from_unixtime('$tp_from')   AND from_unixtime('$tp_till') <= `till`) GROUP BY area_no";
    $res = mysqli_query($connection, $q1);
}
?>
<div class="col-lg-4  col-lg-offset-4 blogCard">
    <div class="">
        <h1 style="text-align:center; color:white" class="blog">View Statics for <i class="fa fa fa-sign-in"> </i> </h1>
        <?php 
        if(isset($invalid)){ ?>
            <div class="alert alert-warning">
              <strong>Invalid!</strong> Select correct time range 
            </div>
            <?php
        }
        ?>
        <form action="" method="post" >
            <div class="form-group ">
                <label for="title"></label>
                <input class="form-control"   type="text" name="daterange" value="<?php echo date("Y/m/d", time()); ?> 1:30:00 - <?php echo date("Y/m/d", time()); ?> 2:00:00" />
            </div>
            <script type="text/javascript">
            $(function() {
                $('input[name="daterange"]').daterangepicker({
                    timePicker: true,
                    timePickerIncrement: 10,
                    locale: {
                        format: 'YYYY/MM/DD HH:mm:00'
                    }
                });
            });
            </script>
            <div class="form-group"> 
                <br>
                <input type="submit" class="btn btn-block btn-default" name="view" value="View">
            </div>
        </form>   
    </div>
</div>
<?php
if(isset($_POST['view'])){  
?>
<div class="col-lg-10  col-lg-offset-1" >
    <br><br>
    <table class="table table-responsive table-bordered col-md-10 text-center">
        <thead>
            <tr>
                <th class="text-center">  Area reserved in the duration </th>
            </tr>
        </thead>
        <tbody>
            <?php
            while($row = mysqli_fetch_assoc($res)){
                $area_no = $row["area_no"];
                $query1 = "SELECT * FROM area WHERE id = '$area_no' LIMIT 1";
                $res1 = mysqli_query($connection, $query1);
                $row1 = mysqli_fetch_assoc($res1); 
            ?>
            <tr>
                <td>  
                <?php echo  $row1['desc']; ?> 
                </td>
            </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
     <br><br>
</div>
<div class="col-lg-10  col-lg-offset-1" >
        <br><br>
    <canvas id="myChart" style="background-color: rgba(255, 255, 255, 0.8); "></canvas>
</div>
<script>
    var ctx = document.getElementById('myChart').getContext('2d');
    var myBarChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [
                <?php
                    $cfrom = $tp_from;
                    $ctill = $cfrom + $inc;
                            //echo $cfrom . " - " . $ctill."<br>";
                    echo "'".date("d/m/y H:i:s",$cfrom)."-".date("d/m/y H:i:s", $ctill)."'";
                    for($i = 2; $i <= 14; $i++){
                        $cfrom += $inc;
                        $ctill += $inc;
                        echo ",'".date("d/m/y H:i:s",$cfrom)."-".date("d/m/y H:i:s", $ctill)."'";
                    }
                ?>
            ],
            datasets: [{
                    label: '<?php echo "Parking"; ?>',
                    data: [
                        <?php
                        
                        for ($i = 0; $i < 14; $i++) {
                            echo $val[$i] . ', ';
                        }
                        ?>
                        0, 5
                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 159, 64, 0.7)',
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 159, 64, 0.7)',
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0)',
                        'rgba(75, 192, 192, 0)'
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',                        
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                         'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 0)',
                        'rgba(75, 192, 192, 0)'
                    ]
                }]
        }
    });
</script>
<?php
};
mysqli_close($connection);
<?php
if(count(get_included_files()) ==1) exit();

$connection = connect();
    
if(isset($_POST['reserve'])){
    $uid = $_SESSION['id'];
    
    $interval = explode("-", $_POST['daterange']);
    $from = str_replace("/", "-", $interval[0]);
    $till = str_replace("/", "-", $interval[1]);
    $tp_from = strtotime($from);
    $tp_till = strtotime($till);
    if($tp_from < $tp_till && $tp_from > time()){
        
        $q1 = "SELECT area_no FROM `bill` WHERE (`from` <= '$from' AND '$from' <= `till`) OR (`from` <= '$till' AND '$till' <= `till`) OR ('$from' <= `from`   AND `till` <= '$till') OR ( `from` <= '$from'   AND '$till' <= `till` ) AND paid = 1 GROUP BY area_no";
        $q = "SELECT id FROM area WHERE id NOT IN ($q1) LIMIT 1";
        $res = mysqli_query($connection, $q);
        if(mysqli_num_rows($res) == 0){
            $novacancy = 1;
        }else{
            $row = mysqli_fetch_assoc($res);
            $area_no = $row["id"];
            if($res){
                $q = "INSERT INTO `bill`(`area_no`, `from`, `till`, `uid`, `cost`) VALUES ('$area_no', '$from', '$till', '$uid', TIMESTAMPDIFF(MINUTE, '$from', '$till')/6)";
                if(mysqli_query($connection, $q))    
                        $donereservation = 1;
            }
        }
    }else{
        $invalid = 1;
    }
    
    
}

mysqli_close($connection);
?>
<div class="col-lg-4  col-lg-offset-4 blogCard">
    <div class="">
        
        <h1 style="text-align:center; color:white" class="blog">Reserve for interval <i class="fa fa fa-sign-in"> </i> </h1>
        <?php 
        if(isset($invalid)){ ?>
            <div class="alert alert-warning">
              <strong>Invalid!</strong> Select correct time range 
            </div>
            <?php
        }else if(isset($novacancy )){ ?>
            <div class="alert alert-warning">
              <strong>No More Vacancies!</strong> Select another time
            </div>
            <?php
        }else if(isset($donereservation)){?>
            <div class="alert alert-success">
              <strong>Congratulations! </strong> Your reservation was successfully done!
            </div>
            <?php
            
        } ?>
        <form action="" method="post" >
            <div class="form-group ">
                <label for="title"></label>
                
                <input class="form-control"   type="text" name="daterange" value="<?php echo date("Y/m/d", time()); ?> 1:30:00 - <?php echo date("Y/m/d", time()); ?> 2:00:00" />
                <hr>
                <select  class="form-control"  name="bank">
                    <option class="form-control"  value="1"> Axis Bank        </option>
                    <option class="form-control"  value="2"> American Bank    </option>
                    <option class="form-control"  value="3"> SBI              </option>
                    <option class="form-control" value="4"> Indian Bank      </option>
                </select>
                <br>
                Credit Card Number:
                <input class="form-control"   type="text" name="number"/>
                <br>
                Expiry Date:
                <input placeholder="MM"   type="text" name="month" style="width:50px; border-radius: 4px; padding: 5px;">
                <input placeholder="YYYY"   type="text" name="year"  style="width:100px; border-radius: 4px; padding: 5px;"> 
                <br><br>
                CVV:
                <input   type="password" name="cvv" style="width:100px; border-radius: 4px; padding: 5px;">
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
                <input type="submit" class="btn btn-block btn-default" name="reserve" value="Reserve">
            </div>
        </form>    
    </div>
</div>
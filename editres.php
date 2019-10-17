<h1 style="text-align:center; color:white" class="blog">Reservation </i> </h1>
<?php
if(count(get_included_files()) ==1) exit();
$id = $_SESSION['id'];
$connection = connect();
if(isset($_GET['res'])){
    $resid = $_GET['res'];
    $q = "SELECT * FROM `bill` WHERE `uid` = $id AND id = '$resid' ";
    $res = mysqli_query($connection, $q);
    $row = mysqli_fetch_assoc($res);
    $area_no = $row["area_no"];
    $from = $row["from"];
    $till = $row["till"];
    if(isset($_POST['reserve'])){
        $uid = $_SESSION['id'];
        $ofrom = $from;
        $otill = $till;
        $interval = explode("-", $_POST['daterange']);
        $from = str_replace("/", "-", $interval[0]);
        $till = str_replace("/", "-", $interval[1]);
        $q1 = "SELECT area_no FROM `bill` WHERE ((`from` <= '$from' AND '$from' <= `till`) OR "
                . "(`from` <= '$till' AND '$till' <= `till`) OR ('$from' <= `from` AND `till` "
                . "<= '$till') OR ( `from` <= '$from'   AND '$till' <= `till` )) AND NOT id = "
                . "'$resid' AND area_no = '$area_no' AND paid = 1";
        $res = mysqli_query($connection, $q1);
        if(mysqli_num_rows($res) == 1){
            $q1 = "SELECT area_no FROM `bill` WHERE ((`from` <= '$from' AND '$from' <= `till`) "
                    . "OR (`from` <= '$till' AND '$till' <= `till`) OR ('$from' <= `from` AND "
                    . "`till` <= '$till') OR ( `from` <= '$from'   AND '$till' <= `till` )) "
                    . "AND NOT id = '$resid'  AND paid = 1 GROUP BY area_no";
            $q = "SELECT id FROM area WHERE id NOT IN ($q1) LIMIT 1";
            $res = mysqli_query($connection, $q);
            if(mysqli_num_rows($res) == 0){
                $area_no = -1;
            }else{
                $row = mysqli_fetch_assoc($res);
                $area_no = $row["id"];
            }
        }
        if($area_no != -1){
            $tp_ofrom = strtotime($ofrom);
            $tp_otill = strtotime($otill);
            $tp_from = strtotime($from);
            $tp_till = strtotime($till);
            if($tp_from < $tp_till && $tp_from > time()){
                $topay =  $tp_otill - $tp_ofrom;
                $refundBefore = 4;              
                if( ($tp_ofrom - time()) >= $refundBefore * 60 * 60){
                    $topay = 0;
                }
                if($tp_ofrom <= $tp_from && $tp_till <= $tp_otill){         
                    if($topay == 0){
                        $topay =  $tp_till - $tp_from;
                    }
                }else if($tp_ofrom >= $tp_till || $tp_otill <= $tp_from){  
                    $topay += $tp_till - $tp_from;
                }else if($tp_from >= $tp_ofrom && $tp_till <= $tp_otill){   
                    $topay =  $tp_till - $tp_from;
                }else if($tp_from < $tp_ofrom){                            
                    if($topay == 0){
                        $topay =  $tp_till - $tp_from;
                    }else{
                        $topay =  $tp_otill - $tp_from;
                    }
                }else if($tp_till > $tp_otill){                           
                    if($topay == 0){
                        $topay =  $tp_till - $tp_from;
                    }else{
                        $topay =  $tp_till - $tp_ofrom;
                    }
                }
                $topay /= 60;
                $q = "UPDATE `bill` SET `area_no`= '$area_no', `from` = '$from', `till` = '$till', `cost` = '$topay' WHERE id = '$resid'";
                if(mysqli_query($connection, $q)){    
                    $donereservation = 1;
                }
            }else{
                $invalid = 1;
            }
        }else{
            $novacancy = 1;
        }
    }
        $frange = str_replace("-", "/", $from). " - " . str_replace("-", "/", $till);
        ?>
        <div class="col-lg-4  col-lg-offset-4 blogCard">
            <div class="">
                <h1 style="text-align:center; color:white" class="blog">Update reservation <i class="fa fa fa-sign-in"> </i> </h1>
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
                        <input class="form-control"   type="text" name="daterange" value="<?php echo $frange ?>" />
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
                    For extra charges:
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

                    <div class="form-group"> 
                        <br>
                        <input type="submit" class="btn btn-block btn-default" name="reserve" value="Reserve">
                    </div>
                </form>    
            </div>
        </div>
<?php
}else{
    if(isset($_GET["del"])){
        $resid = clean($_GET['del']);
        $q = "SELECT * FROM `bill` WHERE id = '$resid' ";
        if(!isset($_SESSION['admin'])){
            $q .= " AND  `uid` = $id " ;
        }
        $res = mysqli_query($connection, $q);
        $row = mysqli_fetch_assoc($res);
        $area_no = $row["area_no"];
        $from = strtotime($row["from"]);
        if($from >= time() + (4 * 60 * 60)){
            $q = "DELETE FROM `bill` WHERE id = '$resid' ";
            if(!isset($_SESSION['admin'])){
                $q .= " AND  `uid` = $id " ;
            }
            $res = mysqli_query($connection, $q);
        }
    }
    $q = "SELECT * FROM `bill` ";
    if(!isset($_SESSION['admin'])){
        $q .= "WHERE   `uid` = $id " ;
    }
    $res = mysqli_query($connection, $q);
    if($res){ ?>
    <div class="col-lg-6  col-lg-offset-3 blogCard">
        <table class="table" style="background-color: rgba(0,0,0,0); color: #fff;;">
            <thead>
                <tr class="">
                    <th>&nbsp; ID       </th>
                    <th>Area&nbsp;No. </th>
                    <th>&nbsp; From     </th>
                    <th>&nbsp; Till     </th>
                    <th>&nbsp; Cost     </th>
                    <th>&nbsp; QR   </th>
                    <?php
                    if(isset($_SESSION['admin'])){
                    ?>
                    <th>User ID</th>
                    <?php
                    }else{ ?>
                        <th>&nbsp; Action   </th>
                    <?php
                    } ?>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($res)) {
                    $from = date("M d,y  h:i A", strtotime($row['from']));
                    $till = date("M d,y  h:i A", strtotime($row['till']));
                    $cost = $row['cost'];
                    $area_no = $row['area_no'];
                    $id = $row['id'];
                    $uid = $row['uid'];
                    $query = "SELECT * FROM area WHERE id = '$area_no' LIMIT 1";
                    $res1 = mysqli_query($connection, $query);
                    $row = mysqli_fetch_assoc($res1);
                    $desc = $row['desc'];
                    ?>
                    <tr>
                        <td>  <?php echo $id ?>     </td>
                        <td>  <?php echo $desc ?>   </td>
                        <td>  <?php echo $from ?>   </td>
                        <td>  <?php echo $till ?>   </td>
                        <td>  <?php echo $cost/5 ?>   </td>
                        <td> <img src="https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=<?php echo md5($id) ?>&choe=UTF-8" title="Link to Google.com" /> </td>
                        <?php
                        if(isset($_SESSION['admin'])){
                        ?>
                        <td>  <?php echo $uid ?>   </td>
                        <?php
                        }else{ 
                        ?>
                        <td>  <a href="index.php?source=editres&res=<?php echo $id ?>"> <span class="glyphicon glyphicon-edit"> </span></a> 
                            &nbsp; &nbsp; <a href="index.php?source=editres&del=<?php echo $id ?>"> <span class="glyphicon glyphicon-trash"> </span></a></td>
                        <?php
                        } ?>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php
    } else { ?>
        <div class="panel panel-heading text-center"><h3> No reservation done till now</h3></div>
    <?php     
    }
}
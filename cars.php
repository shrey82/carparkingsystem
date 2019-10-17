<h1 style="text-align:center; color:white" class="blog">Your Vehicles </i> </h1>
<?php
if(count(get_included_files()) ==1) exit();
$connection = connect();
$uid = $_SESSION['id'];
if(isset($_POST['add'])){
    $no = $_POST["no"];
    $name = $_POST["name"];
    $color = $_POST["color"]; 
    $q = "INSERT INTO `vehicle`(`no`, `uid`, `name`, `color`) VALUES ('$no', '$uid', '$name', '$color')";
    if(mysqli_query($connection, $q))    
        $inserted = 1;   
}
if(isset($_GET['del'])){
    $id = clean($_GET['del']);
    $q = "DELETE FROM `vehicle` WHERE id = '$id'";
    if(mysqli_query($connection, $q))    
        $deleted = 1;
}
$q = "SELECT * FROM `vehicle` WHERE `uid` = $uid";
if($result = mysqli_query($connection, $q)){ ?>
    <div class="col-lg-6  col-lg-offset-3 blogCard">
        <table class="table" style="background-color: rgba(0,0,0,0); color: #fff;;">
            <thead>
                <tr class="">
                    <th>&nbsp; ID           </th>
                    <th>&nbsp; License No.  </th>
                    <th>&nbsp; Model        </th>
                    <th>&nbsp; Color        </th>
                    <th>&nbsp; Action       </th>
                </tr>
            </thead>
            <tbody>
<?php 
    while($row = mysqli_fetch_assoc($result)){ ?>
                <tr>
                    <td>  <?php echo $row["id"] ?>     </td>
                    <td>  <?php echo $row["no"] ?>   </td>
                    <td>  <?php echo $row["name"] ?>   </td>
                    <td>  <?php echo $row["color"] ?>   </td>
                    <td>  <a href="index.php?source=cars&del=<?php echo $row["id"] ?>"> <span class="glyphicon glyphicon-trash"> </span></a></td>
                </tr>
<?php
    } ?>
            </tbody>
        </table>
    
<?php
}
mysqli_close($connection);
?>
    <hr>
    <br>
    <h3 style="text-align:center; color:white" class="blog">Add Vechicle</i> </h3>
    <form action="" method="post" >
        <div class="form-group ">
            <label for="title"><i class="fa fa-envelope"></i>&nbsp;License</label>
            <input type="text" class="form-control" name="no">
        </div>
        <div class="form-group">
            <label for="title"><i class="fa fa-key"></i>  &nbsp;Name</label>
            <input type="text" class="form-control" name="name">
        </div>
        <div class="form-group">
            <label for="title"><i class="fa fa-key"></i>  &nbsp;Color</label>
            <input type="text" class="form-control" name="color">
        </div>
        <div class="form-group"> 
            <br>
            <input type="submit" class="btn btn-block btn-default" name="add" value="Add">
        </div>
    </form>
</div>
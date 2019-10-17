<?php
if(count(get_included_files()) ==1) exit();
$id = $_SESSION['id'];
$connection = connect();
$q = "SELECT * FROM `user` WHERE id = $id";
$res = mysqli_query($connection, $q);
$row = mysqli_fetch_assoc($res);
$name = $row["name"];
$email = $row["email"];
$phone = $row["phone"];
$passswd = $row["passwd"];
if(isset($_POST["update"])){
    $name = $_POST["usern"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    if(!empty($_POST["passwd"]) && $_POST["passwd"] == $_POST["passwd1"]){
        $passswd = password_hash($_POST["passwd"], PASSWORD_DEFAULT);
    }
    $q = "UPDATE `user` SET `passwd`='$passswd',`email`='$email',`name`='$name',`phone`='$phone' WHERE id = $id LIMIT 1";
    if(mysqli_query($connection, $q)){
        $updated = 1;
    }
}
?>
<div class="col-lg-4 col-lg-offset-4 blogCard">
    <div class="">
        <?php if(isset($updated)){ ?>
                <h1 class="text-success" style="text-align:center;" >Updated Successfully</h1>
        <?php } ?>
        <h1 style="text-align:center; color:white" class="blog">Register Now <i class="fa fa fa-sign-in"> </i> </h1>
        <form action="" method="post" >
            <br>
            <div class="form-group">
                <label for="title"><i class="fa fa-user"></i>&nbsp;Name</label>
                <input type="text" class="form-control" name="usern" value="<?php echo $name; ?>">
            </div>
            <div class="form-group">
                <label for="title"><i class="fa fa-envelope"></i>&nbsp;Email</label>
                <input type="text" class="form-control" name="email" value="<?php echo $email; ?>">
            </div>
            <div class="form-group">
                <label for="title"><i class="fa fa-phone"></i>&nbsp;Phone Numeber</label>
                <input type="text" class="form-control" name="phone" value="<?php echo $phone; ?>">
            </div>
            <br>
            <div class="form-group">
                <label for="title"><i class="fa fa-key"></i>  &nbsp;Password <small> Leave blank to keep same</small></label>
                <input type="password" class="form-control" name="passwd" value="">
            </div>
            <div class="form-group">
                <label for="title"><i class="fa fa-key"></i>  &nbsp;Password</label>
                <input type="password" class="form-control" name="passwd1" value="">
            </div>
            <div class="form-group"> 
                <br>
                <input type="submit" class="btn btn-default btn-block " name="update" value="Update">
            </div>
        </form>
    </div>
</div>
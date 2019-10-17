<?php
session_start();

$error = 0; // Error:
$usern = "";  //  1
$passwd = "";  // 	2
$email = "";   //	4
$found = false;

include 'db.php';
$connection = connect();
if (isset($_POST['register'])) {
    

    if (isset($_POST['usern'])) {
        $usern = clean($_POST['usern']);
        if (empty($usern)) {
            $error += 1;
        }
    }

    // Sanitizing email value
    if (isset($_POST['email'])) {
        
        $_POST['email'] = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $_POST['email'] = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $email = $_POST['email'];
	
        if ($email == "") {
            $error += 4;
        }
    }

    if (isset($_POST['passwd']) && isset($_POST['passwd1']) && $_POST['passwd1'] == $_POST['passwd']) {
        if (!preg_match("/^(?=.*\d)(?=.*[a-zA-Z]).{8,}$/", $_POST['passwd'])) {
            $error += 2;
        } else {
            $passwd = password_hash($_POST['passwd'], PASSWORD_DEFAULT);
        }
    }
    if(isset($_POST['phone']) && $_POST['phone']){
        $phone = $_POST['phone'];
        
    }else {
        $error += 12;
    }
    
    if ($error == 0) {
        $email = strtolower(clean($email));        
        $result2 = mysqli_query($connection,"SELECT * FROM `user` WHERE email = '$email'");
        if ($result2) {
            $num2 = mysqli_num_rows($result2);
            if ($num2 > 0) {
                $found = true;
                $error = 8;
            }
        }

        if (!$found){
            $token = clean(md5(uniqid(rand(), true)));
            $query = "INSERT INTO `user` ( `passwd`, `email`,`name`,`phone`) VALUES ('$passwd', '$email', '$usern','$phone')";
            $result = mysqli_query($connection,$query);
            
//            echo  $query."<br>".$passwd."<br>".$passwd."<br>".$email."<br>".$usern;
//            exit();
            if ($result) {
                $_GET["loginMsg"] = 3;
                
            }else{
                $_GET["loginMsg"] = 12;
            }
        }
       
    } 
}else if (isset($_POST['login'])) {

    if ($_POST['email'] != "" && $_POST['passwd'] != "") {

        $_POST['email'] = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $_POST['email'] = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

        $email = strtolower(clean($_POST['email']));
        $passwd = clean($_POST['passwd']);

        if ($_POST['email'] != "") {
            $query = "SELECT * FROM user WHERE email = '$email' LIMIT 1";
            $result = mysqli_query($connection, $query);
            
            if ($result) {
                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $verify = password_verify($_POST['passwd'], $row['passwd']);
                    if ($verify) {
                        $_SESSION['email'] = $email;
                        $_SESSION['usern'] = $row['name'];
                        $_SESSION['id'] = $row['id'];
                        if($email == "admin@admin.com") 
                            $_SESSION['admin'] = 1;
                        
                    } else {
                        $_GET["loginMsg"] = 1;
                    }
                } else {
                    $_GET["loginMsg"] = 1;
                }
            } else {
                $_GET["loginMsg"] = 2;
            }
        }else {
            $_GET["loginMsg"] = 1;
        }
    }
}

mysqli_close($connection);

include_once 'header.php'; 
?>

<?php
if(!isset($_SESSION['usern']) && !isset($_GET["source"])){ ?>
<div class="jumbotron">
    <div class="container">
        <h1>WELCOME</h1>
        <div class="col-md-12">
            <p>Our parking garage is state of the art in automation and convience.</p>
        </div>
    </div>
</div>
<div class="container" >
    <?php 
    if (isset($_GET['loginMsg'])) { ?>
        <div class="alert alert-danger" role="alert"> 
            <h4>
                <?php
                if ($_GET['loginMsg'] == 1) {
                    echo '<i class="fa  fa-warning"></i> Wrong email/password ';
                } else if ($_GET['loginMsg'] == 2) {
                    echo '<i class="fa  fa-warning"></i> Server is busy, try later';
                } else if ($_GET['loginMsg'] == 3) {
                    echo '<i class="fa  fa-warning"></i> Thank you for signing up';
                } else if ($_GET['loginMsg'] == 4) {
                    echo '<i class="fa  fa-warning"></i> Account is activated';
                } else if ($_GET['loginMsg'] == 5) {
                    echo '<i class="fa fa-info-circle fa-lg"></i> <span style="color:#33cccc">  Please check your mailbox and spam folder after few minutes for an account recovery link. <br> If you do not receive mail fill the account recovery form again </span>';
                }else if ($_GET['loginMsg'] == 6) {
                    echo '<i class="fa fa-info-circle fa-lg"></i> <span style="color:#33cccc"> Account is verified </span>';
                }
                ?>
            </h4>
        </div>
        <?php
    }
?>    
    <div class="col-lg-4 blogCard">
        <div class="">

            <h1 style="text-align:center; color:white" class="blog">Register Now <i class="fa fa fa-sign-in"> </i> </h1>
            <form action="" method="post" >
                <br>
                <div class="form-group">
                    <label for="title"><i class="fa fa-user"></i>&nbsp;Name</label>
                    <input type="text" class="form-control" name="usern">
                </div>
                <div class="form-group">
                    <label for="title"><i class="fa fa-envelope"></i>&nbsp;Email</label>
                    <input type="text" class="form-control" name="email">
                </div>
                <div class="form-group">
                    <label for="title"><i class="fa fa-phone"></i>&nbsp;Phone Numeber</label>
                    <input type="text" class="form-control" name="phone">
                </div>
                <br>
                <div class="form-group">
                    <label for="title"><i class="fa fa-key"></i>  &nbsp;Password</label>
                    <input type="password" class="form-control" name="passwd">
                </div>
                <div class="form-group">
                    <label for="title"><i class="fa fa-key"></i>  &nbsp;Password <br>
                        <small>*Password should be longer than 8 character and should contain atleast one number and alpahabet</small>
                    </label>
                    <input type="password" class="form-control" name="passwd1">
                    
                </div>
                <div class="form-group"> 
                    <br>
                    <input type="submit" class="btn btn-default btn-block " name="register" value="Login">
                </div>
            </form>
        </div>
    </div>
    <div class="col-lg-4 col-lg-offset-1  blogCard">
        <div class="">
            <h1 style="text-align:center; color:white" class="blog">Sign In <i class="fa fa fa-sign-in"> </i> </h1>
            
            <form action="" method="post" >
                <br>
                <div class="form-group ">
                    <label for="title"><i class="fa fa-envelope"></i>&nbsp;Email</label>
                    <input type="text" class="form-control" name="email">
                </div>
                <br>
                <div class="form-group">
                    <label for="title"><i class="fa fa-key"></i>  &nbsp;Password</label>
                    <input type="password" class="form-control" name="passwd">
                </div>
                <div class="form-group"> 
                    <br>
                    <input type="submit" class="btn btn-block btn-default" name="login" value="Login">
                </div>
            </form>
            <br>
        </div>
    </div>

</div>
<?php
}else{
    $src = 'editres.php';
    if(isset($_SESSION['admin'])){
        $src = 'data.php'; 
    }
    if(isset($_GET["source"])){
        if($_GET["source"] == 'edit' || $_GET["source"] == 'cars' || $_GET["source"] == 'reserve' ||
           $_GET["source"] == 'editres' || $_GET["source"] == 'data' || $_GET["source"] == 'contact'){
                $src = $_GET["source"].".php";
        }
    }
    include $src;
    
}

?>
 <!-- /container -->  
    </div>      
    
</body>
</html>

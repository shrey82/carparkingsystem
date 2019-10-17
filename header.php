<?php 
if(isset($_GET['logout'])){
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}
?>
<!doctype html>
<html class="no-js" lang=""> 
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Garage Automation System</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta property="fb:app_id" content="173496979681010" />
        <link rel="apple-touch-icon" href="favicon.png">
        <link rel="icon" type="image/gif" href="favicon.png" />
        <script type="text/javascript" src="//cdn.jsdelivr.net/jquery/1/jquery.min.js"></script>
        <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap/3/css/bootstrap.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.bundle.min.js"></script> 
        <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
        <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="css/main.css">
        
    </head>
    <body>
        <div id="fb-root"></div>
        <nav class="navbar navbar-inverse " role="navigation">
            <div class="container">
                <div class="navbar-header col-lg-11">
                    <h1 style="color: rgba(255, 255, 255, 1); font-size:55px" >Garage  Automation System </h1> <br> 
                </div>
                <ul class="nav navbar-nav pull-right" >
                    <?php if(isset($_SESSION['usern'])){ 
                        if(isset($_SESSION['admin'])){ ?>
                            <li><a href="index.php?source=data">View Statics</a>
                        <?php 
                        }else{ ?>
                        <li><a href="index.php?source=cars">Your Cars</a></li>
                        <li><a href="index.php?source=reserve">Reserve</a>
                        <?php
                        } ?>
                        <li><a href="index.php?source=editres">Reservations</a></li>
                        <li><a href="index.php?logout=1">Log Out</a></li>
                        <li><a href="index.php?source=edit">Edit Account</a></li>
                        <?php
                    } ?>
                    <li><a href="index.php?source=contact">Contact Us</a></li>
                </ul>
            </div>
        </nav>
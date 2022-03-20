<?php 
ob_start();
session_start(); 
?>

<!DOCTYPE html>
<html>
<head>
        <?php include_once('./config.php'); ?>
        <?php include_once('./PartialViews/main_head.php') ?>
        <style>
                #jumbo .text-light, #jumbo .text-primary {
                        text-shadow: 2px 2px 2px black, 5px 5px 10px black;
                }

                hr {
                        box-shadow: 2px 2px 2px black, 5px 5px 10px black;
                }

                #jumbo {
                        margin-bottom: 0.5rem !important;
                }

                #category-title {
                        font-family: 'Abril Fatface';
                        color: #fff;
                        text-shadow: 2px 2px 5px black;
                }

                .event-title {
                        font-weight: bolder;
                        font-family: "Libre Calson Text";
                        text-transform: uppercase;
                }

                @media screen and (min-width: 512px) {
                        #jumbo {
                                margin-bottom: 2rem !important; 
                        }
                }

        </style>
</head>
<body>
        <?php 
                include_once('./DAL/eventdata_dal.php');
                $eventObj = new eventdata_dal;
                $eventid = 0;
                if (isset($_REQUEST['eventid'])){
                                        if(!empty($_REQUEST['eventid'])){
                                                $eventid = $_REQUEST['eventid'];
                                        }
                                }
                $eventdata = $eventObj->getAllwithCategory($eventid);
                $eventdata = $eventdata[0];

                include_once('./DAL/category_dal.php');
                $catObj = new category_dal;
                $catdata = $catObj->getDatas('categoryid = '.$eventdata->catid);
                $catdata = $catdata[0];
        ?>
        <!-- Navbar -->
        <nav class="navbar fixed-top navbar-expand-lg navbar-dark">
                <a class="navbar-brand d-flex" style="align-items:baseline;" href="index.php">
                        <img src="./AppData/Images/Integration_logo.png" width="30px" style="display:flex-inline; margin-right:15px;">
                        <h2 style="display:flex-inline; margin-right:40px;">Integration</h2>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                </ul>
                <div class="my-2 my-lg-0 d-inline-flex" style="align-items: baseline; margin-bottom:1rem !important; color:white;">
                        <?php 
                        $firstname = 'Guest User';
                        if (isset($_SESSION['user_firstname'])){
                                $firstname = $_SESSION['user_firstname'];
                        }
                        ?>
                        <span class="font-weight-bold mx-3">Welcome <?php echo($firstname); ?></span>
                        <?php 
                        if ($firstname == 'Guest User') {?>
                                <a class="btn rounded-pill mx-1 nav-btn" href="user_signup.php">SignUp</a>
                                <a class="btn rounded-pill mx-1 nav-btn" href="user_login.php">Login</a>
                        <?php }  else { ?>
                        <ul class="navbar-nav ml-auto mr-md-3">
                        <li class="nav-item dropdown no-arrow">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-user-circle fa-fw"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                                        <a class="dropdown-item" href="user_settings.php">Settings</a>
                                        <a class="dropdown-item" href="user_events.php">Registered Events</a>
                                        <a class="dropdown-item" href="javascript:void(0);" id="logout_modal">Logout</a>
                                </div>
                        </li>
                        </ul>
                        <?php } ?>
                </div>

                </div>
        </nav>
        <!-- Navbar -->
              
        <!-- Jumbotron -->
        <div class="mt-5 jumbotron jumbotron-fluid" id="jumbo"
             style="background-image: url('./AppData/Categories/<?php echo($catdata->imagepath); ?>');
                    background-size: cover;
                    background-position: center center">
        <div class="container text-center">
                <h1 class="display-1" id="category-title"><?php echo($eventdata->catname); ?></h1>
        </div>
        </div>
                <!-- Jumbotron end -->


        <div class="row mb-5 mr-0 ml-0">
                <div class="col-md-1"></div>
                <div class="col-md-3">
                        <img src="./AppData/Events/<?php echo($eventdata->imagepath); ?>" width = "100%" class="mx-auto mb-3 rounded">
                        <button class="btn btn-success my-3 text-uppercase" style="width:100%" id="registerBtn"> Register </button>
                        <button class="btn btn-success my-3 text-uppercase" style="width:100%" id = "paymentBtn"> Make Payment </button>
                </div>
                <div class="col-md-7">
                        <h3 class="event-title"><?php echo($eventdata->eventname);?></h3>
                        <hr/>
                        <blockquote class="blockquote">
                                <i class="fa fa-quote-left"></i>
                                <em><?php echo($eventdata->description); ?></em>
                                <i class="fa fa-quote-right"></i>
                        </blockquote>
                      <div class="mt-5">
                                <?php echo($eventdata->cmscontent);?>
                      </div>
                      <div class="mt-5">
                                <b>CONTACTS:</b>
                                <ol>
                                <?php 
                                $headstring = $eventdata->eventhead;
                                $heads = explode(",", $headstring);
                                $len = count($heads);
                                for ($i=0; $i < $len; $i++) { ?>
                                        <li><?php echo($heads[$i]); ?></li>
                                <?php }
                                ?>
                                </ol>
                      </div>
                      
                </div>
                <div class="col-md-1"></div>
        </div>

        <!-- Contacts -->


        <?php include_once('./PartialViews/main_footer.php')?> 

<!-- page level script-->
<script>
        $(document).ready(function(){
                var RootPathHost = '<?php echo($HTTP_HOST); ?>';
                var regUser = false;
                
                <?php 
                if (isset($_SESSION['user_userid'])) { ?>
                        regUser = true;
                <?php } ?>


                $('#logout_modal').click(function(e) {
                                $.alert({
                                        title: 'Are you sure?',
                                        content: 'Do you really want to log out?',
                                        buttons : {
                                                Yes : function () {
                                                                window.location.replace('user_logout.php');
                                                        },
                                                No : function() {
                                                        }
                                                }
                                        });
                        });

                $('#registerBtn').click(function(e){
                        e.preventDefault();
                        if (regUser) {
                                // Send an ajax request with userid and eventid and make an event registration
                                var formdata = new FormData();
                                formdata.append("reg_eventid", "<?php echo($eventdata->eventid); ?>");
                                formdata.append("reg_userid", "<?php if(isset($_SESSION['user_userid'])){ echo($_SESSION['user_userid']); } else { echo(-1); }?>");
                                formdata.append("mode", "add");

                                $.alert({
                                        title: 'Registration for <?php echo($eventdata->eventname);?>',
                                        content: 'Do you want to register for this event?',
                                        buttons: {
                                                Yes: function () {                        
                                                        $.ajax({
                                                                url: RootPathHost+'/Controller/eventregistration_controller.php',
                                                                enctype: 'multipart/form-data',
                                                                data: formdata,
                                                                processData: false,
                                                                contentType: false,
                                                                type: "POST",
                                                                success: function(data) {
                                                                        var arr = data.split("||||");
                                                                        var retval = arr[arr.length - 1];
                                                                
                                                                        if (parseInt(retval) > 0) {
                                                                                $.alert({
                                                                                title: 'Registration Successful!',
                                                                                content: 'You have sucessfully registered for event <?php echo($eventdata->eventname); ?><br/>Now, please make the appropriate payment to complete your registration.',
                                                                                buttons : {
                                                                                        OK: function () {
                                                                                                // here goes make payment code
                                                                                                },
                                                                                        Pay_Later : function () {
                                                                                                }
                                                                                        }
                                                                                });
                                                                        }
                                                                        else {
                                                                                console.log(arr);
                                                                                $.alert({
                                                                                        title: 'Error in Registration',
                                                                                        content: 'Looks like you have already registered for this event.',
                                                                                        buttons : {
                                                                                                OK: function() {
                                                                                                }
                                                                                        }
                                                                                });
                                                                        } 

                                                                },
                                                                error: function(data) {
                                                                        $.alert({
                                                                                title: 'Error',
                                                                                content: 'Looks like there is a technical problem. Please try again later!',
                                                                                buttons : {
                                                                                        OK: function () {                                
                                                                                                }
                                                                                        }
                                                                        });
                                                                }
                                                        });
                                                },
                                                No: function() {

                                                }
                                        }
                                });
                                
                        } 
                        else 
                        {
                                $.alert({
                                        title: 'Not Signed up yet?',
                                        content: 'Please sign up or login as a user to register for any events',
                                        buttons: {
                                                Login: function() {
                                                        window.location.replace('user_login.php');
                                                },
                                                Sign_Up :  function() {
                                                        window.location.replace('user_signup.php');
                                                },
                                                Cancel : function() {
                                                },
                                        }
                                });
                        };
                });
        });

</script>

<!-- page level script-->
        
</body>
</html>
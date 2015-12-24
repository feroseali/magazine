<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Magazine</title>

    <!-- Bootstrap core CSS -->

    <link href="assets/css/bootstrap.min.css" rel="stylesheet">

    <link href="assets/fonts/css/font-awesome.min.css" rel="stylesheet">
    <link href="assets/css/animate.min.css" rel="stylesheet">

    <!-- Custom styling plus plugins -->
    <link href="assets/css/custom.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/css/maps/jquery-jvectormap-2.0.1.css" />
    <link href="assets/css/icheck/flat/green.css" rel="stylesheet">
    <link href="assets/css/floatexamples.css" rel="stylesheet" />

    <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script>
    $(document).ready(function () {
        if(localStorage.getItem('token')){
            var data = JSON.parse(sessionStorage.getItem('stored_category_data'));
            $('.heading').text(data.category_name);
            $('#cat_date').text(data.createdAt);
            $('#cat_des').text(data.category_description);
            $('#cat_img').attr('src', data.category_image);        }
        else{
            $(location).attr('href','/magazine/');
        }
      });

    var logoutProcess = function(){
        localStorage.removeItem('token');
        $(location).attr('href','/magazine/');
    }  
    </script>

    <!--[if lt IE 9]>
        <script src="../assets/js/ie8-responsive-file-warning.js"></script>
        <![endif]-->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

</head>


<body class="nav-md">

    <div class="container body">


        <div class="main_container">

            <div class="col-md-3 left_col">
                <div class="left_col scroll-view">

                    <div class="navbar nav_title" style="border: 0;">
                        <a href="#" class="site_title"><i class="fa fa-bookmark"></i> <span>Magazine</span></a>
                    </div>
                    <div class="clearfix"></div>
                    <br />

                    <!-- sidebar menu -->
                    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

                        <div class="menu_section">
                            <h3>General</h3>
                            <ul class="nav side-menu">
                                <li><a><i class="fa fa-home"></i> Dashboard</a>
                                </li>
                                <li><a><i class="fa fa-edit"></i>Category <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu" style="display: none">
                                        <li><a href="/magazine/add-category">Add Category</a>
                                        </li>
                                        <li><a href="/magazine/manage-category">Manage Category</a>
                                        </li>
                                    </ul>
                                </li>
                                <li><a><i class="fa fa-desktop"></i>Magazine Content<span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu" style="display: none">
                                        <li><a href="/magazine/add-article">Add Article</a>
                                        </li>
                                        <li><a href="/magazine/manage-article">Manage Articles</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                  </div>
                    <!-- /sidebar menu -->

                    <!-- /menu footer buttons -->
                    <div class="sidebar-footer hidden-small">
                        <a data-toggle="tooltip" data-placement="top" title="Settings">
                            <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                            <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="Lock">
                            <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="Logout">
                            <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                        </a>
                    </div>
                    <!-- /menu footer buttons -->
                </div>
            </div>

            <!-- top navigation -->
            <div class="top_nav">

                <div class="nav_menu">
                    <nav class="" role="navigation">
                        <div class="nav toggle">
                            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                        </div>

                        <ul class="nav navbar-nav navbar-right">
                            <li class="">
                                <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    Admin
                                    <span class=" fa fa-angle-down"></span>
                                </a>
                                <ul class="dropdown-menu dropdown-usermenu animated fadeInDown pull-right">
<!--                                     <li><a href="javascript:;">  Profile</a>
                                    </li>
                                    <li>
                                        <a href="javascript:;">
                                            <span>Settings</span>
                                        </a>
                                    </li> -->
                                    <li><a href="#" onclick="logoutProcess()"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                </div>

            </div>
            <!-- /top navigation -->


            <!-- page content -->
            <div class="right_col" role="main">

                <br />
                 <div class="row x_panel">

                        <div class="col-md-12">
                           <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>View Category</h2>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <br />
                                    <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">

                                        <div class="form-group">

                                            <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0;">
                                              <div class="messages">
                                                        <div class="message_wrapper">
                                                            <h4 class="heading">Category Title</h4>
                                                            <p id="cat_date">Category created date</p>
                                                            <blockquote class="message" id="cat_des">Category Description.</blockquote>

                                                        </div>
                                                        <div class="x_content">
                                                          <div class="bs-example" data-example-id="simple-jumbotron">
                                                              <div class="col-md-12">
                                                                <img id="cat_img" class="img-responsive" src="" alt="Category image">
                                                              </div>
                                                          </div>
                                                      </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                <div class="x_content">



                                    <div class="form-group">
                                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-0">
                                                <a href="/magazine/manage-category" class="btn btn-success"> << Back</a>
                                            </div>
                                        </div>
                                        <!--  <div class="ln_solid"></div> -->
                                    <br />
                                </div>
                                </div>
                            </div>
                        </div>






                        </div>
                        <div class="col-md-6">

                        </div>
                        </div>
                    </div>

                <!-- footer content -->
                <!-- <footer>
                    <div class="">
                        <p class="pull-right">All right reserved <a> WitKraft</a>. | &copy; 2015
                        </p>
                    </div>
                    <div class="clearfix"></div>
                </footer> -->
                <!-- /footer content -->

            </div>
            <!-- /page content -->
        </div>


    </div>

    <div id="custom_notifications" class="custom-notifications dsp_none">
        <ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group">
        </ul>
        <div class="clearfix"></div>
        <div id="notif-group" class="tabbed_notifications"></div>
    </div>

    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/nicescroll/jquery.nicescroll.min.js"></script>

    <!-- chart js -->
    <script src="assets/js/chartjs/chart.min.js"></script>
    <!-- bootstrap progress js -->
    <script src="assets/js/progressbar/bootstrap-progressbar.min.js"></script>
    <!-- icheck -->
    <script src="assets/js/icheck/icheck.min.js"></script>
    <!-- daterangepicker -->
    <script type="text/javascript" src="assets/js/moment.min2.js"></script>
    <script type="text/javascript" src="assets/js/datepicker/daterangepicker.js"></script>
    <!-- sparkline -->
    <script src="assets/js/sparkline/jquery.sparkline.min.js"></script>

    <script src="assets/js/custom.js"></script>

    <!-- flot js -->
    <!--[if lte IE 8]><script type="text/javascript" src="js/excanvas.min.js"></script><![endif]-->
    <script type="text/javascript" src="assets/js/flot/jquery.flot.js"></script>
    <script type="text/javascript" src="assets/js/flot/jquery.flot.pie.js"></script>
    <script type="text/javascript" src="assets/js/flot/jquery.flot.orderBars.js"></script>
    <script type="text/javascript" src="assets/js/flot/jquery.flot.time.min.js"></script>
    <script type="text/javascript" src="assets/js/flot/date.js"></script>
    <script type="text/javascript" src="assets/js/flot/jquery.flot.spline.js"></script>
    <script type="text/javascript" src="assets/js/flot/jquery.flot.stack.js"></script>
    <script type="text/javascript" src="assets/js/flot/curvedLines.js"></script>
    <script type="text/javascript" src="assets/js/flot/jquery.flot.resize.js"></script>

    <!-- dropzone -->
    <script src="assets/js/dropzone/dropzone.js"></script>



</body>

</html>

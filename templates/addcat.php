<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Magazine | <?php echo $title; ?></title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/dropzone.css" rel="stylesheet">

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
    <script src="assets/js/dropzone/dropzone.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script>

    Dropzone.autoDiscover = false;
    // or disable for specific dropzone:

    $(function() {
      // Now that the DOM is fully loaded, create the dropzone, and setup the
      // event listeners
      var myDropzone = new Dropzone("#category-drop-zone");

       myDropzone.on("success", function(file, data) {
        $('#category_image').val(data.filename);
        
      });
    });



    //Dropzone.autoDiscover = false;
    $(document).ready(function () {
        if(!localStorage.getItem('token')){
            $(location).attr('href','/magazine/');
        }
        
    });     


        function validateImage() {
              var img = $("#category_image").val();

              var exts = ['jpg','jpeg','png','gif', 'bmp'];
              // split file name at dot
              var get_ext = img.split('.');
              // reverse name to check extension
              get_ext = get_ext.reverse();

              if (img.length > 0 ) {
                if ( $.inArray ( get_ext[0].toLowerCase(), exts ) > -1 ){
                  AddCategory();
                  return true;
                } else {
                  alert("Upload only jpg, jpeg, png, gif, bmp images");
                  return false;
                }
              } else {
                alert("please upload an image");
                return false;
              }
              return false;
            }



        var AddCategory = function()
        {
            // var data = $("#demo-form2").serialize();
            name = add_category.elements["category_name"].value;
            description = add_category.elements["category_description"].value;
            cat_image = add_category.elements["category_image"].value;

            $.ajax({
                   data: {'category_name': name, 'category_description': description, 'category_image': cat_image},
                   type: "POST",
                   restful:true,
                   //contentType : false,
                     // processData: false,
                   headers: {
                     "Authorization": localStorage.getItem('token')
                   },
                   url: "/magazine/v1/categories",
                   success: function(data){
                     alert("Category added successfully");
                     add_category.reset();
                     categorydropZone.reset();
                     location.reload();
                   },
                   error: function(data){
                     alert("Category created");
                     add_category.reset();
                     categorydropZone.reset();
                     location.reload();
                   }

          });
        event.preventDefault();
        // return false;
        }



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
                        <a href="/magazine/home" class="site_title"><i class="fa fa-bookmark"></i> <span>Magazine</span></a>
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
                                    <ul class="nav child_menu" style="display: none;">
                                        <li><a href="/magazine/add-article">Add Magazine</a>
                                        </li>
                                        <li><a href="/magazine/manage-article">Manage Magazine</a>
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
                                    <h2>Add Category</h2>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <br />

                                    <form method="POST" enctype="multipart/form-data" class="uploadform dropzone needsclick dz-clickable" name="categorydropZone" action="./templates/upload.php" id="category-drop-zone">
                                         <div class="dz-message needsclick">
                                            Drop category image files here or click to upload.<br>
                                          </div>
                                          <div class="fallback">
                                            <input type="file" id="file" name="file"> 
                                          </div>                                         
                                    </form>

                                    <form method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" name="add_category" onSubmit="return validateImage();">

                                        <div class="form-group">

                                            <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0;">
                                             <label class="control-label " for="category-name">Category Name <span class="required">*</span>
                                            </label>
                                                <input type="text" name="category_name" id="category_name" required="required" class="form-control col-md-7 col-xs-12">
                                            </div>
                                        </div>
                                        <div class="form-group">

                                            <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0;">
                                             <label class="control-label " for="category-description">Category Description <span class="required">*</span>
                                            </label>
                                                <textarea type="text" name="category_description" id="category_description" required="required" class="form-control col-md-7 col-xs-12"></textarea>
                                            </div>
                                        </div>
                                        <input type="hidden" id="category_image" name="category_image" value="">
                                        <!-- <div class="form-group">
                                          <div class="col-md-12 col-sm-12 col-xs-12 fallback" style="padding: 0;">
                                           <label class="control-label " for="category-image">Category Image <span class="required">*</span>
                                          </label>
                                              <input type="file" name="category_image" id="category_image" required="required" class="form-control col-md-7 col-xs-12">
                                          </div>
                                        </div> -->
                                        <div class="form-group">
                                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                                    <button class="btn btn-primary">Cancel</button>
                                                    <button type="submit" class="btn btn-success" id="formSubmit">Submit</button>
                                                </div>
                                        </div>
                                    </form>
                                <!-- <div class="x_content">

                                    <p>Drop the category image here</p>
                                    <form action="choices/form_upload.html" class="dropzone" style="border: 1px solid #e5e5e5; height: 300px; "></form>

                                    <br />

                                    <br />
                                </div> -->
                                </div>
                            </div>
                        </div>






                        </div>
                        <div class="col-md-6">
<!-- <form action="/file-upload"
      class="dropzone"
      id="my-awesome-dropzone">  <div class="fallback">
    <input name="category_image" type="file" multiple />
  </div></form> -->
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



</body>

</html>

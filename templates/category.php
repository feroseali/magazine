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
    var showAllCategories = function(){
      $.ajax({
             method: "GET",
             url: "/magazine/v1/categories",
             dataType: 'json',
             success: function(data) {
              //  console.log(data['categories'].length);
               for(var i=0;i<data['categories'].length;i++){
                  var obj = data['categories'][i];
                    var id = obj['id'];
                    var name = obj['category_name'];
                    var created = obj['created_at'];
                    var tr=[];
                    tr.push('<tr>');
                    // tr.push('<td class="a-center "><input type="checkbox" class="flat" name="table_records" ></td>');
                    tr.push("<td>" + id + "</td>");
                    tr.push("<td>" + name + "</td>");
                    tr.push("<td>" + created + "</td>");
                    tr.push('<td class=" "><button class="btn btn-primary btn-xs" onclick="viewCategory('+id+')"><i class="fa fa-folder"></i> View </button><button class="btn btn-info btn-xs" onclick="editCategory('+id+')"><i class="fa fa-pencil"></i> Edit </button><button class="btn btn-danger btn-xs" onclick="deleteCategory('+id+')"><i class="fa fa-trash-o"></i> Delete </button></td>');
                    $('#category_data').append(tr);
                    $('tbody').append($(tr.join('')));
              }
             },
            error: function(){
                console.log("Error");   
            }               
      });
    }

    var deleteCategory = function(id){
      $.ajax({
             type: "POST",
             url: "/magazine/v1/categories/"+id,
             contentType: "application/json",
             dataType: 'json',
             restful:true,
             headers: {
              "Content-Type": "application/json",
              "X-HTTP-Method-Override": "DELETE",
              "Authorization": localStorage.getItem('token')},
             success: function(data) {
               showAllCategories();  
               alert("Category deleted successfully");
             }
      });
    }


    function viewCategory(id){
      $.ajax({
             method: "GET",
             url: "/magazine/v1/categories/"+id,
             dataType: 'json',
             success: function(data) {
               sessionStorage.setItem('stored_category_data', JSON.stringify(data));
             }
      });
      window.location = "/magazine/view-category";
    }

    function editCategory(id){
      console.log(id);
      sessionStorage.setItem('cat_id', id);
      $.ajax({
             method: "GET",
             url: "/magazine/v1/categories/"+id,
             dataType: 'json',
             success: function(data) {
               sessionStorage.setItem('category_data', JSON.stringify(data));
             }
      });
      window.location = "/magazine/edit-category";      
    }


    var logoutProcess = function(){
        localStorage.removeItem('token');
        $(location).attr('href','/magazine/');
    }  


    $(document).ready(function () {
        if(localStorage.getItem('token')){
            showAllCategories();
        }
        else{
            $(location).attr('href','/magazine/');
        }
      });
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
<div id="categories"></div>
                <br />
                <div class="col-md-12" style="padding: 0;">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Magazine Categories</h2>
                                    <div class="clearfix"></div>
                                </div>

                                <div class="x_content">

                                     <table class="table table-striped responsive-utilities jambo_table bulk_action">
                                        <thead>
                                            <tr class="headings">
                                                <!-- <th>
                                                    <input type="checkbox" id="check-all" class="flat">
                                                </th> -->
                                                <th class="column-title">ID: </th>
                                                <th class="column-title">Category Name </th>
                                                <th class="column-title">Created At </th>
                                                <th class="column-title no-link last"><span class="nobr">Action</span>
                                                </th>
                                                <th class="bulk-actions" colspan="7">
                                                    <a class="antoo" style="color:#fff; font-weight:500;">Bulk Actions ( <span class="action-cnt"> </span> ) <i class="fa fa-chevron-down"></i></a>
                                            </th>
                                </tr>
                            </thead>

                            <tbody>
                              <!-- <tr class="even pointer">
                                  <td class="a-center "><input type="checkbox" class="flat" name="table_records" ></td>
                                  <td class=" ">121000040</td>
                                  <td class=" ">Active</td>
                                  <td class=" ">May 23, 2014 11:47:56 PM </td>
                                  <td class=" "><a href="#">View</a> / <a href="#">Delete</a></td>
                              </tr>
                                <tr class="even pointer">
                                    <td class="a-center "><input type="checkbox" class="flat" name="table_records" ></td>
                                    <td class=" ">121000040</td>
                                    <td class=" ">Active</td>
                                    <td class=" ">May 23, 2014 11:47:56 PM </td>
                                    <td class=" "><a href="#">View</a> / <a href="#">Delete</a></td>
                                </tr> -->
                                  </tbody>

                                    </table>
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

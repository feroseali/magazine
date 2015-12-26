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
    function viewArticle(id){
      $.ajax({
             method: "GET",
             url: "/magazine/v1/articles/"+id,
             dataType: 'json',
             success: function(data) {
               sessionStorage.setItem('stored_article_data', JSON.stringify(data));
             }
      });
      window.location = "/magazine/view-article";
    }

    function editArticle(id){
      console.log(id);
      sessionStorage.setItem('art_id', id);
      $.ajax({
             method: "GET",
             url: "/magazine/v1/articles/"+id,
             dataType: 'json',
             success: function(data) {
               sessionStorage.setItem('article_data', JSON.stringify(data));
             }
      });
      window.location = "/magazine/edit-article";    
    }

    function deleteArticle(id){
      $.ajax({
             type: "POST",
             url: "/magazine/v1/articles/"+id,
             contentType: "application/json",
             dataType: 'json',
             restful:true,
             headers: {
              "Content-Type": "application/json",
              "X-HTTP-Method-Override": "DELETE",
              "Authorization": localStorage.getItem('token')},
             success: function(data, jqXHR) {
               alert("Article deleted successfully");
             }
      });
    }

      $(document).ready(function () {
        if(localStorage.getItem('token')){
          $.ajax({
                 method: "GET",
                 url: "/magazine/v1/categories",
                 dataType: 'json',
                 success: function(data) {
                  //  console.log(data['categories'].length);
                   var category_list="<option value='0' selected>Select any category</option>";
                   for(var i=0;i<data['categories'].length;i++){
                      var obj = data['categories'][i];
                        var id = obj['id'];
                        var name = obj['category_name'];
                        category_list += "<option value=" + id  + ">" +name + "</option>"
                        document.getElementById("datas").innerHTML = category_list;
                  }
                 }
          });

          $( "#datas" ).change(function() {
            if($(this).val()!='0'){
              $("#table_show").css("display", "block");
              $('tbody').html("");
              var category_id = $(this).val();
              $.ajax({
                     method: "GET",
                     url: "/magazine/v1/categories/"+category_id+"/articles",
                     dataType: 'json',
                     success: function(data) {
                       for(var i=0;i<data['articles'].length;i++){
                          var obj = data['articles'][i];
                            var id = obj['id'];
                            var title = obj['article_title'];
                            var author = obj['author_name'];
                            var publish = obj['date_published'];
                            var category_name = data['category'][0].category_name;
                            var tr=[];
                            tr.push('<tr>');
                            // tr.push('<td class="a-center "><input type="checkbox" class="flat" name="table_records" ></td>');
                            tr.push("<td>" + id + "</td>");
                            tr.push("<td>" + title + "</td>");
                            tr.push("<td>" + author + "</td>");
                            tr.push("<td>" + publish + "</td>");
                            tr.push("<td>" + category_name + "</td>");
                            tr.push('<td class=" "><button class="btn btn-primary btn-xs" onclick="viewArticle('+id+')"><i class="fa fa-folder"></i> View </button><button class="btn btn-info btn-xs" onclick="editArticle('+id+')"><i class="fa fa-pencil"></i> Edit </button><button class="btn btn-danger btn-xs" onclick="deleteArticle('+id+')"><i class="fa fa-trash-o"></i> Delete </button></td>');
                            $('tbody').append($(tr.join('')));
                      }
                     }
              });
            }
            else{
              $("#table_show").css("display", "none");
            }
          });

      }
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
                                        <li><a href="/magazine/add-article">Add Articles</a>
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
<div id="categories"></div>
                <br />
                <div class="col-md-12" style="padding: 0;">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Magazine Categories</h2>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                  <div class="form-group">

                                      <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0;">
                                       <label class="control-label " for="category-description">Select Category <span class="required">*</span>
                                      </label>
                                      <select name="datas" id="datas" class="form-control">
                                          <option value="0" selected="selected">Select any category</option>
                                      </select>
                                      </div>
                                  </div>
                                </div>
                                <div class="x_content">
                                    <h4 id="msg_no_art" style="color:red; display:none;">No articles available in this category!</h4>
                                     <table class="table table-striped responsive-utilities jambo_table bulk_action" id="table_show" style="display:none;">
                                        <thead>
                                            <tr class="headings">
                                                <!-- <th>
                                                    <input type="checkbox" id="check-all" class="flat">
                                                </th> -->
                                                <th class="column-title">ID: </th>
                                                <th class="column-title">Article Title</th>
                                                <th class="column-title">Author</th>
                                                <th class="column-title">Published Date</th>
                                                <th class="column-title">Category</th>

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


    <!-- datepicker -->
    <script type="text/javascript">
        $(document).ready(function () {

            var cb = function (start, end, label) {
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                //alert("Callback has fired: [" + start.format('MMMM D, YYYY') + " to " + end.format('MMMM D, YYYY') + ", label = " + label + "]");
            }

            var optionSet1 = {
                startDate: moment().subtract(29, 'days'),
                endDate: moment(),
                minDate: '01/01/2012',
                maxDate: '12/31/2015',
                dateLimit: {
                    days: 60
                },
                showDropdowns: true,
                showWeekNumbers: true,
                timePicker: false,
                timePickerIncrement: 1,
                timePicker12Hour: true,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                opens: 'left',
                buttonClasses: ['btn btn-default'],
                applyClass: 'btn-small btn-primary',
                cancelClass: 'btn-small',
                format: 'MM/DD/YYYY',
                separator: ' to ',
                locale: {
                    applyLabel: 'Submit',
                    cancelLabel: 'Clear',
                    fromLabel: 'From',
                    toLabel: 'To',
                    customRangeLabel: 'Custom',
                    daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                    monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                    firstDay: 1
                }
            };
            $('#reportrange span').html(moment().subtract(29, 'days').format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));
            $('#reportrange').daterangepicker(optionSet1, cb);
            $('#reportrange').on('show.daterangepicker', function () {
                console.log("show event fired");
            });
            $('#reportrange').on('hide.daterangepicker', function () {
                console.log("hide event fired");
            });
            $('#reportrange').on('apply.daterangepicker', function (ev, picker) {
                console.log("apply event fired, start/end dates are " + picker.startDate.format('MMMM D, YYYY') + " to " + picker.endDate.format('MMMM D, YYYY'));
            });
            $('#reportrange').on('cancel.daterangepicker', function (ev, picker) {
                console.log("cancel event fired");
            });
            $('#options1').click(function () {
                $('#reportrange').data('daterangepicker').setOptions(optionSet1, cb);
            });
            $('#options2').click(function () {
                $('#reportrange').data('daterangepicker').setOptions(optionSet2, cb);
            });
            $('#destroy').click(function () {
                $('#reportrange').data('daterangepicker').remove();
            });





        });
    </script>
    <!-- /datepicker -->
</body>

</html>

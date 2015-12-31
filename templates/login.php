<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Magazine |</title>

    <!-- Bootstrap core CSS -->

    <link href="assets/css/bootstrap.min.css" rel="stylesheet">

    <link href="assets/fonts/css/font-awesome.min.css" rel="stylesheet">
    <link href="assets/css/animate.min.css" rel="stylesheet">

    <!-- Custom styling plus plugins -->
    <link href="assets/css/custom.css" rel="stylesheet">
    <link href="assets/css/icheck/flat/green.css" rel="stylesheet">


    <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script>
      $(document).ready(function () {
        if(localStorage.getItem('token')){
            $(location).attr('href','/magazine/home');
        }
      });      
            var processLogin = function(){
              email = loginForm.elements["email"].value;
              password = loginForm.elements["password"].value;
              $.ajax({
                     data: {'email': email, 'password': password},
                     type: "POST",
                     url: "/magazine/v1/login",
                     success: function(data){
                       console.log(data['apiKey']);
                       if (typeof data['apiKey'] != 'undefined') {
                        localStorage.setItem('token', data['apiKey']);
                        alert("Login successfully");
                        $(location).attr('href','/magazine/home');
                       }
                       else{
                        alert("Invalid Credentials");
                       }
                       loginForm.reset()
                     },
                     error: function(){
                       alert("Login failed");
                        //  alert("Skill Insertion Failed");
                        //  $("#skill-text").val("");
                     }
            });
               event.preventDefault();
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

<body style="background:#F7F7F7;">

    <div class="">
        <a class="hiddenanchor" id="toregister"></a>
        <a class="hiddenanchor" id="tologin"></a>

        <div id="wrapper">
            <div id="login" class="animate form">
                <section class="login_content">
                    <form method="POST" name="loginForm">
                        <h1>Login Form</h1>
                        <div>
                            <input type="text" class="form-control" name="email" placeholder="Email" required="" />
                        </div>
                        <div>
                            <input type="password" class="form-control" name="password" placeholder="Password" required="" />
                        </div>
                        <div>
                            <button class="btn btn-default" type="button" onclick="processLogin()">Log in</button>
                            <a class="reset_pass" href="#">Lost your password?</a>
                        </div>
                    </form>
                    <!-- form -->    
                        <div class="clearfix"></div>
                        <div class="separator">

                            <!-- <p class="change_link">New to site?
                                <a href="#" class="to_register"> Create Account </a>
                            </p> -->
                            <div class="clearfix"></div>
                            <br />
                            <div>
                             <p>©2015 All Rights Reserved.</p>
                            </div>
                        </div>

                </section>
                <!-- content -->
            </div>
            <!-- <div id="register" class="animate form">
                <section class="login_content">
                    <form>
                        <h1>Create Account</h1>
                        <div>
                            <input type="text" class="form-control" placeholder="Username" required="" />
                        </div>
                        <div>
                            <input type="email" class="form-control" placeholder="Email" required="" />
                        </div>
                        <div>
                            <input type="password" class="form-control" placeholder="Password" required="" />
                        </div>
                        <div>
                            <a class="btn btn-default submit" href="#">Submit</a>
                        </div>
                        <div class="clearfix"></div>
                        <div class="separator">

                            <p class="change_link">Already a member ?
                                <a href="#" class="to_register"> Log in </a>
                            </p>
                            <div class="clearfix"></div>
                            <br />
                            <div>
                                <h1><i class="fa fa-paw" style="font-size: 26px;"></i> Gentelella Alela!</h1>

                                <p>©2015 All Rights Reserved. Gentelella Alela! is a Bootstrap 3 template. Privacy and Terms</p>
                            </div>
                        </div>
                    </form>
                </section>
            </div> -->
        </div>
    </div>

</body>

</html>

<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from codervent.com/bulona/demo/authentication-signup.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 26 Feb 2020 10:09:20 GMT -->

<head>
   <meta charset="utf-8" />
   <meta http-equiv="X-UA-Compatible" content="IE=edge" />
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
   <meta name="description" content="" />
   <meta name="author" content="" />
   <title>Bulona - Bootstrap Admin Dashboard Template</title>
   <!--favicon-->
   <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
   <!-- Bootstrap core CSS-->
   <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
   <!-- animate CSS-->
   <link href="assets/css/animate.css" rel="stylesheet" type="text/css" />
   <!-- Icons CSS-->
   <link href="assets/css/icons.css" rel="stylesheet" type="text/css" />
   <!-- Custom Style-->
   <link href="assets/css/app-style.css" rel="stylesheet" />

</head>

<body>

   <!-- start loader -->
   <div id="pageloader-overlay" class="visible incoming">
      <div class="loader-wrapper-outer">
         <div class="loader-wrapper-inner">
            <div class="loader"></div>
         </div>
      </div>
   </div>
   <!-- end loader -->

   <!-- Start wrapper-->
   <div id="wrapper">

      <div class="card card-authentication1 mx-auto my-4">
         <div class="card-body">
            <div class="card-content p-2">
               <div class="text-center">
                  <img src="assets/images/logo-icon.png" alt="logo icon">
               </div>
               <div class="card-title text-uppercase text-center py-3">Sign Up</div>
               <form class="register-form" id="register-form">
                  @if(Session::has('success'))
                  <div class="alert alert-success">{{Session::get('success')}}</div>
                  @endif
                  @if(Session::has('fail'))
                  <div class="alert alert-danger">{{Session::get('fail')}}</div>
                  @endif
                  @csrf

                  <div class="form-group">
                     <label for="email" class="sr-only">Email ID</label>
                     <div class="position-relative has-icon-right">
                        <input type="email" name="email" name="email" required placeholder="E-mail" class="form-control input-shadow">
                        <span class="text-danger">@error('email') {{$message}} @enderror</span>
                        <div class="form-control-position">
                           <i class="zmdi zmdi-email"></i>
                        </div>
                     </div>
                  </div>

                  <div class="form-group">
                     <label for="username" class="sr-only">User Name</label>
                     <div class="position-relative has-icon-right">
                        <input type="number" name="mobile" required id="mobile" placeholder="Phone Number" class="form-control input-shadow">
                        <div class="form-control-position">
                           <span class="text-danger">@error('username') {{$message}} @enderror</span>
                           <i class="zmdi zmdi-account material-icons-name"></i>
                        </div>
                     </div>
                  </div>

                  <div class="form-group">
                     <label for="pass" class="sr-only">Password</label>
                     <div class="position-relative has-icon-right">
                        <input type="password" name="password" id="password" placeholder="Password" class="form-control input-shadow" />
                        <span class="text-danger">@error('password') {{$message}} @enderror</span>
                        <div class="form-control-position">
                           <i class="zmdi zmdi-lock"></i>
                        </div>
                     </div>
                  </div>

                  <button type="button" id="submit-btn" class="btn btn-primary btn-block waves-effect waves-light">Sign Up</button>

                  <div class="text-center mt-3">Sign Up With</div>

                  <div class="form-row mt-4">
                     <div class="form-group mb-0 col-6">
                        <button type="button" class="btn bg-facebook text-white btn-block"><i class="fa fa-facebook-square"></i> Facebook</button>
                     </div>
                     <div class="form-group mb-0 col-6 text-right">
                        <button type="button" class="btn bg-twitter text-white btn-block"><i class="fa fa-twitter-square"></i> Twitter</button>
                     </div>
                  </div>

               </form>
            </div>
         </div>
         <div class="card-footer text-center py-3">
            <p class="text-dark mb-0">Already have an account? <a href="login"> Sign In here</a></p>
         </div>
      </div>

      <!--Start Back To Top Button-->
      <a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
      <!--End Back To Top Button-->



   </div><!--wrapper-->

   <!-- Bootstrap core JavaScript-->
   <script src="assets/js/jquery.min.js"></script>
   <script src="assets/js/popper.min.js"></script>
   <script src="assets/js/bootstrap.min.js"></script>

   <!-- sidebar-menu js -->
   <script src="assets/js/sidebar-menu.js"></script>

   <!-- Custom scripts -->
   <script src="assets/js/app-script.js"></script>

   <!-- Add this script at the end of your HTML file, before </body> -->
   <script>
      $(document).ready(function() {
         $('#submit-btn').click(function() {
            $.ajax({
               type: 'POST',
               url: '{{ route("register-user") }}',
               data: $('#register-form').serialize(),
               success: function(response) {
                  if (response.success) {
                     // Handle success, e.g., show a success message
                     alert(response.success);
                     // Redirect to the login page using JavaScript
                     window.location.href = '{{ route("login") }}';
                  } else {
                     // Handle errors, e.g., show error messages
                     alert(response.fail || 'Failed to register user');
                  }
               },
               error: function(error) {
                  console.log('Error:', error);
                  alert('Failed to register user');
               }
            });
         });
      });
   </script>

</body>

<!-- Mirrored from codervent.com/bulona/demo/authentication-signup.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 26 Feb 2020 10:09:20 GMT -->

</html>
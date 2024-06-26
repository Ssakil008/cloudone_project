<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from codervent.com/bulona/demo/authentication-signin.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 26 Feb 2020 10:09:20 GMT -->

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Tech Vault</title>
    <!--favicon-->
    <link rel="icon" href="assets/images/TechVaultFavicon.ico" type="image/x-icon">
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

        <div class="loader-wrapper">
            <div class="lds-ring">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
        <div class="card card-authentication1 mx-auto my-5">
            <div class="card-body">
                <div class="card-content p-2">
                    <div class="text-center">
                        <img src="assets/images/TechVaultFavicon.png" alt="logo icon">
                    </div>
                    <div class="card-title text-uppercase text-center py-3">Sign In</div>
                    <form class="login-form" id="login-form">
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
                            <label for="pass" class="sr-only">Password</label>
                            <div class="position-relative has-icon-right">
                                <input type="password" name="password" id="password" placeholder="Password" class="form-control input-shadow" />
                                <span class="text-danger">@error('password') {{$message}} @enderror</span>
                                <div class="form-control-position">
                                    <i class="zmdi zmdi-lock"></i>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-6">
                                <div class="icheck-material-primary">
                                    <input type="checkbox" id="user-checkbox" checked="" />
                                    <label for="user-checkbox">Remember me</label>
                                </div>
                            </div>
                            <div class="form-group col-6 text-right">
                                <a href="authentication-reset-password.html">Reset Password</a>
                            </div>
                        </div>
                        <button type="button" id="submit-btn" class="btn btn-primary btn-block">Sign In</button>
                        <div class="text-center mt-3">Sign In With</div>

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
                <p class="text-dark mb-0">Do not have an account? <a href="register"> Sign Up here</a></p>
            </div>
        </div>

        <!--Start Back To Top Button-->
        <a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
        <!--End Back To Top Button-->



    </div><!--wrapper-->

    <!--Error Modal -->
    <div class="modal fade" id="errormodal">
        <div class="modal-dialog">
            <div class="modal-content border-danger">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white">Error</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center" id="errormessage">
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    <!--End Modal -->

    <!-- Bootstrap core JavaScript-->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>

    <!-- sidebar-menu js -->
    <script src="assets/js/sidebar-menu.js"></script>

    <!-- Custom scripts -->
    <script src="assets/js/app-script.js"></script>

    <script>
        $(document).ready(function() {
            $('#submit-btn').click(function() {
                // Get the form data
                var formData = $('#login-form').serialize();
                console.log(formData);

                // Make an Ajax request
                $.ajax({
                    type: 'POST',
                    url: '{{ route("login-user") }}',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            // Redirect to the desired page
                            window.location.href = '{{ route("dashboard") }}';
                        } else {
                            $('#errormessage').text('Incorrect email or password');
                            $('#errormodal').modal('show');
                            setTimeout(function() {
                                $('#errormodal').modal('hide');
                            }, 2000);
                        }
                    },
                    error: function(error) {
                        console.log('Error:', error);
                        $('#errormessage').text('Incorrect email or password');
                        $('#errormodal').modal('show');
                        setTimeout(function() {
                            $('#errormodal').modal('hide');
                        }, 2000);
                    }
                });
            });
        });
    </script>

</body>

<!-- Mirrored from codervent.com/bulona/demo/authentication-signin.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 26 Feb 2020 10:09:20 GMT -->

</html>
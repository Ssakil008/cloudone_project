@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row pt-2 pb-2 align-items-center">
            <div class="col-sm-9">
                <h4 class="page-title">User Profile</h4>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 text-right">
                <button type="button" id="addNewUser" class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">Add New User</button>
            </div>
        </div>


        <div class="table-responsive">
            <table class="table table-bordered" id="users-table">
                <thead>
                    <tr>
                        <th>Serial No</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Populate table rows dynamically using PHP or JavaScript -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="modal_user" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_user"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Your form goes here -->

                <form id="userForm">
                    @csrf
                    <input type="hidden" name="userId" id="userId">
                    <div class="form-group">
                        <label for="email">Email ID</label>
                        <input type="email" id="email" name="email" name="email" required placeholder="E-mail" value="{{old('email')}}" class="form-control input-shadow">
                        <span class="text-danger" id="email_error"></span>
                    </div>

                    <div class="form-group">
                        <label for="mobile">Mobile Number</label>
                        <input type="number" name="mobile" required id="mobile" placeholder="Mobile Number" class="form-control input-shadow">
                        <span class="text-danger" id="mobile_error"></span>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" required id="password" placeholder="Password" class="form-control input-shadow" />
                        <span class="text-danger" id="password_error"></span>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="userSubmit"></button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript-->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>

<!-- simplebar js -->
<script src="assets/plugins/simplebar/js/simplebar.js"></script>
<!-- sidebar-menu js -->
<script src="assets/js/sidebar-menu.js"></script>
<!-- Custom scripts -->
<script src="assets/js/app-script.js"></script>

<script>
    $(document).ready(function() {
        $('#addNewUser').click(function() {
            $('#userForm')[0].reset();
            $('#userSubmit').text('Submit');
            $('.modal-title').html('<strong>Add New User</strong>');
            $('#addUserModal .text-danger').text('');
        });
    });

    $(document).ready(function() {
        $('#userSubmit').click(function() {
            var isValid = validateForm();
            if (isValid) {
                var formData = $('#userForm').serialize();
                console.log(formData);
                alertify.confirm('Are you sure?', function(e) {
                    if (e) {
                        $.ajax({
                            type: 'POST',
                            url: '{{ route("register-user") }}',
                            data: formData,
                            success: function(response) {
                                if (response.success) {
                                    $('#addUserModal').modal('hide');
                                    $('#successmessage').text('New User Added');
                                    $('#successmodal').modal('show');
                                    setTimeout(function() {
                                        $('#successmodal').modal('hide');
                                        window.location.href = '{{ route("user_setup") }}';
                                    }, 2000);
                                } else {
                                    // Handle server-side errors
                                    displayErrors(response.errors);
                                }
                            },
                            error: function(error) {
                                console.error('AJAX error:', error);
                                $('#addUserModal').modal('hide');
                                $('#errormessage').text('User not added');
                                $('#errormodal').modal('show');
                                setTimeout(function() {
                                    $('#errormodal').modal('hide');
                                }, 2000);
                            }
                        });
                    }
                });
            }
        });

        // Function to validate the form
        function validateForm() {
            var isValid = true;
            $('.error-message').text(''); // Clear previous error messages
            $('#userForm input[required]').each(function() {
                if ($(this).val().trim() === '') {
                    var fieldName = $(this).attr('name');
                    $('#' + fieldName + '_error').text(fieldName + ' is required');
                    isValid = false;
                }
            });
            return isValid;
        }

        // Function to display errors below respective input fields
        function displayErrors(errors) {
            $.each(errors, function(key, value) {
                $('#' + key + '_error').text(value);
            });
        }
    });

    $(document).ready(function() {
        // Fetch entries data from the server
        $.ajax({
            type: 'GET',
            url: '{{ route("getAllUserData") }}',
            success: function(response) {
                // Check if the response has the 'data' property
                if (response.hasOwnProperty('data')) {
                    var users = response.data;
                    var serialNumber = 1;

                    // Iterate through entries and append rows to the table
                    $.each(users, function(index, user) {
                        var row = '<tr>' +
                            '<td>' + serialNumber + '</td>' +
                            '<td>' + user.email + '</td>' +
                            '<td>' + user.mobile + '</td>' +
                            '<td>' +
                            '<i class="icon-note mr-2 edit-btn align-middle" data-user-id="' + user.id + '"></i>' +
                            '<i class="fa fa-trash-o delete-btn align-middle text-danger" data-user-id="' + user.id + '"></i>' +
                            '</td>' +
                            '</tr>';

                        $('#users-table tbody').append(row);
                        serialNumber++;
                    });
                } else {
                    console.error('Invalid response structure:', response);
                }
            },
            error: function(error) {
                console.error('Error fetching entries:', error);
            }
        });

        $('#users-table').on('click', '.edit-btn', function() {
            var userId = $(this).data('user-id');
            console.log(userId);

            $.ajax({
                type: 'GET',
                url: '{{ url("getUserData") }}/' + userId,
                success: function(response) {
                    console.log(response);
                    if (response.hasOwnProperty('data')) {
                        var user = response.data;
                        $('#userForm')[0].reset();
                        $('#addUserModal .text-danger').text('');
                        $('#addUserModal').modal('show');
                        $('#userId').val(user.id);
                        $('#email').val(user.email);
                        $('#mobile').val(user.mobile);
                        $('#userSubmit').text('Update');
                        $('.modal-title').html('<strong>Edit The User</strong>');
                    } else {
                        console.error('Invalid response structure:', response);
                    }
                },
                error: function(error) {
                    console.error('Error fetching user:', error);
                }
            });
        });

        $('#users-table').on('click', '.delete-btn', function() {
            var userId = $(this).data('user-id');
            console.log(userId);

            // Show confirmation modal
            $('#confirmBtn').text('Delete')
            $('#deleteConfirmationModal').modal('show');

            // Handle confirmation
            $('#confirmBtn').click(function() {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    url: '{{ route("deleteUserData") }}',
                    data: {
                        userId: userId,
                    },
                    success: function(response) {
                        console.log(response);
                        $('#deleteConfirmationModal').modal('hide');
                        if (response.success) {
                            // Display success message
                            $('#successmessage').text('User deleted successfully.');
                            $('#successmodal').modal('show');
                            setTimeout(function() {
                                $('#successmodal').modal('hide');
                                // Redirect to user setup page
                                window.location.replace('{{ route("user_setup") }}');
                            }, 2000);
                        } else {
                            // Display error message
                            $('#errormessage').text('User deletion failed');
                            $('#errormodal').modal('show');
                            setTimeout(function() {
                                $('#errormodal').modal('hide');
                            }, 2000);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                        // Display error message
                        $('#errormessage').text('Error deleting user: ' + error);
                        $('#errormodal').modal('show');
                        console.error('Error deleting user:', error);
                    }
                });
            });
        });



    });
</script>
@endsection
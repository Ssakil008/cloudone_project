@extends('layouts.app')
@section('title','User Setup')
@section('content')

<div class="clearfix"></div>

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row pt-2 pb-2 align-items-center">
            <div class="col-sm-9">
                <h4 class="page-title">User Details</h4>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 text-right">
                <button type="button" id="addNewBtn" class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">Add New User</button>
            </div>
        </div>


        <div class="table-responsive">
            <table class="table table-bordered" id="users-table">
                <thead>
                    <tr>
                        <th>Serial No</th>
                        <th>User Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

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
                        <label for="username">User Name</label>
                        <div class="position-relative has-icon-right">
                            <input type="text" name="username" id="username" required placeholder="User Name" class="form-control input-shadow">
                            <span class="text-danger" id="username_error"></span>
                        </div>
                    </div>

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
                        <label for="role">Role</label>
                        <select class="form-control input-shadow" name="role" required id="role">
                            <option value="" disabled selected>Select Role</option>
                            @foreach (\App\Models\Role::all() as $role)
                            <option value="{{ $role->id }}">{{ $role->role }}</option>
                            @endforeach
                        </select>
                        <span class="text-danger" id="role_error"></span>

                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" placeholder="Password" class="form-control input-shadow" />
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/alertify.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">

<script>
    $(document).ready(function() {
        $('#addNewBtn').click(function() {
            $('#userForm')[0].reset();
            $('#userSubmit').text('Submit');
            $('.modal-title').html('<strong>Add New User</strong>');
            $('#addUserModal .text-danger').text('');
        });
    });

    $(document).ready(function() {
        function validateMobileNumber(mobileNumber) {
            var regex = /^(\+?8801|01)[1-9]\d{8}$/;
            return regex.test(mobileNumber);
        }

        function validateEmail(email) {
            var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regex.test(email);
        }
        $('#userSubmit').click(function(e) {
            var isValid = validateForm();
            if (isValid) {
                var formData = $('#userForm').serialize();
                var roleId = $('#role').val(); // Get the selected role ID
                formData += '&roleId=' + roleId; // Append role ID to the form data
                console.log(formData);

                // Get the email from the form
                var email = document.getElementById('email').value;

                // Check email validation
                if (!validateEmail(email)) {
                    alertify.alert('E-mail is not valid');
                    return; // Prevent form submission if email is invalid
                }

                // Get the mobile number from the form
                var mobileNumber = document.getElementById('mobile').value;

                // Check mobile number validation
                if (!validateMobileNumber(mobileNumber)) {
                    alertify.alert('Mobile Number is not valid');
                    return; // Prevent form submission if mobile number is invalid
                }

                e.preventDefault(); // Prevent default form submission behavior
                alertify.confirm('Are you sure?', function(e) {
                    if (e) {
                        $.ajax({
                            type: 'POST',
                            url: '{{ route("register-user") }}',
                            data: formData,
                            success: function(response) {
                                if (response.success) {
                                    $('#addUserModal').modal('hide');
                                    $('#successmessage').text(response.message);
                                    $('#successmodal').modal('show');
                                    setTimeout(function() {
                                        $('#successmodal').modal('hide');
                                        window.location.href = '{{ route("user_setup") }}';
                                    }, 2000);
                                } else {
                                    // Handle server-side errors
                                    $('#addUserModal').modal('hide');
                                    $('#errormessage').text(response.message);
                                    $('#errormodal').modal('show');
                                    setTimeout(function() {
                                        $('#errormodal').modal('hide');
                                    }, 3000);
                                }
                            },
                            error: function(error) {
                                console.error('AJAX error:', error);
                                $('#addUserModal').modal('hide');
                                $('#errormessage').text(response.message);
                                $('#errormodal').modal('show');
                                setTimeout(function() {
                                    $('#errormodal').modal('hide');
                                }, 3000);
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

            // Validate input fields
            $('#userForm input[required]').each(function() {
                if ($(this).val().trim() === '') {
                    var fieldName = $(this).attr('name');
                    $('#' + fieldName + '_error').text(fieldName + ' is required');
                    isValid = false;
                }
            });

            // Validate select fields
            $('#userForm select[required]').each(function() {
                if (!$(this).val()) {
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
        // $.ajax({
        //     type: 'GET',
        //     url: '{{ route("getAllUserData") }}',
        //     success: function(response) {
        //         // Check if the response has the 'data' property
        //         if (response.hasOwnProperty('data')) {
        //             var users = response.data;
        //             var serialNumber = 1;

        //             // Iterate through entries and append rows to the table
        //             $.each(users, function(index, user) {
        //                 var row = '<tr>' +
        //                     '<td>' + serialNumber + '</td>' +
        //                     '<td>' + user.email + '</td>' +
        //                     '<td>' + user.mobile + '</td>' +
        //                     '<td>' + user.user_role.role.role + '</td>' +
        //                     '<td>' +
        //                     '<i class="icon-note mr-2 edit-btn align-middle text-info" data-user-id="' + user.id + '"></i>' +
        //                     '<i class="fa fa-trash-o delete-btn align-middle text-danger" data-user-id="' + user.id + '"></i>' +
        //                     '</td>' +
        //                     '</tr>';

        //                 $('#users-table tbody').append(row);
        //                 serialNumber++;
        //             });
        //         } else {
        //             console.error('Invalid response structure:', response);
        //         }
        //     },
        //     error: function(error) {
        //         console.error('Error fetching entries:', error);
        //     }
        // });

        $('#users-table').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{ route('getAllUserData')}}",
                "type": "GET"
            },
            "columns": [{
                    "data": null,
                    "render": function(data, type, row, meta) {
                        // 'meta' parameter contains information about the row
                        return meta.row + 1; // Row index starts from 0, so add 1 to make it consecutive
                    }
                },
                {
                    "data": "username"
                },
                {
                    "data": "email"
                },
                {
                    "data": "mobile"
                },
                {
                    "data": "role"
                },
                {
                    "data": "action",
                    "render": function(data, type, row) {
                        return data ? data : '<i class="icon-note mr-2 edit-btn align-middle text-info" data-user-id="' + row.id + '"></i>' +
                            '<i class="fa fa-trash-o delete-btn align-middle text-danger" data-user-id="' + row.id + '"></i> ';
                    }
                }
            ]
        });

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: '{{ route("fetchUserPermissions") }}',
            data: {
                menu_id: 2
            },
            success: function(response) {
                var permissions = response.permissions;
                console.log(permissions);

                // Check if the user has permission to edit
                if (permissions.edit === 'yes') {
                    $('.edit-btn').show();
                } else {
                    $('.edit-btn').hide();
                }

                // Check if the user has permission to delete
                if (permissions.delete === 'yes') {
                    $('.delete-btn').show();
                } else {
                    $('.delete-btn').hide();
                }

                // Check if the user has permission to create
                if (permissions.create === 'yes') {
                    $('#addNewBtn').show();
                } else {
                    $('#addNewBtn').hide();
                }
            },
            error: function(error) {
                console.error('Error fetching permissions:', error);
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
                        $('#username').val(user.username);
                        $('#email').val(user.email);
                        $('#role').val(user.user_role.role.id);
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

            alertify.confirm('Are you sure?', function(e) {
                if (e) {
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
                }
            });
        });
    });
</script>
@endsection
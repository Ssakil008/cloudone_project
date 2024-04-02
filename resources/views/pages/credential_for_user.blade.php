@extends('layouts.app')
@section('title','Credential For User')
@section('content')

<div class="clearfix"></div>

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row pt-2 pb-2 align-items-center">
            <div class="col-sm-9">
                <h4 class="page-title">Credential For User</h4>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 text-right">
                <button type="button" id="addNewBtn" class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">Add New</button>
            </div>
        </div>

        <table class="table table-bordered table-hover" id="dataTable">
            <thead>
                <tr>
                    <th>Sl</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Password</th>
                    <th>Actions</th>
                    <th>Additional Information</th>
                    <!-- Thead will be generated dynamically by DataTables -->
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
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
                <form id="dynamicForm">
                    @csrf
                    <input type="hidden" name="userId" id="userId">
                    <div class="form-group">
                        <label for="credential_for">Name</label>
                        <input type="text" id="name" name="name" required placeholder="Name" value="{{ old('name') }}" class="form-control input-shadow">
                        <span class="text-danger" id="name_error"></span>
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
                        <label for="password">Password</label>
                        <input type="text" name="password" required id="password" placeholder="Password" class="form-control input-shadow" />
                        <span class="text-danger" id="password_error"></span>
                    </div>

                    <button type="button" class="btn btn-info btn-sm" id="addField">
                        <i class="icon-plus"></i> Add Field
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" id="removeField">
                        <i class="icon-minus"></i> Remove Field
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info btn-sm" id="userSubmit"></button>
                <a href=""></a>
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
            $('#dynamicForm')[0].reset();
            $(".dynamic-field").remove();
            $('#userSubmit').text('Submit');
            $('.modal-title').html('<strong>Add New User</strong>');
            $('#addUserModal .text-danger').text('');
        });

        $("#removeField").click(function() {
            $(".dynamic-field:last").remove();
        });

        $("#addField").click(function() {
            // Create a new row for the dynamic fields
            var newRow = $('<div class="row dynamic-field">');

            // Create the "Field Name" input field
            var fieldNameInput = $('<input>').attr({
                'type': 'text',
                'class': 'form-control',
                'name': 'fields[][field_name]',
                'placeholder': 'Field Name'
            });

            // Create the "Field Value" input field
            var fieldValueInput = $('<input>').attr({
                'type': 'text',
                'class': 'form-control',
                'name': 'fields[][field_value]',
                'placeholder': 'Field Value'
            });

            // Append the "Field Name" and "Field Value" input fields to the new row
            newRow.append($('<div class="form-group col-md-6">').append('<label>Additional Field</label>').append(fieldNameInput));
            newRow.append($('<div class="form-group col-md-6" style="margin: 30px 0px;">').append(fieldValueInput));

            // Insert the new row before the "Add Field" button
            $("#addField").before(newRow);
        });

        $("#userSubmit").click(function(e) {
            var isValid = validateForm();
            if (isValid) {
                e.preventDefault();

                // Create a new FormData object
                var formData = new FormData($('#dynamicForm')[0]);
                console.log(formData);

                alertify.confirm('Are you sure?', function(e) {
                    if (e) {
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: "{{ route('storeDynamicData') }}",
                            type: "POST",
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function(response) {
                                console.log(response);
                                $('#addUserModal').modal('hide');
                                $('#successmessage').text(response.message);
                                $('#successmodal').modal('show');
                                setTimeout(function() {
                                    $('#successmodal').modal('hide');
                                    // Redirect to user setup page
                                    window.location.replace('{{ route("credential_for_user") }}');
                                }, 2000);
                            },
                            error: function(xhr, status, error) {
                                console.error(xhr.responseText);
                                var errorMessage = "Failed to process the request.";
                                if (xhr.responseJSON && xhr.responseJSON.error) {
                                    errorMessage = xhr.responseJSON.error;
                                }
                                $('#addUserModal').modal('hide');
                                $('#errormessage').text(errorMessage);
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

        $('#dataTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{ route('getDynamicData')}}",
                "type": "GET"
            },
            "columns": [{
                    "data": null,
                    "render": function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    "data": "name"
                },
                {
                    "data": "email"
                },
                {
                    "data": "mobile"
                },
                {
                    "data": "password"
                },
                {
                    "data": "action",
                    "render": function(data, type, row) {
                        return data ? data : '<i class="icon-note mr-2 edit-btn align-middle text-info" data-user-id="' + row.id + '"></i>' +
                            '<i class="fa fa-trash-o delete-btn align-middle text-danger" data-user-id="' + row.id + '"></i> ';
                    }
                },
                {
                    "data": "information",
                    "render": function(data, type, row) {
                        return data ? data : '<a href="{{ route("additional_information") }}" class="show-btn align-middle text-info" data-user-id="' + row.id + '">Show</a>';
                    }
                }
            ],
        });

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: '{{ route("fetchUserPermissions") }}',
            data: {
                menu_id: 4
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

        $('#dataTable').on('click', '.delete-btn', function() {
            var userId = $(this).data('user-id');
            console.log(userId);

            alertify.confirm('Are you sure?', function(e) {
                if (e) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',
                        url: '{{ route("deleteCredentialForUserData") }}',
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
                                    window.location.replace('{{ route("credential_for_user") }}');
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

        $('#dataTable').on('click', '.edit-btn', function() {
            var userId = $(this).data('user-id');
            console.log(userId);

            $.ajax({
                type: 'GET',
                url: '{{ url("getCredentialForUserData") }}/' + userId,
                success: function(response) {
                    console.log(response);
                    if (response.hasOwnProperty('data')) {
                        var user = response.data;
                        $('#dynamicForm')[0].reset();
                        $(".dynamic-field").remove();
                        $('#addUserModal .text-danger').text('');
                        $('#addUserModal').modal('show');
                        $('#userId').val(user.id);
                        $('#name').val(user.name);
                        $('#email').val(user.email);
                        $('#mobile').val(user.mobile);
                        $('#password').val(user.password);
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
    });
</script>



@endsection
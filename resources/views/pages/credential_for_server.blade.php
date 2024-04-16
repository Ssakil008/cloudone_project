@extends('layouts.app')
@section('title','Credential For Server')
@section('content')

<div class="clearfix"></div>

<div class="content-wrapper">
    <div class="container-fluid">
        <!-- Breadcrumb-->
        <div class="row pt-2 pb-2">
            <div class="col-sm-9">
                <h4 class="page-title">Credential Details</h4>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 text-right">
                <!-- Button to Open Modal -->
                <button type="button" id="addNewBtn" class="btn btn-primary">Add New User</button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered" id="entries-table">
                <thead>
                    <tr>
                        <th>Serial No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>URL</th>
                        <th>IP Address</th>
                        <th>Username</th>
                        <th>Password</th>
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

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Your form goes here -->

                <form id="myForm">
                    @csrf
                    <input type="hidden" name="entryId" id="entryId">
                    <div class="form-group">
                        <label for="credential_for">Name</label>
                        <input type="text" id="credential_for" name="credential_for" required placeholder="Name" value="{{ old('name') }}" class="form-control input-shadow">
                        <span class="text-danger" id="credential_for_error"></span>
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
                        <label for="url">Website Name</label>
                        <input type="url" name="url" required id="url" placeholder="Website Name" value="{{old('url')}}" class="form-control input-shadow">
                        <span class="text-danger" id="url_error"></span>
                    </div>


                    <div class="form-group">
                        <label for="ip_address">IP Address</label>
                        <input type="text" id="ip_address" name="ip_address" required placeholder="e.g., 192.168.1.1" value="{{old('ip_address')}}" class="form-control input-shadow">
                        <span class="text-danger" id="ip_address_error"></span>
                    </div>

                    <div class="form-group">
                        <label for="username">User Name</label>
                        <input type="text" name="username" required id="username" placeholder="User Name" value="{{old('username')}}" class="form-control input-shadow">
                        <span class="text-danger" id="username_error"></span>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="text" name="password" required id="password" placeholder="Password" class="form-control input-shadow" />
                        <span class="text-danger" id="password_error"></span>
                    </div>

                    <!-- Add other form fields as needed -->
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="submitBtn"></button>
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
        // Clear form fields when the "Add New" button is clicked
        $('#addNewBtn').click(function() {
            $('#myForm')[0].reset();
            $('#submitBtn').text('Submit');
            $('.modal-title').html('<strong>Add New User</strong>');
            $('#myModal .text-danger').text('');
        });

        $('#addNewBtn').click(function() {
            var menuId = '{{ $menuId }}'; // Replace with the actual menuId you want to check permissions for

            // Make an AJAX call to check permissions
            $.ajax({
                url: '{{ route("checkPermission") }}',
                type: 'POST',
                data: {
                    menuId: menuId
                },
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        // Show the modal
                        $('#myModal').modal('show');
                    } else {
                        // Permission denied, show a message or handle it as needed
                        alertify.alert('Permission denied');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error checking permission:', error);
                    // Handle error
                }
            });
        });
    });

    $(document).ready(function() {
        function validateMobileNumber(mobileNumber) {
            var regex = /^(\+?8801|01)[1-9]\d{8}$/;
            return regex.test(mobileNumber);
        }

        function validateWebsite(website) {
            try {
                new URL(website);
                return true;
            } catch (error) {
                return false;
            }
        }

        function validateEmail(email) {
            var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regex.test(email);
        }

        $('#submitBtn').click(function(e) {
            var isValid = validateForm();
            if (isValid) {
                // Create a new FormData object
                var formData = $('#myForm').serialize();

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

                // Get the website from the form
                var website = document.getElementById('url').value;

                // Check website validation
                if (!validateWebsite(website)) {
                    alertify.alert('URL is not valid');
                    return; // Prevent form submission if email is invalid
                }

                e.preventDefault(); // Prevent default form submission behavior

                alertify.confirm('Are you sure?', function(e) {
                    if (e) {
                        $.ajax({
                            type: 'POST',
                            url: '{{ route("upsertCredential") }}',
                            data: formData,
                            success: function(response) {
                                if (response.success) {
                                    $('#myModal').modal('hide');
                                    $('#successmessage').text(response.message);
                                    $('#successmodal').modal('show');
                                    setTimeout(function() {
                                        $('#successmodal').modal('hide');
                                        window.location.href = '{{ route("credential_for_server") }}';
                                    }, 2000);
                                } else {
                                    // Handle server-side errors
                                    displayErrors(response.errors);
                                }
                            },
                            error: function(error) {
                                console.error('AJAX error:', error);
                                $('#myModal').modal('hide');
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
            $('#myForm input[required]').each(function() {
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
        $('#entries-table').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{ route('get-entries') }}",
                "type": "GET"
            },
            "columns": [{
                    // Render consecutive row numbers
                    "data": null,
                    "render": function(data, type, row, meta) {
                        return meta.row + 1; // Row index starts from 0, so add 1 to make it consecutive
                    }
                },
                {
                    "data": "credential_for"
                },
                {
                    "data": "email"
                },
                {
                    "data": "mobile"
                },
                {
                    "data": "url"
                },
                {
                    "data": "ip_address"
                },
                {
                    "data": "username"
                },
                {
                    "data": "password"
                },
                {
                    "data": "action",
                    "render": function(data, type, row) {
                        if (!data) {
                            return '<i class="icon-note mr-2 edit-btn align-middle text-info" data-entry-id="' + row.id + '"></i>' +
                                '<i class="fa fa-trash-o delete-btn align-middle text-danger" data-entry-id="' + row.id + '"></i>';
                        } else {
                            return data;
                        }
                    }
                }

            ]
        });

        $('#entries-table').on('click', '.edit-btn', function() {
            // Retrieve entry ID from the clicked button
            var entryId = $(this).data('entry-id');
            console.log(entryId);

            // Make an AJAX request to get the entry data based on the ID
            $.ajax({
                type: 'GET',
                url: '{{ url("get-entry") }}/' + entryId,
                success: function(response) {
                    // Check if the response has the 'data' property
                    if (response.hasOwnProperty('data')) {
                        var entry = response.data;

                        // Populate the modal with the entry data
                        $('#myModal .text-danger').text('');
                        $('#myModal').modal('show');
                        $('#entryId').val(entry.id);
                        $('#credential_for').val(entry.credential_for);
                        $('#email').val(entry.email);
                        $('#mobile').val(entry.mobile);
                        $('#url').val(entry.url);
                        $('#ip_address').val(entry.ip_address);
                        $('#username').val(entry.username);
                        $('#password').val(entry.password);
                        $('#submitBtn').text('Update');
                        $('.modal-title').html('<strong>Edit The User</strong>');
                    } else {
                        console.error('Invalid response structure:', response);
                    }
                },
                error: function(error) {
                    console.error('Error fetching entry:', error);
                }
            });
        });

        $('#entries-table').on('click', '.delete-btn', function() {
            var id = $(this).data('entry-id');
            var menuId = '{{ $menuId }}';
            console.log(id, menuId);
            alertify.confirm('Are you sure?', function(e) {
                if (e) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',
                        url: '{{ route("deleteCredential") }}',
                        data: {
                            id: id,
                            menuId: menuId,
                        },
                        success: function(response) {
                            console.log(response);
                            if (response.success) {
                                $('#successmessage').text(response.message); // Show success message
                                $('#successmodal').modal('show');
                                setTimeout(function() {
                                    $('#successmodal').modal('hide');
                                    window.location.href = '{{ route("credential_for_server") }}';
                                }, 2000);
                            } else {
                                $('#errormessage').text(response.message); // Show error message
                                $('#errormodal').modal('show');
                                setTimeout(function() {
                                    $('#errormodal').modal('hide');
                                }, 2000);
                            }
                        },
                        error: function(error) {
                            $('#errormessage').text(response.message); // Show error message
                            $('#errormodal').modal('show');
                            setTimeout(function() {
                                $('#errormodal').modal('hide');
                            }, 2000);
                        }
                    });
                }
            });
        });

    });
</script>

@endsection
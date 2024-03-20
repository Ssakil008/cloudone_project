@extends('layouts.app')
@section('title','Role')
@section('content')

<div class="content-wrapper">
    <div class="content-header row">
    </div>

    <div class="content-body">
        <div class="row">
            <div class="col-lg-12 my-1">
                <div class="d-flex justify-content-between">
                    <button type="button" id="addNewRole" class="btn btn-info btn-sm" data-toggle="modal" data-target="#newRoleModal"><i class="icon-plus"></i> New Role</button>
                    <button type="button" id="addNewPermission" class="btn btn-info btn-sm" data-toggle="modal" data-target="#permissionModal">
                        <i class="fa fa-pencil-square-o"></i> Permission
                    </button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-5">
                <div class="card border border-dark">
                    <div class="card-header">
                        <i class="fa fa-user-o"> Role</i>
                        <div class="mb-2 px-1 py-1">
                            <div class="row">
                                <div class="col-md-12 col-lg-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="roles-table">
                                            <thead>
                                                <tr>
                                                    <th>Sl</th>
                                                    <th>Role</th>
                                                    <th>Description</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card border border-dark">
                    <div class="card-header">
                        <i class="icon-badge"> Permission</i>
                        <div class="mb-2 px-1 py-1">
                            <div class="row">
                                <div class="col-md-12 col-lg-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="permission-table" style="display: none;">
                                            <thead>
                                                <tr>
                                                    <th>Module</th>
                                                    <th>Read</th>
                                                    <th>Create</th>
                                                    <th>Edit</th>
                                                    <th>Delete</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Table body content -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- New Role Modal -->
<div class="modal fade" id="newRoleModal" tabindex="-1" role="dialog" aria-labelledby="newRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newRoleModalLabel">New Role</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addRoleForm">
                    @csrf
                    <input type="hidden" name="roleId" id="roleId">
                    <div class="form-group">
                        <label for="role">Role</label>
                        <input type="text" name="role" required class="form-control" id="role" placeholder="Enter Role">
                        <span class="text-danger" id="role_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <input type="text" name="description" required class="form-control" id="description" placeholder="Enter Description">
                        <span class="text-danger" id="description_error"></span>
                    </div>
                    <button type="button" id="roleSubmit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End modal -->

<!-- Permission Modal -->
<div class="modal fade" id="permissionModal" tabindex="-1" role="dialog" aria-labelledby="permissionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="permissionModalLabel">Permission</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="permissionForm">
                    @csrf
                    <div class="form-group">
                        <label for="permission">Permission</label>
                        <select class="form-control" id="permissionSelect">
                            <option disabled selected>Select Permission</option>
                            <option value="dashboard">Dashboard</option>
                            <option value="credential_for_server">Credential For Server</option>
                            <option value="user_setup">User Setup</option>
                            <option value="role">Role</option>
                            <!-- Add options here if needed -->
                        </select>
                    </div>
                    <div class="d-flex justify-content-start align-items-center">
                        <div class="form-check mr-3">
                            <input type="checkbox" class="form-check-input" id="readCheckbox" name="read">
                            <label class="form-check-label" for="readCheckbox">Read</label>
                        </div>
                        <div class="form-check mr-3">
                            <input type="checkbox" class="form-check-input" id="createCheckbox" name="create">
                            <label class="form-check-label" for="createCheckbox">Create</label>
                        </div>
                        <div class="form-check mr-3">
                            <input type="checkbox" class="form-check-input" id="editCheckbox" name="edit">
                            <label class="form-check-label" for="editCheckbox">Edit</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="deleteCheckbox" name="delete">
                            <label class="form-check-label" for="deleteCheckbox">Delete</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End modal -->



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
        $('#addNewRole').click(function() {
            $('#addRoleForm')[0].reset();
            $('#roleSubmit').text('Submit');
            $('.modal-title').html('<strong>Add New role</strong>');
            $('#newRoleModal .text-danger').text('');
        });
    });

    $(document).ready(function() {
        $('#roleSubmit').click(function() {
            var isValid = validateForm();
            if (isValid) {
                var formData = $('#addRoleForm').serialize();
                console.log(formData);
                alertify.confirm('Are you sure?', function(e) {
                    if (e) {
                        $.ajax({
                            type: 'POST',
                            url: '{{ route("addRole") }}',
                            data: formData,
                            success: function(response) {
                                if (response.success) {
                                    $('#newRoleModal').modal('hide');
                                    $('#successmessage').text('New role Added');
                                    $('#successmodal').modal('show');
                                    setTimeout(function() {
                                        $('#successmodal').modal('hide');
                                        window.location.href = '{{ route("role") }}';
                                    }, 2000);
                                } else {
                                    // Handle server-side errors
                                    displayErrors(response.errors);
                                }
                            },
                            error: function(error) {
                                console.error('AJAX error:', error);
                                $('#newRoleModal').modal('hide');
                                $('#errormessage').text('role not added');
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
            $('#addRoleForm input[required]').each(function() {
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
        // Fetch roles data from the server
        $.ajax({
            type: 'GET',
            url: '{{ route("getAllRoleData") }}',
            success: function(response) {
                // Check if the response has the 'data' property
                if (response.hasOwnProperty('data')) {
                    var roles = response.data;
                    var serialNumber = 1;

                    // Iterate through roles and append rows to the table
                    $.each(roles, function(index, role) {
                        var row = '<tr>' +
                            '<td>' + serialNumber + '</td>' +
                            '<td>' + role.role + '</td>' +
                            '<td>' + role.description + '</td>' +
                            '<td>' +
                            '<i class="icon-lock-open mr-2 permission-btn align-middle text-success" data-role-id="' + role.id + '"></i>' +
                            '<i class="icon-note mr-2 edit-btn align-middle text-info" data-role-id="' + role.id + '"></i>' +
                            '<i class="fa fa-trash-o delete-btn align-middle text-danger" data-role-id="' + role.id + '"></i>' +
                            '</td>' +
                            '</tr>';

                        $('#roles-table tbody').append(row);
                        serialNumber++;
                    });
                } else {
                    console.error('Invalid response structure:', response);
                }
            },
            error: function(error) {
                console.error('Error fetching roles:', error);
            }
        });

        $('#roles-table').on('click', '.edit-btn', function() {
            var roleId = $(this).data('role-id');
            console.log(roleId);

            $.ajax({
                type: 'GET',
                url: '{{ url("getRoleData") }}/' + roleId,
                success: function(response) {
                    console.log(response);
                    if (response.hasOwnProperty('data')) {
                        var role = response.data;
                        $('#addRoleForm')[0].reset();
                        $('#newRoleModal .text-danger').text('');
                        $('#newRoleModal').modal('show');
                        $('#roleId').val(role.id);
                        $('#role').val(role.role);
                        $('#description').val(role.description);
                        $('#roleSubmit').text('Update');
                        $('.modal-title').html('<strong>Edit The Role</strong>');
                    } else {
                        console.error('Invalid response structure:', response);
                    }
                },
                error: function(error) {
                    console.error('Error fetching role:', error);
                }
            });
        });

        $('#roles-table').on('click', '.delete-btn', function() {
            var roleId = $(this).data('role-id');
            console.log(roleId);

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
                    url: '{{ route("deleteRoleData") }}',
                    data: {
                        roleId: roleId,
                    },
                    success: function(response) {
                        console.log(response);
                        $('#deleteConfirmationModal').modal('hide');
                        if (response.success) {
                            // Display success message
                            $('#successmessage').text('role deleted successfully.');
                            $('#successmodal').modal('show');
                            setTimeout(function() {
                                $('#successmodal').modal('hide');
                                // Redirect to role setup page
                                window.location.replace('{{ route("role") }}');
                            }, 2000);
                        } else {
                            // Display error message
                            $('#errormessage').text('role deletion failed');
                            $('#errormodal').modal('show');
                            setTimeout(function() {
                                $('#errormodal').modal('hide');
                            }, 2000);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                        // Display error message
                        $('#errormessage').text('Error deleting role: ' + error);
                        $('#errormodal').modal('show');
                        console.error('Error deleting role:', error);
                    }
                });
            });
        });
    });

    $(document).ready(function() {
        // Add click event listener to permission buttons
        $('.permission-btn').click(function() {
            // Enable the permission button
            $('#addNewPermission').prop('disabled', false);

            // Show the permission table
            $('#permission-table').show();

            // Store the ID of the clicked permission button
            var roleId = $(this).data('role-id');
            // You can use this roleId for further operations
            console.log("Clicked permission button ID: ", roleId);

            // Fetch data for the permission table based on roleId using AJAX
            $.ajax({
                type: 'GET',
                url: '{{ route("getAllPermission", ["id" => ":id"]) }}'.replace(':id', roleId),
                success: function(response) {
                    // Check if the response has the 'data' property
                    if (response.hasOwnProperty('data')) {
                        var permissions = response.data;

                        // Iterate through roles and append rows to the table
                        $.each(permissions, function(index, permission) {
                            var row = '<tr>' +
                                '<td>' + permission.module + '</td>' +
                                '<td>' + permission.read + '</td>' +
                                '<td>' + permission.create + '</td>' +
                                '<td>' + permission.edit + '</td>' +
                                '<td>' + permission.delete + '</td>' +
                                '<td>' +
                                '<i class="icon-note mr-2 permission-edit-btn align-middle text-info" data-permission-id="' + permission.id + '"></i>' +
                                '<i class="fa fa-trash-o permission-delete-btn align-middle text-danger" data-permission-id="' + permission.id + '"></i>' +
                                '</td>' +
                                '</tr>';
                            $('#roles-table tbody').append(row);
                        });
                    } else {
                        console.error('Invalid response structure:', response);
                    }
                },
                error: function(error) {
                    console.error('Error fetching roles:', error);
                }
            });
        });
    });

    $(document).ready(function() {
        $('#permissionForm').submit(function(e) {
            e.preventDefault(); // Prevent form submission

            // Check if at least one checkbox is checked
            if (!$('#readCheckbox').is(':checked') &&
                !$('#createCheckbox').is(':checked') &&
                !$('#editCheckbox').is(':checked') &&
                !$('#deleteCheckbox').is(':checked')) {
                // If none of the checkboxes is checked, show an error message or perform other actions
                alert('Please select at least one permission.');
                return;
            }

            // Prepare form data including role_id
            var formData = {
                role_id: 1, // Change this value based on your requirement
                permission: $('#permissionSelect').val(),
                read: $('#readCheckbox').is(':checked') ? 'yes' : 'no',
                create: $('#createCheckbox').is(':checked') ? 'yes' : 'no',
                edit: $('#editCheckbox').is(':checked') ? 'yes' : 'no',
                delete: $('#deleteCheckbox').is(':checked') ? 'yes' : 'no'
            };

            // Send AJAX request
            $.ajax({
                type: 'POST',
                url: '{{ route("insertPermission") }}',
                data: formData,
                success: function(response) {
                    // Handle success response
                    console.log(response);
                    // Optionally, you can show a success message or perform other actions
                },
                error: function(error) {
                    // Handle error response
                    console.error('Error:', error);
                    // Optionally, you can show an error message or perform other actions
                }
            });
        });
    });
</script>

@endsection
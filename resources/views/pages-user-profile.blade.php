@extends('layouts.app')

@section('content')
        
        <div class="content-wrapper">
            <div class="container-fluid">
                <!-- Breadcrumb-->
                <div class="row pt-2 pb-2">
                    <div class="col-sm-9">
                        <h4 class="page-title">User Profile</h4>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 text-right">
                        <!-- Button to Open Modal -->
                        <button type="button" id="addNewBtn" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Add new</button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="entries-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Credential For</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>URL</th>
                                <th>IP Address</th>
                                <th>Username</th>
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





    </div><!--End wrapper-->

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New User</h5>
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
                            <label for="credential_for" class="sr-only">Name</label>
                            <div class="position-relative has-icon-right">
                                <input type="text" id="credential_for" name="credential_for" required placeholder="Name" value="{{old('name')}}" class="form-control input-shadow">
                                <span class="text-danger">@error('name') {{$message}} @enderror</span>
                                <div class="form-control-position">
                                    <i class="zmdi zmdi-account material-icons-name"></i>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="sr-only">Email ID</label>
                            <div class="position-relative has-icon-right">
                                <input type="email" id="email" name="email" name="email" required placeholder="E-mail" value="{{old('email')}}" class="form-control input-shadow">
                                <span class="text-danger">@error('email') {{$message}} @enderror</span>
                                <div class="form-control-position">
                                    <i class="zmdi zmdi-email"></i>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="mobile" class="sr-only">Mobile Number</label>
                            <div class="position-relative has-icon-right">
                                <input type="number" name="mobile" required id="mobile" placeholder="Mobile Number" class="form-control input-shadow">
                                <div class="form-control-position">
                                    <span class="text-danger">@error('mobile') {{$message}} @enderror</span>
                                    <i class="zmdi zmdi-smartphone material-icons-name"></i>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="url" class="sr-only">Website Name</label>
                            <div class="position-relative has-icon-right">
                                <input type="url" name="url" required id="url" placeholder="Website Name" value="{{old('url')}}" class="form-control input-shadow">
                                <span class="text-danger">@error('url') {{$message}} @enderror</span>
                                <div class="form-control-position">
                                    <i class="zmdi zmdi-laptop"></i>
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="ip" class="sr-only">IP Address</label>
                            <div class="position-relative has-icon-right">
                                <input type="text" id="ip_address" name="ip_address" placeholder="e.g., 192.168.1.1" value="{{old('ip_address')}}" pattern="^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$" class="form-control input-shadow">
                                <span class="text-danger">@error('ip_address') {{$message}} @enderror</span>
                                <div class="form-control-position">
                                    <i class="zmdi zmdi-device-hub"></i>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="username" class="sr-only">User Name</label>
                            <div class="position-relative has-icon-right">
                                <input type="text" name="username" required id="username" placeholder="User Name" value="{{old('username')}}" class="form-control input-shadow">
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

                        <!-- Add other form fields as needed -->
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="submitBtn">Submit</button>
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
    <script src="https://cdn.datatables.net/v/dt/dt-1.11.6/datatables.min.js"></script>

    <script>
        $(document).ready(function() {
            // Clear form fields when the "Add New" button is clicked
            $('#addNewBtn').click(function() {
                $('#myModal')[0].reset();
            });

            // Your other code...
        });

        $(document).ready(function() {
            $('#submitBtn').click(function() {
                // Get form data
                var formData = $('#myForm').serialize();
                console.log(formData);
                // Make AJAX call
                alertify.confirm('Are you sure?', function(e) {
                    if (e) {
                        $.ajax({
                            type: 'POST',
                            url: '{{ route("new-user") }}', // Replace with your actual endpoint URL
                            data: formData,
                            success: function(response) {
                                if (response.success) {
                                    // Handle success, e.g., show a success message
                                    alert(response.success);
                                    $('#myModal').modal('hide');
                                    window.location.href = '{{ route("pages-user-profile") }}';
                                } else {
                                    // Handle errors, e.g., show error messages
                                    alert(response.fail || 'Failed to register user');
                                }
                            },
                            error: function(error) {
                                // Handle error
                                console.error('AJAX error:', error);
                            }
                        });
                    }
                });
            });
        });

        $(document).ready(function() {
            // Fetch entries data from the server
            $.ajax({
                type: 'GET',
                url: '{{ route("get-entries") }}',
                success: function(response) {
                    // Check if the response has the 'data' property
                    if (response.hasOwnProperty('data')) {
                        var entries = response.data;

                        // Iterate through entries and append rows to the table
                        $.each(entries, function(index, entry) {
                            var row = '<tr>' +
                                '<td>' + entry.id + '</td>' +
                                '<td>' + entry.credential_for + '</td>' +
                                '<td>' + entry.email + '</td>' +
                                '<td>' + entry.mobile + '</td>' +
                                '<td>' + entry.url + '</td>' +
                                '<td>' + entry.ip_address + '</td>' +
                                '<td>' + entry.username + '</td>' +
                                '<td>' +
                                '<button class="btn btn-sm btn-primary edit-btn" data-entry-id="' + entry.id + '">Edit</button>' +
                                '<button class="btn btn-sm btn-danger delete-btn" data-entry-id="' + entry.id + '">Delete</button>' +
                                '</td>' +
                                '</tr>';

                            $('#entries-table tbody').append(row);
                        });
                    } else {
                        console.error('Invalid response structure:', response);
                    }
                },
                error: function(error) {
                    console.error('Error fetching entries:', error);
                }
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
                            $('#myModal').modal('show');
                            $('#entryId').val(entry.id);
                            $('#credential_for').val(entry.credential_for);
                            $('#email').val(entry.email);
                            $('#mobile').val(entry.mobile);
                            $('#url').val(entry.url);
                            $('#ip_address').val(entry.ip_address);
                            $('#username').val(entry.username);
                            $('#submitBtn').text('Edit');
                            $('.modal-title').text('Edit The User');
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
                var entryId = $(this).data('entry-id');
                var userId = $(this).data('user-id');
                console.log(entryId, userId);

                // Confirm deletion
                if (confirm('Are you sure you want to delete this entry?')) {
                    // Make an AJAX request to delete the entry
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',
                        url: '{{ route("delete-entry") }}',
                        data: {
                            entryId: entryId,
                        },
                        success: function(response) {
                            if (response.success) {
                                // Reload the entries table or update the UI as needed
                                // For example: $('#entries-table').DataTable().ajax.reload();
                                console.log('Entry deleted successfully.');
                                window.location.href = '{{ route("pages-user-profile") }}';
                            } else {
                                console.error('Failed to delete entry:', response.error || 'Unknown error');
                            }
                        },
                        error: function(error) {
                            console.error('Error deleting entry:', error);
                        }
                    });
                }
            });

        });
    </script>

@endsection

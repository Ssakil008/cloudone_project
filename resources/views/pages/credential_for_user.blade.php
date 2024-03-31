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
                    <th>Field 1</th>
                    <th>Field 2</th>
                </tr>
            </thead>
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
                    <button type="button" class="btn btn-info btn-sm" id="addField">
                        <i class="icon-plus"></i> Add Field
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info btn-sm" id="userSubmit"></button>
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
            $('#userSubmit').text('Submit');
            $('.modal-title').html('<strong>Add New User</strong>');
            $('#addUserModal .text-danger').text('');
        });

        $("#addField").click(function() {
            // Create a new row for the dynamic fields
            var newRow = $('<div class="row">');

            // Create the "Field Name" input field
            var fieldNameInput = $('<input>').attr({
                'type': 'text',
                'class': 'form-control dynamic-field',
                'name': 'fields[][field_name]',
                'placeholder': 'Field Name'
            });

            // Create the "Field Value" input field
            var fieldValueInput = $('<input>').attr({
                'type': 'text',
                'class': 'form-control dynamic-field',
                'name': 'fields[][field_value]',
                'placeholder': 'Field Value'
            });

            // Append the "Field Name" and "Field Value" input fields to the new row
            newRow.append($('<div class="form-group col-md-6">').append('<label>Field Name</label>').append(fieldNameInput));
            newRow.append($('<div class="form-group col-md-6">').append('<label>Field Value</label>').append(fieldValueInput));

            // Insert the new row before the "Add Field" button
            $("#addField").before(newRow);
        });


        $("#userSubmit").click(function(e) {
            e.preventDefault();
            var formData = $('#dynamicForm').serialize();
            console.log(formData);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('storeDynamicData') }}",
                type: "POST",
                data: formData,
                success: function(response) {
                    console.log(response);
                    $('#addUserModal').modal('hide');
                    if (response.success) {
                        // Display success message
                        $('#successmessage').text(response.message);
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
                error: function(error) {
                    console.error(error);
                }
            });
        });
    });

    $(document).ready(function() {
        $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("getDynamicData") }}',
            columns: [{
                    data: 'field_name'
                }, // Assuming 'field_name' is stored in database
                {
                    data: 'field_value'
                },
                // Define columns for other fields as needed
            ]
        });
    });
</script>



@endsection
@extends('layouts.app')
@section('title','Additional Information')
@section('content')

<div class="clearfix"></div>

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row pt-2 pb-2 align-items-center">
            <div class="col-sm-9">
                <h4 class="page-title"></h4>
            </div>
        </div>


        <div id="informationContainer" class="information-container">

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
        var urlParams = new URLSearchParams(window.location.search);
        var id = urlParams.get('id');
        var name = urlParams.get('name');

        // Update the page title
        $('.page-title').text('Additional Information of ' + name);
        // Fetch data via AJAX
        $.ajax({
            url: "{{ route('get_all_information')}}/" + id,
            type: "GET",
            dataType: "json",
            success: function(response) {
                if (response && response.data) {
                    var data = response.data;
                    var informationContainer = $('#informationContainer');
                    informationContainer.empty(); // Clear previous content

                    $.each(data, function(index, item) {
                        // Assuming you want to display field_name and field_value
                        var html = '<div class="information-item">' +
                            '<p><strong>' + item.field_name + '</strong>: ' + item.field_value + '</p>' +
                            '</div>';
                        informationContainer.append(html);
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                // Handle error if needed
            }
        });
    });
</script>
@endsection
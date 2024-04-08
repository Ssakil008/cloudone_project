@extends('layouts.app')
@section('title','Credential For User')
@section('content')

<div class="clearfix"></div>

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row pt-2 pb-2 align-items-center">
            <div class="col-sm-9">
                <h4 class="page-title">Additional Information {{$id}}</h4>
            </div>
        </div>

        <div class="additonal_information">
            <table class="table table-bordered" id="dataTable">
                <thead>
                    <tr>
                        <th>Field Name</th>
                        <th>Field Value</th>
                        <!-- Thead will be generated dynamically by DataTables -->
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        var id = $id;
        $.ajax({
            type: get,
            url: '{{route(getAllInformation)}}/' + id,
            success: function(response) {

            }
        })
    });
</script>
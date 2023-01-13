@extends('layouts.master')
@section('title', 'Vehicles')
<!-- start page title -->
@section('page_css')
@include('partials.datatable_css')
<!-- Plugins css -->
<link href="/libs/dropzone/min/dropzone.min.css" rel="stylesheet" type="text/css" />
<link href="/libs/dropify/css/dropify.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">All Vehicles</h4>
        </div>
    </div>
</div>
<!-- end page title -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#staticBackdrop"> Add </button>
            </div>
            <div class="card-body">
                <h4 class="header-title">Vehicles</h4>
                <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>Vehicle Type</th>
                            <th>Vehicle's Number</th>
                            <th>Vehicle Picture from front</th>
                            <th>Vehicle license plate picture</th>
                        </tr>
                    </thead>


                    <tbody>
                        <tr>
                            <td>Tiger Nixon</td>
                            <td>System Architect</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Garrett Winters</td>
                            <td>Accountant</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Ashton Cox</td>
                            <td>Junior Technical Author</td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>
<!-- end row-->
<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="staticBackdropLabel">Add Vehicle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-xl-12">
                    <div class="card shadow-none">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">

                                    <div class="mb-3">
                                        <label for="example-select" class="form-label">Vehicle Type</label>
                                        <select class="form-select" id="example-select">
                                            <option>Bus</option>
                                            <option>Coaster</option>
                                            <option>Hiace</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <div class="mt-1">
                                            <input type="file" data-plugins="dropify" data-default-file="/images/small/img-2.jpg" />
                                            <p class="text-muted text-center mt-2 mb-0">Vehicle Picture from front</p>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-lg-6">


                                    <div class="mb-3">
                                        <label for="simpleinput" class="form-label">Vehicle's Number</label>
                                        <input type="text" id="simpleinput" class="form-control">
                                    </div>
                                    <div class="col-12">
                                        <div class="mt-1">
                                            <input type="file" data-plugins="dropify" data-default-file="/images/small/img-2.jpg" />
                                            <p class="text-muted text-center mt-2 mb-0">Vehicle license plate picture</p>
                                        </div>
                                    </div>

                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-success waves-effect waves-light">Save</button>
                                </div>

                            </div>
                        </div> <!-- end card-body -->
                    </div> <!-- end card-->
                </div>
            </div>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Submit</button>
            </div> -->
        </div>
    </div>
</div>
@endsection

@section('page_js')
@include('partials.datatable_js')
<!-- Plugins js-->
<script src="/libs/twitter-bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script>

<!-- Plugins js -->
<script src="/libs/dropzone/min/dropzone.min.js"></script>
<script src="/libs/dropify/js/dropify.min.js"></script>

<!-- Init js-->
<script src="/js/pages/form-fileuploads.init.js"></script>

<!-- Init js-->
<script src="/js/pages/form-wizard.init.js"></script>
<script>
    $(document).ready(function() {

    });
</script>
@endsection
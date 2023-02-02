@extends('layouts.app')
@section('title', 'Drivers')
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
            <h4 class="page-title">All Drivers</h4>
        </div>
    </div>
</div>
<!-- end page title -->
<div class="row">
    <div class="col-12 table-responsive">
        <div class="card">
            <div class="card-header">
                <button type="button" type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#staticBackdrop"> Add </button>
            </div>
            <div class="card-body">
                <h4 class="header-title">Drivers</h4>
                <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox">
                            </th>
                            <th>Date</th>
                            <th>Organization Name</th>
                            <th>Name</th>
                            <th>Phone No</th>
                            <th>CNIC Number</th>
                            <th>License Number</th>
                            <th>CNIC Front</th>
                            <th>CNIC Back</th>
                            <th>License Front</th>
                            <th>License Back</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>


                    <tbody>
                        <tr>
                            <td><input type="checkbox"></td>
                            <td>20/12/2022</td>
                            <td><b><a href="#">University of Sargodha</a></b></td>
                            <td>Ali</td>
                            <td>0317-12345678</td>
                            <td>34101-1231568-1</td>
                            <td>GA-19-12017</td>
                            <td>
                                <img src="" alt="cnic front">
                            </td>
                            <td>
                                <img src="" alt="cnic back">
                            </td>
                            <td>
                                <img src="" alt="licsence front">
                            </td>
                            <td>
                                <img src="" alt="License Back">
                            </td>
                            <td><span class="badge bg-success">active</span></td>
                            <td><a href="#" class="action-icon"> <i class="mdi mdi-delete"></i></a></td>
                        </tr>
                        <tr>
                            <td><input type="checkbox"></td>
                            <td>20/12/2022</td>
                            <td><b><a href="#">University of Sargodha</a></b></td>
                            <td>Ali</td>
                            <td>0317-12345678</td>
                            <td>34101-1231568-1</td>
                            <td>GA-19-12017</td>
                            <td>
                                <img src="" alt="cnic front">
                            </td>
                            <td>
                                <img src="" alt="cnic back">
                            </td>
                            <td>
                                <img src="" alt="licsence front">
                            </td>
                            <td>
                                <img src="" alt="License Back">
                            </td>
                            <td><span class="badge bg-danger">disable</span></td>
                            <td><a href="#" class="action-icon"> <i class="mdi mdi-delete"></i></a></td>
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
                <h5 class="modal-title" id="staticBackdropLabel">Add Driver</h5>
                <button type="button" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-xl-12">
                    <div class="card shadow-none">
                        <div class="card-body">
                            <form>
                                <div class="row">
                                    <div class="mb-3">
                                        <label for="example-select" class="form-label">Organization Name</label>
                                        <select class="form-select" id="example-select">
                                            <option>School</option>
                                            <option>college</option>
                                            <option>University</option>
                                            <!-- <option>Org</option> -->
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="simpleinput" class="form-label">Name</label>
                                            <input type="text" id="simpleinput" class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label for="simpleinput" class="form-label">Cnic Number</label>
                                            <input type="number" id="simpleinput" class="form-control">
                                        </div>
                                        <div class="d-flex">
                                            <div class="col-6">
                                                <div class="mt-1 px-1">
                                                    <input type="file" data-plugins="dropify" data-default-file="/images/small/img-2.jpg" />
                                                    <p class="text-muted text-center mt-2 mb-0">Cnic Front</p>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="mt-1 px-1">
                                                    <input type="file" data-plugins="dropify" data-default-file="/images/small/img-2.jpg" />
                                                    <p class="text-muted text-center mt-2 mb-0">Cnic Back</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="simpleinput" class="form-label">Phone No</label>
                                            <input type="number" id="simpleinput" class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label for="simpleinput" class="form-label">License Number</label>
                                            <input type="number" id="simpleinput" class="form-control">
                                        </div>
                                        <div class="d-flex">
                                            <div class="col-6">
                                                <div class="mt-1 px-1">
                                                    <input type="file" data-plugins="dropify" data-default-file="/images/small/img-2.jpg" />
                                                    <p class="text-muted text-center mt-2 mb-0">License Front</p>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="mt-1 px-1">
                                                    <input type="file" data-plugins="dropify" data-default-file="/images/small/img-2.jpg" />
                                                    <p class="text-muted text-center mt-2 mb-0">License Back</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div> <!-- end col -->
                                </div> <!-- end #basicwizard-->
                                <div class="text-end mt-2">
                                    <button type="button" type="submit" class="btn btn-success waves-effect waves-light">Save</button>
                                </div>
                            </form>
                        </div> <!-- end card-body -->
                    </div> <!-- end card-->
                </div>
            </div>
            <!-- <div class="modal-footer">
                <button type="button" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" type="button" class="btn btn-primary">Submit</button>
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
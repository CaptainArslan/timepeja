@extends('layouts.master')
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
    <div class="col-lg-8 table-responsive">
        <div class="card">
            <div class="card-header">
                <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#staticBackdrop"> Add </button>
            </div>
            <div class="card-body">
                <h4 class="header-title">Drivers</h4>
                <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>Organization Name</th>
                            <th>Name</th>
                            <th>Phone No</th>
                            <th>CNIC Number</th>
                            <th>License Number</th>
                            <th>CNIC Front</th>
                            <th>CNIC Back</th>
                            <th>License Front</th>
                            <th>License Back</th>
                            <th>Action</th>
                        </tr>
                    </thead>


                    <tbody>
                        <tr>
                            <td><b><a href="#">Tiger Nixon</a></b></td>
                            <td>System Architect</td>
                            <td>Edinburgh</td>
                            <td>61</td>
                            <td>2011/04/25</td>
                            <td>$320,800</td>
                            <td>61</td>
                            <td>61</td>
                            <td>61</td>
                            <td><a href="#" class="action-icon"> <i class="mdi mdi-delete"></i></a></td>
                        </tr>
                    </tbody>
                </table>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start mb-3">
                    <img class="d-flex me-3 rounded-circle avatar-lg" src="/images/users/user-8.jpg" alt="Generic placeholder image">
                    <div class="w-100">
                        <h4 class="mt-0 mb-1">Jade M. Walker</h4>
                        <p class="text-muted">Branch manager</p>
                        <p class="text-muted"><i class="mdi mdi-office-building"></i> Vine Corporation</p>

                        <a href="javascript: void(0);" class="btn- btn-xs btn-info">Send Email</a>
                        <a href="javascript: void(0);" class="btn- btn-xs btn-secondary">Call</a>
                        <a href="javascript: void(0);" class="btn- btn-xs btn-secondary">Edit</a>
                    </div>
                </div>

                <h5 class="mb-3 mt-4 text-uppercase bg-light p-2"><i class="mdi mdi-account-circle me-1"></i> Personal Information</h5>
                <div class="">
                    <h4 class="font-13 text-muted text-uppercase">About Me :</h4>
                    <p class="mb-3">
                        Hi I'm Johnathn Deo,has been the industry's standard dummy text ever since the
                        1500s, when an unknown printer took a galley of type.
                    </p>

                    <h4 class="font-13 text-muted text-uppercase mb-1">Date of Birth :</h4>
                    <p class="mb-3"> March 23, 1984 (34 Years)</p>

                    <h4 class="font-13 text-muted text-uppercase mb-1">Company :</h4>
                    <p class="mb-3">Vine Corporation</p>

                    <h4 class="font-13 text-muted text-uppercase mb-1">Added :</h4>
                    <p class="mb-3"> April 22, 2016</p>

                    <h4 class="font-13 text-muted text-uppercase mb-1">Updated :</h4>
                    <p class="mb-0"> Dec 13, 2017</p>

                </div>
            </div>
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>
<!-- end row-->
<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="staticBackdropLabel">Add Driver</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                    <button type="submit" class="btn btn-success waves-effect waves-light">Save</button>
                                </div>
                            </form>
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
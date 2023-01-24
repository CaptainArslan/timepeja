@extends('layouts.app')
@section('title', 'Passengers')
<!-- start page title -->
@section('page_css')
@include('partials.datatable_css')
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">All Passengers</h4>
        </div>
    </div>
</div>
<!-- end page title -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex">
                <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#staticBackdrop"> Add </button>
                
            </div>
            <div class="card-body">
                <h4 class="header-title">Passengers</h4>
                <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>Organization Name</th>
                            <th>Passenger Type</th>
                            <th>Name</th>
                            <th>School Name</th>
                            <th>Roll No</th>
                            <th>Gender</th>
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
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title" id="myCenterModalLabel">Add Passenger</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body p-4">
                <form>
                    <div class="mb-3">
                        <label for="example-select" class="form-label">Organization Name</label>
                        <select class="form-select" id="example-select">
                            <option>School</option>
                            <option>college</option>
                            <option>University</option>
                            <!-- <option>Org</option> -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="example-select" class="form-label">Passenger Type</label>
                        <select class="form-select" id="example-select">
                            <option>Student</option>
                            <option>Guardian</option>
                            <option>Employee</option>
                            <!-- <option>Org</option> -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="example-select" class="form-label">For Manager Only</label>
                        <select class="form-select" id="example-select">
                            <option>John</option>
                            <option>Charles</option>
                            <option>Chips</option>
                            <!-- <option>Org</option> -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Name</label>
                        <input type="text" id="simpleinput" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">School Name</label>
                        <input type="text" id="simpleinput" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Roll No</label>
                        <input type="number" id="simpleinput" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="example-select" class="form-label">Gender</label>
                        <select class="form-select" id="example-select">
                            <option>Male</option>
                            <option>Female</option>
                            <!-- <option>Org</option> -->
                        </select>
                    </div>


                    <div class="text-end">
                        <button type="submit" class="btn btn-success waves-effect waves-light">Save</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endsection

@section('page_js')
@include('partials.datatable_js')
<!-- Plugins js-->
<script src="/libs/twitter-bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script>

<!-- Init js-->
<script src="/js/pages/form-wizard.init.js"></script>
<script>
    $(document).ready(function() {

    });
</script>
@endsection
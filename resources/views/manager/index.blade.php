@extends('layouts.master')
@section('title', 'Managers')
<!-- start page title -->
@section('page_css')
@include('partials.datatable_css')
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">All Managers</h4>
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
                <h4 class="header-title">Managers</h4>
                <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Office</th>
                            <th>Age</th>
                            <th>Start date</th>
                            <th>Salary</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Tiger Nixon</td>
                            <td>System Architect</td>
                            <td>Edinburgh</td>
                            <td>61</td>
                            <td>2011/04/25</td>
                            <td>$320,800</td>
                        </tr>
                        <tr>
                            <td>Garrett Winters</td>
                            <td>Accountant</td>
                            <td>Tokyo</td>
                            <td>63</td>
                            <td>2011/07/25</td>
                            <td>$170,750</td>
                        </tr>
                        <tr>
                            <td>Ashton Cox</td>
                            <td>Junior Technical Author</td>
                            <td>San Francisco</td>
                            <td>66</td>
                            <td>2009/01/12</td>
                            <td>$86,000</td>
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
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Add Manager</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-xl-12">
                    <div class="card shadow-none">
                        <div class="card-body">
                            <form>
                                <div id="basicwizard">
                                    <ul class="nav nav-pills bg-light nav-justified form-wizard-header mb-4">
                                        <li class="nav-item">
                                            <a href="#company" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                                <i class="fas fa-align-center"></i>
                                                <span class="d-none d-sm-inline">Organization Detail</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#company_head" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                                <i class="fas fa-user"></i>
                                                <span class="d-none d-sm-inline">Company Head</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#transport_manager" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                                <i class="fas fa-bus-alt"></i>
                                                <span class="d-none d-sm-inline">Transport Manager</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#financials" id="financials_tabs" value="financials" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                                <i class=" fas fa-money-bill-wave"></i>
                                                <span class="d-none d-sm-inline">Financial</span>
                                            </a>
                                        </li>
                                    </ul>

                                    <div class="tab-content b-0 mb-0 pt-0">

                                        <!-- Company detail -->
                                        <div class="tab-pane" id="company">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="simpleinput" class="form-label">Organization Name</label>
                                                        <input type="text" id="simpleinput" class="form-control">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="simpleinput" class="form-label">Branch code</label>
                                                        <input type="number" id="simpleinput" class="form-control">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="example-email" class="form-label">Email</label>
                                                        <input type="email" id="example-email" name="example-email" class="form-control" placeholder="Email">
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="simpleinput" class="form-label">Branch Name</label>
                                                        <input type="text" id="simpleinput" class="form-control">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="example-select" class="form-label">Types</label>
                                                        <select class="form-select" id="example-select">
                                                            <option>School</option>
                                                            <option>college</option>
                                                            <option>University</option>
                                                            <!-- <option>Org</option> -->
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="simpleinput" class="form-label">Phone No</label>
                                                        <input type="number" id="simpleinput" class="form-control">
                                                    </div>
                                                </div> <!-- end col -->
                                                <div class="mb-3">
                                                    <label for="example-textarea" class="form-label">Address</label>
                                                    <input class="form-control" id="example-textarea"></input>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Company head -->
                                        <div class="tab-pane" id="company_head">
                                            <div class="row">
                                                <div class="col-lg-6">

                                                    <div class="mb-3">
                                                        <label for="simpleinput" class="form-label">Head Name</label>
                                                        <input type="text" id="simpleinput" class="form-control">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="simpleinput" class="form-label">Phone No</label>
                                                        <input type="number" id="simpleinput" class="form-control">
                                                    </div>

                                                </div>

                                                <div class="col-lg-6">

                                                    <div class="mb-3">
                                                        <label for="example-email" class="form-label">Email</label>
                                                        <input type="email" id="example-email" name="example-email" class="form-control" placeholder="Email">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="example-textarea" class="form-label">Address</label>
                                                        <input class="form-control" id="example-textarea" rows="5"></input>
                                                    </div>

                                                </div> <!-- end col -->
                                            </div> <!-- end row -->
                                        </div>

                                        <!-- Company transport manager -->
                                        <div class="tab-pane" id="transport_manager">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="simpleinput" class="form-label">Name</label>
                                                        <input type="text" id="simpleinput" class="form-control">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="simpleinput" class="form-label">Phone No</label>
                                                        <input type="number" id="simpleinput" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="example-email" class="form-label">Email</label>
                                                        <input type="email" id="example-email" name="example-email" class="form-control" placeholder="Email">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="example-fileinput" class="form-label">Manager Picture</label>
                                                        <input type="file" id="example-fileinput" class="form-control">
                                                    </div>
                                                </div> <!-- end col -->
                                            </div> <!-- end row -->
                                        </div>

                                        <!-- Company financials -->
                                        <div class="tab-pane" id="financials">
                                            <div class="row">
                                                <div class="col-12">

                                                    <div class="mb-3">
                                                        <label for="fullname" class="form-label">Full Name * :</label>
                                                        <input type="text" class="form-control" name="fullname" id="fullname" required="">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="email" class="form-label">Email * :</label>
                                                        <input type="email" id="email" class="form-control" name="email" data-parsley-trigger="change" required="">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Gender * :</label>

                                                        <div class="form-check mb-1">
                                                            <input type="radio" name="gender" id="genderM" value="Male" required="" class="form-check-input">
                                                            <label for="genderM">Male</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="radio" name="gender" id="genderF" value="Female" class="form-check-input">
                                                            <label for="genderF">Female</label>
                                                        </div>
                                                    </div>
                                                </div> <!-- end col -->
                                            </div> <!-- end row -->

                                            <ul class="list-inline wizard mb-0" id="btns">
                                                <button type="button" class="btn btn-primary">Submit</button>
                                            </ul>
                                        </div>
                                    </div> <!-- tab-content -->
                                </div> <!-- end #basicwizard-->
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

<!-- Init js-->
<script src="/js/pages/form-wizard.init.js"></script>
<script>
    $(document).ready(function() {

    });
</script>
@endsection
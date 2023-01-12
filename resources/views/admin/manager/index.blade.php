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
                                                <span class="d-none d-sm-inline">Company Detail</span>
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
                                                    <form>

                                                        <div class="mb-3">
                                                            <label for="simpleinput" class="form-label">Text</label>
                                                            <input type="text" id="simpleinput" class="form-control">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="example-email" class="form-label">Email</label>
                                                            <input type="email" id="example-email" name="example-email" class="form-control" placeholder="Email">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="example-password" class="form-label">Password</label>
                                                            <input type="password" id="example-password" class="form-control" value="password">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="password" class="form-label">Show/Hide Password</label>
                                                            <div class="input-group input-group-merge">
                                                                <input type="password" id="password" class="form-control" placeholder="Enter your password">
                                                                <div class="input-group-text" data-password="false">
                                                                    <span class="password-eye"></span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="example-palaceholder" class="form-label">Placeholder</label>
                                                            <input type="text" id="example-palaceholder" class="form-control" placeholder="placeholder">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="example-textarea" class="form-label">Text area</label>
                                                            <textarea class="form-control" id="example-textarea" rows="5"></textarea>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="example-readonly" class="form-label">Readonly</label>
                                                            <input type="text" id="example-readonly" class="form-control" readonly="" value="Readonly value">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="example-disable" class="form-label">Disabled</label>
                                                            <input type="text" class="form-control" id="example-disable" disabled="" value="Disabled value">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="example-static" class="form-label">Static control</label>
                                                            <input type="text" readonly="" class="form-control-plaintext" id="example-static" value="email@example.com">
                                                        </div>

                                                        <div>
                                                            <label for="example-helping" class="form-label">Helping text</label>
                                                            <input type="text" id="example-helping" class="form-control" placeholder="Helping text">
                                                            <span class="help-block"><small>A block of help text that breaks onto a new line and may extend beyond one line.</small></span>
                                                        </div>

                                                    </form>
                                                </div> <!-- end col -->

                                                <div class="col-lg-6">
                                                    <form>
                                                        <div class="mb-3">
                                                            <label for="example-select" class="form-label">Input Select</label>
                                                            <select class="form-select" id="example-select">
                                                                <option>1</option>
                                                                <option>2</option>
                                                                <option>3</option>
                                                                <option>4</option>
                                                                <option>5</option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="example-multiselect" class="form-label">Multiple Select</label>
                                                            <select id="example-multiselect" multiple="" class="form-select">
                                                                <option>1</option>
                                                                <option>2</option>
                                                                <option>3</option>
                                                                <option>4</option>
                                                                <option>5</option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="example-fileinput" class="form-label">Default file input</label>
                                                            <input type="file" id="example-fileinput" class="form-control">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="example-date" class="form-label">Date</label>
                                                            <input class="form-control" id="example-date" type="date" name="date">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="example-month" class="form-label">Month</label>
                                                            <input class="form-control" id="example-month" type="month" name="month">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="example-time" class="form-label">Time</label>
                                                            <input class="form-control" id="example-time" type="time" name="time">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="example-week" class="form-label">Week</label>
                                                            <input class="form-control" id="example-week" type="week" name="week">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="example-number" class="form-label">Number</label>
                                                            <input class="form-control" id="example-number" type="number" name="number">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="example-color" class="form-label">Color</label>
                                                            <input class="form-control" id="example-color" type="color" name="color" value="#727cf5">
                                                        </div>

                                                        <div>
                                                            <label for="example-range" class="form-label">Range</label>
                                                            <input class="form-range" id="example-range" type="range" name="range" min="0" max="100">
                                                        </div>
                                                    </form>
                                                </div> <!-- end col -->
                                            </div>
                                        </div>

                                        <!-- Company head -->
                                        <div class="tab-pane" id="company_head">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="row mb-3">
                                                        <label class="col-md-3 col-form-label" for="userName">Head Name</label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" id="userName" name="userName" value="">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label class="col-md-3 col-form-label" for="password"> Phone</label>
                                                        <div class="col-md-9">
                                                            <input type="number" id="password" name="password" class="form-control" value="">
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <label class="col-md-3 col-form-label" for="confirm">Email</label>
                                                        <div class="col-md-9">
                                                            <input type="email" id="confirm" name="confirm" class="form-control" value="">
                                                        </div>
                                                    </div>
                                                </div> <!-- end col -->
                                            </div> <!-- end row -->
                                        </div>

                                        <!-- Company transport manager -->
                                        <div class="tab-pane" id="transport_manager">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="row mb-3">
                                                        <label class="col-md-3 col-form-label" for="name"> First name</label>
                                                        <div class="col-md-9">
                                                            <input type="text" id="name" name="name" class="form-control" value="Francis">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label class="col-md-3 col-form-label" for="surname"> Last name</label>
                                                        <div class="col-md-9">
                                                            <input type="text" id="surname" name="surname" class="form-control" value="Brinkman">
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <label class="col-md-3 col-form-label" for="email">Email</label>
                                                        <div class="col-md-9">
                                                            <input type="email" id="email" name="email" class="form-control" value="cory1979@hotmail.com">
                                                        </div>
                                                    </div>
                                                </div> <!-- end col -->
                                            </div> <!-- end row -->
                                        </div>

                                        <!-- Company financials -->
                                        <div class="tab-pane" id="financials">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="text-center">
                                                        <h2 class="mt-0"><i class="mdi mdi-check-all"></i></h2>
                                                        <h3 class="mt-0">Thank you !</h3>

                                                        <p class="w-75 mb-2 mx-auto">Quisque nec turpis at urna dictum luctus. Suspendisse convallis dignissim eros at volutpat. In egestas mattis dui. Aliquam
                                                            mattis dictum aliquet.</p>

                                                        <div class="mb-3">
                                                            <div class="form-check d-inline-block">
                                                                <input type="checkbox" class="form-check-input" id="customCheck1">
                                                                <label class="form-check-label" for="customCheck1">I agree with the Terms and Conditions</label>
                                                            </div>
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
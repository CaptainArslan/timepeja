@extends('layouts.app')
@section('title', 'All Drivers')
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
<!-- Start Content  -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- <h4 class="header-title">Select Organization</h4> -->
                <form action="">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="organization">Select Oganization</label>
                            <select class="form-control" data-toggle="select2" data-width="100%" id="organization">
                                <option>Select</option>
                                <option value="AK">123456 - branch - Punjab University</option>
                                <option value="HI">123456 - branch - Gujrant University</option>
                                <option value="CA">123456 - branch - Gift University</option>
                                <option value="NV">123456 - branch - Kips University</option>
                                <option value="OR">123456 - branch - Sialkot Univeristy</option>
                            </select>
                        </div> <!-- end col -->
                        <div class="col-md-3">
                            <label for="driver">Select Driver</label>
                            <select class="form-control" data-toggle="select2" data-width="100%" id="driver">
                                <option>Select</option>
                                <option value="AK">Arslan</option>
                                <option value="HI">Qasim</option>
                                <option value="CA">Romi</option>
                                <option value="NV">Ashtisham</option>
                                <option value="OR">Azam</option>
                            </select>
                        </div> <!-- end col -->
                        <!-- <div class="col-md-2">
                            <label for="status">Status</label>
                            <select class="form-control" data-toggle="select2" data-width="100%" id="status">
                                <option value="">Online</option>
                                <option value="">Offline</option>
                            </select>
                        </div>  -->
                        <div class="col-md-2">
                            <label for="from">From</label>
                            <input class="form-control" id="from" type="date" name="date">
                        </div> <!-- end col -->
                        <div class="col-md-2">
                            <label for="to">To</label>
                            <input class="form-control" id="to" type="date" name="date">
                        </div> <!-- end col -->
                        <div class="col-md-1">
                            <label for="publish_schedule">.</label>
                            <button type="button" class="btn btn-success" id="publish_schedule"> Submit </button>
                        </div> <!-- end col -->
                    </div> <!-- end row -->
                </form>
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>
<!-- end page title -->
<div class="row">
    <div class="col-12 table-responsive">
        <div class="card">
            <div class="card-header">
                <h4 class="header-title">Drivers List</h4>
                <!-- <button type="button" type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#staticBackdrop"> Add </button> -->
            </div>
            <div class="card-body">
                <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
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
                        @forelse($drivers as $driver)
                        <tr>
                            <td>{{ date("Y-m-d",strtotime($driver->created_at)) }}</td>
                            <td><b><a href="#">{{ $driver->organizations['name'] }}</a></b></td>
                            <td>{{ $driver->name }}</td>
                            <td>{{ $driver->phone }}</td>
                            <td>{{ $driver->cnic }}</td>
                            <td>{{ $driver->license_no }}</td>
                            <td>
                                <img src="{{ $driver->cnic_front_pic }}" alt="cnic front" height="50" width="50">
                            </td>
                            <td>
                                <img src="{{ $driver->cnic_back_pic }}" alt="cnic back" height="50" width="50">
                            </td>
                            <td>
                                <img src="{{ $driver->license_no_front_pic }}" alt="licsence front" height="50" width="50">
                            </td>
                            <td>
                                <img src="{{ $driver->license_no_back_pic }}" alt="License Back" height="50" width="50">
                            </td>
                            <td><span class="badge  @if($driver->status) bg-success @else bg-danger @endif ">
                                    @if($driver->status) Active @else Deactive @endif</span></td>
                            <td>
                                <div class="btn-group btn-group-sm" style="float: none;" data-id="{{ $driver->id }}">
                                    <button type="button" type="button" class="tabledit-edit-button btn btn-success" style="float: none;" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                        <span class="mdi mdi-pencil"></span>
                                    </button>
                                </div>

                                <div class="btn-group btn-group-sm" style="float: none;" data-id="{{ $driver->id }}">
                                    <button type="button" type="button" class="tabledit-edit-button btn btn-danger" style="float: none;">
                                        <span class="mdi mdi-delete"></span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        @endforelse
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
                <h5 class="modal-title" id="staticBackdropLabel">Update Driver</h5>
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
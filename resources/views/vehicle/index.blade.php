@extends('layouts.app')
@section('title', 'Add Vehicles')
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
            <h4 class="page-title">Add Vehicles</h4>
        </div>
    </div>
</div>
<!-- end page title -->
<div class="row">
    <div class="col-lg-12 table-responsive">
        <div class="card">
            <div class="card-header">
                <button type="button" type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#staticBackdrop"> Add </button>
            </div>
            <div class="card-body">
                <h4 class="header-title">Vehicles</h4>
                <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox">
                            </th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Organization Name</th>
                            <th>Vehicle Type</th>
                            <th>Vehicle's Number</th>
                            <th>Vehicle picture from front</th>
                            <th>Vehicle license plate picture</th>
                            <th>Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>


                    <tbody>
                        <tr>
                            <th>
                                <input type="checkbox">
                            </th>
                            <th></th>
                            <th></th>
                            <td><b><a href="#">Tiger Nixon</a></b></td>
                            <td>Bus</td>
                            <td>GAO-123</td>
                            <td></td>
                            <td></td>
                            <td>2023/01/19</td>
                            <td>
                                <div class="btn-group btn-group-sm" style="float: none;"><button type="button" class="tabledit-edit-button btn btn-success" style="float: none;" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><span class="mdi mdi-pencil"></span></button></div>
                                <div class="btn-group btn-group-sm" style="float: none;"><button type="button" class="tabledit-edit-button btn btn-danger" style="float: none;"><span class="mdi mdi-delete"></span></button></div>
                            </td>
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
                <button type="button" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('vehicles.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="col-xl-12">
                        <div class="card shadow-none">
                            <div class="card-body">
                                <div class="row">
                                    <div class="mb-3">
                                        <label for="organization" class="form-label">Organization Name</label>
                                        <select class="form-select" id="organization" name="org_id">
                                            <option value="">Select Organization</option>
                                            @forelse ($organizations as $organization)
                                            <option value="{{$organization->id}}"> {{ucfirst($organization->name)}} </option>
                                            @empty
                                            <option>Organization not Available</option>
                                            @endforelse
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="veh_type" class="form-label">Vehicle Type</label>
                                            <select class="form-select" id="veh_type" name="veh_id">
                                                <option value="">Please Select Vehicle Type</option>
                                                @forelse ($vehicle_types as $vehicle_type)
                                                <option value="{{$vehicle_type->id}}"> {{ucfirst($vehicle_type->name)}} </option>
                                                @empty
                                                <option>Vehicle Type not Available</option>
                                                @endforelse
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <div class="mt-1">
                                                <input type="file" data-plugins="dropify" name="veh_front_pic" data-default-file="/images/small/img-2.jpg" />
                                                <p class="text-muted text-center mt-2 mb-0">Vehicle Picture from front</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="simpleinput" class="form-label">Vehicle's Number</label>
                                            <input type="text" id="simpleinput" name="veh_no" class="form-control">
                                        </div>
                                        <div class="col-12">
                                            <div class="mt-1">
                                                <input type="file" data-plugins="dropify" name="veh_back_pic" data-default-file="/images/small/img-2.jpg" />
                                                <p class="text-muted text-center mt-2 mb-0">Vehicle license plate picture</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end card-body -->
                            <div class="text-end">
                                <button type="button" type="submit" class="btn btn-success">Save</button>
                            </div>
                        </div> <!-- end card-->
                    </div>
                </form>
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
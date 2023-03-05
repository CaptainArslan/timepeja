@extends('layouts.app')
@section('title', 'Add Route')
<!-- start page title -->
@section('page_css')
@include('partials.datatable_css')
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Add Route</h4>
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
                <h4 class="header-title">Latest Routes</h4>
                <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Organization Name</th>
                            <th>Route Name</th>
                            <th>Route No</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Action</th>
                        </tr>
                    </thead>


                    <tbody>
                        @forelse ($routes as $route)
                        <tr>
                            <td>{{ date("Y-m-d",strtotime($route->created_at)) }}</td>
                            <td>{{ date("H:m:s",strtotime($route->created_at)) }}</td>
                            <td><b><a href="#">{{ $route->organizations['name'] }}</a></b></td>
                            <td>{{ $route->name }}</td>
                            <td>{{ $route->number  }}</td>
                            <td>{{ $route->to }}</td>
                            <td>{{ $route->from }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" style="float: none;"><button type="button" class="tabledit-edit-button btn btn-success" style="float: none;" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><span class="mdi mdi-pencil"></span></button></div>
                                <div class="btn-group btn-group-sm" style="float: none;"><button type="button" class="tabledit-edit-button btn btn-danger" style="float: none;"><span class="mdi mdi-delete"></span></button></div>
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
    <div class="modal-dialog modal-lg  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title" id="myCenterModalLabel">Add Route</h4>
                <button type="button" type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body p-4">
                <form action="" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="org_name" class="form-label">Organization Name</label>
                        <select class="form-select" id="org_name" name="org_name">
                            <option value="">Select</option>
                            @forelse ($organizations as $organization)
                            <option value="{{ $organization->id }}">{{ $organization->branch_code }} - {{ $organization->name }} - {{ $organization->branch_name }}</option>
                            @empty
                            <option value="">Please select</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="route_no" class="form-label">Route No</label>
                        <input type="number" id="route_no" name="route_no" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="to" class="form-label">To</label>
                        <input type="text" id="to" name="to" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="from" class="form-label">Select from Map</label>
                        <div id="gmaps-basic" class="gmaps"></div>
                    </div>
                    <div class="mb-3">
                        <label for="from" class="form-label">From</label>
                        <input type="text" id="from" name="from" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="from" class="form-label">From Map</label>
                        <div id="gmaps-basic" class="gmaps"></div>
                    </div>
                    <div class="mb-3">
                        <label for="route_name" class="form-label">Route Name</label>
                        <input type="text" id="route_name" name="route_name" class="form-control" readonly>
                    </div>
                    <div class="text-end">
                        <button type="button" type="submit" class="btn btn-success waves-effect waves-light">Save</button>
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

<!-- google maps api -->
<script src="https://maps.google.com/maps/api/js?key=AIzaSyDsucrEdmswqYrw0f6ej3bf4M4suDeRgNA"></script>

<!-- gmap js-->
<script src="{{asset('libs/gmaps/gmaps.min.js')}}"></script>

<!-- Init js-->
<script src="{{asset('js/pages/google-maps.init.js')}}"></script>

<!-- Dashboar 1 init js-->
<script src="{{asset('js/pages/dashboard-1.init.js')}}"></script>

<script>
    $(document).ready(function() {
        $('#route_no, #to,  #from').change(function(e) {
            e.preventDefault();
            var routeno = $('#route_no').val();
            var to = $('#to').val();
            var from = $('#from').val();
            $('#route_name').val(routeno + '  ' + to + '  ' + from);
        });

    });
</script>
@endsection
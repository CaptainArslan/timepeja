@extends('layouts.app')
@section('title', 'Passengers')
<!-- start page title -->
@section('page_css')
<style>
    .imagefit {
        width: 50px;
        height: 50px;
        object-fit: contain;
    }
</style>
@include('partials.datatable_css')
<!-- Plugins css -->
<link href="/libs/dropzone/min/dropzone.min.css" rel="stylesheet" type="text/css" />
<link href="/libs/dropify/css/dropify.min.css" rel="stylesheet" type="text/css" />
<link href="{{ asset('libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex justify-content-between">
            <h4 class="page-title">Passengers</h4>
            <div class="page-title">
                <button type="button" type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#passengerModal">
                    Add Passengers </button>
            </div>
        </div>
    </div>
</div>

<!-- end page title -->
<!-- Filters -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- <h4 class="header-title">Select Organization</h4> -->
                <form action="{{ route('routes.index') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <label for="date-1">From</label>
                            <input class="form-control" type="date" name="from" value="{{ request()->input('from', old('from')) }}">
                        </div>
                        <div class="col-md-4">
                            <label for="date">To</label>
                            <input class="form-control" type="date" name="to" value="{{ request()->input('to', old('to')) }}">
                        </div>
                        <div class="col-md-1">
                            <label for="route_list"></label>
                            <button type="submit" class="btn btn-success" name="filter" id="route_list"> Submit </button>
                        </div>
                    </div> <!-- end row -->
                </form>
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>

<!-- end page title -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <!-- <button type="button" type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#passengerModal"> Add </button> -->
                <h3>Latest Passenger</h3>
            </div>
            <div class="card-body">
                <!-- <h4 class="header-title">Latest Passenger</h4> -->
                <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>otp</th>
                            <th>image</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($passengers as $passenger)
                        <tr>
                            <td>{{ $passenger->name }}</td>
                            <td>{{ $passenger->phone }}</td>
                            <td>@if ($passenger->email)
                                {{ $passenger->email }}
                                @else
                                N/A
                                @endif
                            </td>
                            <td>{{ $passenger->otp }}</td>
                            <td><img src="{{ $passenger->image }}" alt="{{ $passenger->name }}" class="imagefit"></td>
                            <td>

                                <input type="hidden" class="db_name" value="{{ $passenger->name }}">
                                <input type="hidden" class="db_email" value=" {{ $passenger->email }}">
                                <input type="hidden" class="db_phone" value="{{ $passenger->phone }}">
                                <input type="hidden" class="db_image" value="{{ $passenger->image }}">

                                <div class="btn-group btn-group-sm" style="float: none;" data-id="{{ $passenger->id }}" onclick="fillEditForm(this)">
                                    <button type="button" class="tabledit-edit-button btn btn-success" style="float: none;" data-bs-toggle="modal" data-bs-target="#editPassengerModal">
                                        <span class="mdi mdi-pencil"></span>
                                    </button>
                                </div>
                                <div class="btn-group btn-group-sm" style="float: none;">
                                    <button type="button" class="tabledit-edit-button btn btn-danger" style="float: none;" data-id="{{ $passenger->id }}" onclick="deletePassenger(this)">
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

<!-- Create Modal -->
<div class="modal fade " id="passengerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="passengerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title" id="myCenterModalLabel">Add Passenger</h4>
                <button type="button" type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class=" modal-body p-4">
                <form action="{{ route('passenger.store') }}" method="post" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <div id="basicwizard">
                        <div class="tab-content b-0 mb-0 pt-0">
                            <div class="tab-pane active" id="basictab2">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="user_type" class="form-label">Name</label>
                                            <input type="text" class="form-control" name="name" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="further_user_type" class="form-label">Phone</label>
                                            <input type="text" class="form-control" name="phone" data-toggle="input-mask" data-mask-format="0000-0000000" maxlength="12" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="simpleinput" class="form-label ">Email</label>
                                            <input type="email" class="form-control" name="email">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="simpleinput" class="form-label">Password </label>
                                            <input type="password" class="form-control" name="password" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="mt-1 px-1">
                                            <input type="file" accept="image/*" data-plugins="dropify" data-default-file="" name="image" id="image" data-allowed-file-extensions='png jpg jpeg' />
                                            <p class="text-muted text-center mt-2 mb-0">Passenger Profile Image</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end mt-2">
                                    <button type="submit" class="btn btn-success waves-effect waves-light">Submit</button>
                                </div> <!-- end row -->
                            </div>

                        </div> <!-- tab-content -->
                    </div> <!-- end #basicwizard-->
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- Create modal -->


<!-- Create Modal -->
<div class="modal fade " id="editPassengerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="passengerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title" id="myCenterModalLabel">Edit Passenger</h4>
                <button type="button" type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class=" modal-body p-4">
                <form action="#" method="post" enctype="multipart/form-data" autocomplete="off" id="editPassenger">
                    @csrf
                    @method('PUT')
                    <div id="basicwizard">
                        <div class="tab-content b-0 mb-0 pt-0">
                            <div class="tab-pane active" id="basictab2">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="user_type" class="form-label">Name</label>
                                            <input type="text" class="form-control" name="name" id="edit_name" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="further_user_type" class="form-label">Phone</label>
                                            <input type="text" class="form-control" name="phone" data-toggle="input-mask" data-mask-format="0000-0000000" maxlength="12" id="edit_phone" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="simpleinput" class="form-label ">Email</label>
                                            <input type="email" class="form-control" name="email" id="edit_email">
                                        </div>
                                    </div>
                                    <!-- <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="simpleinput" class="form-label">Password </label>
                                            <input type="password" class="form-control" name="password" required>
                                        </div>
                                    </div> -->
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="mt-1 px-1">
                                            <input type="file" accept="image/*" data-plugins="dropify" data-default-file="" name="image" id="edit_image" data-allowed-file-extensions='png jpg jpeg' />
                                            <p class="text-muted text-center mt-2 mb-0">Passenger Profile Image</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end mt-2">
                                    <button type="submit" class="btn btn-success waves-effect waves-light">Submit</button>
                                </div> <!-- end row -->
                            </div>

                        </div> <!-- tab-content -->
                    </div> <!-- end #basicwizard-->
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- Create modal -->
@endsection
@section('page_js')
@include('partials.datatable_js')
<script src="{{ asset('/libs/select2/js/select2.min.js') }}"></script>

<script src="/libs/dropzone/min/dropzone.min.js"></script>
<script src="/libs/dropify/js/dropify.min.js"></script>

<!-- Init js-->
<script src="/js/pages/form-fileuploads.init.js"></script>
<!-- <script src="{{asset('/js/passenger.js')}}"></script> -->

<script>
    function fillEditForm(param) {
        let _this = $(param).parents('tr');
        let id = $(param).data('id');
        $('#editPassenger').attr('action', 'passenger/' + id);
        console.log(_this.find('.db_cnic_front_pic').val());
        $('#edit_name').val(_this.find('.db_name').val());
        $('#edit_phone').val(_this.find('.db_phone').val());
        $('#edit_email').val(_this.find('.db_email').val());

        resetPreviewDropify(_this.find('.db_image').val(), "#edit_image");

    }

    function deletePassenger(param) {
        Swal.fire({
            title: 'Are you sure?',
            icon: 'warning',
            confirmButtonColor: '#e64942',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: `No`,
        }).then((result) => {
            if (result.isConfirmed) {
                let id = $(param).data('id');
                $.ajax({
                    type: "DELETE",
                    url: "/passenger/delete/" + id,
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            $(param).parent().find('.child').css('background', 'tomato');
                            $(param).parent().find('.dt-hasChild').css('background', 'tomato');
                            $(param).closest('tr').fadeOut(800, function() {
                                $(this).parent().find('.dt-hasChild').remove();
                                $(this).parent().find('.child').remove();
                            });
                        } else {
                            alert('error Occured while deleting')
                        }
                    },
                    error: (error) => {
                        console.log(JSON.stringify(error));
                    }
                });
            }
        });
    }
</script>

@endsection
@extends('layouts.app')
@section('title', 'Profile')
<!-- start page title -->
@section('page_css')
<link href="/libs/dropzone/min/dropzone.min.css" rel="stylesheet" type="text/css" />
<link href="/libs/dropify/css/dropify.min.css" rel="stylesheet" type="text/css" />
<link href="{{ asset('libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />
@endsection
@section('content')
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">UBold</a></li>
                        <li class="breadcrumb-item active">Profile</li>
                    </ol>
                </div>
                <h4 class="page-title"> {{ucfirst(auth()->user()->user_name)}} Profile</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-lg-4 col-xl-4">
            <div class="card text-center">
                <div class="card-body">
                    <img src="
                    @if (auth()->user()->image)
                    {{ asset('uploads/managers/profiles/'.auth()->user()->image)}}
                    @else
                    {{ asset('uploads/managers/profiles/placeholder.jpg')}}
                    @endif
                    " class="rounded-circle avatar-lg img-thumbnail" style="object-fit: contain;" alt="profile-image">

                    <h4 class="mb-0">{{auth()->user()->user_name}}</h4>
                    <p class="text-muted">{{auth()->user()->email}}</p>

                    <div class="text-start mt-3">
                        <p class="text-muted mb-2 font-13"><strong>Full Name :</strong> <span class="ms-2">{{auth()->user()->full_name}}</span></p>
                        <p class="text-muted mb-2 font-13"><strong>Mobile :</strong><span class="ms-2">{{auth()->user()->phone}}</span></p>
                        <p class="text-muted mb-2 font-13"><strong>Email :</strong> <span class="ms-2">{{auth()->user()->email}}</span></p>
                    </div>
                </div>
            </div> <!-- end card -->
        </div> <!-- end col-->

        <div class="col-lg-8 col-xl-8">
            <div class="card">
                <div class="card-body">
                    <div class="tab-content">
                        <!-- class="tab-pane" -->
                        <div id="settings">
                            <form action="{{route('profile.update', auth()->user()->id)}}" method="POST" enctype="multipart/form-data">
                                @method('PUT')
                                @csrf
                                <h5 class="mb-4 text-uppercase"><i class="mdi mdi-account-circle me-1"></i> Personal Info</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="fullname" class="form-label">Full Name</label>
                                            <input type="text" class="form-control" id="fullname" name="full_name" value="{{auth()->user()->full_name}}" placeholder="Enter first name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Phone No</label>
                                            <input type="text" data-toggle="input-mask" data-mask-format="0000-0000000" class="form-control" id="phone" name="phone" value="{{auth()->user()->phone}}" placeholder="Enter Phone No">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="useremail" class="form-label">Email Address</label>
                                            <input type="email" class="form-control" id="useremail" name="email" value="{{auth()->user()->email}}" placeholder="Enter email">
                                            <span class="form-text text-muted"><small>If you want to change email please </small></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="userpassword" class="form-label">Current Password</label>
                                            <input type="password" class="form-control" id="old_password" name="old_password" placeholder="Enter Current password" autocomplete="off">
                                            <span class="form-text text-muted"><small>If you want to change password please </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="useremail" class="form-label">New Password</label>
                                            <input type="password" class="form-control" id="password" name="password" value="" placeholder="Enter New Password" autocomplete="off">
                                            <span class="form-text text-muted"><small>If you want to change email please </small></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="userpassword" class="form-label">Confirm Password</label>
                                            <input type="password" class="form-control" id="userpassword" name="password_confirmation" placeholder="Confirm password" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="useremail" class="form-label">Profile Image</label>
                                            <input type="file" accept="image/*" data-plugins="dropify" data-default-file="" name="profile_image" id="profile_image" data-allowed-file-extensions='png jpg jpeg' />
                                            <span class="form-text text-muted"><small>If you want to change your profile image please <a href="javascript: void(0);">click</a> here.</small></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-success waves-effect waves-light mt-2"><i class="mdi mdi-content-save"></i> Update</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
<!-- end page title -->



@endsection
@section('page_js')

<script src="{{ asset('/libs/dropzone/min/dropzone.min.js') }}"></script>
<script src="{{ asset('/libs/dropify/js/dropify.min.js') }}"></script>

<!-- Init js-->
<script src="{{ asset('/js/pages/form-fileuploads.init.js') }}"></script>

<script>
    $(document).ready(function() {
        let image = "";
        if ("{{ asset('uploads/managers/profiles/'.auth()->user()->image) }}") {
            image = "{{ asset('uploads/managers/profiles/'.auth()->user()->image) }}";
        }
        resetPreviewDropify(image, "#profile_image");
    })
</script>

@endsection
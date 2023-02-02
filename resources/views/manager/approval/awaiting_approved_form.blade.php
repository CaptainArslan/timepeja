@extends('layouts.app')
@section('title', 'Awaiting User')
<!-- start page title -->
@section('page_css')
@endsection
<!-- end page title -->
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">All Awaiting User</h4>
        </div>
    </div>
</div>
<div class="col-12">
    <div class="accordion custom-accordion" id="custom-accordion-one">
        <div class="card mb-0">
            <div class="card-header" id="headingNine">
                <h5 class="m-0 position-relative">
                    <a class="custom-accordion-title text-reset d-block" data-bs-toggle="collapse" href="#collapseNine" aria-expanded="true" aria-controls="collapseNine">
                        User Information<i class="mdi mdi-chevron-down accordion-arrow"></i>
                    </a>
                </h5>
            </div>

            <div id="collapseNine" class="collapse show" aria-labelledby="headingFour" data-bs-parent="#custom-accordion-one">
                <div class="col-lg-12">
                    <div class="card bg-pattern shadow-none">
                        <div class="card-body">
                            <div class="text-center">
                                <img src="/images/companies/cisco.png" alt="logo" class="avatar-xl rounded-circle mb-3">
                            </div>
                            <div class="row text-center text-muted">
                                <div class="col-6">
                                    <h4>ALi</h4>
                                </div>
                                <div class="col-6">
                                    <p class="font-14">Unique Code:
                                        <b>
                                            <a class=""> Bcsf17r23</a>
                                        </b>
                                    </p>
                                </div>
                            </div>
                            <div class="row text-center text-muted">
                                <div class="col-6">
                                    <p class="font-14 ">
                                        Home Address: 123 Address example
                                    </p>
                                </div>
                                <div class="col-6">
                                    <p class="font-14">
                                        Pickup Address: 123 Address example
                                    </p>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="card shadow-none">
                                    <div class="card-body">
                                        <h4 class="header-title mb-3">Pickup Address</h4>
                                        <div id="gmaps-basic" class="gmaps"></div>
                                    </div>
                                </div> <!-- end card-->
                            </div>
                            <div class="row text-center text-muted">
                                <div class="col-4">
                                    <p class="font-14 ">
                                        Organization Name: University of Lahore
                                    </p>
                                </div>
                                <div class="col-4">
                                    <p class="font-14">
                                        Qualification Level: Middle
                                    </p>
                                </div>
                                <div class="col-4">
                                    <p class="font-14 ">
                                        Class / Depart : 9th / CS
                                    </p>
                                </div>
                                <div class="col-4">
                                    <p class="font-14 ">
                                        Roll / Emp id : stu-123 / emp-123
                                    </p>
                                </div>
                                <div class="col-4">
                                    <p class="font-14">
                                        Batch Year : 2017
                                    </p>
                                </div>
                                <div class="col-4">
                                    <p class="font-14 ">
                                        Degree duration: 4 year
                                    </p>
                                </div>
                                <div class="col-4">
                                    <p class="font-14">
                                        Section :
                                    </p>
                                </div>
                                <div class="col-4">
                                    <p class="font-14 ">
                                        Deaprtment :
                                    </p>
                                </div>
                            </div>
                            <!-- Approval Buttons -->
                            <div class="row mt-4 text-center">
                                <div class="col-4">
                                    <button type="button " class="btn btn-success">Approve</button>
                                </div>
                                <div class="col-4">
                                    <button type="button " class="btn btn-warning">Meet Personally</button>
                                </div>
                                <div class="col-4">
                                    <button type="button " class="btn btn-danger">Disapprove</button>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end card -->
                </div>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header" id="headingFive">
                <h5 class="m-0 position-relative">
                    <a class="custom-accordion-title text-reset collapsed d-block" data-bs-toggle="collapse" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                        Guardian 1 Asad (Relation) <i class="mdi mdi-chevron-down accordion-arrow"></i>
                    </a>
                </h5>
            </div>
            <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-bs-parent="#custom-accordion-one">
                <div class="col-lg-12">
                    <div class="card bg-pattern shadow-none">
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6">
                                    <p class="font-14">Home Address: example address</p>
                                    <p class="font-14">CNIC: 34101-1116185-1</p>
                                </div>
                                <div class="col-6">
                                    <h4>Selfie</h4>
                                    <div class="">
                                        <img src="/images/companies/cisco.png" alt="logo" class="avatar-xl rounded-circle mb-3">
                                    </div>
                                </div>
                            </div>
                            <div class="row text-center">
                                <div class="col-6">
                                    <h4>Cnic First</h4>
                                    <div class="">
                                        <img src="/images/companies/cisco.png" alt="logo" class="avatar-xl rounded-circle mb-3">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h4>Cnic Back</h4>
                                    <div class="">
                                        <img src="/images/companies/cisco.png" alt="logo" class="avatar-xl rounded-circle mb-3">
                                    </div>
                                </div>
                            </div>
                            <!-- Approval Buttons -->
                            <div class="row mt-4 text-center">
                                <div class="col-4">
                                    <button type="button " class="btn btn-success">Approve</button>
                                </div>
                                <div class="col-4">
                                    <button type="button " class="btn btn-warning">Meet Personally</button>
                                </div>
                                <div class="col-4">
                                    <button type="button " class="btn btn-danger">Disapprove</button>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end card -->
                </div>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header" id="headingSix">
                <h5 class="m-0 position-relative">
                    <a class="custom-accordion-title text-reset collapsed d-block" data-bs-toggle="collapse" href="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                        Guardian 2 Ali (Relation) <i class="mdi mdi-chevron-down accordion-arrow"></i>
                    </a>
                </h5>
            </div>
            <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-bs-parent="#custom-accordion-one">
                <div class="col-lg-12">
                    <div class="card bg-pattern shadow-none">
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6">
                                    <p class="font-14">Home Address: example address</p>
                                    <p class="font-14">CNIC: 34101-1116185-1</p>
                                </div>
                                <div class="col-6">
                                    <h4>selfie</h4>
                                    <div class="">
                                        <img src="/images/companies/cisco.png" alt="logo" class="avatar-xl rounded-circle mb-3">
                                    </div>
                                </div>
                            </div>
                            <div class="row text-center">
                                <div class="col-6">
                                    <h4>Cnic First</h4>
                                    <div class="">
                                        <img src="/images/companies/cisco.png" alt="logo" class="avatar-xl rounded-circle mb-3">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h4>Cnic Back</h4>
                                    <div class="">
                                        <img src="/images/companies/cisco.png" alt="logo" class="avatar-xl rounded-circle mb-3">
                                    </div>
                                </div>
                            </div>
                            <!-- Approval Buttons -->
                            <div class="row mt-4 text-center">
                                <div class="col-4">
                                    <button type="button " class="btn btn-success">Approve</button>
                                </div>
                                <div class="col-4">
                                    <button type="button " class="btn btn-warning">Meet Personally</button>
                                </div>
                                <div class="col-4">
                                    <button type="button " class="btn btn-danger">Disapprove</button>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end card -->
                </div>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header" id="headingSeven">
                <h5 class="m-0 position-relative">
                    <a class="custom-accordion-title text-reset collapsed d-block" data-bs-toggle="collapse" href="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                        Guardian 3 Afzal (Relation) <i class="mdi mdi-chevron-down accordion-arrow"></i>
                    </a>
                </h5>
            </div>
            <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-bs-parent="#custom-accordion-one">
                <div class="col-lg-12">
                    <div class="card bg-pattern shadow-none">
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6">
                                    <p class="font-14">Home Address: example address</p>
                                    <p class="font-14">CNIC: 34101-1116185-1</p>
                                </div>
                                <div class="col-6">
                                    <h4>Selfie</h4>
                                    <div class="">
                                        <img src="/images/companies/cisco.png" alt="logo" class="avatar-xl rounded-circle mb-3">
                                    </div>
                                </div>
                            </div>
                            <div class="row text-center">
                                <div class="col-6">
                                    <h4>Cnic First</h4>
                                    <div class="">
                                        <img src="/images/companies/cisco.png" alt="logo" class="avatar-xl rounded-circle mb-3">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h4>Cnic Back</h4>
                                    <div class="">
                                        <img src="/images/companies/cisco.png" alt="logo" class="avatar-xl rounded-circle mb-3">
                                    </div>
                                </div>
                            </div>
                            <!-- Approval Buttons -->
                            <div class="row mt-4 text-center">
                                <div class="col-4">
                                    <button type="button " class="btn btn-success">Approve</button>
                                </div>
                                <div class="col-4">
                                    <button type="button " class="btn btn-warning">Meet Personally</button>
                                </div>
                                <div class="col-4">
                                    <button type="button " class="btn btn-danger">Disapprove</button>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end card -->
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('page_js')
<!-- google maps api -->
<script src="https://maps.google.com/maps/api/js?key=AIzaSyDsucrEdmswqYrw0f6ej3bf4M4suDeRgNA"></script>

<!-- gmap js-->
<script src="{{asset('libs/gmaps/gmaps.min.js')}}"></script>

<!-- Init js-->
<script src="{{asset('js/pages/google-maps.init.js')}}"></script>
<script>
    
</script>

@endsection


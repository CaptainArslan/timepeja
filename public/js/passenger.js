
$(document).ready(function () {
    $('#user_type').change(function (e) {
        e.preventDefault();
        $('#further_user_type').empty();
        var options = '';
        var user_type = $(this).val();
        options = get_user_further_type_option(user_type);
        $('#further_user_type').append(options);
        set_further_user_type_dropdown();
    });
    $('#further_user_type').change(function (e) {
        e.preventDefault();
        check_user_type()
    });
});

function check_user_type() {
    var html = '';
    var type = $('#further_user_type').val();
    if (type == 'primary_level' || type == 'middle_level' || type == 'high_level') {
        html = student_school();
        empty_form();
        $('#student_school_form_container').append(html);
    } else if (type == 'college') {
        html = student_college();
        empty_form();
        $('#student_college_form_container').append(html);
    } else if (type == 'university') {
        html = student_university();
        empty_form();
        $('#student_university_form_container').append(html);
    } else if(type == 'student_guardian') {
        html = guardian_student_form_school();
        empty_form();
        $('#guradian_student_school_form_container').append(html);
    } else if(type == 'employee_guardian') {
        html = employee_form();
        empty_form();
        $('#employee_guradian_form_container').append(html)
    } else {
        empty_form();
    }
}

check_user_type()

function set_further_user_type_dropdown() {
    var type = $('#user_type').val();
    if (type == 'student' || type == 'student_guardian') {
        $('#further_user_type').prop('disabled', false);
    } else {
        $('#further_user_type').prop('disabled', true);
    }
}

set_further_user_type_dropdown();

function get_user_further_type_option(param) {
    var option = '';
    if (param == 'student' || param == 'student_guardian') {
        option = `
                <option value="">Select</option>
                <option value="primary_level">Primary level (Play groun to 5th)</option>
                <option value="middle_level">Middle Level (6th to 8th) </option>
                <option value="high_level">Higher school (9th to 10th) </option>
                <option value="college">Intermediate / college (11th / 12th)</option>
                <option value="university">Bachelors / university</option>
                <option value="university">Master / university</option>
            `;
    } else {
        option = `<option value="">Select</option>`;
    }
    return option;
}

function empty_form() {
    $('#student_school_form_container').empty();
    $('#student_college_form_container').empty();
    $('#student_university_form_container').empty();
}

function student_school() {
    return `
        <form id="student_school_form">
            <div class="row">
                <div class="col-lg-4">
                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Roll No</label>
                        <input type="text" class="form-control">
                    </div>
                </div> 
                <div class="col-lg-4">
                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Class</label>
                        <input type="text" class="form-control">
                    </div>
                </div> 
                <div class="col-lg-4">
                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Section</label>
                        <input type="text" class="form-control">
                    </div>
                </div> 
                <div class="col-lg-4">
                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Student Card</label>
                        <input type="file" class="form-control">
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="mb-3">
                        <label for="date-1">Transport Start Date</label>
                        <input class="form-control transport-start-date" type="date" name="transport-start-date-school" value="{{ now()->format('Y-m-d') }}">
                    </div>    
                </div>
                <div class="col-lg-4">
                    <div class="mb-3">
                        <label for="date">Transport End Date</label>
                        <input class="form-control transport-end-date-university" type="date" name="transport-end-date-school" value="{{ now()->format('Y-m-d') }}">
                    </div>
                </div>
            </div>
        </form>`;
}

function student_college() {
    return `<form id="student_college_form">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Roll No</label>
                                <input type="text" class="form-control">
                            </div>
                        </div> 
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Qualification Level</label>
                                <input type="text" class="form-control">
                            </div>
                        </div> 
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Class</label>
                                <input type="text" class="form-control">
                            </div>
                        </div> 
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Section</label>
                                <input type="text" class="form-control">
                            </div>
                        </div> 
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Batch Year</label>
                                <input type="text" class="form-control">
                            </div>
                        </div> 
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Degree / Course Duration</label>
                                <input type="text" class="form-control">
                            </div>
                        </div> 
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Student Card</label>
                                <input type="file" class="form-control">
                            </div>
                        </div> 
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="date-1">Transport Start Date</label>
                                <input class="form-control transport-start-date-university" type="date" name="transport-start-date-university" value="{{ now()->format('Y-m-d') }}">
                            </div>    
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="date-2">Transport End Date</label>
                                <input class="form-control transport-end-date-university" type="date" name="transport-end-date-university" value="{{ now()->format('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                </form>`;
}

function student_university() {
    return ` <form id="student_university_form">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Roll No</label>
                                <input type="text" class="form-control">
                            </div>
                        </div> 
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Qualification Level</label>
                                <input type="text" class="form-control">
                            </div>
                        </div> 
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Department</label>
                                <input type="text" class="form-control">
                            </div>
                        </div> 
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Class</label>
                                <input type="text" class="form-control">
                            </div>
                        </div> 
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Class Timing</label>
                                <input type="text" class="form-control">
                            </div>
                        </div> 
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Batch Year</label>
                                <input type="text" class="form-control">
                            </div>
                        </div> 
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Degree Duration</label>
                                <input type="text" class="form-control">
                            </div>
                        </div> 
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Student Card</label>
                                <input type="file" class="form-control">
                            </div>
                        </div> 
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="date-1">Transport Start Date</label>
                                <input class="form-control transport-start-date-university" type="date" name="transport-start-date-university" value="{{ now()->format('Y-m-d') }}">
                            </div>    
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="date">Transport End Date</label>
                                <input class="form-control transport-end-date-university" type="date" name="transport-end-date-university" value="{{ now()->format('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                </form>`;
}

function employee_form() {
    return ` <form>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Employee ID</label>
                                <input type="text" class="form-control">
                            </div>
                        </div> 
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Department</label>
                                <input type="text" class="form-control">
                            </div>
                        </div> 
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Designation / Postion / Post</label>
                                <input type="text" class="form-control">
                            </div>
                        </div> 
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Employee Card</label>
                                <input type="file" class="form-control">
                            </div>
                        </div> 
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="date-1">Transport Start Date</label>
                                <input class="form-control transport-start-date-university" type="date" name="transport-start-date-university" value="{{ now()->format('Y-m-d') }}">
                            </div>    
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="date">Transport End Date</label>
                                <input class="form-control transport-end-date-university" type="date" name="transport-end-date-university" value="{{ now()->format('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                </form>`;
}

function guardian_student_form_school(){

    return ` <form>
                    <div class="row">
                        <div class="h5">Student Information</div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Mr</label>
                                <input type="text" name="guardian_student_name" class="form-control">
                            </div>
                        </div> 
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Phone Number</label>
                                <input type="number" name="guardian_student_phone_no" class="form-control">
                            </div>
                        </div> 
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Email Address (optional)</label>
                                <input type="text" name="guardian_student_email" class="form-control">
                            </div>
                        </div> 
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Home Address</label>
                                <input type="text" class="form-control">
                            </div>
                        </div> 
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="date-1">Transport Start Date</label>
                                <input class="form-control transport-start-date-guardian" type="date" name="transport-start-date-guardian" value="{{ now()->format('Y-m-d') }}">
                            </div>    
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="date">Transport End Date</label>
                                <input class="form-control transport-start-end-guardian" type="date" name="transport-end-date-guardian" value="{{ now()->format('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                </form>`;
    
}
$(document).ready(function () {
    // this is for to add asterick for all the required fields
    $("input[required], select[required], textarea[required]")
        .prev("label")
        .append('<span class="required-asterisk text-danger"> * </span>');

    // this for to select today date
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, "0");
    var mm = String(today.getMonth() + 1).padStart(2, "0");
    var yyyy = today.getFullYear();
    var formattedDate = yyyy + "-" + mm + "-" + dd;
    $(".today-date").val(formattedDate);

    // for to set current time
    var now = new Date();
    var hours = now.getHours();
    var minutes = now.getMinutes();
    // Format the time in the required format
    var formatted_time =
        (hours < 10 ? "0" : "") +
        hours +
        ":" +
        (minutes < 10 ? "0" : "") +
        minutes;
    // Set the input value to the current time
    $(".current-time").val(formatted_time);

    // When a parent  checkbox is clicked
    $(".parent_checkbox").change(function () {
        // If the parent checkbox is checked
        if ($(this).is(":checked")) {
            // Check all child checkboxes
            $(".child_checkbox").prop("checked", true);
        } else {
            // Uncheck all child checkboxes
            $(".child_checkbox").prop("checked", false);
        }
    });

    // When a child checkbox is changeed
    $(".child_checkbox").change(function () {
        // If the child checkbox is unchecked
        if (!$(this).is(":checked")) {
            // Uncheck the parent checkbox
            $(".parent_checkbox").prop("checked", false);
        }
        // If all child checkboxes are checked
        if (
            $(".child_checkbox:checked").length === $(".child_checkbox").length
        ) {
            // Check the parent checkbox
            $(".parent_checkbox").prop("checked", true);
        }
    });

    // this is for to add asterick for all the required fields

    var now = new Date();
    var currentDateTime = now.toISOString().substring(0, 16);
    // $(".currentDateTime").val(currentDateTime);
    $(".currentDateTime").prop("min", currentDateTime);
});

//Name Validation for character and whitespaces
const isName = (nameval) => {
    var namepattern = /^[a-zA-Z ]{2,30}$/;
    if (!namepattern.test(nameval)) return false;
    return true;
};

//Email validate function
/**
 *
 * @param {*} emailval
 * @returns
 */
const isEmail = (emailval) => {
    var atSymbol = emailval.indexOf("@");
    var emailpattern =
        /^(([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5}){1,25})+([;.](([a-zA-Z0-9_\-\.]+)@{[a-zA-Z0-9_\-\.]+0\.([a-zA-Z]{2,5}){1,25})+)*$/;
    if (atSymbol < 1) return false;
    var dot = emailval.lastIndexOf(".");
    if (dot <= atSymbol + 3) return false;
    if (dot === emailval.length - 1) return false;
    if (!emailpattern.test(emailval)) return false;
    return true;
};

/**
 *
 * @param {*} phoneval
 * @returns
 */
const isPhone = (phoneval) => {
    var phonePattern = /^[0-9]{4}-[0-9]{7}$/;
    var onlyNumbers = /^[0-9]+$/;
    if (!phonePattern.test(phoneval)) return false;
    else if (!onlyNumbers.test(phoneval)) return false;
    return true;
};

/**
 *
 * @param {*} cnicval
 * @returns
 */
const isCnic = (cnicval) => {
    var cnicPattern = /^[0-9]{13}$/;
    var onlyNumbers = /^[0-9]+$/;
    if (!cnicPattern.test(cnicval)) return false;
    else if (!onlyNumbers.test(cnicval)) return false;
    return true;
};

/**
 *
 * @param {*} licenseval
 * @returns
 */
const isLicense = (licenseval) => {
    var licensePattern = /^[A-Z]{2,3}[-\s][0-9]{2}[-\s][A-Z0-9]{1,4}$/;
    // var onlyNumbers = /^[0-9]+$/;
    if (!licensePattern.test(licenseval)) return false;
    // else if (!onlyNumbers.test(cnicval)) return false;
    return true;
};

/**
 *
 * @param {*} input
 * @param {*} errormsgs
 */
function setErrorMsg(input, errormsgs) {
    $(input).addClass("is-invalid");
    $(input + "_error").html(errormsgs);
}

/**
 *
 * @param {*} input
 */
function setSuccessMsg(input) {
    $(input).removeClass("is-invalid");
    $(input + "_error").html("");
}

/**
 * it return options as we send him the object or data
 *
 * @param   {[type]}  options  response is the response
 *
 * @return  {[type]}       this will make html option and return that options
 */
function makeOptions(res) {
    let html = '<option value="">Please Select</option>';
    res.map((item) => {
        html += `<option value="${item.id}">${item.name}</option>`;
    });
    return html;
}

/**
 * this function is use to hide all the previous date from calender
 *
 * @param   {[type]}  pramaid  [pramaid description]
 *
 * @return  {[type]}           [return description]
 */
function preventPreviousDate(pramaid) {
    var today = new Date();
    var day = today.getDate();
    var month = today.getMonth() + 1;
    var year = today.getFullYear();
    if (day < 10) {
        day = "0" + day;
    }
    if (month < 10) {
        month = "0" + month;
    }
    var today_formatted = year + "-" + month + "-" + day;
    // Set the minimum date for the date input field
    document.getElementById(pramaid).setAttribute("min", today_formatted);
}

/**
 * [formatTime description]
 *
 * @param   {[type]}  timeString  [timeString description]
 *
 * @return  {[type]}              [return description]
 */
function formatTime(timeString) {
    const [hourString, minute] = timeString.split(":");
    const hour = +hourString % 24;
    return (hour % 12 || 12) + " : " + minute + (hour < 12 ? " AM" : " PM");
}

function formatDate(dateString) {
    const parts = dateString.split("-");
    return `${parts[2]}/${parts[1]}/${parts[0]}`;
}

/**
 *
 * @param {*} select2class
 * @param {*} formid
 */
function initializeSelect2(select2class, formid) {
    $(select2class).select2({
        dropdownParent: $(formid), // modal : id modal
        placeholder: "Select",
        allowClear: true,
        width: "100%",
        height: "30px",
    });
}

/**
 *
 * @param {*} formIds
 */
function formSubmitConfirmation(formIds) {
    $(formIds).on("submit", function (e) {
        e.preventDefault(); // Prevent the form from submitting normally
        const form = this; // Store the form element in a variable
        // Display a SweetAlert confirmation dialog
        Swal.fire({
            title: "Are you sure?",
            text: "You will not be able to undo this action.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, submit it!",
            cancelButtonText: "No, cancel it",
        }).then((result) => {
            if (result.isConfirmed) {
                // If the user confirms, submit the form
                form.submit();
            }
        });
    });
}

/**
 * this function reset the dropify image preview
 * @param {*} imageUrl
 * @param {*} inputId
 * @returns
 */
function resetPreviewDropify(imageUrl, inputId) {
    // alert(imageUrl + '   ' + inputId);
    let imagenUrl = imageUrl;
    let drEvent = $(inputId).dropify({
        defaultFile: imagenUrl,
    });
    drEvent = drEvent.data("dropify");
    drEvent.resetPreview();
    drEvent.clearElement();
    drEvent.settings.defaultFile = imagenUrl;
    drEvent.destroy();
    drEvent.init();
}

// this function is use to show the success message
function showSuccess( message = "Success created" ) {
    $.toast({
        heading: "Success",
        text: message,
        icon: "success",
        position: "top-right",
        loader: false,
        bgColor: "#1abc9c",
        hideAfter: 5000,
        stack: 5,
    });
}

// this function is use to show the error message
function showError( message = "Error Occured!") {
    $.toast({
        heading: "Error",
        text: message,
        icon: "success",
        position: "top-right",
        loader: false,
        bgColor: "#f1556c",
        hideAfter: 5000,
        stack: 5,
    });
}

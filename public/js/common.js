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
    $(".parent_checkbox").click(function () {
        // If the parent checkbox is checked
        if ($(this).is(":checked")) {
            // Check all child checkboxes
            $(".child_checkbox").prop("checked", true);
        } else {
            // Uncheck all child checkboxes
            $(".child_checkbox").prop("checked", false);
        }
    });

    // When a child checkbox is clicked
    $(".child_checkbox").click(function () {
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
});
//Name Validation for character and whitespaces
const isName = (nameval) => {
    var namepattern = /^[a-zA-Z ]{2,30}$/;
    if (!namepattern.test(nameval)) return false;
    return true;
};

//Email validate function
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

//validate phone number
const isPhone = (phoneval) => {
    var phonePattern = /^[0-9]{4}-[0-9]{7}$/;
    var onlyNumbers = /^[0-9]+$/;
    if (!phonePattern.test(phoneval)) return false;
    else if (!onlyNumbers.test(phoneval)) return false;
    return true;
};

//validate cnic number
const isCnic = (cnicval) => {
    var cnicPattern = /^[0-9]{13}$/;
    var onlyNumbers = /^[0-9]+$/;
    if (!cnicPattern.test(cnicval)) return false;
    else if (!onlyNumbers.test(cnicval)) return false;
    return true;
};

//validate license number
const isLicense = (licenseval) => {
    var licensePattern = /^[A-Z]{2,3}[-\s][0-9]{2}[-\s][A-Z0-9]{1,4}$/;
    // var onlyNumbers = /^[0-9]+$/;
    if (!licensePattern.test(licenseval)) return false;
    // else if (!onlyNumbers.test(cnicval)) return false;
    return true;
};

//set error message function
function setErrorMsg(input, errormsgs) {
    $(input).addClass("is-invalid");
    $(input + "_error").html(errormsgs);
}

//set Success Message
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
    const hour = + hourString % 24;
    return (hour % 12 || 12) + " : " + minute + (hour < 12 ? " AM " : " PM ");
}

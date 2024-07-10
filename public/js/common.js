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

const isPhone = (phoneval) => {
    var phonePattern = /^[0-9]{4}-[0-9]{7}$/;
    var onlyNumbers = /^[0-9]+$/;
    if (!phonePattern.test(phoneval)) return false;
    else if (!onlyNumbers.test(phoneval)) return false;
    return true;
};

const isCnic = (cnicval) => {
    var cnicPattern = /^[0-9]{13}$/;
    var onlyNumbers = /^[0-9]+$/;
    if (!cnicPattern.test(cnicval)) return false;
    else if (!onlyNumbers.test(cnicval)) return false;
    return true;
};

const isLicense = (licenseval) => {
    var licensePattern = /^[A-Z]{2,3}[-\s][0-9]{2}[-\s][A-Z0-9]{1,4}$/;
    // var onlyNumbers = /^[0-9]+$/;
    if (!licensePattern.test(licenseval)) return false;
    // else if (!onlyNumbers.test(cnicval)) return false;
    return true;
};

function setErrorMsg(input, errormsgs) {
    $(input).addClass("is-invalid");
    $(input + "_error").html(errormsgs);
}

function setSuccessMsg(input) {
    $(input).removeClass("is-invalid");
    $(input + "_error").html("");
}

function makeOptions(res) {
    let html = '<option value="">Please Select</option>';
    res.map((item) => {
        html += `<option value="${item.id}">${item.name}</option>`;
    });
    return html;
}

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

function formatTime(timeString) {
    const [hourString, minute] = timeString.split(":");
    const hour = +hourString % 24;
    return (hour % 12 || 12) + " : " + minute + (hour < 12 ? " AM" : " PM");
}

function formatDate(dateString) {
    const parts = dateString.split("-");
    return `${parts[2]}/${parts[1]}/${parts[0]}`;
}

function initializeSelect2(select2class, formid) {
    $(select2class).select2({
        dropdownParent: $(formid), // modal : id modal
        placeholder: "Select",
        allowClear: true,
        width: "100%",
        height: "30px",
    });
}

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

function showSuccess(message = "Success created") {
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

function showError(message = "Error Occured!") {
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

function loadingStart(title = "Loading...") {
    return Swal.fire({
        title: title,
        allowEscapeKey: false,
        allowOutsideClick: false,
        showCancelButton: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });
}

function loadingStop() {
    if (swal) {
        swal.close();
    }
}

function renderPagination(data, container) {
    let html = "";

    if (data.data && data.data.length > 0) {
        html += `<div class="post-pagination wow fadeInUp" data-wow-delay="0.10s">
            <ul class="pagination">`;

        if (data.prev_page_url !== null) {
            html += `<li><a href="javascript:void(0)" data-page="${
                data.current_page - 1
            }"><i class="fa-solid fa-arrow-left-long"></i></a></li>`;
        }

        for (let i = 1; i <= data.last_page; i++) {
            html += `<li class="${
                data.current_page == i ? "active" : ""
            }"><a href="javascript:void(0)" data-page="${i}">${i}</a></li>`;
        }

        if (data.next_page_url !== null) {
            html += `<li><a href="javascript:void(0)" data-page="${
                data.current_page + 1
            }"><i class="fa-solid fa-arrow-right-long"></i></a></li>`;
        }

        html += `       </ul>
    </div>`;
    }

    $(container).html(html);
}

function limitString(string, limit = 15) {
    return string.length > limit ? string.substring(0, limit) + "..." : string;
}

function formatTimestampHumanReadable(timestamp) {
    let currentTime = Math.floor(Date.now() / 1000); // Current time in seconds
    let timestampSeconds = Math.floor(new Date(timestamp).getTime() / 1000); // Timestamp in seconds

    let difference = currentTime - timestampSeconds;

    // Define time intervals in seconds
    let intervals = {
        year: 31536000,
        month: 2592000,
        week: 604800,
        day: 86400,
        hour: 3600,
        minute: 60,
    };

    // Loop through intervals to find the appropriate one
    for (let key in intervals) {
        let value = Math.floor(difference / intervals[key]);
        if (value >= 1) {
            return value + " " + key + (value > 1 ? "s" : "") + " ago";
        }
    }

    return "Just now";
}


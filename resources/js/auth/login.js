$(document).ready(function () {
    $("#username").on("input", function () {
        if (!$("#username").val()) {
            $("#error-username").removeClass("d-none");
        } else {
            $("#error-username").addClass("d-none");
        }
    });

    $("#password").on("input", function () {
        if (!$("#password").val()) {
            $("#error-password").removeClass("d-none");
        } else {
            $("#error-password").addClass("d-none");
        }
    });
});

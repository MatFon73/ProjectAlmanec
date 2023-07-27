var CedulaLogin = document.getElementById("CedulaLogin");
var PassLogin = document.getElementById("PassLogin");

function Login() {
    if (CedulaLogin.value == "" || PassLogin.value == "") {
        Swal.fire({
            icon: "warning",
            title: "Login",
            confirmButtonColor: "#5cb85c",
            text: 'There is a blank space.',
        });
        return false;
    }
    $.ajax({
        url: "app/Execute_controller.php",
        type: "POST",
        data: "cedulaLogin=" + CedulaLogin.value + "&passLogin=" + PassLogin.value,
        dataType: 'json',
        success: function (response) {
            localStorage.setItem("UserData", response.CheckUser);
            Swal.fire({
                icon: response.r2,
                title: "Login",
                confirmButtonColor: "#5cb85c",
                text: response.r1 + " with " + response.CheckUser,
            }).then(function () {
                if (response.r2 == "success") {
                    window.location.href = "public/dashboard.html";
                }
            });
        },
        error: function (e) {
            Swal.fire({
                icon: "error",
                title: "Login",
                confirmButtonColor: "#5cb85c",
                text: e,
            });
        },
    });
    return false;
}
function LogOut() {
    $.ajax({
        url: "../app/Execute_controller.php",
        type: "POST",
        data: "LogOut=" + localStorage.getItem("UserData"),
        success: function () {
            localStorage.removeItem("UserData");
            Swal.fire({
                icon: "success",
                title: "LogOut",
                confirmButtonColor: "#5cb85c",
                text: "Logged out successfully.",
            }).then(function () {
                window.location.href = "../index.html";
            });
        },
        error: function (e) {
            Swal.fire({
                icon: "error",
                title: "LogOut",
                confirmButtonColor: "#5cb85c",
                text: e,
            });
        },
    });
    return false;
}
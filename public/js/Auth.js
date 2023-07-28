var CedulaLogin = document.getElementById("CedulaLogin");
var PassLogin = document.getElementById("PassLogin");

function Login() {
    if (CedulaLogin.value == "" || PassLogin.value == "") {
        Swal.fire({
            icon: "warning",
            title: "Iniciar Sesion",
            confirmButtonColor: "#5cb85c",
            text: 'Hay un espacio en blanco.',
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
                title: "Iniciar Sesion",
                confirmButtonColor: "#5cb85c",
                text: response.r1 + " Con " + response.CheckUser,
            }).then(function () {
                if (response.r2 == "success") {
                    window.location.href = "public/dashboard.html";
                }
            });
        },
        error: function (e) {
            Swal.fire({
                icon: "error",
                title: "Iniciar Sesion",
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
                title: "Cerrar Sesion",
                confirmButtonColor: "#5cb85c",
                text: "Sesion cerrada con exito.",
            }).then(function () {
                window.location.href = "../index.html";
            });
        },
        error: function (e) {
            Swal.fire({
                icon: "error",
                title: "Cerrar Sesion",
                confirmButtonColor: "#5cb85c",
                text: e,
            });
        },
    });
    return false;
}
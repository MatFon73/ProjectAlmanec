window.onload = function () {
    const menubar = document.querySelector(".menubar");
    const closeBtn = document.querySelector("#btn");

    closeBtn.addEventListener("click", function () {
        menubar.classList.toggle("open")
        menuBtnChange()
    })

    function menuBtnChange() {
        if (menubar.classList.contains("open")) {
            closeBtn.classList.replace("bx-menu", "bx-menu-alt-right")
        } else {
            closeBtn.classList.replace("bx-menu-alt-right", "bx-menu")
        }
    }
}
$(document).ready(function(){
    $('#example').dataTable();
})
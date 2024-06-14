function toggleSidebar() {
    var sidebar = document.getElementById("sidebar");
    var bg = document.getElementById("sidebar_menu_bg");

    if (sidebar.classList.contains("active")) {
        sidebar.classList.remove("active");
        setTimeout(function() {
            bg.classList.remove("active");
        }, 500); // Delay time matches the transition time
    } else {
        sidebar.classList.add("active");
        bg.classList.add("active");
    }
}
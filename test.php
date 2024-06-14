<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"/>
    <link rel="stylesheet" type="text/css" href="navbar.css"/>
    <style>
body {
    width: 100%;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    min-height: 100vh;
    font-family: "Poppins", sans-serif;
    background-color: #e2e5de;
}

header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 20px;
    background-color: #0cae87;
    height: 90px;
    color: white;
    border-radius: 0 0 30px 30px;
}

.checkbtn {
    background: transparent;
    border: none;
    cursor: pointer;
    padding: 0;
    color: white;
    font-size: 30px;
}

.nav-logo {
    flex-grow: 1;
    text-align: center;
    /* Added properties for image styling */
}

.nav-logo img {
    max-height: 70px; /* Adjust as needed */
    max-width: 100%;
    height: auto;
}

.sidebar {
    height: 100%;
    width: 0;
    position: fixed;
    z-index: 104; /* Increased to be above the blur background */
    top: 0;
    left: 0;
    background-color: rgba(255, 255, 255, 0.1); /* Updated background */
    color: #fff; /* Text color */
    overflow-x: hidden;
    transition: width 0.5s; /* Transition time */
    padding-top: 60px;
}

.sidebar a {
    text-decoration: none;
    color: #fff; /* Text color */
}

.sidebar li {
    padding: 10px 15px;
    list-style: none;
    font-size: 1.5em;
    display: flex;
    align-items: center;
    transition: 0.3s;
    cursor: pointer;
    width: 100%; /* Ensure full width */
    height: 50px; /* Fixed height */
}

.sidebar li:hover {
    background-color: #575757;
}

.sidebar .closebtn {
    position: absolute;
    top: 20px;
    left: 25px; /* Moved to the left */
    font-size: 36px;
    height: 50px; /* Fixed height */
    height: 100%;
    line-height: 50px; /* Align text vertically */
    width: 200px; /* Fixed width */
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: flex-start; /* Align content to the left */
    white-space: nowrap; /* Prevent text from wrapping */
}

.sidebar .toggle-sidebar {
    position: absolute;
    top: 20px;
    left: 25px; /* Moved to the left */
    font-size: 16px;
    color: white;
    border: none;
    background: transparent;
    cursor: pointer;
}

.sidebar.active {
    width: 250px;
}

.sidebar-logout {
    padding: 10px 15px;
}

.btn-logout {
    width: 100%;
    padding: 10px 15px;
    font-size: 1.5em;
    color: white;
    background-color: #0cae87;
    border: none;
    cursor: pointer;
    text-align: center;
    transition: background-color 0.3s;
}

.btn-logout:hover {
    background-color: #088f68;
}
.hs-toggles {
    display: flex;
    flex-direction: column;
    padding-left: 0; /* Remove default padding of ul */
}

.hst-item {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
    width: 100%; /* Ensure full width */
    height: 50px; /* Fixed height */
}

.hst-icon {
    margin-right: 10px;
    font-size: 1.5em;
    flex-shrink: 0; /* Prevent shrinking */
}

.name {
    font-size: 1.2em;
    white-space: nowrap; /* Prevent text from wrapping */
    overflow: hidden; /* Hide overflow text */
    text-overflow: ellipsis; /* Add ellipsis to overflow text */
    display: flex;
    align-items: center;
    flex: 1; /* Allow text to take remaining space */
}

#sidebar_menu_bg.active {
    display: block;
}

#sidebar_menu_bg {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(36, 36, 40, .8);
    z-index: 103; /* Ensure it is below the sidebar */
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    display: none;
}

    </style>
</head>
<body>
<header>
    <button class="checkbtn" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>
    <div class="nav-logo">
        <img src="images/logo.png" alt="HELP ME COOK Logo">
    </div>
</header>

<div id="sidebar_menu_bg" onclick="toggleSidebar()"></div>

<div class="sidebar" id="sidebar">
    <button class="btn btn-radius btn-sm btn-secondary toggle-sidebar" onclick="toggleSidebar()">
        <i class="fas fa-angle-left mr-2"></i>Close menu
    </button>
    <div class="sb-setting">
        <div class="header-setting">
            <ul class="hs-toggles">
                <li class="hst-item" data-toggle="tooltip" title="Chatbot">
                    <a href="ai.php">
                        <div class="name"><span>Chatbot</span></div>
                    </a>
                </li>
                <li class="hst-item" data-toggle="tooltip" title="Recipe Library">
                    <a href="recipe_library.php">
                        <div class="name"><span>Recipe Library</span></div>
                    </a>
                </li>
                <li class="hst-item" data-toggle="tooltip" title="User Creation">
                    <a href="userCreation.php">
                        <div class="name"><span>User Creation</span></div>
                    </a>
                </li>
                <li class="hst-item" data-toggle="tooltip" title="Notification">
                    <a href="notification.php">
                        <div class="name"><span>Notification</span></div>
                    </a>
                </li>
                <li class="hst-item" data-toggle="tooltip" title="Profile">
                    <a href="profile.php">
                        <div class="name"><span>Profile</span></div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="sidebar-logout">
        <form action="logout.php" method="post">
            <button type="submit" class="btn-logout">Logout</button>
        </form>
    </div>
</div>

<script src="script.js"></script>
</body>

</html>

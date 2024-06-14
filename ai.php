<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot</title>
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
    />
    <link rel="stylesheet" type="text/css" href="navbar.css"/>
    <style>
 
      .center-div {
        width: 50vw;
        height: 80vh;
        background-color: rgb(102, 71, 255);
        border-radius: 15px;
        padding: 5px;
        box-sizing: border-box;
        color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
      }
 
      @media only screen and (max-width: 600px) {
        .center-div {
          height: 40vh;
          width: 80vw;
          background-color: rgb(102, 71, 255);
          border-radius: 15px;
          padding: 5px;
          box-sizing: border-box;
          color: white;
          display: flex;
          justify-content: center;
          align-items: center;
          text-align: center;
        }
      }
    </style>
</head>
<body>
<header>
    <button class="checkbtn" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>
    <label class="nav-logo">HELP ME COOK</label>
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
  

<div>
      <div class="absolute inset-4 ">
        <div
          class="center-div relative h-full w-full overflow-clip rounded-md border border-zinc-200 bg-white p-2 px-0 py-0"
        >
          <iframe
            style="border: none;"
            srcdoc="<body><script src='https://cdn.botpress.cloud/webchat/v0/inject.js'></script>
            <script>
              window.botpressWebChat.init({
                  'composerPlaceholder': 'Chat with bot',
                  'botConversationDescription': 'This chatbot was built surprisingly fast with Botpress',
                  'botName': 'Chatbot',
                  'botId': '69d204b8-d41e-44ed-960e-da32fcee2b08',
                  'hostUrl': 'https://cdn.botpress.cloud/webchat/v0',
                  'messagingUrl': 'https://messaging.botpress.cloud',
                  'clientId': '69d204b8-d41e-44ed-960e-da32fcee2b08',
                  'enableConversationDeletion': true,
                  'showPoweredBy': true,
                  'className': 'webchatIframe',
                  'containerWidth': '100%25',
                  'layoutWidth': '100%25',
                  'hideWidget': true,
                  'showCloseButton': false,
                  'disableAnimations': true,
                  'closeOnEscape': false,
                  'showConversationsButton': false,
                  'enableTranscriptDownload': false,
                  'stylesheet':'https://webchat-styler-css.botpress.app/prod/code/3fcd3e4e-d5bc-4bf5-8699-14b621b3ada2/v31782/style.css'
                  
              });
            window.botpressWebChat.onEvent(function () { window.botpressWebChat.sendEvent({ type: 'show' }) }, ['LIFECYCLE.LOADED']);
            </script></body>"
            width="100%"
            height="100%"
          ></iframe>
        </div>
      </div>
    </div>

<div class="chatbot-container">
<script src="https://cdn.botpress.cloud/webchat/v0/inject.js"></script>
<script>
    window.botpressWebChat.init({
        // Replace <your-bot-id> and <your-client-id> with your actual bot and client IDs
        "botId": "69d204b8-d41e-44ed-960e-da32fcee2b08>",
        "clientId": "<69d204b8-d41e-44ed-960e-da32fcee2b08>",
 
        // Set the URL for the Botpress WebChat JavaScript file and the messaging server
        "hostUrl": "https://cdn.botpress.cloud/webchat/v0",
        "messagingUrl": "https://messaging.botpress.cloud",
 
        // Set the name of the bot that will be displayed in the WebChat interface
        "Chatbot": "Test",
 
        // Set the width of the WebChat container and layout to 100% (Full Screen)
        "containerWidth": "100%25",
        "layoutWidth": "100%25",
 
        // Hide the widget and disable animations
        "hideWidget": true,
        "disableAnimations": true,
    });
 
    // Opens up the Chatbot by default
    // This lets users start chatting with the Chatbot without needing to click any buttons or menus.
    window.botpressWebChat.onEvent(
        function () {
            window.botpressWebChat.sendEvent({ type: "show" });
        },
        ["LIFECYCLE.LOADED"]
    );
</script>
 
</div>

<script src="script.js"></script>

</body>
</html>

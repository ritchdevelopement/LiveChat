jQuery(document).ready(function() {
    var chatRefresh = 250;
    var userOnline = 0;
    var startButton = document.querySelector("#lv-start");
    var chatContainer = document.querySelector("#lv-container");
    var closeChat = document.querySelector("#lv-close");
    var sendInput = document.querySelector("#lv-input");
    var chatOutput = document.querySelector("#lv-output");
    
    window.addEventListener("unload", function () {
        userOnline = 0;
        sendMessage();
    });

    window.addEventListener("beforeunload", function () {
        userOnline = 0;
        sendMessage();
    });

    startButton.onclick = function() {
        chatContainer.style.maxHeight = "400px";
        startButton.style.display = "none";
        sendInput.focus();
        userOnline = 1;
        sendMessage();
    }

    closeChat.onclick = function() {
        chatContainer.style.maxHeight = 0;
        startButton.style.display = "block";
    }

    sendInput.onkeyup = function(e) {
        if(e.keyCode == 13) {
            sendMessage();
        }
    }

    function sendMessage() {
        messageString = sendInput.value;

            jQuery.post(livechat_script.ajaxurl, data = {
                'action': 'writeMessage',
                'chatuser': 'Visitor',
                'message': messageString,
                'useronline': userOnline
            });
            
            sendInput.value = "";
            getMessages();
    }

    function getMessages() {
        jQuery.post(livechat_script.ajaxurl, data = {
            'action': 'readMessages',
        }, 
        function(response) {
            var tempDiv = document.createElement("div");
            tempDiv.innerHTML = response;

            if(chatOutput.innerHTML != tempDiv.innerHTML ) { 
                chatOutput.innerHTML = tempDiv.innerHTML;
                chatOutput.scrollTop = chatOutput.scrollHeight;
            }
        });        
    }

    setInterval(function() {
        getMessages();
    }, chatRefresh);

});


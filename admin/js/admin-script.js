jQuery(document).ready(function() {
    var chatRefresh = 250;
    var checkUserRefresh = 1000;
    var dbClear = document.querySelector("#db-clear");
    var inputMessage = document.querySelector("#message-input");
    var chatuser = document.querySelector("#chatuser");

    dbClear.onclick = function() {
        clearDB();
    }
	
	inputMessage.onkeyup = function(e) {
        if(e.keyCode == 13) {
            sendMessage();
        }
    }

    function clearDB () {
        jQuery.post(livechat_script.ajaxurl, data = {
            'action': 'clearDatabase',
        }, 
        function(response) {
            alert(response);
        });      
    }

    function sendMessage() {
        messageString = inputMessage.value; 
        chatuserString = chatuser.value;

        if(messageString != "") {
            jQuery.post(livechat_script.ajaxurl, data = {
                'action': 'writeMessage',
                'chatuser': chatuserString,
                'message': messageString,
                'useronline': 1
            });
            inputMessage.value = "";
            getMessages();
        };
    }

    function getMessages() {
        var lcHistory = document.querySelector("#chat-history");

        jQuery.post(livechat_script.ajaxurl, data = {
            'action': 'readMessages',
        }, 
        function(response) {
            var tempDiv = document.createElement("div");
            tempDiv.innerHTML = response;

            if(lcHistory.innerHTML != tempDiv.innerHTML ) { 
                lcHistory.innerHTML = tempDiv.innerHTML;
                lcHistory.scrollTop = lcHistory.scrollHeight;
            }
        });        
    }

    function checkUserOnline() {
        jQuery.post(livechat_script.ajaxurl, data = {
            'action': 'checkUserOnline',
        }, 
        function(response) {
            var userOnline = document.querySelector("#vistitoronline");
            var userOnlineString = "";

            if(response == "false" || response == "") {
                userOnlineString = "Nein";
                userOnline.style.color = "red"
            } else {
                userOnlineString = "Ja";
                userOnline.style.color = "green"
            }
            userOnline.innerHTML = userOnlineString;
        });
    }

    setInterval(function() {
        checkUserOnline();
    }, checkUserRefresh);

    setInterval(function() {
        getMessages();
    }, chatRefresh);
});
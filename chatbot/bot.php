<!-- Created By CampCodes -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Somehow I got an error, so I comment the title, just uncomment to show -->
    <!-- <title>Online Chatbot in PHP | CampCodes</title> -->
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>
    <div class="wrapper">
        <div class="title">Real estate hub Chatbot</div>
        <div class="form">
            <div class="bot-inbox inbox">
                <div class="icon">
                    <i class="fas fa-user"></i>
                </div>
                <div class="msg-header">
                    <p>Hello there, how can I help you?</p>
                </div>
            </div>
        </div>
        <div class="typing-field">
            <div class="input-data">
                <input id="data" type="text" placeholder="Type something here.." required>
                <button id="send-btn">Send</button>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
    $("#send-btn").on("click", function(){
        let userMessage = $("#data").val();
        if (userMessage.trim() !== "") {
            // Display user message
            let userMessageHtml = '<div class="user-inbox inbox"><div class="msg-header"><p>' + userMessage + '</p></div></div>';
            $(".form").append(userMessageHtml);
            $("#data").val(''); // Clear input field
            
            // Scroll to the bottom of the chat
            $(".form").scrollTop($(".form")[0].scrollHeight);

            // Start AJAX request to PHP backend
            $.ajax({
                url: 'message.php',
                type: 'POST',
                data: { text: userMessage },
                success: function(response){
                    // Display bot's reply
                    let botReplyHtml = '<div class="bot-inbox inbox"><div class="icon"><i class="fas fa-user"></i></div><div class="msg-header"><p>' + response + '</p></div></div>';
                    $(".form").append(botReplyHtml);
                    // Scroll to the bottom of the chat
                    $(".form").scrollTop($(".form")[0].scrollHeight);
                },
                error: function(){
                    alert('Error in fetching response!');
                }
            });
        }
    });
});

    </script>
    
</body>
</html>
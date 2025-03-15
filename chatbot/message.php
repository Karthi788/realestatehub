<?php
// // connecting to database
// $conn = mysqli_connect("localhost", "root", "", "bot") or die("Database Error");

// // getting user message through ajax
// $getMesg = mysqli_real_escape_string($conn, $_POST['text']);

// //checking user query to database query
// $check_data = "SELECT replies FROM chatbot WHERE queries LIKE '%$getMesg%'";
// $run_query = mysqli_query($conn, $check_data) or die("Error");

// // if user query matched to database query we'll show the reply otherwise it go to else statement
// if(mysqli_num_rows($run_query) > 0){
//     //fetching replay from the database according to the user query
//     $fetch_data = mysqli_fetch_assoc($run_query);
//     //storing replay to a varible which we'll send to ajax
//     $replay = $fetch_data['replies'];
//     echo $replay;
// }else{
//     echo "Sorry can't be able to understand you!";
// }



if (isset($_POST['text'])) {
    $user_message = $_POST['text']; // Get the user's message
    
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://open-ai21.p.rapidapi.com/claude3",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode([
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $user_message
                ]
            ],
            'web_access' => null
        ]),
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "x-rapidapi-host: open-ai21.p.rapidapi.com",
            "x-rapidapi-key: de850c551dmsh43329e04cefb370p10b0e8jsn0c545f168283"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $response_data = json_decode($response, true);
        if (isset($response_data['result']) && $response_data['status'] === true) {
            $bot_reply = $response_data['result'];  // Extracting the message from the 'result' field
            echo $bot_reply;
        } else {
            echo "Sorry, I couldn't understand your query.";
        }
    }
}

?>
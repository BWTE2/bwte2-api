<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');
header("Access-Control-Allow-Credentials: true");
header('Content-type: application/json');
header('Access-Control-Allow-Methods: PUT, OPTIONS');

require_once("./phpqrcode.php");


/* ////////////////////////////////////////////////////////////////
 * SCRIPT
*/////////////////////////////////////////////////////////////////


handleAllRequests();


/* ////////////////////////////////////////////////////////////////
 * FUNCTIONS
*/////////////////////////////////////////////////////////////////


function handleAllRequests(){
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        handleGetRequest();
    }
    else {
        http_response_code(405);
    }
}


/*
* GET
*/
function handleGetRequest(){
    // token should be also necessary to prove owner
    // so random person cannot guess address and upload file instead of owner
    if(isset($_GET["test"]) && isset($_GET["user"]) && isset($_GET["question"]))
    {
        if(!isset($_GET["token"]))
            $token = 0;
        else
            $token = $_GET["token"];

        QRcode::png(
            $_SERVER['HTTP_HOST'] .
            "/bwte2/app/views/qr-mobile/?codeTest=" .
            $_GET["test"] .
            "&studentId=" .
            $_GET["user"] .
            "&questionId=" .
            $_GET["question"] .
            "&token=" .
            $token);
    }
}
?>
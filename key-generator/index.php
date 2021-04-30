<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');
header("Access-Control-Allow-Credentials: true");
header('Content-type: application/json');
header('Access-Control-Allow-Methods: GET, OPTIONS');

require_once("../../bwte2-backend/controllers/help_controllers/KeyGenerator.php");
const FLAGS = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;
/*
/* ////////////////////////////////////////////////////////////////
 * SCRIPT
*/////////////////////////////////////////////////////////////////

if($_SERVER["REQUEST_METHOD"] === 'OPTIONS'){
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
    header("HTTP/1.1 200 OK");
    die();
}


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
    $keyGenerator = new KeyGenerator();
    $key = $keyGenerator->generateKey();
    http_response_code(200);
    $json = ["response" => ["key" => $key]];
    echo json_encode($json, FLAGS);
}

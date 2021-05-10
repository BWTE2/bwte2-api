<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');
header("Access-Control-Allow-Credentials: true");
header('Content-type: application/json');
header('Access-Control-Allow-Methods: POST, OPTIONS');

require_once("../../bwte2-backend/controllers/help_controllers/LecturerAccessor.php");
require_once("../../bwte2-backend/controllers/help_controllers/StudentCreator.php");
const FLAGS = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;

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

function handleAllRequests()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //registracia ucitela
        handlePostRequest();
    }
    else {
        http_response_code(405);
    }
}

/*
 * POST
 */


function handlePostRequest(){
    $data = getInputJsonData();

    $json = sendPostData($data);
    echo json_encode($json, FLAGS);
}

function getInputJsonData(){
    $json = file_get_contents('php://input');
    return json_decode($json, false);
}


function sendPostData($data){
    $studentCreator = new StudentCreator();
    $response = $studentCreator->createStudent($data);

    http_response_code(201);
    return ["response" => $response];
}



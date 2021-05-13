<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');
header("Access-Control-Allow-Credentials: true");
header('Content-type: application/json');
header('Access-Control-Allow-Methods: PUT, OPTIONS');
session_start();
require_once("../../../bwte2-backend/controllers/help_controllers/StudentCreator.php");
require_once("../../../bwte2-backend/controllers/MainTestController.php");
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


function handleAllRequests(){
    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        handlePutRequest();
    }
    else {
        http_response_code(405);
    }
}

/*
 * PUT
 */

function handlePutRequest(){
    if(isset($_GET["key"])){
        handlePutStateRequest();
    }
    else{
        http_response_code(412);
    }
}

function handlePutStateRequest(){
    $key = $_GET["key"];
    $studentId = $_GET["studentId"];
    $json = getJson($key, $studentId);
    echo json_encode($json, FLAGS);
}


function getJson($key, $studentId){
    $studentCreator = new StudentCreator();
    $inputJson = getInputJsonData();
    $wasIn = $inputJson->wasIn;

    if($wasIn) {
        $response = $studentCreator->updateInTestStatus($key, $studentId);
    }
    else{
        $response = $studentCreator->updateOutTestStatus($key, $studentId);
    }

    http_response_code(200);
    return ["response" => $response];
}


function getInputJsonData(){
    $json = file_get_contents('php://input');
    return json_decode($json, false);
}

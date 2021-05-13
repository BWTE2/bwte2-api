<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');
header("Access-Control-Allow-Credentials: true");
header('Content-type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS');

require_once("../../bwte2-backend/controllers/help_controllers/LecturerAccessor.php");
require_once("../../bwte2-backend/controllers/MainTestController.php");
const FLAGS = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;
session_start();
/* ////////////////////////////////////////////////////////////////
 * SCRIPT
*/////////////////////////////////////////////////////////////////




if($_SERVER["REQUEST_METHOD"] === 'OPTIONS'){
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
    header("HTTP/1.1 200 OK");
    die();
}



if(isLogged()) {
    handleAllRequests();
}
else{
    $responseMessage = ["responseCode" => 401, "responseMessaage" => "Neautorizovany prístup"];

    echo json_encode(["responseErrorMessage" => $responseMessage]);
}




/* ////////////////////////////////////////////////////////////////
 * FUNCTIONS
*/////////////////////////////////////////////////////////////////


function isLogged(){
    if(isset($_SESSION["lecturerId"])) {
        return true;
    }
    return false;
}


function handleAllRequests() {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        handleGetRequest();
    } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        handlePostRequest();
    } else if($_SERVER['REQUEST_METHOD'] === 'PUT'){
        handlePutRequest();
    }
    else {
        http_response_code(405);
    }
}


/*
 * GET
 */


function handleGetRequest(){
    if(isset($_GET["key"])){
        //ziskat konkretny test
        handleGetOneTestInfoRequest();
    }
    else{
        //ziskat vypis testov ucitela
        handleGetAllTestInfoRequest();
    }
}

function handleGetOneTestInfoRequest(){
    $key = $_GET["key"];
    $json = getOneTestInfoJson($key);
    echo json_encode($json, FLAGS);
}

function getOneTestInfoJson($key){
    $testController = new MainTestController();
    $response = $testController->getOneTestInfo($key);

    http_response_code(200);
    return ["response" => $response];
}

function handleGetAllTestInfoRequest(){
    $json = getAllTestsInfoJson();
    echo json_encode($json, FLAGS);
}

function getAllTestsInfoJson(){
    $testService = new MainTestController();

    $response = $testService->getAllTestsInfo();

    http_response_code(200);
    return ["response" => $response];
}



/*
 * POST
 */

function handlePostRequest(){
    if(!isset($_GET["key"])){
        http_response_code(412);
        return;
    }
    $data = getInputJsonData();
    $json = sendPostData($data);
    echo json_encode($json, FLAGS);
}



function sendPostData($data){
    $key = $_GET["key"];

    $testService = new MainTestController();
    $response = $testService->addTest($data, $key);

    http_response_code(201);
    return ["response" => $response];
}


/*
 * PUT
 */

function handlePutRequest(){
    if(!isset($_GET["key"])){
        http_response_code(412);
        return;
    }
    $data = getInputJsonData();

    if($data->wantActivate){
        $json = activateTest();
    }
    else{
        $json = deactivateTest();
    }
    echo json_encode($json, FLAGS);
}


function getInputJsonData(){
    $json = file_get_contents('php://input');
    return json_decode($json, false);
}

function activateTest(){
    $key = $_GET["key"];

    $testService = new MainTestController();
    $response = $testService->activateTest($key);

    http_response_code(200);
    return ["response" => $response];
}

function deactivateTest(){
    $key = $_GET["key"];

    $testService = new MainTestController();
    $response = $testService->deactivateTest($key);

    http_response_code(200);
    return ["response" => $response];
}

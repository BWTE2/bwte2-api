<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');
header("Access-Control-Allow-Credentials: true");
header('Content-type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS');

require_once("../../bwte2-backend/controllers/help_controllers/LecturerAccessor.php");
require_once("../../bwte2-backend/controllers/MainTestController.php");
const FLAGS = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;

/* ////////////////////////////////////////////////////////////////
 * SCRIPT
*/////////////////////////////////////////////////////////////////

$_SESSION["lecturerId"] = 1; //toto tu je docasne kym nie je dokoncene prihlasovanie ucitela


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
    http_response_code(401);
}




/* ////////////////////////////////////////////////////////////////
 * FUNCTIONS
*/////////////////////////////////////////////////////////////////


function isLogged(){
    if(isset($_SESSION["lecturerId"])) {
        return true;
    }

    //TODO: v priapde potreby skontkretizovat

    return true;
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

    if(wantActivateTest($data)){
        $json = activateTest();
        echo json_encode($json, FLAGS);
    }
    else if (wantDeActivateTest($data)){
        $json = deactivateTest();
        echo json_encode($json, FLAGS);
    }
    else{
        http_response_code(412);
    }
}


function wantActivateTest($data){
    //TODO
    return true;
}

function wantDeActivateTest($data){
    //TODO
    return true;
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

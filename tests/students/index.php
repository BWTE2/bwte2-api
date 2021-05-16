<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');
header("Access-Control-Allow-Credentials: true");
header('Content-type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS');

require_once("../../../bwte2-backend/controllers/help_controllers/LecturerAccessor.php");
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


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    handlePostRequest();
}
else if(isLogged()) {
    handleAllRestrictedRequests();
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

    //TODO: v pripade potreby skontkretizovat

    return true;
}


function handleAllRestrictedRequests() {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        handleGetRequest();
    }
    else if($_SERVER['REQUEST_METHOD'] === 'PUT'){
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
    if(isset($_GET["key"], $_GET["student_id"])){
        handleGetStudentAnswersRequest();
    }
    elseif(isset($_GET["key"]) && !isset($_GET["student_id"])){
        handleGetAllStudentsRequest();
    }
    else{
        http_response_code(412);
    }
}

function handleGetStudentAnswersRequest(){
    $key = $_GET["key"];
    $studentId = $_GET["student_id"];
    $json = getOneStudentAnswersJson($key, $studentId);
    echo json_encode($json, FLAGS);
}

function getOneStudentAnswersJson($key, $studentId){
    $testController = new MainTestController();
    $response = $testController->getOneStudentAnswers($key, $studentId);

    http_response_code(200);
    return ["response" => $response];
}

function handleGetAllStudentsRequest(){
    $key = $_GET["key"];
    $json = getAllStudentsJson($key);
    echo json_encode($json, FLAGS);
}

function getAllStudentsJson($key){
    $testService = new MainTestController();

    $response = $testService->getAllStudents($key);

    http_response_code(200);
    return ["response" => $response];
}



/*
 * POST
 */

function handlePostRequest(){
    if(!isset($_GET["key"], $_GET["student_id"])){
        http_response_code(412);
        return;
    }
    $data = getInputJsonData();

    if(arePostDataCorrect($data)){
        $json = sendPostData($data);
        echo json_encode($json, FLAGS);
    }
    else{
        http_response_code(412);
    }
}


function arePostDataCorrect($data){
    //TODO
    return true;
}

function sendPostData($data){
    $key = $_GET["key"];
    $studentId = $_GET["student_id"];

    $testService = new MainTestController();
    $response = $testService->sendAnswers($data, $key, $studentId);

    http_response_code(201);
    return ["response" => $response];
}


/*
 * PUT
 */

function handlePutRequest(){
    if(!isset($_GET["key"], $_GET["student_id"])){
        http_response_code(412);
        return;
    }
    $data = getInputJsonData();

    $json = sendPutData($data);
    echo json_encode($json, FLAGS);

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

function sendPutData($data){
    $key = $_GET["key"];
    $studentId = $_GET["student_id"];

    $testService = new MainTestController();
    $response = $testService->updateEvaluation($data, $key, $studentId);

    http_response_code(201);
    return ["response" => $response];
}



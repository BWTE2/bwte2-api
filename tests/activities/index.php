<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header("Connection: keep-alive");

require_once("../../../bwte2-backend/controllers/MainTestController.php");

const FLAGS = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;

/* ////////////////////////////////////////////////////////////////
 * SCRIPT
*/////////////////////////////////////////////////////////////////


if(isset($_GET["key"])){
    handleSending();
}
else{
    http_response_code(412);
}



/* ////////////////////////////////////////////////////////////////
 * FUNCTIONS
*/////////////////////////////////////////////////////////////////


function handleSending(){
    $key = $_GET["key"];
    if(isValidKey($key)){
        runSending();
    }
    else{
        echo "event: activities\n";
        echo "data: " . json_encode(["response" => []]) . PHP_EOL . PHP_EOL;
    }
}

function isValidKey($key){
    $testController = new MainTestController();
    return $testController->isValidKey($key);
}

function runSending()
{
    $key = $_GET["key"];
    $studentsStates = getStudentsStates($key);
    $testService = new MainTestController();
    $isTestRunning = $testService->isTestRunning($key);

    while ($isTestRunning) {
        echo "event: activities\n";
        echo "data: " . json_encode(["response" =>  $studentsStates]) . PHP_EOL . PHP_EOL;

        $studentsStates = getStudentsStates($key);
        $isTestRunning = $testService->isTestRunning($key);

        ob_flush();
        flush();
        sleep(1);
    }
}

function getStudentsStates($key){
    $testService = new MainTestController();

    return $testService->getStudentsStates($key);
}

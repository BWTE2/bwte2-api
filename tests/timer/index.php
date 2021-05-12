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
        runSending($key);
    }
    else{
        echo "data: invalid-key";
    }
}

function isValidKey($key){
    $testService = new MainTestController();
    return $testService->isValidKey($key);
}

function runSending($key)
{
    $time = getMaxTime($key);
    $testService = new MainTestController();
    $isTestRunning = $testService->isTestRunning($key);

    while ($time >= 0 && $isTestRunning) {
        echo "data: " . json_encode(["response" => ["time" => $time]], FLAGS) . PHP_EOL . PHP_EOL;

        $time--;
        $isTestRunning = $testService->isTestRunning($key);

        ob_flush();
        flush();
        sleep(1);
    }

    if(!$isTestRunning){
        echo "data: inactive-test";
    }
}

function getMaxTime($key){
    $testService = new MainTestController();

    return $testService->getTestMaxTime($key);
}


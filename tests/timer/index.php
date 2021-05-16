<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header("Connection: keep-alive");

require_once("../../../bwte2-backend/controllers/help_controllers/StudentCreator.php");
require_once("../../../bwte2-backend/controllers/MainTestController.php");

const FLAGS = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;

/* ////////////////////////////////////////////////////////////////
 * SCRIPT
*/////////////////////////////////////////////////////////////////


if(isset($_GET["key"]) && isset($_GET["studentId"])){
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
        echo "event: timer\n";
        echo "data: 0";
    }
}

function isValidKey($key){
    $testService = new MainTestController();
    return $testService->isValidKey($key);
}

function runSending($key)
{
    $studentId = $_GET["studentId"];
    $studentCreator = new StudentCreator();
    $studentStatus = $studentCreator->getActualStatus($key, $studentId);

    $time = getMaxTime($key);
    $testService = new MainTestController();
    $isTestRunning = $testService->isTestRunning($key);

    while ($time >= 0 && $isTestRunning && $studentStatus !== "FINISHED") {
        echo "event: timer\n";
        echo "data: " .  $time . PHP_EOL . PHP_EOL;

        $time--;
        $isTestRunning = $testService->isTestRunning($key);
        $studentStatus = $studentCreator->getActualStatus($key, $studentId);
        if(!$isTestRunning){
            $time = 0;
        }
        if($studentStatus === "FINISHED"){
            $time = -1;
        }

        ob_flush();
        flush();
        sleep(1);
    }

    if(!$isTestRunning){
        $time = 0;
        echo "event: timer\n";
        echo "data: " .  $time . PHP_EOL . PHP_EOL;

        ob_flush();
        flush();
        sleep(1);
    }
    if($studentStatus === "FINISHED"){
        $time = -1;
        echo "event: timer\n";
        echo "data: " .  $time . PHP_EOL . PHP_EOL;

        ob_flush();
        flush();
        sleep(1);
    }

}

function getMaxTime($key){
    $testService = new MainTestController();

    return $testService->getTestMaxTime($key);
}


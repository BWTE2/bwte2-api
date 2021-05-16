<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');
header("Access-Control-Allow-Credentials: true");
header('Content-type: application/json');
header('Access-Control-Allow-Methods: POST, OPTIONS,GET');

require_once("../../../../bwte2-backend/controllers/help_controllers/FileExporter.php");

const FLAGS = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;

/* ////////////////////////////////////////////////////////////////
 * SCRIPT
*/////////////////////////////////////////////////////////////////

if ($_SERVER["REQUEST_METHOD"] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
    header("HTTP/1.1 200 OK");
    die();
}


if (isLogged()) {
    handleAllRequests();
} else {
    http_response_code(401);
}


/* ////////////////////////////////////////////////////////////////
 * FUNCTIONS
*/////////////////////////////////////////////////////////////////

function isLogged()
{
    if (isset($_SESSION["lecturerId"])) {
        return true;
    }

    //TODO: v pripade potreby skontkretizovat

    return true;
}


function handleAllRequests()
{
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        handlePostRequest();
    } else {
        http_response_code(405);
    }
}


/*
 * POST
 */

function handlePostRequest()
{
    if (!isset($_GET["key"])) {
        http_response_code(412);
        return;
    }

    crateExport();
}

function crateExport()
{
    $key = $_GET["key"];
    $testService = new FileExporter();
     $testService->createStudentPdf($key);
    http_response_code(201);
}


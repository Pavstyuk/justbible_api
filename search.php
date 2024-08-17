<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

define('STARTED', true);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header("Access-Control-Allow-Headers: X-Requested-With");
header('Content-Type: application/json; charset=utf-8');

require_once "get-bible.php";

isset($_GET["type"]) ? $type = htmlspecialchars($_GET["type"]) : $type = "json";

isset($_GET["search"]) ? $query = strtolower(htmlspecialchars($_GET["search"])) : $query = null;

// Start Errors

if ($query === null || mb_strlen($query) < 3) {
    $array = array(
        "result" => false,
        "data" => "Error! Unknown search query"
    );
    echo json_encode($array);
    exit;
}

// End Errors

$results = array();

foreach ($bible as $book_name => $book) {
    foreach ($book as $chap_num => $chapter) {
        foreach ($chapter as $verse_num => $text) {
            if (str_contains(strtolower($text), $query)) {
                $arr = array(
                    "text" => $text,
                    "book" => $book_name,
                    "chapter" => $chap_num,
                    "verse" => $verse_num,
                );
                array_push($results, $arr);
            }
        }
    }
}



if (count($results) > 0) {
    echo json_encode($results);
    exit;
} else {
    $array = array(
        "result" => null,
        "data" => "Sorry. Nothing found for you query"
    );
    echo json_encode($array);
    exit;
}

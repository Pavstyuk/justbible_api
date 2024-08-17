<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

define('STARTED', true);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header("Access-Control-Allow-Headers: X-Requested-With");

require_once "get-bible.php";

isset($_GET["type"]) ? $type = htmlspecialchars($_GET["type"]) : $type = "none";

$rnd_num = random_int(0, 65);
$rnd_book = $books_arr[$rnd_num];
$rnd_chap = random_int(1, count($bible[$rnd_book]));
$rnd_verse = random_int(1, count($bible[$rnd_book][$rnd_chap]));


if ($type == "string") {
    header("Content-Type: text/html; charset=utf-8");
    echo $bible[$rnd_book][$rnd_chap][$rnd_verse] . " ($rnd_book $rnd_chap:$rnd_verse)";
} else {
    header('Content-Type: application/json; charset=utf-8');
    $array = array(
        "text" => $bible[$rnd_book][$rnd_chap][$rnd_verse],
        "book" => "$rnd_book",
        "chapter" => $rnd_chap,
        "verse" =>  $rnd_verse
    );

    echo json_encode($array);
}

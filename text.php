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

$verses = array();
$verses_span = array();

isset($_GET["translation"]) ? $translation = $_GET["translation"] : $translation = "rbo";
isset($_GET["book"]) ? $book_num = ((int) htmlspecialchars($_GET["book"]) - 1) : $book_num = 0;
isset($_GET["chapter"]) ? $chapter = (int) htmlspecialchars($_GET["chapter"]) : $chapter = 0;
isset($_GET["verse"]) ? $verse =  htmlspecialchars($_GET["verse"]) : $verse = 0;
if (str_contains($verse, ",")) $verses = explode(",", $verse);
if (str_contains($verse, "-")) $verse_span = explode("-", $verse);
if (isset($verse_span) && count($verse_span) > 0) {
    for ($i = $verse_span[0]; $i <= $verse_span[1]; $i++) {
        array_push($verses, $i);
    }
}

// Start Errors ...

if (isset($books_arr[$book_num])) {
    $book = $books_arr[$book_num];
} else {
    $array = array(
        "result" => false,
        "data" => "Error! Unknown book"
    );
    echo json_encode($array);
    exit;
}

if (!isset($bible[$book][$chapter]) && $chapter !== 0) {
    $array = array(
        "result" => false,
        "data" => "Error! Unknown chapter."
    );
    echo json_encode($array);
    exit;
}
// End Errors ...

if (count($verses) == 0) array_push($verses, $verse);

if ($chapter == 0) {
    $array = $bible[$book];
    if ($is_info) {
        $info = array(
            "translation" => $trans,
            "book" => $book
        );
        $array["info"] = $info;
    }
    echo json_encode($array);
    exit;
}

if ($chapter != 0 && $verses[0] == 0) {
    $array = $bible[$book][$chapter];
    if ($is_info) {
        $info = array(
            "translation" => $trans,
            "book" => $book,
            "chapter" => $chapter,
        );
        $array["info"] = $info;
    }
    echo json_encode($array);
    exit;
}

if (isset($verses) && count($verses) > 0) {
    $array = array();
    foreach ($verses as $ver) {
        if (isset($bible[$book][$chapter][$ver])) {
            $array[$ver] = $bible[$book][$chapter][$ver];
        } else {
            $array[$ver] = null;
        }
    }
    if ($is_info) {
        $info = array(
            "translation" => $trans,
            "book" => $book,
            "chapter" => $chapter,
            "verse" => implode(",", $verses)
        );
        $array["info"] = $info;
    }
    echo json_encode($array);
    exit;
} else {
    $array = array(
        "result" => false,
        "data" => "Error! Unknown parameters."
    );
    echo json_encode($array);
    exit;
}

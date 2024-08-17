<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header("Access-Control-Allow-Headers: X-Requested-With");
header('Content-Type: application/json; charset=utf-8');


if (isset($_GET["translation"]) && $_GET["translation"] == "rst") {
    $url_rst = __DIR__ . "/json/rst-new.json";
    $rst = file_get_contents($url_rst);
    $bible = json_decode($rst, TRUE);
} else {
    $url_rbo = __DIR__ . "/json/rbo-new.json";
    $rbo = file_get_contents($url_rbo);
    $bible = json_decode($rbo, TRUE);
}

$verses = array();
$verses_span = array();

$books_arr = ["Бытие", "Исход", "Левит", "Числа", "Второзаконие", "Иисус Навин", "Судьи", "Руфь", "1 Царств", "2 Царств", "3 Царств", "4 Царств", "1 Паралипоменон", "2 Паралипоменон", "Ездра", "Неемия", "Есфирь", "Иов", "Псалтирь", "Притчи", "Екклесиаст", "Песня Песней", "Исаия", "Иеремия", "Плач Иеремии", "Иезекииль", "Даниил", "Осия", "Иоиль", "Амос", "Авдий", "Иона", "Михей", "Наум", "Аввакум", "Софония", "Аггей", "Захария", "Малахия", "Матфей", "Марк", "Лука", "Иоанн", "Деяния", "Иаков", "1 Петра", "2 Петра", "1 Иоанна", "2 Иоанна", "3 Иоанна", "Иуда", "Римлянам", "1 Коринфянам", "2 Коринфянам", "Галатам", "Ефесянам", "Филиппийцам", "Колоссянам", "1 Фессалоникийцам", "2 Фессалоникийцам", "1 Тимофею", "2 Тимофею", "Титу", "Филимону", "Евреям", "Откровение"];

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
    // echo $book;
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
    echo json_encode($bible[$book]);
    exit;
}

if ($chapter != 0 && $verses[0] == 0) {
    echo json_encode($bible[$book][$chapter]);
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

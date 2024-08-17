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

$books_arr = ["Бытие", "Исход", "Левит", "Числа", "Второзаконие", "Иисус Навин", "Судьи", "Руфь", "1 Царств", "2 Царств", "3 Царств", "4 Царств", "1 Паралипоменон", "2 Паралипоменон", "Ездра", "Неемия", "Есфирь", "Иов", "Псалтирь", "Притчи", "Екклесиаст", "Песня Песней", "Исаия", "Иеремия", "Плач Иеремии", "Иезекииль", "Даниил", "Осия", "Иоиль", "Амос", "Авдий", "Иона", "Михей", "Наум", "Аввакум", "Софония", "Аггей", "Захария", "Малахия", "Матфей", "Марк", "Лука", "Иоанн", "Деяния", "Иаков", "1 Петра", "2 Петра", "1 Иоанна", "2 Иоанна", "3 Иоанна", "Иуда", "Римлянам", "1 Коринфянам", "2 Коринфянам", "Галатам", "Ефесянам", "Филиппийцам", "Колоссянам", "1 Фессалоникийцам", "2 Фессалоникийцам", "1 Тимофею", "2 Тимофею", "Титу", "Филимону", "Евреям", "Откровение"];

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

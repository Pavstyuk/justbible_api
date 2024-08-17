<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header("Access-Control-Allow-Headers: X-Requested-With");

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

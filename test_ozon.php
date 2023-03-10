<?php

$path = 'ftp://lexizli:swwlexizli!@77.222.54.50/html/sww.com.ru/stock/stock_out.csv';
if (file_exists($path)) {
    echo "\nОстатки склада последний раз выгружены: " . date("F d Y H:i:s.", filemtime($path) - 10800) . "\n";
    echo "\nТекущее время: " . date("F d Y H:i:s.", time()) . "\n";
}
$path = 'ftp://lexizli:swwlexizli!@77.222.54.50/html/sww.com.ru/syncro_ozon_v0/work_files/ozon_stock.csv';
if (file_exists($path)) {
    echo "\nОстатки Озона последний раз выгружены: " . date("F d Y H:i:s.", filemtime($path) - 10800) . "\n";
}

$ozonStockIn = array();
$ozonOut = array();
$tikhvinStock = array();

/*
Формат записи (строки с товарами) в выгрузке остатков Озона

"2000470400018";"57181371";"241715497";"Комбинезон 888R-PP-15";"0";"0";"0";"0";"0";"0"

интересуют: 
            Первый столбец — артикул (штрихкод) по которому будем синхронизировать   тут —> 2000470400018
            шестой столбец — текущий остаток 
            седьмой столбец - резерв от текущего остатка в предыдущем столбце
            восьмой столбец — остатки в Москве
            девятый столбец - резерв в Москве
*/
function getOzonStockData(&$arrayWithOzonStock) // without $fileName temporary
{
    $arrayInput = array();
    $arrayWithOzonStock = array();
    echo "Reading Ozon stock data\n\n";
    $filename = 'ftp://lexizli:swwlexizli!@77.222.54.50/html/sww.com.ru/syncro_ozon_v0/work_files/ozon_stock.csv';  // temporary
    $arrayInput = file($filename);
    foreach ($arrayInput as $tempBuf) {
        $tempBuffParts = explode(";", $tempBuf);
        $arrayWithOzonStock[] = str_replace(
            '"',
            "",
            $tempBuffParts[0] . ";"
                . $tempBuffParts[6] . ";"
                . $tempBuffParts[7] . ";"
                . $tempBuffParts[8] . ";"
                . trim($tempBuffParts[9]) . ";"
                . $tempBuffParts[3] . "\n"
        );
    }
    return $arrayWithOzonStock;
}

function getSwwTikhvinData(&$arrayTikhvinStock)
{
    $arrayInput = array();
    $arrayTikhvinStock = array();
    echo "Reading SWW stock data\n\n";
    $filename = 'ftp://lexizli:swwlexizli!@77.222.54.50/html/sww.com.ru/stock/stock_out.csv';  // temporary
    $arrayInput = file($filename);
    $i = 0;
    foreach ($arrayInput as $tempBuf) {
        if (strpos($tempBuf, ";") > 0) {
            $tempBuffParts = explode(";", $tempBuf);
            if(strlen(trim($tempBuffParts[2])) > 5) 
            {
                $arrayTikhvinStock[] = trim($tempBuffParts[2]) . ";" . $tempBuffParts[1] . " \n";;
            }
        } else continue;
    }
    return $arrayTikhvinStock;
}

/*
$ozonStockIn = getOzonStockData($ozonStockIn);
foreach($ozonStockIn as $test) {
    echo $test;
}
*/
$tikhvinStock = getSwwTikhvinData($tikhvinStock);
foreach ($tikhvinStock as $test) {
    echo $test;
}

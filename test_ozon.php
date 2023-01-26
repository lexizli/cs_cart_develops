<?php

$path = 'ftp://lexizli:swwlexizli!@77.222.54.50/html/sww.com.ru/stock/stock_out.csv';
if (file_exists($path)) {
echo "\nОстатки склада последний раз выгружены: " . date("F d Y H:i:s.", filemtime($path)-10800)."\n";
echo "\nТекущее время: " . date("F d Y H:i:s.", time())."\n";
}
$path = 'ftp://lexizli:swwlexizli!@77.222.54.50/html/sww.com.ru/syncro_ozon_v0/work_files/ozon_stock.csv';
if (file_exists($path)) {
echo "\nОстатки Озона последний раз выгружены: " . date("F d Y H:i:s.", filemtime($path)-10800)."\n";
}

$ozonStockIn = array();
$ozonOut = array();
$tikhvinStock = array();

function getOzonStockData(&$arrayWithOzonStock) // without $fileName temporary
/*
Формат записи (строки с товарами)

"2000470400018";"57181371";"241715497";"Комбинезон 888R-PP-15";"0";"0";"0";"0";"0";"0"

интересуют: 
            Первый столбец — артикул (штрихкод) по которому будем синхронизировать   тут —> 2000470400018
            шестой столбец — текущий остаток 
            седьмой столбец - резерв от текущего остатка в предыдущем столбце
            восьмой столбец — остатки в Москве
            девятый столбец - резерв в Москве
*/
{
    $arrayInput = array();
    $arrayWithOzonStock = array();
    echo "Reading Ozon stock data\n";
    $filename = 'ftp://lexizli:swwlexizli!@77.222.54.50/html/sww.com.ru/syncro_ozon_v0/work_files/ozon_stock.csv';  // temporary
    $arrayInput = file($filename);
    foreach($arrayInput as $tempBuf)
    {
        $tempBuffParts = explode(";",$tempBuf);
        $arrayWithOzonStock[] = str_replace('"',"",
            $tempBuffParts[0].";"
            .$tempBuffParts[6].";"
            .$tempBuffParts[7].";"
            .$tempBuffParts[8].";"
            .str_replace("\n","",$tempBuffParts[9]).";"
            .$tempBuffParts[3]."\n");     
    }
    return $arrayWithOzonStock;
}

$ozonStockIn = getOzonStockData($ozonStockIn);
foreach($ozonStockIn as $test) {
    echo $test;
}

?>

<?php
// На сервері зберігається список Товарів (Id, Назва, Країна
// виробника, Ціна). Розробити Web сторінку для списку товарів. Розмістити
// на сторінці кнопки для сортування товарів у алфавітному порядку та у
// протилежному.

$goods = [
    
    [ 'id' => 1, 'name' => 'candle', 'country' => 'Ireland', 'price' => 5 ],
    [ 'id' => 2, 'name' => 'light bulb', 'country' => 'China',   'price' => 6 ],
    [ 'id' => 3, 'name' => 'chair', 'country' => 'China',   'price' => 50 ],
    [ 'id' => 4, 'name' => 'table', 'country' => 'Poland',  'price' => 100 ],
    [ 'id' => 5, 'name' => 'watch', 'country' => 'Switzerland','price' => 300 ],
    [ 'id' => 6, 'name' => 'bed', 'country' => 'China', 'price' => 200 ],
    [ 'id' => 7, 'name' => 'vyshyvanka', 'country' => 'Ukraine', 'price' => 120 ]
];


function SaveGoodsToCSV($goods){

    $fp = fopen('goods_data.csv','w');

    fputcsv($fp, ['ID','Name', 'Country', 'Price']);

    foreach ($goods as $good) {
        fputcsv($fp,$good);
    }
    fclose($fp);
}
SaveGoodsToCSV($goods);


if($_SERVER["REQUEST_METHOD"] === "GET" ){
    if($_GET['order-alph']){
        global $goods;
        usort($goods, function ($item1, $item2) {
            return $item1['name'] <=> $item2['name'];
        });
    }
}
if($_SERVER["REQUEST_METHOD"] === "GET" ){
    if($_GET['order-dis-alph']){
        global $goods;
        usort($goods, function ($item1, $item2) {
            return $item2['name'] <=> $item1['name'];
        });
    }
}

include 'table.phtml';

?>
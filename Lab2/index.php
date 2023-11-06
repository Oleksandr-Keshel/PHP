<?php
// 9.  Асоціативний масив “Аукціон” (
// Код, назва лота, дата початку торгів, дата завершення торгів, стартова ціна, кінцева ціна). 
// Запит торгів за вказану дату із початковою ціною що не перевищує Х.

function getAuctionsFromFile(){
    $auctionsJSON = file_get_contents("auctions_data.json");
    $auctions = json_decode($auctionsJSON, true);
    return $auctions;
}

$auctions = getAuctionsFromFile();

// $auction = [
//     'code'=> null,
//     'lotName'=> null,
//     'startDate'=> null,
//     'finishDate'=>null,
//     'startPrice'=> null,
//     'finalPrice'=> null
// ];

// $auctions = [
//     ['code'=> 1,
//     'lotName'=> 'Car Tesla',
//     'startDate'=> '2023-03-01',
//     'finishDate'=>'2023-03-31',
//     'startPrice'=> 100000,
//     'finalPrice'=> 900000
//     ],

//     ['code'=> 2,
//     'lotName'=> 'Ocean House',
//     'startDate'=> '2023-03-01',
//     'finishDate'=>'2023-03-15',
//     'startPrice'=> 1000000,
//     'finalPrice'=> 9000000
//     ],

//     ['code'=> 3,
//     'lotName'=> 'Painting',
//     'startDate'=> '2023-06-01',
//     'finishDate'=>'2023-07-11',
//     'startPrice'=> 300,
//     'finalPrice'=> 1200
//     ],

//     ['code'=> 4,
//     'lotName'=> 'Chinese porcelain vase',
//     'startDate'=> '2022-10-04',
//     'finishDate'=>'2022-12-26',
//     'startPrice'=> 16000,
//     'finalPrice'=> 50000
//     ],

//     ['code'=> 5,
//     'lotName'=> 'The Most Ordinary Chair',
//     'startDate'=> '2023-06-02',
//     'finishDate'=>'2023-09-26',
//     'startPrice'=> 500,
//     'finalPrice'=> 9999999
//     ],

//     ['code'=> 6,
//     'lotName'=> 'Nike\'s CR7 Football Boots',
//     'startDate'=> '2023-05-26',
//     'finishDate'=>'2023-08-16',
//     'startPrice'=> 1300,
//     'finalPrice'=> 2070
//     ]
// ];



// function for selecting all array elements that match the query
function getByDate_StartPrice($auctions, $date, $maxStartPrice) {  
    echo '
    <br>
    <br>

    <h2>Filtered array of auctions</h2>

    <table border="1">
    <tr>
        <th>Code</th>
        <th>Lot Name</th>
        <th>Start Date</th>
        <th>Finish Date</th>
        <th>Start Price</th>
        <th>Final Price</th>
    </tr>
    ';
    foreach ($auctions as $auction){
        if( $date >= $auction['startDate'] && $date <= $auction['finishDate'] &&
            $maxStartPrice >= $auction['startPrice'] ){
            echo '
                <tr>
                    <td>' .$auction['code'] . '</td>
                    <td>' .$auction['lotName']. '</td>
                    <td>' .$auction['startDate']. '</td>
                    <td>' .$auction['finishDate']. '</td>
                    <td>' .$auction['startPrice']. '</td>
                    <td>' .$auction['finalPrice']. '</td>
                </tr>
            ';
        }
    
    }
    echo '</table>';
    
};

// $filtered_aucts = getByDate_StartPrice($auctions,"2023-03-05", 10000000);        //Example of using query

// selecting all array elements that match the query using status bar
if(isset($_GET['date']) && isset($_GET['maxStartPrice'])){      // (ex:   index.php?date=2023-06-09&maxStartPrice=1000 )
    $date = $_GET['date'];
    $maxStartPrice = intval($_GET['maxStartPrice']);
    $filtered_aucts = getByDate_StartPrice($auctions, $date, $maxStartPrice);
}

// form for adding auction
include 'templates/auction_form.phtml';
if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])){
    $code = count($auctions) + 1;
    $lotName = $_POST['lotName'] ?? '';
    $startDate = $_POST['startDate'] ?? '';
    $finishDate = $_POST['finishDate'] ?? '';
    $startPrice = $_POST['startPrice'] ?? '';
    $finalPrice = $_POST['finalPrice'] ?? '';


    $newAuct = ['code'=> $code,
                'lotName'=> $lotName,
                'startDate'=> $startDate,
                'finishDate'=> $finishDate,
                'startPrice'=> $startPrice,
                'finalPrice'=> $finalPrice
    ];
    array_push($auctions, $newAuct);

}
  


// form for editing auction
include 'templates/auction_form_change.phtml';
if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['edit-code'])){

    $editCode = (int)$_POST['edit-code'];
    $auctToEdit = getAuctionToEdit($auctions, $editCode);
    if($auctToEdit){
        ?>
        <br>
        <form action="index.php" method="POST">
            <input  id="edit-code" name="edit-code" value="<?= $editCode;?>" type="hidden">
            <label for="edit-lotName">Lot Name</label>
            <input id="edit-lotName" name="edit-lotName" value="<?= $auctToEdit['lotName'];?>" type="text">

            <br />
            <label for="edit-startDate">Start Date</label>
            <input id="edit-startDate" name="edit-startDate" value="<?= $auctToEdit['startDate'];?>" type="text">

            <br />
            <label for="edit-finishDate">Finish Date</label>
            <input id="edit-finishDate" name="edit-finishDate" value="<?= $auctToEdit['finishDate'];?>" type="text">

            <br />
            <label for="edit-startPrice">Start Price</label>
            <input id="edit-startPrice" name="edit-startPrice" value="<?= $auctToEdit['startPrice'];?>" type="text">

            <br />
            <label for="edit-finalPrice">Final Price</label>
            <input id="edit-finalPrice" name="edit-finalPrice" value="<?= $auctToEdit['finalPrice'];?>" type="text">

            <br />

            <button type="submit" name="change-auction">Change</button>
        </form>
        <?php
        if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['change-auction'])){

            $editCode = test_input($_POST['edit-code']); 
            $editLotName = test_input($_POST['edit-lotName']);
            $editStartDate = test_input($_POST['edit-startDate']);
            $editFinishDate = test_input($_POST['edit-finishDate']);
            $editStartPrice = test_input($_POST['edit-startPrice']);
            $editFinalPrice = test_input($_POST['edit-finalPrice']);
        
            if(empty($editLotName) ||empty($editStartDate)||empty($editFinishDate)||empty($editStartPrice)||
            empty($editFinalPrice)){
                return false;
            }
        
            foreach ($auctions as &$auction) {
                
                if($auction['code'] == $editCode){

                    $auction['lotName'] = $editLotName;
                    $auction['startDate'] = $editStartDate;
                    $auction['finishDate'] = $editFinishDate;
                    $auction['startPrice'] = $editStartPrice;
                    $auction['finalPrice'] = $editFinalPrice;
                    unset($auction);
                    break;
                }
            }
            
        }
        
    }
    
}

function test_input($data){
    $data = trim($data);
    $data = htmlspecialchars($data);
    return $data;
}

function getAuctionToEdit($auctions, $editCode) { 
    $auctToEdit = null;
    foreach($auctions as $auction){
        if($auction['code'] == $editCode){
            $auctToEdit = $auction;
            break;
        }
    }
    return $auctToEdit;
}

include 'templates/auction_table.phtml';



function saveAuctionToFile($auctions){
    $auctionsJSON = json_encode($auctions);
    file_put_contents("auctions_data.json", $auctionsJSON);
}

saveAuction($auctions);


?>
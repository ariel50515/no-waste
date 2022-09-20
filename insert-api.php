<?php 
include __DIR__ . '/parts/connect_db.php';

header('Content-Type: application/json');

$output = [
    'success' => false,
    'error' => '',
    'code' => 0,
    'postData' => $_POST, // 除錯用的
];

// if(empty($_POST['name'])){
//     $output['error'] = '參數不足';
//     $output['code'] = 400;
//     echo json_encode($output, JSON_UNESCAPED_UNICODE); 
//     exit;
// }

// TODO: 檢查欄位資料

$sql = "INSERT INTO `product_food`(
    `product_categories`, `product_description`, `unit_price`, `sale_price`, `shop_deadline`,`picture`, `created_at` 
    ) VALUES (?, ?, ?, ?, ?, ?, NOW())";

$stmt = $pdo->prepare($sql);

try {
    $stmt->execute([
        $_POST['categories'],
        $_POST['description'],
        $_POST['price'],
        $_POST['sale_price'],
        $_POST['shop_deadline'],
        $_POST['picture'],
    ]);
} catch(PDOException $ex) {
    $output['error'] = $ex->getMessage();
}


if($stmt->rowCount()){
    $output['success'] = true;
} else {
    if(empty($output['error']))
        $output['error'] = '資料沒有新增';

}




echo json_encode($output, JSON_UNESCAPED_UNICODE); 
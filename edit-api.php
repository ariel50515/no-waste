<?php 
require __DIR__ . '/parts/admin-required.php';
require __DIR__ . '/parts/connect_db.php';

header('Content-Type: application/json');

$output = [
    'success' => false,
    'error' => '',
    'code' => 0,
    'postData' => $_POST, // 除錯用的
];

// if(empty($_POST['name'])){
//     $output['error'] = '修改資料參數不足';
//     $output['code'] = 400;
//     echo json_encode($output, JSON_UNESCAPED_UNICODE); 
//     exit;
// }
// TODO: 檢查欄位資料

$sql = "UPDATE `food_product` SET 
`product_categories`=?,
`product_name`=?,
`product_description`=?,
`unit_price`=?,
`sale_price`=?
WHERE sid=?";

$stmt = $pdo->prepare($sql);

try {
    $stmt->execute([
        $_POST['product_categories'],
        $_POST['product_name'],
        $_POST['product_description'],
        $_POST['unit_price'],
        $_POST['sale_price'],
        $_POST['sid']
    ]);
} catch(PDOException $ex) {
    $output['error'] = $ex->getMessage();
}

if($stmt->rowCount()){
    $output['success'] = true;
} else {
    if(empty($output['error']))
        $output['error'] = '資料沒有修改';
}
echo json_encode($output, JSON_UNESCAPED_UNICODE); 
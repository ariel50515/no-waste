<?php
include __DIR__ . '/parts/connect_db.php';

header('Content-Type: application/json');

// $output = [
//     'success' => false,
//     'error' => '',
//     'code' => 0,
//     'postData' => $_POST, // 除錯用的
// ];

$folder = __DIR__. '/img/';

$extMap = [
    'image/jpeg' => '.jpg',
    'image/png' => '.png',
];

$output = [
    'success' => false, 
    'error' => '',
    'data' => [],
    'files' => $_FILES, // 除錯用
];

if(empty($_FILES['picture']['name'])){
    $output['error'] = '尚未上傳圖片';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}

// 副檔名對應
$ext = $extMap[$_FILES['picture']['type']];
if(empty($ext)){
    $output['error'] = '檔案格式錯誤: 請上傳 jpeg/png';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}

// 隨機檔名
$filename = md5($_FILES['picture']['name']. uniqid()). $ext;
$output['filename'] = $filename;

if(!move_uploaded_file(
        $_FILES['picture']['tmp_name'],
        $folder . $filename
    )) {
    $output['error'] = '無法移動上傳檔案, 注意資料夾權限問題';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}

// if(empty($_POST['name'])){
//     $output['error'] = '參數不足';
//     $output['code'] = 400;
//     echo json_encode($output, JSON_UNESCAPED_UNICODE); 
//     exit;
// }
// TODO: 檢查欄位資料

$sql = "INSERT INTO `food_product`(
    `product_categories`, `product_description`, `unit_price`, `sale_price`, `created_at` 
    ) VALUES (?, ?, ?, ?, NOW())";

$sql2 = "INSERT INTO `picture`(
    `picture_url`) VALUES(?)";

$stmt = $pdo->prepare($sql);
$stmt2 = $pdo->prepare($sql2);

try {
    $stmt->execute([
        $_POST['categories'],
        $_POST['product_description'],
        $_POST['price'],
        $_POST['sale_price'],
    ]);
} catch (PDOException $ex) {
    $output['error'] = $ex->getMessage();
}

// echo print_r($stmt);
// exit;

try {
    $stmt2->execute([
        $filename
    ]);
} catch (PDOException $ex) {
    $output['error'] = $ex->getMessage();
}

if ($stmt->rowCount()) {
    $output['success'] = true;
} else {
    if (empty($output['error']))
        $output['error'] = '資料沒有新增';
}



if ($stmt->rowCount()) {
    $output['success'] = true;
} else {
    if (empty($output['error']))
        $output['error'] = '資料沒有新增';
}


echo json_encode($output, JSON_UNESCAPED_UNICODE);

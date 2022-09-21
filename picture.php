<?php 
require __DIR__.'/parts/connect_db.php';

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


if(! 
    move_uploaded_file(
        $_FILES['picture']['tmp_name'],
        $folder. $filename
    )
) {
    $output['error'] = '無法移動上傳檔案, 注意資料夾權限問題';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}

if(empty($_POST['name']) OR empty($_POST['address'])){
    $output['error'] = '沒有足夠資訊';
    $output['code'] = 400;
    echo json_encode($output,JSON_UNESCAPED_UNICODE);
    exit;
}

$sql = "INSERT INTO `store`(
    `store_name`,`store_address`,`store_img`
)VALUES(?,?,?)";

$stmt = $pdo->prepare($sql);


try{
$stmt->execute([
    $_POST['name'],
    $_POST['address'],
    $filename,
]);
} catch(PDOException $ex){
    $output['error'] =$ex->getMessage();
}

if($stmt->rowCount()){
    $output['success']= true;
}else{
    $output['error'] = '資料庫沒有新增';
}

echo json_encode($output,JSON_UNESCAPED_UNICODE);
?>
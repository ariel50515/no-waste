<?php
require __DIR__. '/parts/connect_db.php';

$pageName = 'card-list-admin';
// $pKeys = array_keys($_SESSION['cart']);

$rows = []; // 預設值
$data_ar = []; // dict

if(!empty($pKeys)) {
    $sql = sprintf("SELECT * FROM food_product WHERE sid IN(%s)", implode(',', $pKeys));
    $rows = $pdo->query($sql)->fetchAll();

    foreach($rows as $r){
        $r['quantity'] = $_SESSION['cart'][$r['sid']];
        $data_ar[$r['sid']] = $r;
    }
}

?>
<?php include __DIR__ . '/parts/html-head.php'; ?>
<?php include __DIR__ . '/parts/navbar.php'; ?>

<div class="container">
<div class="row">
    <div class="col">
        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <!-- <th scope="col"><i class="fas fa-trash-alt"></i></th> -->
                <th scope="col">商品編號</th>
                <th scope="col">商品照</th>
                <th scope="col">商品類別</th>
                <th scope="col">商品名</th>
                <th scope="col">定價</th>
                <th scope="col">折數</th>
                <th scope="col">取餐時間</th>
            </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $r) : ?>
                    <tr>
                        <td>
                            <a href="javascript: delete_it(<?= $r['sid'] ?>)">
                                <i class="fa-solid fa-trash-can"></i>
                            </a>
                        </td>
                        <td><?= $r['sid'] ?></td>
                        <td><?= $r['picture'] ?></td>
                        <td><?= $r['product_categories'] ?></td>
                        <td><?= $r['product_name'] ?></td>
                        <td><?= $r['unit_price'] ?></td>
                        <td><?= $r['sale_price'] ?></td>
                        <td><?= $r['shop_deadline'] ?></td>
                        <!--
                        <td><?= strip_tags($r['food_pruduct']) ?></td>
                        -->
                        <td><?= htmlentities($r['food_pruduct']) ?></td>
                        <td>
                            <a href="edit-form.php?sid=<?= $r['sid'] ?>">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
    </div>
</div>
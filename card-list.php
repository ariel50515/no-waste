<?php
require __DIR__ . '/parts/connect_db.php';
$pageName = 'list';

$perPage = 20; // 一頁有幾筆
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// 算總筆數
$t_sql = "SELECT COUNT(1) FROM food_product";
$totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0];
$totalPages = ceil($totalRows / $perPage);
$rows = [];

// 如果有資料
if ($totalRows) {
    if ($page < 1) {
        header('Location: ?page=1');
        exit;
    }
    if ($page > $totalPages) {
        header('Location: ?page=' . $totalPages);
        exit;
    }

    $sql = sprintf(
        "SELECT `food_product`.*, `shop_list`.*,`picture`.`picture_url`,`food_category`.*
        FROM `food_product`
        JOIN `shop_list` 
        ON `food_product`.`sid`=`shop_list`.`sid`

        JOIN `picture` 
        ON `food_product`.`picture_sid`=`picture`.`sid` 

        JOIN `food_category` 
        ON `food_product`.`product_categories_sid`=`food_category`.`sid` 
        
        ORDER BY `food_product`.`sid` ASC LIMIT %s, %s",
        ($page - 1) * $perPage,
        $perPage
    );
    $rows = $pdo->query($sql)->fetchAll();
}

$output = [
    'totalRows' => $totalRows,
    'totalPages' => $totalPages,
    'page' => $page,
    'rows' => $rows,
    'perPage' => $perPage,
];
// echo json_encode($output); exit;

?>
<?php include __DIR__ . '/parts/html-head.php'; ?>
<?php include __DIR__ . '/parts/navbar.php'; ?>
<div class="container">
    <div class="row">
        <div class="col">
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li class="page-item <?= 1 == $page ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $page - 1 ?>">
                            <i class="fa-solid fa-circle-arrow-left"></i>
                        </a>
                    </li>

                    <?php for ($i = $page - 5; $i <= $page + 5; $i++) :
                        if ($i >= 1 and $i <= $totalPages) :
                    ?>
                            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                            </li>
                    <?php
                        endif;
                    endfor; ?>

                    <li class="page-item <?= $totalPages == $page ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $page + 1 ?>">
                            <i class="fa-solid fa-circle-arrow-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th scope="col">
                            <i class="fas fa-trash-alt"></i>
                        </th>
                        <th scope="col">商品編號</th>
                        <th scope="col">商品照</th>
                        <th scope="col">商家名稱</th>
                        <th scope="col">商品類別</th>
                        <th scope="col">商品名</th>
                        <th scope="col">定價</th>
                        <th scope="col">折數</th>
                        <th scope="col">取餐時間</th>
                        <th scope="col">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </th>
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

                            <td>
                                <img src="./img/<?= $r['picture_url'] ?>" alt="" style="width:150px;">

                            </td>
                            <td><?= $r['shop_name'] ?></td>
                            <td><?= $r['product_categories'] ?></td>
                            <td><?= $r['product_name'] ?></td>
                            <td><?= $r['unit_price'] ?></td>
                            <td><?= $r['sale_price'] ?></td>
                            <td><?= $r['shop_deadline'] ?></td>
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
    <?php include __DIR__ . '/parts/scripts.php'; ?>
    <script>
        const table = document.querySelector('table');

        function delete_it(sid) {
            if (confirm(`確定要刪除編號為 ${sid} 的資料嗎?`)) {
                location.href = `delete.php?sid=${sid}`;
            }
        }
    </script>
    <?php include __DIR__ . '/parts/html-foot.php'; ?>
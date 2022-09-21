<?php
require __DIR__ . '/parts/admin-required.php';
include __DIR__ . '/parts/connect_db.php';
$pageName = 'edit';

$sid = isset($_GET['sid']) ? intval($_GET['sid']) : 0;
if (empty($sid)) {
    header('Location: card-list.php');
    exit;
}

$sql = "SELECT * FROM food_product WHERE sid = $sid";
$r = $pdo->query($sql)->fetch();
if (empty($r)) {
    header('Location: card-list.php');
    exit;
}

?>
<?php require __DIR__ . '/parts/html-head.php'; ?>
<?php include __DIR__ . '/parts/navbar.php'; ?>
<div class="container">
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">修改商品資料</h5>
                    <img src="" alt="" id="img" style="width: 300px;">
                    <form name="form1" onsubmit="checkForm(); return false;" novalidate>
                        <div class="mb-3">
                            <label for="picture" class="form-label"></label>
                            <input type="file" class="form-control" id="picture" name="picture" accept="image/png,image/jpeg">
                        </div>
                        <div class="mb-3">
                            <label for="sid" class="form-label">商品編號</label>
                            <input type="text" class="form-control" id="sid" name="sid" value="<?= ($r['sid']) ?> " disabled>
                        </div>
                        <div class="mb-3">
                            <label for="product_categories" class="form-label">商品類別</label>
                            <input type="text" class="form-control" id="product_categories" name="product_categories" value="<?= $r['product_categories'] ?>">
                        </div>
                        <div class="mb-3">
                            <label for="product_name" class="form-label">商品名</label>
                            <input type="text" class="form-control" id="product_name" name="product_name" value="<?= $r['product_name'] ?>">
                        </div>
                        <div class="mb-3">
                            <label for="product_description" class="fproduct_description">商品敘述</label>
                            <textarea class="form-control" name="product_description" id="product_description" cols="50" rows="4"><?= $r['product_description'] ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="unit_price" class="form-label">定價</label>
                            <input type="text" class="form-control" id="unit_price" name="unit_price" value="<?= $r['unit_price'] ?>">
                        </div>
                        <div class="mb-3">
                            <label for="sale_price" class="form-label">折數</label>
                            <input type="text" class="form-control" id="sale_price" name="sale_price" value="<?= $r['sale_price'] ?>">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
<?php include __DIR__ . '/parts/scripts.php'; ?>
<script>
    function checkForm() {
        // document.form1.email.value

        const fd = new FormData(document.form1);

        // for (let k of fd.keys()) {
        //     console.log(`${k}: ${fd.get(k)}`);
        // }
        // TODO: 檢查欄位資料

        fetch('edit-api.php', {
            method: 'POST',
            body: fd
        }).then(r => r.json()).then(obj => {
            console.log(obj);
            if (!obj.success) {
                alert(obj.error);
            } else {
                alert('修改成功')
                location.href = 'card-list.php';
            }
        })


    }
</script>
<?php include __DIR__ . '/parts/html-foot.php'; ?>
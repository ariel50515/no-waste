<?php
require __DIR__ . '/parts/admin-required.php';
require __DIR__ . '/parts/connect_db.php';
$pageName = 'insert';
?>
<?php require __DIR__ . '/parts/html-head.php'; ?>
<?php include __DIR__ . '/parts/navbar.php'; ?>
<div class="container">
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">商品資訊</h5>
                    <img src="" alt="" id="img" style="width: 300px; height: 500px">
                    <form name="form1" onsubmit="checkForm(); return false;">

                    <div class="mb-3">
                            <label for="picture" class="form-label"></label>
                            <input type="file" class="form-control" id="picture" name="picture">
                        </div>
                        <div class="mb-3">
                            <label for="categories" class="form-label id=" categories">商品類別
                            <option value=""></option>
                            </label>
                            <input type="text" class="form-control" id="categories" name="categories">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">商品敘述</label>
                            <textarea type="text" class="form-control" id="description" name="description" rows="4"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">定價</label>
                            <input type="text" class="form-control" id="price" name="price">
                        </div>
                        <div class="mb-3">
                            <label for="sale_price" class="form-label">折數</label>
                            <input type="text" class="form-control" id="sale_price" name="sale_price">
                        </div>
                        <div class="mb-3">
                            <label for="shop_deadline" class="form-label">取餐時間</label>
                            <input type="text" class="form-control" id="shop_deadline" name="shop_deadline">
                        </div>
                        <button type="submit" class="btn btn-primary">送出</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/parts/scripts.php'; ?>
<script>
    let img = document.querySelector("#img");
    let inp = document.querySelector("#picture");
    inp.addEventListener("change" , (evt) => {
        const file = evt.target.files[0];
        img.src = URL.createObjectURL(file)
    })

    function checkForm() {
        // document.form1.email.value

        const fd = new FormData(document.form1);

        // for (let k of fd.keys()) {
        //     console.log(`${k}: ${fd.get(k)}`);
        // }
        // // TODO: 檢查欄位資料

        fetch('insert-api.php', {
            method: 'POST',
            body: fd
        }).then(r => r.json()).then(obj => {
            console.log(obj);
            if (!obj.success) {
                alert(obj.error);
            } else {
                alert('已新增惜食商品')
                location.href = 'list.php';
            }
        })


    }
</script>
<?php include __DIR__ . '/parts/html-foot.php'; ?>
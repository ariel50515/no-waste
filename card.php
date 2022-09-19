<?php include __DIR__ . '/parts/connect_db.php';
$pageName = 'card';
?>

<?php include __DIR__ . '/parts/html-head.php'; ?>
<?php include __DIR__ . '/parts/navbar.php'; ?>
<div class="contanier"></div>
<div class="row">
    <div class="col">
        <div class="card" style="width: 18rem;">
            <img src="" class="card-img-top" alt="">
            <div class="card-body">
                <h5 class="card-title">Card title</h5>
                <p class="card-text"></p>
                <a href="#" class="btn btn-primary">Go somewhere</a>
            </div>
        </div>
    </div>

</div>
</div>
<?php include __DIR__ . '/parts/scripts.php'; ?>
<script>
    const dallorCommas = function(n) {
        return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
    };

    function removeProductItem(event) {
        event.preventDefault(); // 避免 <a> 的連結
        const tr = $(event.target).closest('tr.p-item')
        const sid = tr.attr('data-sid');

        $.get('add-to-cart-api.php', {
            sid
        }, function(data) {
            tr.remove();
            countCartObj(data);
            calPrices();
        }, 'json');
    }

    function changeQty(event) {
        let qty = $(event.target).val();
        let tr = $(event.target).closest('tr');
        let sid = tr.attr('data-sid');

        $.get('add-to-cart-api.php', {
            sid,
            qty
        }, function(data) {
            countCartObj(data);
            calPrices();
        }, 'json');

    }

    function calPrices() {
        const p_items = $('.p-item');
        let total = 0;
        if (!p_items.length) {
            alert('請先將商品加入購物車');
            location.href = 'product-list.php';
            return;
        }

        p_items.each(function(i, el) {
            console.log($(el).attr('data-sid'));
            // let price = parseInt( $(el).find('.price').attr('data-price') );
            // let price = $(el).find('.price').attr('data-price') * 1;

            const $price = $(el).find('.price'); // 價格的 <td>
            $price.text('$ ' + $price.attr('data-price'));

            const $qty = $(el).find('.quantity'); // <select> combo box
            // 如果有的話才設定
            if ($qty.attr('data-qty')) {
                $qty.val($qty.attr('data-qty'));
            }
            $qty.removeAttr('data-qty'); // 設定完就移除

            const $sub_total = $(el).find('.sub-total');

            $sub_total.text('$ ' + dallorCommas($price.attr('data-price') * $qty.val()));
            total += $price.attr('data-price') * $qty.val();
        });

        $('#totalAmount').text('$ ' + dallorCommas(total));

    }
    calPrices();
</script>

<?php include __DIR__ . '/parts/html-foot.php'; ?>
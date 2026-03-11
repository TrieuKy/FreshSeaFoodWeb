<?php ob_start(); ?>

<h1 class="page-title">🛒 Giỏ Hàng Của Bạn</h1>

<?php if (empty($cart)): ?>
<div class="empty-state">
    <div class="empty-icon">🛒</div>
    <h3>Giỏ hàng đang trống</h3>
    <p>Hãy thêm một ít hải sản tươi ngon vào giỏ nhé!</p>
    <a href="/banhaisan" class="btn btn-accent">🦐 Mua sắm ngay</a>
</div>

<?php else: ?>
<div class="cart-wrapper">
    <!-- Cart Items -->
    <div>
        <div class="table-card">
            <!-- Card Header -->
            <div class="card-header">
                <h3>Sản phẩm (<?php echo count($cart); ?> loại)</h3>
                <a href="/banhaisan/cart/clear" class="btn btn-sm btn-ghost" style="color:var(--danger);"
                   onclick="return confirm('Xóa toàn bộ giỏ hàng?')">🗑 Xóa tất cả</a>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Đơn giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart as $id => $item): ?>
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:0.75rem;">
                                <img class="cart-product-img"
                                     src="/banhaisan/public/images/<?php echo htmlspecialchars($item['image']); ?>"
                                     alt="<?php echo htmlspecialchars($item['name']); ?>"
                                     onerror="this.src='https://placehold.co/56x56/e0f4f4/0a7075?text=🦐'">
                                <div>
                                    <div style="font-weight:700;color:var(--text-dark);"><?php echo htmlspecialchars($item['name']); ?></div>
                                    <div style="font-size:0.78rem;color:var(--text-light);">Hải sản tươi</div>
                                </div>
                            </div>
                        </td>
                        <td style="color:var(--danger);font-weight:700;"><?php echo number_format($item['price'], 0, ',', '.'); ?>đ</td>
                        <td>
                            <div class="cart-qty">
                                <button onclick="updateQty(<?php echo $id; ?>, -1)">−</button>
                                <input type="number" value="<?php echo $item['quantity']; ?>" min="1" max="99"
                                       id="qty-<?php echo $id; ?>"
                                       onchange="setQty(<?php echo $id; ?>, this.value)">
                                <button onclick="updateQty(<?php echo $id; ?>, 1)">+</button>
                            </div>
                        </td>
                        <td style="font-weight:800;color:var(--text-dark);"><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?>đ</td>
                        <td>
                            <a href="/banhaisan/cart/delete/<?php echo $id; ?>" class="btn btn-sm btn-danger"
                               onclick="return confirm('Xóa sản phẩm này?')">✕</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div style="margin-top:1rem;">
            <a href="/banhaisan" class="btn btn-ghost">← Tiếp tục mua hàng</a>
        </div>
    </div>

    <!-- Order Summary -->
    <div class="order-summary-card">
        <h3>📋 Tóm Tắt Đơn Hàng</h3>

        <?php 
            $subtotal = 0;
            foreach ($cart as $item) $subtotal += $item['price'] * $item['quantity'];
            $shipping = $subtotal >= 500000 ? 0 : 30000;
            $total = $subtotal + $shipping;
        ?>

        <div class="summary-line">
            <span>Tạm tính</span>
            <span><?php echo number_format($subtotal, 0, ',', '.'); ?>đ</span>
        </div>
        <div class="summary-line">
            <span>Phí giao hàng</span>
            <span>
                <?php if ($shipping === 0): ?>
                    <span style="color:var(--success);font-weight:600;">Miễn phí 🎉</span>
                <?php else: ?>
                    <?php echo number_format($shipping, 0, ',', '.'); ?>đ
                <?php endif; ?>
            </span>
        </div>
        <?php if ($shipping > 0): ?>
        <div style="font-size:0.78rem;color:var(--text-light);margin-bottom:0.75rem;background:var(--bg-light);padding:0.5rem 0.75rem;border-radius:var(--radius-sm);">
            Mua thêm <strong style="color:var(--primary);"><?php echo number_format(500000 - $subtotal, 0, ',', '.'); ?>đ</strong> để được miễn phí ship
        </div>
        <?php endif; ?>

        <div class="summary-line total">
            <span>Tổng cộng</span>
            <span><?php echo number_format($total, 0, ',', '.'); ?>đ</span>
        </div>

        <a href="/banhaisan/cart/checkout" class="btn btn-accent btn-block" style="margin-top:1.2rem;font-size:1rem;padding:0.85rem;">
            💳 Tiến hành thanh toán
        </a>

        <div style="margin-top:1rem;text-align:center;font-size:0.8rem;color:var(--text-light);">
            🔒 Thanh toán an toàn · 🚚 Giao trong 2 giờ
        </div>
    </div>
</div>

<script>
function updateQty(id, delta) {
    const input = document.getElementById('qty-' + id);
    let val = parseInt(input.value) + delta;
    if (val < 1) val = 1;
    window.location.href = '/banhaisan/cart/update/' + id + '?qty=' + val;
}
function setQty(id, val) {
    val = Math.max(1, parseInt(val) || 1);
    window.location.href = '/banhaisan/cart/update/' + id + '?qty=' + val;
}
</script>
<?php endif; ?>

<?php $content = ob_get_clean(); include __DIR__ . '/../layout.php'; ?>

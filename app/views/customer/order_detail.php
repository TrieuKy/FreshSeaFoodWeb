<?php ob_start(); $pageTitle = 'Chi Tiết Đơn Hàng'; ?>

<?php
$statusMap = [
    'pending'   => ['label'=>'Chờ xác nhận','color'=>'#fff3cd','text'=>'#856404','icon'=>'⏳'],
    'confirmed' => ['label'=>'Đã xác nhận', 'color'=>'#d4edda','text'=>'#155724','icon'=>'✅'],
    'shipping'  => ['label'=>'Đang giao',   'color'=>'#cce5ff','text'=>'#004085','icon'=>'🚚'],
    'delivered' => ['label'=>'Đã giao',     'color'=>'#d4f5e2','text'=>'#0f6b35','icon'=>'🎉'],
    'cancelled' => ['label'=>'Đã hủy',      'color'=>'#fde8e8','text'=>'#9b1c1c','icon'=>'❌'],
];
$s = $statusMap[$order['status']] ?? $statusMap['pending'];
?>

<div style="max-width:720px;margin:0 auto;">
    <a href="/banhaisan/customer/profile" style="color:var(--primary);font-size:0.88rem;">← Lịch sử đơn hàng</a>
    <div style="display:flex;justify-content:space-between;align-items:center;margin:0.75rem 0 1.5rem;">
        <h1 style="font-size:1.3rem;font-weight:800;">Đơn hàng #<?php echo str_pad($order['id'],5,'0',STR_PAD_LEFT); ?></h1>
        <span style="padding:0.35rem 1rem;border-radius:50px;font-weight:700;font-size:0.85rem;background:<?php echo $s['color']; ?>;color:<?php echo $s['text']; ?>;">
            <?php echo $s['icon']; ?> <?php echo $s['label']; ?>
        </span>
    </div>

    <div class="card" style="margin-bottom:1rem;">
        <h3 style="font-size:0.9rem;font-weight:700;margin-bottom:1rem;color:var(--text-dark);">Sản Phẩm Đã Đặt</h3>
        <?php foreach ($order['items'] as $item): ?>
        <div style="display:flex;justify-content:space-between;align-items:center;padding:0.6rem 0;border-bottom:1px solid var(--border-light);font-size:0.88rem;">
            <span><?php echo htmlspecialchars($item['product_name']); ?> <span style="color:var(--text-light);">x<?php echo $item['quantity']; ?></span></span>
            <strong><?php echo number_format($item['quantity']*$item['unit_price'],0,',','.'); ?>đ</strong>
        </div>
        <?php endforeach; ?>

        <div style="margin-top:0.75rem;display:flex;flex-direction:column;gap:0.4rem;align-items:flex-end;font-size:0.88rem;">
            <?php $sub = $order['total_price'] - $order['shipping_fee'] + $order['discount_amount']; ?>
            <div style="display:flex;gap:2rem;color:var(--text-light);"><span>Tạm tính:</span><span><?php echo number_format($sub,0,',','.'); ?>đ</span></div>
            <div style="display:flex;gap:2rem;"><span>Phí ship:</span><span><?php echo $order['shipping_fee']>0?number_format($order['shipping_fee'],0,',','.').'đ':'Miễn phí'; ?></span></div>
            <?php if ($order['discount_amount']>0): ?>
            <div style="display:flex;gap:2rem;color:var(--success);"><span>Giảm giá:</span><span>-<?php echo number_format($order['discount_amount'],0,',','.'); ?>đ</span></div>
            <?php endif; ?>
            <div style="display:flex;gap:2rem;font-size:1rem;font-weight:800;color:var(--danger);"><span>Tổng cộng:</span><span><?php echo number_format($order['total_price'],0,',','.'); ?>đ</span></div>
        </div>
    </div>

    <div class="card" style="margin-bottom:1rem;">
        <h3 style="font-size:0.9rem;font-weight:700;margin-bottom:0.75rem;">Thông Tin Giao Hàng</h3>
        <p style="font-size:0.88rem;margin-bottom:0.3rem;"><strong>Người nhận:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
        <p style="font-size:0.88rem;margin-bottom:0.3rem;"><strong>SĐT:</strong> <?php echo htmlspecialchars($order['customer_phone']); ?></p>
        <p style="font-size:0.88rem;margin-bottom:0.3rem;"><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($order['customer_address']); ?></p>
        <p style="font-size:0.88rem;"><strong>Thanh toán:</strong> <?php echo $order['payment_method']==='qr'?'📱 QR Code':'💵 COD'; ?></p>
        <?php if (!empty($order['note'])): ?>
        <p style="font-size:0.88rem;margin-top:0.3rem;"><strong>Ghi chú:</strong> <?php echo htmlspecialchars($order['note']); ?></p>
        <?php endif; ?>
    </div>

    <div style="display:flex;gap:0.75rem;justify-content:center;">
        <a href="/banhaisan/customer/profile" class="btn btn-outline">← Quay lại</a>
        <a href="/banhaisan/product/index" class="btn btn-accent">🛒 Mua thêm</a>
    </div>
</div>

<?php $content = ob_get_clean(); include __DIR__ . '/../layout.php'; ?>

<?php ob_start(); $pageTitle = 'Chi Tiet Don Hang'; ?>

<?php
$statusMap = [
    'pending'   => ['label'=>'Cho xac nhan','color'=>'#fff3cd','text'=>'#856404','icon'=>'⏳'],
    'confirmed' => ['label'=>'Da xac nhan', 'color'=>'#d4edda','text'=>'#155724','icon'=>'✅'],
    'shipping'  => ['label'=>'Dang giao',   'color'=>'#cce5ff','text'=>'#004085','icon'=>'🚚'],
    'delivered' => ['label'=>'Da giao',     'color'=>'#d4f5e2','text'=>'#0f6b35','icon'=>'🎉'],
    'cancelled' => ['label'=>'Da huy',      'color'=>'#fde8e8','text'=>'#9b1c1c','icon'=>'❌'],
];
$s = $statusMap[$order['status']] ?? $statusMap['pending'];
?>

<div style="max-width:720px;margin:0 auto;">
    <a href="/banhaisan/customer/profile" style="color:var(--primary);font-size:0.88rem;">← Lich su don hang</a>
    <div style="display:flex;justify-content:space-between;align-items:center;margin:0.75rem 0 1.5rem;">
        <h1 style="font-size:1.3rem;font-weight:800;">Don hang #<?php echo str_pad($order['id'],5,'0',STR_PAD_LEFT); ?></h1>
        <span style="padding:0.35rem 1rem;border-radius:50px;font-weight:700;font-size:0.85rem;background:<?php echo $s['color']; ?>;color:<?php echo $s['text']; ?>;">
            <?php echo $s['icon']; ?> <?php echo $s['label']; ?>
        </span>
    </div>

    <div class="card" style="margin-bottom:1rem;">
        <h3 style="font-size:0.9rem;font-weight:700;margin-bottom:1rem;color:var(--text-dark);">San Pham Da Dat</h3>
        <?php foreach ($order['items'] as $item): ?>
        <div style="display:flex;justify-content:space-between;align-items:center;padding:0.6rem 0;border-bottom:1px solid var(--border-light);font-size:0.88rem;">
            <span><?php echo htmlspecialchars($item['product_name']); ?> <span style="color:var(--text-light);">x<?php echo $item['quantity']; ?></span></span>
            <strong><?php echo number_format($item['quantity']*$item['unit_price'],0,',','.'); ?>d</strong>
        </div>
        <?php endforeach; ?>

        <div style="margin-top:0.75rem;display:flex;flex-direction:column;gap:0.4rem;align-items:flex-end;font-size:0.88rem;">
            <?php $sub = $order['total_price'] - $order['shipping_fee'] + $order['discount_amount']; ?>
            <div style="display:flex;gap:2rem;color:var(--text-light);"><span>Tam tinh:</span><span><?php echo number_format($sub,0,',','.'); ?>d</span></div>
            <div style="display:flex;gap:2rem;"><span>Phi ship:</span><span><?php echo $order['shipping_fee']>0?number_format($order['shipping_fee'],0,',','.').'d':'Mien phi'; ?></span></div>
            <?php if ($order['discount_amount']>0): ?>
            <div style="display:flex;gap:2rem;color:var(--success);"><span>Giam gia:</span><span>-<?php echo number_format($order['discount_amount'],0,',','.'); ?>d</span></div>
            <?php endif; ?>
            <div style="display:flex;gap:2rem;font-size:1rem;font-weight:800;color:var(--danger);"><span>Tong cong:</span><span><?php echo number_format($order['total_price'],0,',','.'); ?>d</span></div>
        </div>
    </div>

    <div class="card" style="margin-bottom:1rem;">
        <h3 style="font-size:0.9rem;font-weight:700;margin-bottom:0.75rem;">Thong Tin Giao Hang</h3>
        <p style="font-size:0.88rem;margin-bottom:0.3rem;"><strong>Nguoi nhan:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
        <p style="font-size:0.88rem;margin-bottom:0.3rem;"><strong>SĐT:</strong> <?php echo htmlspecialchars($order['customer_phone']); ?></p>
        <p style="font-size:0.88rem;margin-bottom:0.3rem;"><strong>Dia chi:</strong> <?php echo htmlspecialchars($order['customer_address']); ?></p>
        <p style="font-size:0.88rem;"><strong>Thanh toan:</strong> <?php echo $order['payment_method']==='qr'?'📱 QR Code':'💵 COD'; ?></p>
        <?php if (!empty($order['note'])): ?>
        <p style="font-size:0.88rem;margin-top:0.3rem;"><strong>Ghi chu:</strong> <?php echo htmlspecialchars($order['note']); ?></p>
        <?php endif; ?>
    </div>

    <div style="display:flex;gap:0.75rem;justify-content:center;">
        <a href="/banhaisan/customer/profile" class="btn btn-outline">← Quay lai</a>
        <a href="/banhaisan/product/index" class="btn btn-accent">🛒 Mua them</a>
    </div>
</div>

<?php $content = ob_get_clean(); include __DIR__ . '/../layout.php'; ?>

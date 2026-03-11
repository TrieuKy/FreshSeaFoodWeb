<?php ob_start(); $pageTitle = 'Đơn Hàng Khách Hàng'; ?>

<div style="margin-bottom:1.5rem;">
    <a href="/banhaisan/admin/customers" style="color:var(--primary);font-size:0.88rem;">← Danh sách khách hàng</a>
    <h1 class="page-title" style="margin-top:0.25rem;margin-bottom:0.25rem;">
        📋 Đơn hàng của: <span style="color:var(--primary);"><?php echo htmlspecialchars($customer['fullname'] ?: $customer['username']); ?></span>
    </h1>
    <p style="font-size:0.85rem;color:var(--text-light);">@<?php echo htmlspecialchars($customer['username']); ?> · <?php echo count($orders); ?> đơn hàng</p>
</div>

<?php if (empty($orders)): ?>
<div class="card" style="text-align:center;padding:3rem;">
    <div class="empty-icon">📭</div>
    <p>Khách hàng này chưa có đơn hàng nào.</p>
</div>
<?php else: ?>
<?php
$statusMap = [
    'pending'   => ['label'=>'⏳ Chờ xác nhận','color'=>'#fff3cd','text'=>'#856404'],
    'confirmed' => ['label'=>'✅ Đã xác nhận', 'color'=>'#d4edda','text'=>'#155724'],
    'shipping'  => ['label'=>'🚚 Đang giao',   'color'=>'#cce5ff','text'=>'#004085'],
    'delivered' => ['label'=>'🎉 Đã giao',      'color'=>'#d4f5e2','text'=>'#0f6b35'],
    'cancelled' => ['label'=>'❌ Đã hủy',       'color'=>'#fde8e8','text'=>'#9b1c1c'],
];
?>
<div class="table-card">
    <div class="card-header">
        <h3>Lịch Sử Đơn Hàng (<?php echo count($orders); ?> đơn — Tổng chi tiêu: <span style="color:var(--danger);"><?php echo number_format(array_sum(array_column($orders,'total_price')),0,',','.'); ?>đ</span>)</h3>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Mã ĐH</th>
                <th>Ngày đặt</th>
                <th>Sản phẩm</th>
                <th>Tổng tiền</th>
                <th>Thanh toán</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $o):
                $s = $statusMap[$o['status']] ?? $statusMap['pending'];
            ?>
            <tr>
                <td style="font-weight:700;color:var(--primary);">#<?php echo str_pad($o['id'],5,'0',STR_PAD_LEFT); ?></td>
                <td style="font-size:0.82rem;"><?php echo date('d/m/Y H:i', strtotime($o['created_at'])); ?></td>
                <td style="font-size:0.82rem;color:var(--text-light);"><?php echo $o['item_count']; ?> sản phẩm</td>
                <td style="font-weight:700;color:var(--danger);"><?php echo number_format($o['total_price'],0,',','.'); ?>đ</td>
                <td style="font-size:0.82rem;"><?php echo $o['payment_method']==='qr'?'📱 QR':'💵 COD'; ?></td>
                <td>
                    <span style="padding:0.2rem 0.6rem;border-radius:50px;font-size:0.75rem;font-weight:600;background:<?php echo $s['color']; ?>;color:<?php echo $s['text']; ?>;">
                        <?php echo $s['label']; ?>
                    </span>
                </td>
                <td>
                    <a href="/banhaisan/admin/order_detail?id=<?php echo $o['id']; ?>" class="btn btn-sm btn-outline">👁 Chi tiết</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<?php $content = ob_get_clean(); include __DIR__ . '/../layout.php'; ?>

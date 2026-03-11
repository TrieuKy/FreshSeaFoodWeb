<?php ob_start(); $pageTitle = 'Quản Lý Đơn Hàng'; ?>

<?php
// Trạng thái + màu sắc
$statusMap = [
    'pending'   => ['label' => '⏳ Chờ xác nhận', 'color' => '#fff3cd', 'text' => '#856404', 'next' => 'confirmed', 'nextLabel' => '✅ Xác nhận'],
    'confirmed' => ['label' => '✅ Đã xác nhận',  'color' => '#d4edda', 'text' => '#155724', 'next' => 'shipping',  'nextLabel' => '🚚 Bắt đầu giao'],
    'shipping'  => ['label' => '🚚 Đang giao',     'color' => '#cce5ff', 'text' => '#004085', 'next' => 'delivered', 'nextLabel' => '🎉 Hoàn thành'],
    'delivered' => ['label' => '🎉 Đã giao',       'color' => '#d4f5e2', 'text' => '#0f6b35', 'next' => null,        'nextLabel' => null],
    'cancelled' => ['label' => '❌ Đã hủy',        'color' => '#fde8e8', 'text' => '#9b1c1c', 'next' => null,        'nextLabel' => null],
];

// Tab filter
$filterStatus = $_GET['status'] ?? 'all';
$filteredOrders = $filterStatus === 'all' ? $orders : array_filter($orders, fn($o) => $o['status'] === $filterStatus);
?>

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;flex-wrap:wrap;gap:0.75rem;">
    <h1 class="page-title" style="margin-bottom:0;">📋 Quản Lý Đơn Hàng</h1>
    <span style="font-size:0.85rem;color:var(--text-light);">Tổng <?php echo count($orders); ?> đơn</span>
</div>

<!-- Tab filter trạng thái -->
<div style="display:flex;gap:0.5rem;flex-wrap:wrap;margin-bottom:1.2rem;">
    <?php
    $tabs = ['all'=>'Tất cả','pending'=>'Chờ xác nhận','confirmed'=>'Đã xác nhận','shipping'=>'Đang giao','delivered'=>'Đã giao','cancelled'=>'Đã hủy'];
    foreach ($tabs as $key => $label):
        $count    = ($key === 'all') ? count($orders) : count(array_filter($orders, fn($o) => $o['status'] === $key));
        $isActive = $filterStatus === $key;
    ?>
    <a href="?status=<?php echo $key; ?>"
       style="padding:0.4rem 0.9rem;border-radius:50px;font-size:0.82rem;font-weight:600;text-decoration:none;
              background:<?php echo $isActive ? 'var(--primary)' : 'var(--bg-white)'; ?>;
              color:<?php echo $isActive ? 'white' : 'var(--text-body)'; ?>;
              border:1px solid <?php echo $isActive ? 'var(--primary)' : 'var(--border)'; ?>;">
        <?php echo $label; ?> (<?php echo $count; ?>)
    </a>
    <?php endforeach; ?>
</div>

<!-- Bảng đơn hàng -->
<div class="table-card">
    <?php if (empty($filteredOrders)): ?>
        <div class="empty-state" style="padding:3rem;">
            <div class="empty-icon">📭</div>
            <p>Chưa có đơn hàng nào <?php echo $filterStatus !== 'all' ? 'ở trạng thái này' : ''; ?></p>
        </div>
    <?php else: ?>
    <table class="table">
        <thead>
            <tr>
                <th>Mã ĐH</th>
                <th>Khách hàng</th>
                <th>SĐT</th>
                <th>Tổng tiền</th>
                <th>Thanh toán</th>
                <th>Trạng thái</th>
                <th>Ngày đặt</th>
                <th style="min-width:160px;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($filteredOrders as $o):
                $s = $statusMap[$o['status']] ?? $statusMap['pending'];
            ?>
            <tr>
                <td style="font-weight:700;color:var(--primary);">#<?php echo str_pad($o['id'], 5, '0', STR_PAD_LEFT); ?></td>
                <td>
                    <div style="font-weight:600;"><?php echo htmlspecialchars($o['customer_name']); ?></div>
                    <div style="font-size:0.78rem;color:var(--text-light);"><?php echo htmlspecialchars($o['username'] ?? ''); ?></div>
                </td>
                <td style="font-size:0.88rem;"><?php echo htmlspecialchars($o['customer_phone']); ?></td>
                <td style="font-weight:700;color:var(--danger);"><?php echo number_format($o['total_price'],0,',','.'); ?>đ</td>
                <td>
                    <?php echo $o['payment_method'] === 'qr' ? '📱 QR' : '💵 COD'; ?>
                </td>
                <td>
                    <span style="padding:0.25rem 0.7rem;border-radius:50px;font-size:0.78rem;font-weight:600;
                                 background:<?php echo $s['color']; ?>;color:<?php echo $s['text']; ?>;">
                        <?php echo $s['label']; ?>
                    </span>
                </td>
                <td style="font-size:0.82rem;color:var(--text-light);">
                    <?php echo date('d/m H:i', strtotime($o['created_at'])); ?>
                </td>
                <td>
                    <div style="display:flex;gap:0.4rem;flex-wrap:wrap;">
                        <a href="/banhaisan/admin/order_detail?id=<?php echo $o['id']; ?>" class="btn btn-sm btn-outline">👁 Chi tiết</a>
                        <?php if ($s['next']): ?>
                        <a href="/banhaisan/admin/update_order_status?id=<?php echo $o['id']; ?>&status=<?php echo $s['next']; ?>"
                           class="btn btn-sm"
                           onclick="return confirm('<?php echo $s['nextLabel']; ?>?')">
                            <?php echo $s['nextLabel']; ?>
                        </a>
                        <?php endif; ?>
                        <?php if ($o['status'] === 'pending'): ?>
                        <a href="/banhaisan/admin/update_order_status?id=<?php echo $o['id']; ?>&status=cancelled"
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Hủy đơn hàng này?')">✕ Hủy</a>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>

<?php $content = ob_get_clean(); include __DIR__ . '/../layout.php'; ?>

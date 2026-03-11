<?php ob_start(); $pageTitle = 'Chi Tiết Đơn Hàng'; ?>

<?php
$statusMap = [
    'pending'   => ['label' => '⏳ Chờ xác nhận', 'color' => '#fff3cd', 'text' => '#856404'],
    'confirmed' => ['label' => '✅ Đã xác nhận',  'color' => '#d4edda', 'text' => '#155724'],
    'shipping'  => ['label' => '🚚 Đang giao',     'color' => '#cce5ff', 'text' => '#004085'],
    'delivered' => ['label' => '🎉 Đã giao',       'color' => '#d4f5e2', 'text' => '#0f6b35'],
    'cancelled' => ['label' => '❌ Đã hủy',        'color' => '#fde8e8', 'text' => '#9b1c1c'],
];
$s = $statusMap[$order['status']] ?? $statusMap['pending'];
?>

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;flex-wrap:wrap;gap:0.75rem;">
    <div>
        <a href="/banhaisan/admin/orders" style="color:var(--primary);font-size:0.88rem;">← Về danh sách đơn hàng</a>
        <h1 class="page-title" style="margin-bottom:0;margin-top:0.25rem;">
            Đơn hàng #<?php echo str_pad($order['id'], 5, '0', STR_PAD_LEFT); ?>
        </h1>
    </div>
    <span style="padding:0.4rem 1.2rem;border-radius:50px;font-size:0.9rem;font-weight:700;
                 background:<?php echo $s['color']; ?>;color:<?php echo $s['text']; ?>;">
        <?php echo $s['label']; ?>
    </span>
</div>

<div style="display:grid;grid-template-columns:1fr 340px;gap:1.5rem;align-items:start;">
    <!-- Chi tiết sản phẩm -->
    <div>
        <div class="table-card">
            <div class="card-header"><h3>🛍 Sản Phẩm Đã Đặt</h3></div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Hình</th>
                        <th>Tên sản phẩm</th>
                        <th style="text-align:center;">SL</th>
                        <th style="text-align:right;">Đơn giá</th>
                        <th style="text-align:right;">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order['items'] as $item): ?>
                    <tr>
                        <td>
                            <img src="/banhaisan/public/images/<?php echo htmlspecialchars($item['image'] ?? ''); ?>"
                                 style="width:44px;height:44px;object-fit:cover;border-radius:6px;"
                                 onerror="this.src='https://placehold.co/44/e0f4f4/0a7075?text=🦐'">
                        </td>
                        <td style="font-weight:600;"><?php echo htmlspecialchars($item['product_name']); ?></td>
                        <td style="text-align:center;"><?php echo $item['quantity']; ?></td>
                        <td style="text-align:right;"><?php echo number_format($item['unit_price'],0,',','.'); ?>đ</td>
                        <td style="text-align:right;font-weight:700;color:var(--danger);">
                            <?php echo number_format($item['quantity'] * $item['unit_price'],0,',','.'); ?>đ
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Tổng -->
            <div style="padding:1rem;border-top:2px solid var(--border-light);display:flex;flex-direction:column;gap:0.5rem;align-items:flex-end;">
                <?php $sub = $order['total_price'] - $order['shipping_fee'] + $order['discount_amount']; ?>
                <div style="display:flex;gap:2rem;font-size:0.88rem;color:var(--text-light);">
                    <span>Tạm tính:</span><span><?php echo number_format($sub,0,',','.'); ?>đ</span>
                </div>
                <div style="display:flex;gap:2rem;font-size:0.88rem;">
                    <span>Phí ship:</span>
                    <span><?php echo $order['shipping_fee'] > 0 ? number_format($order['shipping_fee'],0,',','.').'đ' : '🎁 Miễn phí'; ?></span>
                </div>
                <?php if ($order['discount_amount'] > 0): ?>
                <div style="display:flex;gap:2rem;font-size:0.88rem;color:var(--success);">
                    <span>Giảm giá:</span><span>−<?php echo number_format($order['discount_amount'],0,',','.'); ?>đ</span>
                </div>
                <?php endif; ?>
                <div style="display:flex;gap:2rem;font-size:1.1rem;font-weight:800;color:var(--danger);">
                    <span>Tổng:</span><span><?php echo number_format($order['total_price'],0,',','.'); ?>đ</span>
                </div>
            </div>
        </div>

        <?php if (!empty($order['note'])): ?>
        <div class="card" style="margin-top:1rem;">
            <p style="font-size:0.85rem;font-weight:700;color:var(--text-light);margin-bottom:0.3rem;">📝 Ghi chú của khách:</p>
            <p style="color:var(--text-body);"><?php echo nl2br(htmlspecialchars($order['note'])); ?></p>
        </div>
        <?php endif; ?>
    </div>

    <!-- Sidebar: thông tin khách + actions -->
    <div>
        <div class="card" style="margin-bottom:1rem;">
            <h3 style="font-size:0.95rem;font-weight:700;margin-bottom:1rem;color:var(--text-dark);">👤 Thông Tin Khách Hàng</h3>
            <p style="margin-bottom:0.4rem;font-size:0.88rem;"><strong>Tên:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
            <p style="margin-bottom:0.4rem;font-size:0.88rem;"><strong>SĐT:</strong> <a href="tel:<?php echo $order['customer_phone']; ?>" style="color:var(--primary);"><?php echo htmlspecialchars($order['customer_phone']); ?></a></p>
            <p style="margin-bottom:0.4rem;font-size:0.88rem;"><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($order['customer_address']); ?></p>
            <p style="font-size:0.88rem;"><strong>Thanh toán:</strong> <?php echo $order['payment_method'] === 'qr' ? '📱 QR Code' : '💵 COD'; ?></p>
        </div>

        <!-- Cập nhật trạng thái -->
        <div class="card">
            <h3 style="font-size:0.95rem;font-weight:700;margin-bottom:1rem;color:var(--text-dark);">⚙️ Cập Nhật Trạng Thái</h3>
            <form action="/banhaisan/admin/update_order_status" method="GET">
                <input type="hidden" name="id" value="<?php echo $order['id']; ?>">
                <input type="hidden" name="redirect" value="detail">
                <select name="status" class="form-control" style="margin-bottom:0.75rem;">
                    <option value="pending"   <?php echo $order['status']==='pending'   ? 'selected' : ''; ?>>⏳ Chờ xác nhận</option>
                    <option value="confirmed" <?php echo $order['status']==='confirmed' ? 'selected' : ''; ?>>✅ Đã xác nhận</option>
                    <option value="shipping"  <?php echo $order['status']==='shipping'  ? 'selected' : ''; ?>>🚚 Đang giao</option>
                    <option value="delivered" <?php echo $order['status']==='delivered' ? 'selected' : ''; ?>>🎉 Đã giao</option>
                    <option value="cancelled" <?php echo $order['status']==='cancelled' ? 'selected' : ''; ?>>❌ Đã hủy</option>
                </select>
                <button type="submit" class="btn btn-block">💾 Lưu trạng thái</button>
            </form>
        </div>

        <!-- Ngày đặt -->
        <div class="card" style="margin-top:1rem;font-size:0.82rem;color:var(--text-light);">
            📅 Đặt lúc: <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); include __DIR__ . '/../layout.php'; ?>

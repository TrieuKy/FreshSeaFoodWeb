<?php ob_start(); ?>

<div style="border-bottom: 2px solid var(--danger-color); margin-bottom: 2rem; padding-bottom: 1rem;">
    <h1 style="color: var(--danger-color);">Tổng quan nhân viên</h1>
</div>

<div class="row">
    <div class="col" style="flex: 2;">
        <div class="card">
            <h3 class="mb-3">Danh sách đơn hàng cần xử lý</h3>
            <?php if (empty($orders)): ?>
                <div class="alert">Chưa có đơn hàng nào.</div>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Mã Đơn</th>
                            <th>Khách hàng</th>
                            <th>Thông tin KH</th>
                            <th>Tổng tiền</th>
                            <th>Phương thức</th>
                            <th>Ngày đặt</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>#<?php echo $order['id']; ?></td>
                            <td><?php echo htmlspecialchars($order['username']); ?></td>
                            <td>
                                <div><strong>Tên:</strong> <?php echo htmlspecialchars($order['customer_name'] ?? ''); ?></div>
                                <div><strong>SĐT:</strong> <?php echo htmlspecialchars($order['customer_phone'] ?? ''); ?></div>
                                <div><strong>ĐC:</strong> <?php echo htmlspecialchars($order['customer_address'] ?? ''); ?></div>
                            </td>
                            <td><?php echo number_format($order['total_price'], 0, ',', '.'); ?> đ</td>
                            <td>
                                <?php
                                    if (($order['payment_method'] ?? 'cod') == 'qr') {
                                        echo '<span class="badge" style="background:#17a2b8;">QR Code</span>';
                                    } else {
                                        echo '<span class="badge" style="background:#6c757d;">COD</span>';
                                    }
                                ?>
                            </td>
                            <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                            <td>
                                <?php
                                    $statusColor = 'secondary';
                                    if ($order['status'] == 'pending') $statusColor = 'warning';
                                    if ($order['status'] == 'shipping') $statusColor = 'primary';
                                    if ($order['status'] == 'completed') $statusColor = 'success';
                                    if ($order['status'] == 'cancelled') $statusColor = 'danger';
                                ?>
                                <span style="background-color: var(--<?php echo $statusColor; ?>-color); color:white; padding: 2px 8px; border-radius: 4px; font-size: 0.8rem;">
                                    <?php echo ucfirst($order['status']); ?>
                                </span>
                            </td>
                            <td>
                                <form action="/banhaisan/employee/update_status" method="POST" style="display:inline;">
                                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                    <select name="status" onchange="this.form.submit()" style="padding: 4px; border-radius: 4px; border: 1px solid #ccc;">
                                        <option value="pending" <?php echo $order['status']=='pending'?'selected':''; ?>>Pending</option>
                                        <option value="shipping" <?php echo $order['status']=='shipping'?'selected':''; ?>>Shipping</option>
                                        <option value="completed" <?php echo $order['status']=='completed'?'selected':''; ?>>Completed</option>
                                        <option value="cancelled" <?php echo $order['status']=='cancelled'?'selected':''; ?>>Cancelled</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <div class="col" style="flex: 1;">
        <div class="card">
            <h3 class="mb-3">CSKH & Chat</h3>
            <div class="alert" style="background: #e2e3e5; color: #383d41;">
                <i class="fas fa-comments"></i> Hệ thống chat đang bảo trì.
            </div>
            <ul class="list-group">
                <li class="list-group-item">
                    <strong>Khách A:</strong> "Tôm hôm nay có tươi không shop?"
                    <br><small style="color:blue;">Trả lời ngay</small>
                </li>
                 <li class="list-group-item">
                    <strong>Khách B:</strong> "Giao hàng lâu quá!"
                    <br><small style="color:blue;">Trả lời ngay</small>
                </li>
            </ul>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); include __DIR__ . '/../layout.php'; ?>
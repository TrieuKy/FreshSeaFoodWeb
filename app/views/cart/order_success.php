<?php ob_start(); ?>

<div class="success-page">
    <div class="success-icon">✅</div>

    <h1 style="font-size:1.6rem;font-weight:800;color:var(--text-dark);margin-bottom:0.5rem;">Đặt Hàng Thành Công!</h1>
    <p style="color:var(--text-light);font-size:0.95rem;margin-bottom:2rem;">
        Cảm ơn bạn đã tin tưởng mua sắm tại <strong>Hải Sản Tươi</strong>. Chúng tôi sẽ xử lý đơn hàng sớm nhất!
    </p>

    <?php if ($order): ?>
    <div class="card" style="text-align:left;margin-bottom:2rem;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;padding-bottom:1rem;border-bottom:1px solid var(--border-light);">
            <span style="font-size:0.88rem;color:var(--text-light);">Mã đơn hàng</span>
            <strong style="color:var(--primary);font-size:1rem;">#<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></strong>
        </div>

        <div style="display:flex;justify-content:space-between;margin-bottom:0.5rem;">
            <span style="font-size:0.88rem;color:var(--text-light);">Người nhận</span>
            <span style="font-weight:600;"><?php echo htmlspecialchars($order['name']); ?></span>
        </div>
        <div style="display:flex;justify-content:space-between;margin-bottom:0.5rem;">
            <span style="font-size:0.88rem;color:var(--text-light);">Thanh toán</span>
            <span style="font-weight:600;"><?php echo $order['payment'] === 'qr' ? '📱 Chuyển khoản QR' : '💵 COD khi nhận hàng'; ?></span>
        </div>
        <div style="display:flex;justify-content:space-between;margin-bottom:1rem;padding-bottom:1rem;border-bottom:1px solid var(--border-light);">
            <span style="font-size:0.88rem;color:var(--text-light);">Tổng tiền</span>
            <span style="font-weight:800;color:var(--danger);font-size:1.1rem;"><?php echo number_format($order['total'], 0, ',', '.'); ?>đ</span>
        </div>

        <!-- Order Timeline -->
        <div style="margin-top:0.75rem;">
            <p style="font-size:0.8rem;font-weight:700;color:var(--text-light);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:0.75rem;">Trạng thái đơn hàng</p>
            <div style="display:flex;justify-content:space-between;position:relative;">
                <div style="position:absolute;top:14px;left:10%;right:10%;height:2px;background:var(--border-light);z-index:0;"></div>
                <div style="position:absolute;top:14px;left:10%;width:30%;height:2px;background:var(--primary);z-index:1;"></div>

                <?php 
                $steps = [
                    ['icon' => '📝', 'label' => 'Đặt hàng', 'done' => true],
                    ['icon' => '✅', 'label' => 'Xác nhận', 'done' => false],
                    ['icon' => '🚚', 'label' => 'Giao hàng', 'done' => false],
                    ['icon' => '🎉', 'label' => 'Hoàn thành', 'done' => false],
                ];
                foreach ($steps as $step): ?>
                <div style="text-align:center;z-index:2;">
                    <div style="width:28px;height:28px;border-radius:50%;background:<?php echo $step['done'] ? 'var(--primary)' : 'var(--border)'; ?>;color:white;display:flex;align-items:center;justify-content:center;margin:0 auto;font-size:0.75rem;">
                        <?php echo $step['done'] ? '✓' : $step['icon']; ?>
                    </div>
                    <p style="font-size:0.7rem;color:<?php echo $step['done'] ? 'var(--primary)' : 'var(--text-light)'; ?>;margin-top:0.3rem;font-weight:<?php echo $step['done'] ? '700' : '400'; ?>;"><?php echo $step['label']; ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
        <a href="/banhaisan/product/index" class="btn btn-accent btn-lg">🛒 Tiếp tục mua sắm</a>
        <a href="/banhaisan/customer/order_detail?id=<?php echo $order['id'] ?? ''; ?>"
           class="btn btn-outline btn-lg">📋 Xem đơn hàng</a>
    </div>

    <p style="font-size:0.8rem;color:var(--text-light);margin-top:2rem;">
        📞 Cần hỗ trợ? Gọi ngay <strong>0909 123 456</strong>
    </p>
</div>

<?php $content = ob_get_clean(); $pageTitle = 'Đặt Hàng Thành Công'; include __DIR__ . '/../layout.php'; ?>

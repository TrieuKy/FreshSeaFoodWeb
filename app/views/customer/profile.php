<?php ob_start(); $pageTitle = 'Tài Khoản Của Tôi'; ?>

<?php
$statusMap = [
    'pending'   => ['label' => 'Chờ xác nhận', 'color' => '#fff3cd', 'text' => '#856404', 'icon' => '⏳'],
    'confirmed' => ['label' => 'Đã xác nhận',  'color' => '#d4edda', 'text' => '#155724', 'icon' => '✅'],
    'shipping'  => ['label' => 'Đang giao',     'color' => '#cce5ff', 'text' => '#004085', 'icon' => '🚚'],
    'delivered' => ['label' => 'Đã giao',       'color' => '#d4f5e2', 'text' => '#0f6b35', 'icon' => '🎉'],
    'cancelled' => ['label' => 'Đã hủy',        'color' => '#fde8e8', 'text' => '#9b1c1c', 'icon' => '❌'],
];
?>

<div style="display:grid;grid-template-columns:260px 1fr;gap:1.5rem;align-items:start;max-width:1100px;margin:0 auto;">

    <!-- Sidebar profile -->
    <div class="card" style="text-align:center;">
        <div style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--accent));color:white;font-size:2rem;display:flex;align-items:center;justify-content:center;margin:0 auto 0.75rem;font-weight:700;">
            <?php echo strtoupper(substr($_SESSION['username'] ?? 'K', 0, 1)); ?>
        </div>
        <h3 style="font-size:1rem;font-weight:700;color:var(--text-dark);margin-bottom:0.25rem;">
            <?php echo htmlspecialchars($_SESSION['fullname'] ?? $_SESSION['username']); ?>
        </h3>
        <p style="font-size:0.82rem;color:var(--text-light);margin-bottom:1.2rem;">@<?php echo htmlspecialchars($_SESSION['username']); ?></p>
        <div style="display:flex;justify-content:center;gap:1.5rem;margin-bottom:1.2rem;padding-bottom:1.2rem;border-bottom:1px solid var(--border-light);">
            <div style="text-align:center;">
                <div style="font-size:1.3rem;font-weight:800;color:var(--primary);"><?php echo count($orders); ?></div>
                <div style="font-size:0.72rem;color:var(--text-light);">Đơn hàng</div>
            </div>
            <div style="text-align:center;">
                <div style="font-size:1.3rem;font-weight:800;color:var(--success);"><?php echo count(array_filter($orders, fn($o) => $o['status']==='delivered')); ?></div>
                <div style="font-size:0.72rem;color:var(--text-light);">Hoàn thành</div>
            </div>
        </div>
        <nav style="display:flex;flex-direction:column;gap:0.3rem;text-align:left;">
            <a href="/banhaisan/customer/profile" class="btn btn-ghost" style="justify-content:flex-start;font-size:0.88rem;">📋 Lịch sử đơn hàng</a>
            <a href="/banhaisan/product/index" class="btn btn-ghost" style="justify-content:flex-start;font-size:0.88rem;">🛒 Tiếp tục mua sắm</a>
            <a href="/banhaisan/auth/logout" class="btn btn-ghost" style="justify-content:flex-start;font-size:0.88rem;color:var(--danger);">🚪 Đăng xuất</a>
        </nav>
    </div>

    <!-- Lịch sử đơn hàng -->
    <div>
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.2rem;">
            <h2 style="font-size:1.2rem;font-weight:800;color:var(--text-dark);">📋 Lịch Sử Đặt Hàng</h2>
            <span style="font-size:0.82rem;color:var(--text-light);"><?php echo count($orders); ?> đơn hàng</span>
        </div>

        <?php if (empty($orders)): ?>
        <div class="card" style="text-align:center;padding:3rem;">
            <div style="font-size:3.5rem;margin-bottom:1rem;">🛒</div>
            <h3 style="font-size:1rem;color:var(--text-dark);font-weight:600;margin-bottom:0.5rem;">Chưa có đơn hàng nào</h3>
            <p style="color:var(--text-light);font-size:0.88rem;margin-bottom:1.5rem;">Bắt đầu mua sắm để nhận những sản phẩm hải sản tươi ngon!</p>
            <a href="/banhaisan/product/index" class="btn btn-accent">🐟 Khám phá sản phẩm</a>
        </div>
        <?php else: ?>

        <div style="display:flex;flex-direction:column;gap:1rem;">
            <?php foreach ($orders as $order):
                $s = $statusMap[$order['status']] ?? $statusMap['pending'];
                $steps = ['pending'=>0,'confirmed'=>1,'shipping'=>2,'delivered'=>3];
                $curStep = $steps[$order['status']] ?? 0;
            ?>
            <div class="card" style="padding:1.2rem;">
                <div style="display:flex;justify-content:space-between;flex-wrap:wrap;gap:0.5rem;margin-bottom:0.8rem;">
                    <div>
                        <span style="font-size:0.75rem;color:var(--text-light);">Đơn hàng</span>
                        <div style="font-weight:800;color:var(--primary);font-size:1rem;">#<?php echo str_pad($order['id'],5,'0',STR_PAD_LEFT); ?></div>
                    </div>
                    <span style="padding:0.25rem 0.8rem;border-radius:50px;font-size:0.78rem;font-weight:700;background:<?php echo $s['color']; ?>;color:<?php echo $s['text']; ?>;height:fit-content;">
                        <?php echo $s['icon']; ?> <?php echo $s['label']; ?>
                    </span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:0.5rem;">
                    <span style="font-size:0.82rem;color:var(--text-light);"><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></span>
                    <div style="display:flex;align-items:center;gap:1rem;">
                        <strong style="color:var(--danger);font-size:1rem;"><?php echo number_format($order['total_price'],0,',','.'); ?>đ</strong>
                        <a href="/banhaisan/customer/order_detail?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline">Xem chi tiết</a>
                    </div>
                </div>

                <?php if ($order['status'] !== 'cancelled'): ?>
                <div style="margin-top:0.8rem;padding-top:0.8rem;border-top:1px solid var(--border-light);">
                    <div style="display:flex;justify-content:space-between;position:relative;">
                        <div style="position:absolute;top:10px;left:8%;right:8%;height:2px;background:var(--border-light);z-index:0;"></div>
                        <div style="position:absolute;top:10px;left:8%;width:<?php echo min($curStep*28,84); ?>%;height:2px;background:var(--primary);z-index:1;transition:width 0.5s;"></div>
                        <?php foreach (['Đặt hàng','Xác nhận','Đang giao','Hoàn thành'] as $i => $lbl): ?>
                        <div style="text-align:center;z-index:2;flex:1;">
                            <div style="width:20px;height:20px;border-radius:50%;margin:0 auto;font-size:0.6rem;display:flex;align-items:center;justify-content:center;font-weight:700;
                                        background:<?php echo $i<=$curStep?'var(--primary)':'var(--border)'; ?>;
                                        color:<?php echo $i<=$curStep?'white':'var(--text-light)'; ?>;">
                                <?php echo $i<$curStep?'✓':($i+1); ?>
                            </div>
                            <p style="font-size:0.62rem;margin-top:0.2rem;color:<?php echo $i<=$curStep?'var(--primary)':'var(--text-light)'; ?>;font-weight:<?php echo $i<=$curStep?700:400; ?>;"><?php echo $lbl; ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php $content = ob_get_clean(); include __DIR__ . '/../layout.php'; ?>
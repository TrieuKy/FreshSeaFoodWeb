<?php ob_start(); $pageTitle = 'Quản Trị'; ?>

<div class="admin-layout">
    <!-- Sidebar -->
    <div class="admin-sidebar">
        <div class="sidebar-title">Quản lý</div>
        <a href="/banhaisan/admin/dashboard" class="active">📊 Bảng điều khiển</a>
        <a href="/banhaisan/admin/orders">📋 Đơn hàng
            <?php if (!empty($stats['order_counts']['pending'])): ?>
            <span style="background:var(--danger);color:white;border-radius:50px;padding:0.1rem 0.5rem;font-size:0.72rem;font-weight:700;margin-left:auto;"><?php echo $stats['order_counts']['pending']; ?></span>
            <?php endif; ?>
        </a>
        <a href="/banhaisan/admin/products">📦 Sản phẩm</a>
        <a href="/banhaisan/admin/vouchers">🏷️ Voucher</a>
        <a href="/banhaisan/admin/customers">👥 Khách hàng</a>
        <a href="/banhaisan/admin/statistics">📊 Thống kê</a>
        <div class="sidebar-title" style="margin-top:1.5rem;">Tài khoản</div>
        <a href="/banhaisan/auth/logout">🚪 Đăng xuất</a>
    </div>

    <!-- Main Content -->
    <div>
        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">💰 Doanh thu tháng</div>
                <div class="stat-value"><?php echo number_format($stats['revenue'] ?? 0); ?></div>
                <div class="stat-sub">VNĐ</div>
            </div>
            <div class="stat-card success">
                <div class="stat-label">📋 Tổng đơn hàng</div>
                <div class="stat-value"><?php echo $stats['total_orders'] ?? 0; ?></div>
                <div class="stat-sub">đơn hàng</div>
            </div>
            <div class="stat-card accent">
                <div class="stat-label">🐟 Sản phẩm</div>
                <div class="stat-value"><?php echo $stats['total_products'] ?? 0; ?></div>
                <div class="stat-sub">loại hải sản</div>
            </div>
            <div class="stat-card warning">
                <div class="stat-label">👥 Khách hàng mới</div>
                <div class="stat-value"><?php echo $stats['new_customers'] ?? 0; ?></div>
                <div class="stat-sub">trong tháng</div>
            </div>
        </div>

        <!-- Order status mini cards -->
        <?php if (!empty($stats['order_counts'])): ?>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:0.75rem;margin-bottom:1.5rem;">
            <?php
            $oc = $stats['order_counts'];
            $ocItems = [
                ['⏳','Chờ xác nhận',$oc['pending'],'#fff3cd','#856404'],
                ['✅','Đã xác nhận',$oc['confirmed'],'#d4edda','#155724'],
                ['🚚','Đang giao',$oc['shipping'],'#cce5ff','#004085'],
                ['🎉','Đã giao',$oc['delivered'],'#d4f5e2','#0f6b35'],
                ['❌','Đã hủy',$oc['cancelled'],'#fde8e8','#9b1c1c'],
            ];
            foreach ($ocItems as $item):
            ?>
            <a href="/banhaisan/admin/orders?status=<?php echo strtolower(str_replace(' ','_',$item[1])); ?>"
               style="padding:0.8rem;border-radius:var(--radius-md);background:<?php echo $item[3]; ?>;text-decoration:none;
                      display:flex;flex-direction:column;gap:0.25rem;border:1px solid <?php echo $item[3]; ?>;">
                <span style="font-size:1.3rem;"><?php echo $item[0]; ?></span>
                <span style="font-size:1.5rem;font-weight:800;color:<?php echo $item[4]; ?>"><?php echo $item[2]; ?></span>
                <span style="font-size:0.72rem;color:<?php echo $item[4]; ?>;font-weight:600;"><?php echo $item[1]; ?></span>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Quick Actions -->
        <div class="card mb-3">
            <h3 style="font-size:1rem;font-weight:700;color:var(--text-dark);margin-bottom:1rem;">⚡ Thao Tác Nhanh</h3>
            <div style="display:flex;gap:0.75rem;flex-wrap:wrap;">
                <a href="/banhaisan/admin/products" class="btn">📦 Quản lý sản phẩm</a>
                <a href="/banhaisan/admin/vouchers" class="btn btn-outline">🏷️ Quản lý voucher</a>
                <a href="#" class="btn btn-ghost">📋 Xem đơn hàng</a>
            </div>
        </div>

        <!-- Notifications -->
        <div class="card">
            <h3 style="font-size:1rem;font-weight:700;color:var(--text-dark);margin-bottom:1rem;">🔔 Thông Báo Hệ Thống</h3>
            <div style="display:flex;flex-direction:column;gap:0.6rem;">
                <div class="alert alert-warning">⚠️ Kho hàng "Tôm hùm Alaska" sắp hết (Còn 5kg)</div>
                <div class="alert alert-info">ℹ️ Có 3 yêu cầu hỗ trợ mới từ nhân viên.</div>
            </div>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); include __DIR__ . '/../layout.php'; ?>

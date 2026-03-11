<?php ob_start(); $pageTitle = 'Quản Lý Khách Hàng'; ?>

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;flex-wrap:wrap;gap:0.75rem;">
    <h1 class="page-title" style="margin-bottom:0;">👥 Quản Lý Khách Hàng</h1>
    <span style="font-size:0.85rem;color:var(--text-light);"><?php echo count($customers); ?> khách hàng</span>
</div>

<!-- Tìm kiếm -->
<div class="card" style="margin-bottom:1.5rem;padding:0.75rem 1rem;">
    <form method="GET" style="display:flex;gap:0.75rem;align-items:center;">
        <input type="text" name="q" value="<?php echo htmlspecialchars($_GET['q']??''); ?>"
               class="form-control" style="max-width:340px;" placeholder="🔍 Tìm theo tên, email, số điện thoại...">
        <button type="submit" class="btn">Tìm kiếm</button>
        <?php if (!empty($_GET['q'])): ?>
        <a href="/banhaisan/admin/customers" class="btn btn-ghost">✕ Xóa lọc</a>
        <?php endif; ?>
    </form>
</div>

<!-- Bảng khách hàng -->
<div class="table-card">
    <div class="card-header">
        <h3>Danh sách<?php echo !empty($_GET['q']) ? ' — Kết quả tìm "'.htmlspecialchars($_GET['q']).'"' : ''; ?></h3>
    </div>
    <?php if (empty($customers)): ?>
    <div class="empty-state" style="padding:3rem;">
        <div class="empty-icon">👥</div>
        <p>Không tìm thấy khách hàng nào<?php echo !empty($_GET['q']) ? ' phù hợp' : ''; ?>.</p>
    </div>
    <?php else: ?>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Khách hàng</th>
                <th>Email</th>
                <th>Số ĐT</th>
                <th style="text-align:center;">Đơn hàng</th>
                <th style="text-align:right;">Tổng chi tiêu</th>
                <th>Ngày tham gia</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($customers as $c): ?>
            <tr>
                <td style="color:var(--text-light);font-size:0.82rem;">#<?php echo str_pad($c['id'],4,'0',STR_PAD_LEFT); ?></td>
                <td>
                    <div style="display:flex;align-items:center;gap:0.6rem;">
                        <div style="width:36px;height:36px;border-radius:50%;background:var(--primary);color:white;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.9rem;flex-shrink:0;">
                            <?php echo strtoupper(substr($c['username'],0,1)); ?>
                        </div>
                        <div>
                            <div style="font-weight:600;color:var(--text-dark);"><?php echo htmlspecialchars($c['fullname'] ?: $c['username']); ?></div>
                            <div style="font-size:0.75rem;color:var(--text-light);">@<?php echo htmlspecialchars($c['username']); ?></div>
                        </div>
                    </div>
                </td>
                <td style="font-size:0.85rem;"><?php echo htmlspecialchars($c['email'] ?? '—'); ?></td>
                <td style="font-size:0.85rem;"><?php echo htmlspecialchars($c['phone'] ?? '—'); ?></td>
                <td style="text-align:center;">
                    <span style="font-weight:700;color:var(--primary);"><?php echo $c['order_count']; ?></span>
                    <span style="font-size:0.76rem;color:var(--text-light);"> đơn</span>
                </td>
                <td style="text-align:right;font-weight:700;color:var(--danger);">
                    <?php echo $c['total_spent'] > 0 ? number_format($c['total_spent'],0,',','.').'đ' : '—'; ?>
                </td>
                <td style="font-size:0.8rem;color:var(--text-light);">
                    <?php echo date('d/m/Y', strtotime($c['created_at'])); ?>
                </td>
                <td>
                    <a href="/banhaisan/admin/customer_orders?id=<?php echo $c['id']; ?>"
                       class="btn btn-sm btn-outline">📋 Lịch sử ĐH</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>

<?php $content = ob_get_clean(); include __DIR__ . '/../layout.php'; ?>

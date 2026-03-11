<?php ob_start(); $pageTitle = 'Thống Kê Doanh Thu'; ?>

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;flex-wrap:wrap;gap:0.75rem;">
    <h1 class="page-title" style="margin-bottom:0;">📊 Thống Kê & Báo Cáo</h1>
    <a href="/banhaisan/admin/export_revenue?month=<?php echo $month; ?>&year=<?php echo $year; ?>"
       class="btn btn-outline" style="font-size:0.85rem;">📥 Xuất CSV Excel</a>
</div>

<!-- Bộ lọc tháng / năm -->
<div class="card" style="margin-bottom:1.5rem;padding:1rem;">
    <form method="GET" style="display:flex;gap:1rem;align-items:flex-end;flex-wrap:wrap;">
        <div>
            <label class="form-label" style="font-size:0.8rem;">Tháng</label>
            <select name="month" class="form-control" style="width:auto;">
                <?php for ($i=1;$i<=12;$i++): ?>
                <option value="<?php echo $i; ?>" <?php echo ($i==$month)?'selected':''; ?>>Tháng <?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div>
            <label class="form-label" style="font-size:0.8rem;">Năm</label>
            <select name="year" class="form-control" style="width:auto;">
                <?php for ($y=2024;$y<=2027;$y++): ?>
                <option value="<?php echo $y; ?>" <?php echo ($y==$year)?'selected':''; ?>><?php echo $y; ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <button type="submit" class="btn">🔍 Xem báo cáo</button>
    </form>
</div>

<!-- Thống kê nhanh -->
<div class="stats-grid" style="margin-bottom:1.5rem;">
    <div class="stat-card">
        <div class="stat-label">💰 Doanh thu tháng <?php echo $month.'/'.$year; ?></div>
        <div class="stat-value"><?php echo number_format($monthStats['revenue']??0,0,',','.'); ?></div>
        <div class="stat-sub">VNĐ</div>
    </div>
    <div class="stat-card success">
        <div class="stat-label">📋 Tổng đơn hàng</div>
        <div class="stat-value"><?php echo $monthStats['orders']??0; ?></div>
        <div class="stat-sub">đơn hàng</div>
    </div>
    <div class="stat-card accent">
        <div class="stat-label">🎉 Giao thành công</div>
        <div class="stat-value"><?php echo $monthStats['delivered']??0; ?></div>
        <div class="stat-sub">đơn hàng</div>
    </div>
    <div class="stat-card warning">
        <div class="stat-label">❌ Đã hủy</div>
        <div class="stat-value"><?php echo $monthStats['cancelled']??0; ?></div>
        <div class="stat-sub">đơn hàng</div>
    </div>
</div>

<!-- Biểu đồ doanh thu theo ngày -->
<?php if (!empty($dailyRevenue)): ?>
<div class="card" style="margin-bottom:1.5rem;overflow:hidden;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.2rem;">
        <h3 style="font-size:0.95rem;font-weight:700;margin-bottom:0;">📈 Doanh thu theo ngày — Tháng <?php echo $month.'/'.$year; ?></h3>
        <span style="font-size:0.78rem;color:var(--text-light);">
            Tổng: <strong><?php echo number_format(array_sum(array_column($dailyRevenue,'revenue')),0,',','.'); ?>đ</strong>
        </span>
    </div>
    <?php $maxRev = max(array_column($dailyRevenue,'revenue') ?: [1]); ?>
    <!-- Chart container -->
    <div style="display:flex;align-items:flex-end;gap:4px;height:180px;padding:0 4px;overflow-x:auto;position:relative;">
        <!-- Y-axis label -->
        <div style="position:absolute;top:0;left:0;right:0;bottom:24px;pointer-events:none;">
            <?php for ($l=4;$l>=1;$l--): ?>
            <div style="position:absolute;left:0;right:0;top:<?php echo ($l==4)?'0':(((4-$l)*25).'%'); ?>;border-top:1px dashed var(--border-light);display:flex;align-items:center;">
                <span style="font-size:0.6rem;color:var(--text-light);background:white;padding:0 2px;white-space:nowrap;">
                    <?php echo number_format($maxRev*(5-$l)/4,0,',','.'); ?>
                </span>
            </div>
            <?php endfor; ?>
        </div>
        <?php foreach ($dailyRevenue as $day):
            $pct = $maxRev > 0 ? ($day['revenue']/$maxRev*100) : 2;
            $pct = max($pct, 3);
        ?>
        <div style="flex:1;min-width:22px;max-width:40px;display:flex;flex-direction:column;align-items:center;justify-content:flex-end;height:100%;cursor:pointer;position:relative;z-index:1;"
             title="Ngày <?php echo $day['day']; ?>/<?php echo $month; ?>: <?php echo number_format($day['revenue'],0,',','.'); ?>đ">
            <div class="bar-label" style="font-size:0.62rem;color:var(--text-light);margin-bottom:2px;white-space:nowrap;display:none;">
                <?php echo number_format($day['revenue']/1000,0); ?>K
            </div>
            <div style="width:80%;background:linear-gradient(180deg,var(--accent),var(--primary));border-radius:4px 4px 0 0;height:<?php echo $pct; ?>%;min-height:4px;transition:opacity 0.2s;"
                 onmouseover="this.style.opacity='0.7';this.previousElementSibling.style.display='block';"
                 onmouseout="this.style.opacity='1';this.previousElementSibling.style.display='none';"></div>
            <span style="font-size:0.58rem;color:var(--text-light);margin-top:3px;font-weight:500;"><?php echo $day['day']; ?></span>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php else: ?>
<div class="card" style="margin-bottom:1.5rem;text-align:center;padding:2rem;color:var(--text-light);">
    📭 Chưa có dữ liệu doanh thu trong tháng <?php echo $month.'/'.$year; ?>
</div>
<?php endif; ?>

<!-- Bảng chi tiết đơn hàng tháng này -->
<div class="table-card">
    <div class="card-header">
        <h3>Chi Tiết Đơn Hàng Tháng <?php echo $month.'/'.$year; ?> (<?php echo count($monthOrders); ?> đơn)</h3>
    </div>
    <?php if (empty($monthOrders)): ?>
    <div class="empty-state" style="padding:2rem;"><div class="empty-icon">📭</div><p>Không có đơn hàng nào trong tháng này.</p></div>
    <?php else: ?>
    <table class="table">
        <thead>
            <tr>
                <th>Mã ĐH</th>
                <th>Khách hàng</th>
                <th>Ngày đặt</th>
                <th>Sản phẩm</th>
                <th style="text-align:right;">Doanh thu</th>
                <th>Trạng thái</th>
                <th>Xem</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($monthOrders as $o):
                $sLabel = ['pending'=>'⏳ Chờ','confirmed'=>'✅ Xác nhận','shipping'=>'🚚 Đang giao','delivered'=>'🎉 Đã giao','cancelled'=>'❌ Đã hủy'];
                $sColor = ['pending'=>'#856404','confirmed'=>'#155724','shipping'=>'#004085','delivered'=>'#0f6b35','cancelled'=>'#9b1c1c'];
            ?>
            <tr>
                <td style="font-weight:700;color:var(--primary);">#<?php echo str_pad($o['id'],5,'0',STR_PAD_LEFT); ?></td>
                <td><?php echo htmlspecialchars($o['customer_name']); ?></td>
                <td style="font-size:0.82rem;"><?php echo date('d/m H:i', strtotime($o['created_at'])); ?></td>
                <td style="font-size:0.82rem;color:var(--text-light);"><?php echo $o['item_count']; ?> sản phẩm</td>
                <td style="text-align:right;font-weight:700;color:var(--danger);"><?php echo number_format($o['total_price'],0,',','.'); ?>đ</td>
                <td style="font-weight:600;color:<?php echo $sColor[$o['status']]??'#000'; ?>;font-size:0.82rem;">
                    <?php echo $sLabel[$o['status']] ?? $o['status']; ?>
                </td>
                <td>
                    <a href="/banhaisan/admin/order_detail?id=<?php echo $o['id']; ?>" class="btn btn-sm btn-outline" style="font-size:0.75rem;">👁</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr style="font-weight:700;background:var(--bg-light);">
                <td colspan="4" style="padding:0.75rem;">Tổng cộng (<?php echo count($monthOrders); ?> đơn)</td>
                <td style="text-align:right;color:var(--danger);font-size:1rem;"><?php echo number_format(array_sum(array_column($monthOrders,'total_price')),0,',','.'); ?>đ</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>
    <?php endif; ?>
</div>

<?php $content = ob_get_clean(); include __DIR__ . '/../layout.php'; ?>

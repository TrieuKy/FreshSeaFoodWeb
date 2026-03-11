<?php ob_start(); $pageTitle = 'Quản Lý Voucher'; ?>

<h1 class="page-title">🏷️ Quản Lý Mã Giảm Giá</h1>

<div style="display:grid;grid-template-columns:340px 1fr;gap:1.5rem;align-items:start;">
    <!-- Form tạo voucher -->
    <div class="card">
        <h3 style="font-size:1rem;font-weight:700;color:var(--text-dark);margin-bottom:1.2rem;">➕ Tạo Voucher Mới</h3>
        <form action="/banhaisan/admin/store_voucher" method="POST">
            <div class="form-group">
                <label class="form-label">Mã Code *</label>
                <input type="text" name="code" class="form-control" required
                       placeholder="VD: HAISAN20" style="text-transform:uppercase;"
                       oninput="this.value=this.value.toUpperCase()">
            </div>
            <div class="form-group">
                <label class="form-label">Loại giảm giá</label>
                <select name="discount_type" class="form-control" id="discountType" onchange="toggleLabel()">
                    <option value="percent">Phần trăm (%)</option>
                    <option value="fixed">Số tiền cố định (đ)</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label" id="discountLabel">Giá trị giảm (%) *</label>
                <input type="number" name="discount_value" class="form-control" required min="1" placeholder="VD: 10">
            </div>
            <div class="form-group">
                <label class="form-label">Đơn hàng tối thiểu (đ)</label>
                <input type="number" name="min_order" class="form-control" min="0" value="0" placeholder="0 = không giới hạn">
            </div>
            <div class="form-group">
                <label class="form-label">Hạn sử dụng</label>
                <input type="date" name="expires_at" class="form-control"
                       min="<?php echo date('Y-m-d'); ?>">
            </div>
            <button type="submit" class="btn btn-accent btn-block">💾 Tạo mã giảm giá</button>
        </form>
    </div>

    <!-- Danh sách vouchers -->
    <div class="table-card">
        <div class="card-header">
            <h3>Danh sách voucher (<?php echo count($vouchers); ?>)</h3>
        </div>
        <?php if (empty($vouchers)): ?>
            <div class="empty-state" style="padding:2rem;">
                <div class="empty-icon">🏷️</div>
                <p>Chưa có voucher nào. Tạo voucher đầu tiên!</p>
            </div>
        <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Mã Code</th>
                    <th>Giảm giá</th>
                    <th>Đơn tối thiểu</th>
                    <th>Hạn dùng</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($vouchers as $v): ?>
                <?php
                    $expired = $v['expires_at'] && $v['expires_at'] < date('Y-m-d');
                    $status_color = ($v['is_active'] && !$expired) ? '#d4f5e2' : '#fde8e8';
                    $status_text_color = ($v['is_active'] && !$expired) ? '#0f6b35' : '#9b1c1c';
                    $status_label = $expired ? 'Hết hạn' : ($v['is_active'] ? 'Hoạt động' : 'Tắt');
                ?>
                <tr>
                    <td>
                        <strong style="font-size:1rem;color:var(--primary);letter-spacing:1px;"><?php echo htmlspecialchars($v['code']); ?></strong>
                    </td>
                    <td style="font-weight:700;color:var(--danger);">
                        <?php if ($v['discount_type'] === 'percent'): ?>
                            −<?php echo $v['discount_value']; ?>%
                        <?php else: ?>
                            −<?php echo number_format($v['discount_value'], 0, ',', '.'); ?>đ
                        <?php endif; ?>
                    </td>
                    <td style="font-size:0.85rem;">
                        <?php echo $v['min_order_value'] > 0 ? 'Từ '.number_format($v['min_order_value'],0,',','.').'đ' : 'Không giới hạn'; ?>
                    </td>
                    <td style="font-size:0.85rem;">
                        <?php echo $v['expires_at'] ? date('d/m/Y', strtotime($v['expires_at'])) : '∞ Không hết hạn'; ?>
                    </td>
                    <td>
                        <span style="padding:0.2rem 0.7rem;border-radius:50px;font-size:0.78rem;font-weight:600;
                                     background:<?php echo $status_color; ?>;color:<?php echo $status_text_color; ?>;">
                            <?php echo $status_label; ?>
                        </span>
                    </td>
                    <td>
                        <a href="/banhaisan/admin/delete_voucher?id=<?php echo $v['id']; ?>"
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Xóa voucher <?php echo $v['code']; ?>?')">🗑 Xóa</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<script>
function toggleLabel() {
    const type = document.getElementById('discountType').value;
    document.getElementById('discountLabel').textContent =
        type === 'percent' ? 'Giá trị giảm (%) *' : 'Số tiền giảm (đ) *';
}
</script>

<?php $content = ob_get_clean(); include __DIR__ . '/../layout.php'; ?>
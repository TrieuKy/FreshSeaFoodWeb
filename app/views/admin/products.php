<?php ob_start(); $pageTitle = 'Quản Lý Sản Phẩm'; ?>

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
    <h1 class="page-title" style="margin-bottom:0;">📦 Quản Lý Kho Hàng</h1>
    <button class="btn btn-accent" onclick="toggleForm()">+ Thêm sản phẩm</button>
</div>

<!-- Form Thêm sản phẩm -->
<div id="addForm" style="display:none;" class="card mb-3">
    <h3 style="font-size:1rem;font-weight:700;margin-bottom:1.2rem;">➕ Thêm Sản Phẩm Mới</h3>
    <form action="/banhaisan/admin/store_product" method="POST" enctype="multipart/form-data">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <div class="form-group">
                <label class="form-label">Tên sản phẩm *</label>
                <input type="text" name="name" class="form-control" required placeholder="VD: Tôm Hùm Alaska">
            </div>
            <div class="form-group">
                <label class="form-label">Danh mục *</label>
                <select name="category_id" class="form-control" required>
                    <option value="">-- Chọn danh mục --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>">
                            <?php echo $cat['icon']; ?> <?php echo htmlspecialchars($cat['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Giá (đ) *</label>
                <input type="number" name="price" class="form-control" required min="0" placeholder="VD: 250000">
            </div>
            <div class="form-group">
                <label class="form-label">Tồn kho</label>
                <input type="number" name="stock" class="form-control" min="0" value="0">
            </div>
            <div class="form-group">
                <label class="form-label">Đơn vị</label>
                <select name="unit" class="form-control">
                    <option value="kg">kg</option>
                    <option value="con">con</option>
                    <option value="hộp">hộp</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Hình ảnh</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Mô tả</label>
            <textarea name="description" class="form-control" rows="2" placeholder="Mô tả ngắn về sản phẩm"></textarea>
        </div>
        <div style="display:flex;gap:0.75rem;margin-top:0.75rem;">
            <button type="submit" class="btn">💾 Lưu sản phẩm</button>
            <button type="button" class="btn btn-ghost" onclick="toggleForm()">Hủy</button>
        </div>
    </form>
</div>

<!-- Bảng sản phẩm -->
<div class="table-card">
    <div class="card-header">
        <h3>Danh sách (<?php echo count($products); ?> sản phẩm)</h3>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Hình ảnh</th>
                <th>Tên sản phẩm</th>
                <th>Danh mục</th>
                <th>Giá</th>
                <th>Tồn kho</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $p): ?>
            <tr>
                <td>
                    <img src="/banhaisan/public/images/<?php echo htmlspecialchars($p['image'] ?? ''); ?>"
                         style="width:48px;height:48px;object-fit:cover;border-radius:6px;border:1px solid var(--border-light);"
                         onerror="this.src='https://placehold.co/48x48/e0f4f4/0a7075?text=🦐'">
                </td>
                <td style="font-weight:600;color:var(--text-dark);"><?php echo htmlspecialchars($p['name']); ?></td>
                <td>
                    <?php if (!empty($p['category_icon'])): ?>
                        <span><?php echo $p['category_icon']; ?> <?php echo htmlspecialchars($p['category_name'] ?? '—'); ?></span>
                    <?php else: ?>
                        <span style="color:var(--text-light);">—</span>
                    <?php endif; ?>
                </td>
                <td style="color:var(--danger);font-weight:700;"><?php echo number_format($p['price'], 0, ',', '.'); ?>đ</td>
                <td>
                    <span style="padding:0.2rem 0.6rem;border-radius:50px;font-size:0.78rem;font-weight:600;
                                 background:<?php echo ($p['stock'] ?? 0) > 0 ? '#d4f5e2' : '#fde8e8'; ?>;
                                 color:<?php echo ($p['stock'] ?? 0) > 0 ? '#0f6b35' : '#9b1c1c'; ?>;">
                        <?php echo ($p['stock'] ?? 0) > 0 ? ($p['stock'].' '.$p['unit']) : 'Hết hàng'; ?>
                    </span>
                    <?php if (($p['stock'] ?? 0) > 0 && ($p['stock'] ?? 0) < 5): ?>
                        <div style="font-size:0.75rem;color:var(--danger);font-weight:700;margin-top:0.3rem;">⚠️ Sắp hết (cần nhập thêm)</div>
                    <?php endif; ?>
                </td>
                <td>
                    <div style="display:flex;gap:0.4rem;">
                        <a href="/banhaisan/admin/edit_product?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-outline">✏️ Sửa</a>
                        <a href="/banhaisan/admin/delete_product?id=<?php echo $p['id']; ?>"
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Xóa sản phẩm này?')">🗑</a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
function toggleForm() {
    const f = document.getElementById('addForm');
    f.style.display = f.style.display === 'none' ? 'block' : 'none';
}
</script>

<?php $content = ob_get_clean(); include __DIR__ . '/../layout.php'; ?>
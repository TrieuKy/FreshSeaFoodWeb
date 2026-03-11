<?php ob_start(); $pageTitle = 'Chỉnh Sửa Sản Phẩm'; ?>

<div style="max-width:700px;margin:0 auto;">
    <h1 class="page-title">✏️ Chỉnh Sửa Sản Phẩm</h1>

    <div class="card">
        <form action="/banhaisan/admin/update_product" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
            <input type="hidden" name="current_image" value="<?php echo $product['image']; ?>">

            <div class="form-group">
                <label class="form-label">Tên sản phẩm *</label>
                <input type="text" name="name" class="form-control"
                       value="<?php echo htmlspecialchars($product['name']); ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Danh mục *</label>
                <select name="category_id" class="form-control" required>
                    <option value="">-- Chọn danh mục --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>"
                            <?php echo ($product['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                            <?php echo $cat['icon']; ?> <?php echo htmlspecialchars($cat['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;">
                <div class="form-group">
                    <label class="form-label">Giá (đ) *</label>
                    <input type="number" name="price" class="form-control"
                           value="<?php echo $product['price']; ?>" required min="0">
                </div>
                <div class="form-group">
                    <label class="form-label">Tồn kho</label>
                    <input type="number" name="stock" class="form-control"
                           value="<?php echo $product['stock'] ?? 0; ?>" min="0">
                </div>
                <div class="form-group">
                    <label class="form-label">Đơn vị</label>
                    <select name="unit" class="form-control">
                        <option value="kg"  <?php echo ($product['unit'] ?? 'kg') == 'kg'  ? 'selected' : ''; ?>>kg</option>
                        <option value="con" <?php echo ($product['unit'] ?? '') == 'con' ? 'selected' : ''; ?>>con</option>
                        <option value="hộp" <?php echo ($product['unit'] ?? '') == 'hộp' ? 'selected' : ''; ?>>hộp</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Mô tả</label>
                <textarea name="description" class="form-control" rows="4"><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Hình ảnh hiện tại</label>
                <?php if (!empty($product['image'])): ?>
                    <div style="margin-bottom:0.75rem;">
                        <img src="/banhaisan/public/images/<?php echo htmlspecialchars($product['image']); ?>"
                             style="width:100px;height:100px;object-fit:cover;border-radius:8px;border:1px solid var(--border-light);"
                             onerror="this.style.display='none'">
                    </div>
                <?php endif; ?>
                <label class="form-label" style="font-size:0.85rem;">Tải ảnh mới (để trống nếu không đổi)</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>

            <div style="display:flex;gap:0.75rem;justify-content:flex-end;margin-top:1.5rem;">
                <a href="/banhaisan/admin/products" class="btn btn-ghost">← Hủy</a>
                <button type="submit" class="btn btn-accent">💾 Cập nhật</button>
            </div>
        </form>
    </div>
</div>

<?php $content = ob_get_clean(); include __DIR__ . '/../layout.php'; ?>

<?php ob_start(); 
$pageTitle = htmlspecialchars($product['name']);
?>

<!-- Breadcrumb -->
<nav style="font-size:0.83rem;color:var(--text-light);margin-bottom:1.5rem;">
    <a href="/banhaisan" style="color:var(--primary);">Trang chủ</a> › 
    <?php if (!empty($product['category'])): ?>
    <a href="/banhaisan/product/index?category=<?php echo urlencode($product['category']); ?>" style="color:var(--primary);">
        <?php echo htmlspecialchars($product['category']); ?>
    </a> › 
    <?php endif; ?>
    <span><?php echo htmlspecialchars($product['name']); ?></span>
</nav>

<!-- Product Detail Grid -->
<div class="product-detail-grid">
    <!-- Image -->
    <div>
        <img class="detail-image-main"
             src="/banhaisan/public/images/<?php echo htmlspecialchars($product['image']); ?>" 
             alt="<?php echo htmlspecialchars($product['name']); ?>"
             onerror="this.src='https://placehold.co/500x500/e0f4f4/0a7075?text=🦐'">
    </div>

    <!-- Info -->
    <div>
        <?php if (!empty($product['category'])): ?>
            <span style="font-size:0.8rem;font-weight:700;color:var(--primary);text-transform:uppercase;letter-spacing:0.5px;">
                <?php echo htmlspecialchars($product['category']); ?>
            </span>
        <?php endif; ?>

        <h1 style="font-size:1.7rem;font-weight:800;color:var(--text-dark);margin:0.5rem 0;line-height:1.2;">
            <?php echo htmlspecialchars($product['name']); ?>
        </h1>

        <div class="detail-price"><?php echo number_format($product['price'], 0, ',', '.'); ?>đ / kg</div>

        <!-- Meta Info -->
        <div class="detail-meta">
            <span>🌊 <strong>Xuất xứ:</strong> Việt Nam</span>
            <span>❄️ <strong>Bảo quản:</strong> 0–4°C, dùng trong 24 giờ</span>
            <span>✅ <strong>Trạng thái:</strong> <span style="color:var(--success);font-weight:600;">Còn hàng</span></span>
        </div>

        <hr style="border:none;border-top:1px solid var(--border-light);margin:1.2rem 0;">

        <!-- Description -->
        <?php if (!empty($product['description'])): ?>
        <p style="font-size:0.93rem;color:var(--text-body);line-height:1.7;margin-bottom:1.2rem;">
            <?php echo nl2br(htmlspecialchars($product['description'])); ?>
        </p>
        <?php endif; ?>

        <!-- Quantity + Add to Cart -->
        <div style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap;margin-bottom:1.2rem;">
            <div class="qty-control" style="border:2px solid var(--border);border-radius:var(--radius-sm);overflow:hidden;">
                <button type="button" onclick="changeQty(this,-1)" style="width:36px;height:42px;border:none;background:var(--bg-page);font-size:1.1rem;font-weight:700;cursor:pointer;color:var(--text-dark);">−</button>
                <input type="number" id="detailQty" value="1" min="1" max="99" 
                       style="width:50px;height:42px;border:none;text-align:center;font-family:var(--font);font-size:1rem;font-weight:700;color:var(--text-dark);outline:none;">
                <button type="button" onclick="changeQty(this,1)" style="width:36px;height:42px;border:none;background:var(--bg-page);font-size:1.1rem;font-weight:700;cursor:pointer;color:var(--text-dark);">+</button>
            </div>
            <a href="/banhaisan/cart/add/<?php echo $product['id']; ?>" class="btn btn-accent btn-lg" style="flex:1;min-width:200px;">
                🛒 Thêm vào giỏ hàng
            </a>
        </div>

        <!-- Quick Buy -->
        <a href="/banhaisan/cart/add/<?php echo $product['id']; ?>" class="btn btn-outline btn-block" style="margin-bottom:1.2rem;">
            ⚡ Mua ngay
        </a>

        <!-- Guarantee Info -->
        <div style="background:var(--bg-light);border-radius:var(--radius-md);padding:1rem;font-size:0.82rem;color:var(--text-body);display:grid;grid-template-columns:1fr 1fr;gap:0.5rem;">
            <span>✅ Cam kết hoàn tiền 100%</span>
            <span>🚚 Giao trong 2 giờ nội thành</span>
            <span>🌊 Đánh bắt tươi mỗi ngày</span>
            <span>📦 Đóng gói đảm bảo vệ sinh</span>
        </div>
    </div>
</div>

<!-- Related Products -->
<?php if (!empty($related)): ?>
<div class="section-header" style="margin-top:3rem;">
    <h2>🦐 Sản Phẩm Tương Tự</h2>
    <a href="/banhaisan/product/index">Xem tất cả →</a>
</div>
<div class="product-grid">
    <?php 
    $count = 0;
    foreach ($related as $rel):
        if ($rel['id'] == $product['id']) continue;
        if (++$count > 4) break;
    ?>
    <div class="product-card">
        <div class="product-image">
            <img src="/banhaisan/public/images/<?php echo htmlspecialchars($rel['image']); ?>"
                 alt="<?php echo htmlspecialchars($rel['name']); ?>"
                 onerror="this.src='https://placehold.co/300x300/e0f4f4/0a7075?text=🦐'"
                 style="height:180px;object-fit:cover;">
            <div class="product-overlay">
                <a href="/banhaisan/product/detail/<?php echo $rel['id']; ?>" class="btn-quick-view">👁 Xem chi tiết</a>
            </div>
        </div>
        <div class="product-info">
            <h3 class="product-title"><?php echo htmlspecialchars($rel['name']); ?></h3>
            <div class="product-price-row">
                <span class="product-price"><?php echo number_format($rel['price'], 0, ',', '.'); ?>đ</span>
                <span class="product-unit">/ kg</span>
            </div>
            <a href="/banhaisan/cart/add/<?php echo $rel['id']; ?>" class="btn-cart" style="margin-top:0.5rem;">🛒 Thêm vào giỏ</a>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<script>
function changeQty(btn, delta) {
    const input = document.getElementById('detailQty');
    let val = parseInt(input.value) + delta;
    if (val < 1) val = 1;
    input.value = val;
}
</script>

<?php $content = ob_get_clean(); include __DIR__ . '/../layout.php'; ?>

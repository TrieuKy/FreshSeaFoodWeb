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
             style="<?php echo $product['stock'] <= 0 ? 'filter:grayscale(100%);opacity:0.8;' : ''; ?>"
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
        <?php if (!empty($reviews)): 
            $avgRating = round(array_sum(array_column($reviews, 'rating')) / count($reviews), 1);
        ?>
        <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.75rem;">
            <span style="color:#FFA000;font-size:1.1rem;letter-spacing:1px;">
                <?php echo str_repeat('★', round($avgRating)) . str_repeat('☆', 5 - round($avgRating)); ?>
            </span>
            <span style="font-size:0.9rem;font-weight:600;color:var(--text-dark);"><?php echo $avgRating; ?>/5</span>
            <span style="font-size:0.85rem;color:var(--text-light);">(<?php echo count($reviews); ?> đánh giá)</span>
        </div>
        <?php endif; ?>

        <div class="detail-price"><?php echo number_format($product['price'], 0, ',', '.'); ?>đ / kg</div>

        <!-- Meta Info -->
        <div class="detail-meta">
            <span>🌊 <strong>Xuất xứ:</strong> Việt Nam</span>
            <span>❄️ <strong>Bảo quản:</strong> 0–4°C, dùng trong 24 giờ</span>
            <span><?php echo $product['stock'] > 0 ? '✅' : '❌'; ?> <strong>Trạng thái:</strong> <span style="color:var(--<?php echo $product['stock'] > 0 ? 'success' : 'danger'; ?>);font-weight:600;"><?php echo $product['stock'] > 0 ? 'Còn hàng' : 'Hết hàng'; ?></span></span>
        </div>

        <hr style="border:none;border-top:1px solid var(--border-light);margin:1.2rem 0;">

        <!-- Description -->
        <?php if (!empty($product['description'])): ?>
        <p style="font-size:0.93rem;color:var(--text-body);line-height:1.7;margin-bottom:1.2rem;">
            <?php echo nl2br(htmlspecialchars($product['description'])); ?>
        </p>
        <?php endif; ?>

        <?php if ($product['stock'] > 0): ?>
        <!-- Quantity + Add to Cart -->
        <div style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap;margin-bottom:1.2rem;">
            <div class="qty-control" style="border:2px solid var(--border);border-radius:var(--radius-sm);overflow:hidden;">
                <button type="button" onclick="changeQty(this,-1)" style="width:36px;height:42px;border:none;background:var(--bg-page);font-size:1.1rem;font-weight:700;cursor:pointer;color:var(--text-dark);">−</button>
                <input type="number" id="detailQty" value="1" min="1" max="99" 
                       style="width:50px;height:42px;border:none;text-align:center;font-family:var(--font);font-size:1rem;font-weight:700;color:var(--text-dark);outline:none;">
                <button type="button" onclick="changeQty(this,1)" style="width:36px;height:42px;border:none;background:var(--bg-page);font-size:1.1rem;font-weight:700;cursor:pointer;color:var(--text-dark);">+</button>
            </div>
            <a href="/banhaisan/cart/add/<?php echo $product['id']; ?>" class="btn btn-accent btn-lg" style="flex:1;min-width:200px;" id="btnAddCartDetail">
                🛒 Thêm vào giỏ hàng
            </a>
        </div>

        <!-- Quick Buy -->
        <a href="/banhaisan/cart/add/<?php echo $product['id']; ?>" class="btn btn-outline btn-block" style="margin-bottom:1.2rem;">
            ⚡ Mua ngay
        </a>
        <script>
        document.getElementById('btnAddCartDetail').addEventListener('click', function(e) {
            e.preventDefault();
            const qty = document.getElementById('detailQty').value;
            window.location.href = this.href + '?qty=' + qty;
        });
        </script>
        <?php else: ?>
        <div style="margin-bottom:1.2rem;">
            <button disabled class="btn btn-block" style="background:#e0e0e0;color:#888;border:none;cursor:not-allowed;">
                ❌ Sản phẩm tạm hết hàng
            </button>
        </div>
        <?php endif; ?>

        <!-- Guarantee Info -->
        <div style="background:var(--bg-light);border-radius:var(--radius-md);padding:1rem;font-size:0.82rem;color:var(--text-body);display:grid;grid-template-columns:1fr 1fr;gap:0.5rem;">
            <span>✅ Cam kết hoàn tiền 100%</span>
            <span>🚚 Giao trong 2 giờ nội thành</span>
            <span>🌊 Đánh bắt tươi mỗi ngày</span>
            <span>📦 Đóng gói đảm bảo vệ sinh</span>
        </div>
    </div>
</div>

<!-- Đánh Giá & Nhận Xét -->
<div class="section-header" style="margin-top:3rem;border-top:1px solid var(--border-light);padding-top:2rem;">
    <h2>⭐ Đánh Giá & Nhận Xét</h2>
</div>
<div style="background:var(--bg-card);padding:1.5rem;border-radius:var(--radius-lg);box-shadow:var(--shadow-sm);margin-bottom:2rem;">
    <?php if ($canReview): ?>
        <div style="background:var(--bg-light);padding:1rem;border-radius:var(--radius-md);margin-bottom:1.5rem;border:1px solid var(--border-light);">
            <h4 style="margin:0 0 0.5rem 0;color:var(--text-dark);">Viết đánh giá của bạn</h4>
            <form action="/banhaisan/customer/submit_review" method="POST" style="display:flex;flex-direction:column;gap:0.75rem;">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <input type="hidden" name="order_id" value="<?php echo $orderIdToReview; ?>">
                
                <div style="display:flex;align-items:center;gap:0.5rem;">
                    <label style="font-weight:600;color:var(--text-dark);font-size:0.9rem;">Chất lượng (1-5 sao):</label>
                    <select name="rating" required style="padding:0.3rem;border-radius:4px;border:1px solid #ccc;outline:none;">
                        <option value="5">⭐⭐⭐⭐⭐ Tuyệt vời</option>
                        <option value="4">⭐⭐⭐⭐ Rất tốt</option>
                        <option value="3">⭐⭐⭐ Tạm được</option>
                        <option value="2">⭐⭐ Kém</option>
                        <option value="1">⭐ Rất tệ</option>
                    </select>
                </div>
                
                <textarea name="comment" rows="3" placeholder="Chia sẻ cảm nhận của bạn về sản phẩm này..." required style="width:100%;padding:0.75rem;border:1px solid #ccc;border-radius:6px;outline:none;font-family:inherit;font-size:0.9rem;resize:vertical;"></textarea>
                
                <div style="text-align:right;">
                    <button type="submit" class="btn btn-primary" style="padding:0.5rem 1.5rem;">Gửi Đánh Giá</button>
                </div>
            </form>
        </div>
    <?php endif; ?>

    <?php if (empty($reviews)): ?>
        <p style="text-align:center;color:var(--text-light);padding:2rem 0;">Chưa có đánh giá nào cho sản phẩm này.</p>
    <?php else: ?>
        <div style="display:flex;flex-direction:column;gap:1.5rem;">
            <?php foreach ($reviews as $rev): ?>
                <div style="display:flex;gap:1rem;border-bottom:1px solid var(--border-light);padding-bottom:1.5rem;last-child:border-bottom:none;last-child:padding-bottom:0;">
                    <div style="flex-shrink:0;">
                        <?php if(!empty($rev['avatar'])): ?>
                            <img src="/banhaisan/public/images/avatars/<?php echo htmlspecialchars($rev['avatar']); ?>" style="width:40px;height:40px;border-radius:50%;object-fit:cover;">
                        <?php else: ?>
                            <div style="width:40px;height:40px;border-radius:50%;background:var(--primary);color:white;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:1.1rem;">
                                <?php echo strtoupper(substr($rev['fullname'] ?: 'U', 0, 1)); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div>
                        <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.25rem;">
                            <strong style="color:var(--text-dark);font-size:0.95rem;"><?php echo htmlspecialchars($rev['fullname']); ?></strong>
                            <span style="color:#FFA000;font-size:0.9rem;letter-spacing:2px;"><?php echo str_repeat('★', $rev['rating']) . str_repeat('☆', 5 - $rev['rating']); ?></span>
                            <span style="color:var(--text-light);font-size:0.75rem;"><?php echo date('d/m/Y H:i', strtotime($rev['created_at'])); ?></span>
                        </div>
                        <p style="margin:0;color:var(--text-body);font-size:0.9rem;line-height:1.5;">
                            <?php echo nl2br(htmlspecialchars($rev['comment'])); ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
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

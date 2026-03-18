<?php ob_start(); ?>

<!-- Hero Banner -->
<div class="hero-banner">
    <div class="hero-content">
        <div class="hero-badge">🌊 Tươi từ biển đến bàn ăn</div>
        <h1 class="hero-title">Hải Sản <span>Tươi Sống</span><br>Mỗi Ngày</h1>
        <p class="hero-subtitle">Chất lượng đảm bảo từ ngư dân trực tiếp. Giao hàng trong 2 giờ, cam kết hoàn tiền nếu không tươi.</p>
        <div class="hero-actions">
            <a href="#products" class="btn btn-accent btn-lg">🛒 Mua ngay</a>
            <a href="#" class="btn btn-outline btn-lg" style="color:white;border-color:rgba(255,255,255,0.5);">Xem ưu đãi</a>
        </div>
    </div>
    <div class="hero-emoji">🦞</div>
</div>

<!-- USP Strip -->
<div class="usp-strip">
    <div class="usp-card">
        <div class="usp-icon">🌊</div>
        <h4>Tươi 100% mỗi ngày</h4>
        <p>Hải sản sống hoặc làm lạnh dưới 4°C</p>
    </div>
    <div class="usp-card">
        <div class="usp-icon">🚚</div>
        <h4>Giao trong 2 giờ</h4>
        <p>Nội thành TP.HCM miễn phí từ 500k</p>
    </div>
    <div class="usp-card">
        <div class="usp-icon">✅</div>
        <h4>Cam kết hoàn tiền</h4>
        <p>Không tươi → hoàn tiền 100%</p>
    </div>
    <div class="usp-card">
        <div class="usp-icon">💰</div>
        <h4>Giá tốt nhất</h4>
        <p>Trực tiếp từ ngư dân, không qua trung gian</p>
    </div>
</div>

<!-- Products Section -->
<div id="products">
    <div class="section-header">
        <h2>🦀 Hải Sản Ngày Hôm Nay</h2>
        <?php if(!empty($_GET['q']) || !empty($_GET['category']) || !empty($_GET['min_price']) || !empty($_GET['max_price']) || !empty($_GET['sort'])): ?>
            <a href="/banhaisan/product/index">✕ Xóa bộ lọc</a>
        <?php endif; ?>
    </div>

    <!-- Filter Bar -->
    <div class="filter-bar">
        <form action="/banhaisan/product/index" method="GET" id="filterForm">
            <div class="filter-row">
                <span class="filter-label">Loại:</span>
                <div class="filter-pills">
                    <a href="/banhaisan/product/index" class="pill <?php echo (!isset($_GET['category']) || $_GET['category'] == '') ? 'active' : ''; ?>">Tất cả</a>
                    <a href="/banhaisan/product/index?category=Tươi sống" class="pill <?php echo (isset($_GET['category']) && $_GET['category'] == 'Tươi sống') ? 'active' : ''; ?>">🐟 Tươi sống</a>
                    <a href="/banhaisan/product/index?category=Đông lạnh" class="pill <?php echo (isset($_GET['category']) && $_GET['category'] == 'Đông lạnh') ? 'active' : ''; ?>">❄️ Đông lạnh</a>
                    <a href="/banhaisan/product/index?category=tom" class="pill <?php echo (isset($_GET['category']) && $_GET['category'] == 'tom') ? 'active' : ''; ?>">🍤 Tôm</a>
                    <a href="/banhaisan/product/index?category=cua" class="pill <?php echo (isset($_GET['category']) && $_GET['category'] == 'cua') ? 'active' : ''; ?>">🦀 Cua</a>
                    <a href="/banhaisan/product/index?category=oc" class="pill <?php echo (isset($_GET['category']) && $_GET['category'] == 'oc') ? 'active' : ''; ?>">🐚 Ốc</a>
                </div>

                <div class="filter-inputs">
                    <input type="number" name="min_price" placeholder="Giá từ" value="<?php echo htmlspecialchars($_GET['min_price'] ?? ''); ?>">
                    <span style="color:var(--text-light);font-size:0.85rem;">—</span>
                    <input type="number" name="max_price" placeholder="Đến giá" value="<?php echo htmlspecialchars($_GET['max_price'] ?? ''); ?>">
                    
                    <select name="sort" style="padding:0.4rem;border:1px solid var(--border);border-radius:var(--radius-sm);font-size:0.85rem;outline:none;background:white;">
                        <option value="">Sắp xếp: Mới nhất</option>
                        <option value="price_asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'price_asc') ? 'selected' : ''; ?>>Giá: Thấp đến Cao</option>
                        <option value="price_desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'price_desc') ? 'selected' : ''; ?>>Giá: Cao đến Thấp</option>
                    </select>

                    <input type="hidden" name="q" value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>">
                    <?php if (isset($_GET['category'])): ?>
                        <input type="hidden" name="category" value="<?php echo htmlspecialchars($_GET['category']); ?>">
                    <?php endif; ?>
                    <button type="submit" class="btn btn-sm">Lọc</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Product Grid -->
    <?php if (empty($products)): ?>
    <div class="empty-state">
        <div class="empty-icon">🔍</div>
        <h3>Không tìm thấy sản phẩm</h3>
        <p>Hãy thử thay đổi bộ lọc hoặc từ khóa tìm kiếm</p>
        <a href="/banhaisan/product/index" class="btn">Xem tất cả sản phẩm</a>
    </div>
    <?php else: ?>
    <div class="product-grid">
        <?php foreach ($products as $i => $product): ?>
            <div class="product-card">
                <!-- Badges -->
                <div class="product-badge">
                    <?php if ($i < 2): ?><span class="badge badge-hot">🔥 Hot</span><?php endif; ?>
                    <?php if ($product['price'] > 200000): ?><span class="badge badge-new">⭐ Premium</span><?php endif; ?>
                </div>

                <!-- Image -->
                <div class="product-image">
                    <img 
                        src="/banhaisan/public/images/<?php echo htmlspecialchars($product['image']); ?>" 
                        alt="<?php echo htmlspecialchars($product['name']); ?>"
                        onerror="this.src='https://placehold.co/300x300/e0f4f4/0a7075?text=🦐'"
                        loading="lazy"
                    >
                    <div class="product-overlay">
                        <a href="/banhaisan/product/detail/<?php echo $product['id']; ?>" class="btn-quick-view">👁 Xem chi tiết</a>
                    </div>
                </div>

                <!-- Info -->
                <div class="product-info">
                    <?php if (!empty($product['category'])): ?>
                        <span class="product-category"><?php echo htmlspecialchars($product['category']); ?></span>
                    <?php endif; ?>
                    <h3 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h3>
                    <div class="product-price-row">
                        <span class="product-price"><?php echo number_format($product['price'], 0, ',', '.'); ?>đ</span>
                        <span class="product-unit">/ kg</span>
                    </div>

                    <div class="product-actions">
                        <div class="qty-control">
                            <button type="button" onclick="changeQty(this, -1)">−</button>
                            <input type="number" value="1" min="1" max="99" name="qty_<?php echo $product['id']; ?>">
                            <button type="button" onclick="changeQty(this, 1)">+</button>
                        </div>
                        <a href="/banhaisan/cart/add/<?php echo $product['id']; ?>" class="btn-cart">
                            🛒 Thêm vào giỏ
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<?php $content = ob_get_clean(); include __DIR__ . '/../layout.php'; ?>

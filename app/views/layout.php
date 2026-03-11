<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' | ' : ''; ?>Hải Sản Tươi Sống - Giao Hàng Tận Nơi</title>
    <meta name="description" content="Mua hải sản tươi sống trực tuyến - Tôm, Cua, Cá, Ốc chất lượng cao. Giao hàng trong 2 giờ tại TP.HCM.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/banhaisan/public/css/style.css">
</head>
<body>

    <!-- Header Top Bar -->
    <div class="header-top">
        <span>🚚 Miễn phí giao hàng đơn từ 500.000đ</span>
        <span>📞 Hotline: 0909 123 456</span>
        <span>🕐 T2-CN: 6:00 - 22:00</span>
    </div>

    <header>
        <nav>
            <!-- Logo -->
            <div class="logo">
                <a href="/banhaisan">
                    <span class="logo-icon">🦞</span>
                    <div class="logo-text-wrap">
                        <span>HẢI SẢN TƯƠI</span>
                        <span class="logo-sub">Fresh Seafood Daily</span>
                    </div>
                </a>
            </div>

            <!-- Search Bar -->
            <div class="header-search">
                <form action="/banhaisan/product/index" method="GET">
                    <input type="text" name="q" placeholder="Tìm tôm, cua, cá, ốc..." value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>">
                    <button type="submit">🔍</button>
                </form>
            </div>

            <div class="nav-links">
                <?php if (isset($_SESSION['username'])): ?>
                    <a href="<?php echo $_SESSION['role']==='customer' ? '/banhaisan/customer/profile' : '/banhaisan/admin/dashboard'; ?>"
                       style="display:flex;flex-direction:column;line-height:1.1;padding:0.4rem 0.8rem;">
                        <span style="font-size:0.72rem;color:var(--text-light);">Xin chào,</span>
                        <span style="font-weight:700;font-size:0.88rem;"><?php echo htmlspecialchars(explode(' ', $_SESSION['username'])[0]); ?></span>
                    </a>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <a href="/banhaisan/admin/orders">📋 Đơn hàng</a>
                        <a href="/banhaisan/admin/statistics">📊 Thống kê</a>
                    <?php else: ?>
                        <a href="/banhaisan/customer/profile">👤 Tài khoản</a>
                    <?php endif; ?>
                    <a href="/banhaisan/auth/logout" class="btn-ghost" style="color:var(--text-light);">Đăng xuất</a>
                <?php else: ?>
                    <a href="/banhaisan/auth/login_customer">Đăng nhập</a>
                    <a href="/banhaisan/auth/register" class="btn-nav-accent" style="background:var(--accent);color:white;padding:0.45rem 1rem;border-radius:var(--radius-md);font-weight:600;">Đăng ký</a>
                <?php endif; ?>

                <a href="/banhaisan/cart/index" style="position:relative;padding:0.45rem 0.9rem;">
                    🛒
                    <?php 
                        $count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
                        if($count > 0) echo "<span class='cart-count'>$count</span>"; 
                    ?>
                </a>
            </div>
        </nav>
    </header>

    <!-- Category Navigation Bar -->
    <nav class="category-nav">
        <ul>
            <li><a href="/banhaisan/product/index" <?php echo (!isset($_GET['category'])) ? 'class="active"' : ''; ?>>🌊 Tất cả</a></li>
            <li><a href="/banhaisan/product/index?category=tom" <?php echo (isset($_GET['category']) && $_GET['category'] == 'tom') ? 'class="active"' : ''; ?>>🍤 Tôm</a></li>
            <li><a href="/banhaisan/product/index?category=cua" <?php echo (isset($_GET['category']) && $_GET['category'] == 'cua') ? 'class="active"' : ''; ?>>🦀 Cua</a></li>
            <li><a href="/banhaisan/product/index?category=ca" <?php echo (isset($_GET['category']) && $_GET['category'] == 'ca') ? 'class="active"' : ''; ?>>🐟 Cá</a></li>
            <li><a href="/banhaisan/product/index?category=oc" <?php echo (isset($_GET['category']) && $_GET['category'] == 'oc') ? 'class="active"' : ''; ?>>🐚 Ốc</a></li>
            <li><a href="/banhaisan/product/index?category=dac_san">🌟 Đặc sản</a></li>
        </ul>
    </nav>

    <main class="container">
        <?php echo $content; ?>
    </main>

    <!-- Footer -->
    <footer>
        <div class="footer-main">
            <!-- Brand -->
            <div class="footer-brand">
                <div class="logo-footer">
                    <span>🦞</span>
                    HẢI SẢN TƯƠI
                </div>
                <p>Chuyên cung cấp hải sản tươi sống chất lượng cao, được chọn lọc kỹ càng từ các vùng biển nổi tiếng. Cam kết tươi ngon hoặc hoàn tiền 100%.</p>
                <div class="social-links">
                    <a href="#" class="social-link" title="Facebook">📘</a>
                    <a href="#" class="social-link" title="Zalo">💬</a>
                    <a href="#" class="social-link" title="Instagram">📷</a>
                    <a href="#" class="social-link" title="YouTube">▶️</a>
                </div>
            </div>

            <!-- Danh mục -->
            <div class="footer-col">
                <h4>Sản Phẩm</h4>
                <ul>
                    <li><a href="/banhaisan/product/index?category=tom">🍤 Tôm các loại</a></li>
                    <li><a href="/banhaisan/product/index?category=cua">🦀 Cua & Ghẹ</a></li>
                    <li><a href="/banhaisan/product/index?category=ca">🐟 Cá tươi</a></li>
                    <li><a href="/banhaisan/product/index?category=oc">🐚 Ốc & Nhuyễn thể</a></li>
                    <li><a href="/banhaisan/product/index?category=dac_san">🌟 Hải sản đặc sản</a></li>
                </ul>
            </div>

            <!-- Chính sách -->
            <div class="footer-col">
                <h4>Hỗ Trợ</h4>
                <ul>
                    <li><a href="#">Chính sách giao hàng</a></li>
                    <li><a href="#">Chính sách đổi trả</a></li>
                    <li><a href="#">Cam kết chất lượng</a></li>
                    <li><a href="#">Hướng dẫn đặt hàng</a></li>
                    <li><a href="#">Câu hỏi thường gặp</a></li>
                </ul>
            </div>

            <!-- Liên hệ -->
            <div class="footer-col footer-contact">
                <h4>Liên Hệ</h4>
                <p>📍 123 Đường Nguyễn Trãi, Q.5, TP.HCM</p>
                <p>📞 <a href="tel:0909123456" style="color:rgba(255,255,255,0.65);">0909 123 456</a></p>
                <p>📧 <a href="mailto:info@haisan.vn" style="color:rgba(255,255,255,0.65);">info@haisan.vn</a></p>
                <p>🕐 T2 - CN: 6:00 - 22:00</p>
            </div>
        </div>

        <div class="footer-bottom">
            <p>© 2026 Hải Sản Tươi Sống. All rights reserved.</p>
            <p>Thiết kế bởi <strong style="color:rgba(255,255,255,0.9)">SeaFood Team</strong></p>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button class="back-to-top" id="backToTop" onclick="window.scrollTo({top:0,behavior:'smooth'})">↑</button>

    <!-- Toast Notification -->
    <div id="cartToast" style="
        position: fixed; bottom: 2rem; left: 50%; transform: translateX(-50%) translateY(100px);
        background: var(--text-dark); color: white; padding: 0.9rem 1.5rem;
        border-radius: var(--radius-md); box-shadow: var(--shadow-lg);
        font-size: 0.9rem; font-weight: 500; z-index: 9999;
        transition: transform 0.3s cubic-bezier(0.175,0.885,0.32,1.275), opacity 0.3s;
        opacity: 0; min-width: 260px; text-align: center;
        border-left: 4px solid var(--success);">
    </div>

    <script>
        // Back to top
        window.addEventListener('scroll', function() {
            document.getElementById('backToTop').classList.toggle('visible', window.scrollY > 300);
        });

        // Qty control
        function changeQty(btn, delta) {
            const input = btn.parentElement.querySelector('input');
            let val = parseInt(input.value) || 1;
            val = Math.max(1, val + delta);
            input.value = val;
        }

        // Toast notification
        function showToast(html, isError = false) {
            const t = document.getElementById('cartToast');
            t.innerHTML = html;
            t.style.borderLeftColor = isError ? 'var(--danger)' : 'var(--success)';
            t.style.opacity = '1';
            t.style.transform = 'translateX(-50%) translateY(0)';
            clearTimeout(window._toastTimer);
            window._toastTimer = setTimeout(() => {
                t.style.opacity = '0';
                t.style.transform = 'translateX(-50%) translateY(100px)';
            }, 3000);
        }

        // AJAX Add to Cart - bắt tất cả link có href chứa /cart/add/
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a[href*="/cart/add/"]');
            if (!link) return;

            e.preventDefault();

            // Lấy số lượng từ input qty gần nhất (nếu có)
            const card = link.closest('.product-card, .product-detail, form');
            let qty = 1;
            if (card) {
                const qtyInput = card.querySelector('input[type="number"]');
                if (qtyInput) qty = Math.max(1, parseInt(qtyInput.value) || 1);
            }

            const url = link.href + (link.href.includes('?') ? '&' : '?') + 'qty=' + qty;

            // Loading state
            const orig = link.innerHTML;
            link.innerHTML = '⏳ Đang thêm...';
            link.style.opacity = '0.7';

            fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                link.innerHTML = orig;
                link.style.opacity = '1';

                if (data.success) {
                    showToast(data.message);

                    // Cập nhật số lượng giỏ hàng trong header
                    const cartBadge = document.querySelector('.cart-count');
                    if (data.cart_count > 0) {
                        if (cartBadge) {
                            cartBadge.textContent = data.cart_count;
                        } else {
                            const cartLink = document.querySelector('a[href*="/cart/index"]');
                            if (cartLink) {
                                const badge = document.createElement('span');
                                badge.className = 'cart-count';
                                badge.textContent = data.cart_count;
                                cartLink.appendChild(badge);
                            }
                        }
                    }

                    // Đổi màu nút tạm thời
                    link.style.background = 'var(--success)';
                    setTimeout(() => { link.style.background = ''; }, 1500);
                } else {
                    showToast('❌ ' + (data.message || 'Có lỗi xảy ra'), true);
                }
            })
            .catch(() => {
                link.innerHTML = orig;
                link.style.opacity = '1';
                showToast('❌ Không thể kết nối server', true);
            });
        });
    </script>
</body>
</html>

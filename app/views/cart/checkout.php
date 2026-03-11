<?php ob_start(); ?>

<div class="checkout-grid">
    <!-- Left: Forms -->
    <div>
        <!-- Thông tin giao hàng -->
        <div class="checkout-section">
            <h3><span class="step-num">1</span> Thông Tin Giao Hàng</h3>
            <form action="/banhaisan/cart/process_checkout" method="POST" id="checkoutForm">
                <div class="form-group">
                    <label class="form-label" for="fullname">Họ và Tên *</label>
                    <input type="text" id="fullname" name="fullname" class="form-control" 
                           required placeholder="Nguyễn Văn A" 
                           value="<?php echo isset($_SESSION['fullname']) ? htmlspecialchars($_SESSION['fullname']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label class="form-label" for="phone">Số Điện Thoại *</label>
                    <input type="tel" id="phone" name="phone" class="form-control" 
                           required placeholder="09xxxxxxxx" maxlength="11"
                           pattern="[0-9]{9,11}">
                </div>

                <div class="form-group">
                    <label class="form-label" for="address">Địa Chỉ Giao Hàng *</label>
                    <textarea id="address" name="address" class="form-control" 
                              required placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="note">Ghi chú đơn hàng <span style="color:var(--text-light);font-weight:400;">(không bắt buộc)</span></label>
                    <textarea id="note" name="note" class="form-control"
                              placeholder="Giờ giao hàng, hướng dẫn tìm nhà, yêu cầu đặc biệt..." rows="2"></textarea>
                </div>
        </div>

        <!-- Phương thức thanh toán -->
        <div class="checkout-section">
            <h3><span class="step-num">2</span> Phương Thức Thanh Toán</h3>

            <label class="payment-option" id="lblCod" onclick="selectPayment('cod')">
                <input type="radio" name="payment_method" value="cod" id="cod" checked>
                <label for="cod" style="cursor:pointer;">
                    💵 Thanh toán khi nhận hàng (COD)
                </label>
                <span style="margin-left:auto;font-size:0.8rem;color:var(--text-light);">An toàn & tiện lợi</span>
            </label>

            <label class="payment-option" id="lblQr" onclick="selectPayment('qr')">
                <input type="radio" name="payment_method" value="qr" id="qr" onchange="toggleQr()">
                <label for="qr" style="cursor:pointer;">
                    📱 Chuyển khoản / QR Code
                </label>
                <span style="margin-left:auto;font-size:0.8rem;color:var(--success);">Giảm thêm 5%</span>
            </label>

            <!-- QR Code Box -->
            <div id="qrCodeBox" class="qr-box">
                <h4 style="color:var(--primary);margin-bottom:0.75rem;">Quét mã QR để thanh toán</h4>
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=HaiSanTuoi-<?php echo $totalPrice; ?>" 
                     alt="QR Thanh Toán" width="180" style="margin:0 auto;">
                <p style="margin-top:0.75rem;font-size:0.88rem;color:var(--text-body);">
                    Số tiền: <strong style="color:var(--danger);font-size:1.05rem;"><?php echo number_format($totalPrice, 0, ',', '.'); ?>đ</strong>
                </p>
                <p style="font-size:0.8rem;color:var(--text-light);">Ghi rõ SĐT trong nội dung chuyển khoản</p>
            </div>
        </div>

        <button type="submit" class="btn btn-accent btn-lg btn-block" style="font-size:1.05rem;" id="submitBtn">
            ✅ Hoàn Tất Đặt Hàng
        </button>
        </form>
    </div>

    <!-- Right: Order Summary -->
    <div>
        <div class="order-summary-card">
            <h3>📋 Tóm Tắt Đơn Hàng</h3>

            <ul style="margin-bottom:1.2rem;">
                <?php foreach ($cart as $item): ?>
                <li style="display:flex;justify-content:space-between;align-items:center;padding:0.6rem 0;border-bottom:1px solid var(--border-light);font-size:0.88rem;">
                    <span style="color:var(--text-body);">
                        <?php echo htmlspecialchars($item['name']); ?>
                        <span style="color:var(--text-light);"> × <?php echo $item['quantity']; ?></span>
                    </span>
                    <span style="font-weight:700;color:var(--text-dark);"><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?>đ</span>
                </li>
                <?php endforeach; ?>
            </ul>

            <?php 
                $subtotal = 0;
                foreach ($cart as $item) $subtotal += $item['price'] * $item['quantity'];
                $shippingFee = $shipping ?? ($subtotal >= 500000 ? 0 : 30000);
                $finalTotal = $subtotal + $shippingFee;
            ?>

            <div class="summary-line">
                <span>Tạm tính</span>
                <span><?php echo number_format($subtotal, 0, ',', '.'); ?>đ</span>
            </div>
            <div class="summary-line">
                <span>Phí giao hàng</span>
                <span>
                    <?php if ($shippingFee == 0): ?>
                        <span style="color:var(--success);font-weight:600;">Miễn phí</span>
                    <?php else: ?>
                        <?php echo number_format($shippingFee, 0, ',', '.'); ?>đ
                    <?php endif; ?>
                </span>
            </div>

            </div>

            <!-- Voucher -->
            <div style="padding:0.75rem 0;">
                <div style="display:flex;gap:0.5rem;margin-bottom:0.5rem;">
                    <input type="text" id="voucherCode" placeholder="Nhập mã giảm giá..."
                           style="flex:1;padding:0.5rem 0.75rem;border:1.5px solid var(--border);border-radius:var(--radius-sm);font-size:0.85rem;outline:none;"
                           oninput="this.value=this.value.toUpperCase()">
                    <button type="button" onclick="applyVoucher()"
                            style="padding:0.5rem 0.9rem;background:var(--primary);color:white;border:none;border-radius:var(--radius-sm);font-size:0.82rem;cursor:pointer;white-space:nowrap;">
                        Áp dụng
                    </button>
                </div>
                <div id="voucherMsg" style="font-size:0.8rem;display:none;"></div>
            </div>

            <!-- Discount line (hidden until voucher applied) -->
            <div id="discountLine" class="summary-line" style="display:none;color:var(--success);">
                <span>Giảm giá (voucher)</span>
                <span id="discountDisplay">-0đ</span>
            </div>
            <!-- Hidden inputs for discount -->
            <input type="hidden" name="discount_amount" id="discountAmount" value="0">
            <input type="hidden" name="voucher_code"    id="appliedVoucher" value="">

            <div class="summary-line total">
                <span>Tổng thanh toán</span>
                <span id="finalTotalDisplay"><?php echo number_format($finalTotal, 0, ',', '.'); ?>đ</span>
            </div>

            <!-- Cam kết -->
            <div style="margin-top:1.2rem;padding:0.8rem;background:var(--bg-light);border-radius:var(--radius-sm);font-size:0.8rem;color:var(--text-light);">
                🔒 Thông tin được bảo mật<br>
                🚚 Giao hàng trong 2 giờ<br>
                ✅ Cam kết hoàn tiền nếu không tươi
            </div>
        </div>
    </div>
</div>

<script>
function selectPayment(method) {
    document.getElementById('cod').checked = (method === 'cod');
    document.getElementById('qr').checked = (method === 'qr');
    document.getElementById('lblCod').classList.toggle('selected', method === 'cod');
    document.getElementById('lblQr').classList.toggle('selected', method === 'qr');
    document.getElementById('qrCodeBox').style.display = (method === 'qr') ? 'block' : 'none';
}
document.getElementById('lblCod').classList.add('selected');

// Phone validation
document.getElementById('phone').addEventListener('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '');
});

// Voucher AJAX
const baseTotal = <?php echo $finalTotal; ?>;

function applyVoucher() {
    const code = document.getElementById('voucherCode').value.trim();
    const msg  = document.getElementById('voucherMsg');

    if (!code) { showVoucherMsg('Vui lòng nhập mã voucher', false); return; }

    fetch('/banhaisan/customer/check_voucher', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'code=' + encodeURIComponent(code) + '&total=' + baseTotal
    })
    .then(r => r.json())
    .then(d => {
        if (d.valid) {
            showVoucherMsg('✅ ' + d.message, true);
            document.getElementById('discountLine').style.display  = 'flex';
            document.getElementById('discountDisplay').textContent  = '-' + Math.round(d.discount).toLocaleString('vi-VN') + 'đ';
            document.getElementById('discountAmount').value         = d.discount;
            document.getElementById('appliedVoucher').value         = code;
            const newTotal = baseTotal - d.discount;
            document.getElementById('finalTotalDisplay').textContent = Math.round(newTotal).toLocaleString('vi-VN') + 'đ';
        } else {
            showVoucherMsg('❌ ' + d.message, false);
            resetDiscount();
        }
    })
    .catch(() => { showVoucherMsg('❌ Lỗi kết nối', false); });
}

function showVoucherMsg(text, ok) {
    const el = document.getElementById('voucherMsg');
    el.innerHTML = text;
    el.style.display = 'block';
    el.style.color   = ok ? 'var(--success)' : 'var(--danger)';
}

function resetDiscount() {
    document.getElementById('discountLine').style.display   = 'none';
    document.getElementById('discountAmount').value          = 0;
    document.getElementById('appliedVoucher').value          = '';
    document.getElementById('finalTotalDisplay').textContent = baseTotal.toLocaleString('vi-VN') + 'đ';
}
</script>

<?php $content = ob_get_clean(); include __DIR__ . '/../layout.php'; ?>

<?php ob_start(); ?>

<div style="max-width:600px; margin: 2rem auto; text-align:center; padding: 2rem; background: #fff; border-radius: var(--radius); box-shadow: var(--shadow-md);">
    <h2 style="color:var(--primary); margin-bottom: 1rem;">Thanh Toán <?php echo $order['payment_method'] === 'cod' ? 'Cọc 50%' : 'Đơn Hàng'; ?></h2>
    <p style="color:var(--text-light); margin-bottom: 2rem;">Vui lòng quét mã QR dưới đây để hoàn tất việc thanh toán <?php echo $order['payment_method'] === 'cod' ? 'cọc 50%' : '100%'; ?> cho đơn hàng <strong>#<?php echo $orderId; ?></strong>.</p>

    <!-- QR Code -->
    <div style="background:var(--bg-light); padding: 1.5rem; border-radius: var(--radius-sm); display:inline-block; border: 1px solid var(--border-light); margin-bottom: 2rem;">
        <img src="https://img.vietqr.io/image/MB-1234567890-compact2.png?amount=<?php echo $paymentAmount; ?>&addInfo=DH<?php echo $orderId; ?>&accountName=DEMO%20ACCOUNT" 
             alt="QR Thanh Toán" width="250" style="margin:0 auto; border-radius: 8px;">
        
        <div style="margin-top: 1rem; text-align:left;">
            <div style="display:flex; justify-content:space-between; margin-bottom:0.5rem; font-size: 0.95rem;">
                <span style="color:var(--text-light);">Số tiền <?php echo $order['payment_method'] === 'cod' ? '(Cọc 50%)' : '(100%)'; ?>:</span>
                <span style="font-weight:700; color:var(--danger); font-size: 1.1rem;"><?php echo number_format($paymentAmount, 0, ',', '.'); ?>đ</span>
            </div>
            <div style="display:flex; justify-content:space-between; font-size: 0.95rem;">
                <span style="color:var(--text-light);">Lời nhắn:</span>
                <span style="font-weight:600;">DH<?php echo $orderId; ?></span>
            </div>
        </div>
    </div>

    <!-- Spinner & Status -->
    <div id="paymentStatus" style="display:flex; flex-direction:column; align-items:center; gap:0.5rem; color:var(--primary);">
        <div style="width: 24px; height: 24px; border: 3px solid var(--border); border-top: 3px solid var(--primary); border-radius: 50%; animation: spin 1s linear infinite;"></div>
        <p style="font-size:0.95rem; font-weight: 500;">Đang chờ nhận thanh toán...</p>
    </div>

    <!-- Nút Demo -->
    <div style="margin-top: 3rem; padding-top: 1.5rem; border-top: 1px dashed var(--border);">
        <p style="font-size:0.8rem; color:var(--text-light); margin-bottom:0.5rem;">[Dành cho Demo] Nhấn nút này để giả lập ngân hàng báo có tiền:</p>
        <button type="button" onclick="mockWebhook()" class="btn btn-outline" style="font-size:0.85rem; padding: 0.5rem 1rem;">
            🤖 Mô phỏng VietQR báo có
        </button>
    </div>
</div>

<style>
@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
</style>

<script>
    const orderIdToWait = <?php echo $order['id']; ?>;

    // Polling API mỗi 3 giây
    const pollInterval = setInterval(() => {
        fetch(`/banhaisan/cart/check_payment?id=${orderIdToWait}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                clearInterval(pollInterval);
                document.getElementById('paymentStatus').innerHTML = '<div style="color:var(--success); font-weight:bold; font-size:1.1rem;">✅ Nhận thanh toán thành công! Đang chuyển hướng...</div>';
                setTimeout(() => {
                    window.location.href = '/banhaisan/cart/order_success';
                }, 1500);
            }
        })
        .catch(err => console.error("Lỗi khi kiểm tra thanh toán:", err));
    }, 3000);

    // Xử lý nút giả lập webhook
    function mockWebhook() {
        fetch('/banhaisan/cart/mock_webhook', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${orderIdToWait}`
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                console.log("Mock Webhook Triggered Successfully!");
            } else {
                alert("Lỗi khi giả lập thanh toán.");
            }
        });
    }
</script>

<?php $content = ob_get_clean(); $pageTitle = 'Thanh Toán QRCode'; include __DIR__ . '/../layout.php'; ?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hóa Đơn #<?php echo str_pad($order['id'], 5, '0', STR_PAD_LEFT); ?></title>
    <style>
        body { font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.5; color: #000; margin: 0; padding: 20px; background: #fff; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); }
        .invoice-box table { width: 100%; line-height: inherit; text-align: left; }
        .invoice-box table td { padding: 5px; vertical-align: top; }
        .invoice-box table tr td:nth-child(2) { text-align: right; }
        .invoice-box table tr.top table td { padding-bottom: 20px; }
        .invoice-box table tr.top table td.title { font-size: 35px; line-height: 35px; color: #333; }
        .invoice-box table tr.information table td { padding-bottom: 40px; }
        .invoice-box table tr.heading td { background: #eee; border-bottom: 1px solid #ddd; font-weight: bold; }
        .invoice-box table tr.details td { padding-bottom: 20px; }
        .invoice-box table tr.item td{ border-bottom: 1px solid #eee; }
        .invoice-box table tr.item.last td { border-bottom: none; }
        .invoice-box table tr.total td:nth-child(2) { border-top: 2px solid #eee; font-weight: bold; }
        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }
        .no-print { display: block; margin-bottom: 20px; text-align: center; }
        @media print {
            .no-print { display: none; }
            .invoice-box { border: none; box-shadow: none; padding: 0; }
            body { padding: 0; background: #fff; }
        }
    </style>
</head>
<body onload="window.print();">

    <div class="no-print">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px; cursor: pointer; background: #0a7075; color: #fff; border: none; border-radius: 4px;">🖨️ In Hóa Đơn Lại</button>
        <button onclick="window.history.back()" style="padding: 10px 20px; font-size: 16px; cursor: pointer; background: #ccc; color: #333; border: none; border-radius: 4px; margin-left:10px;">⬅️ Trở Về</button>
    </div>

    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="4">
                    <table>
                        <tr>
                            <td class="title">
                                <span style="font-size: 24px; font-weight: 800; color: #0a7075;">🌊 BÁN HẢI SẢN</span>
                            </td>
                            <td>
                                <strong>Hóa Đơn #:</strong> <?php echo str_pad($order['id'], 5, '0', STR_PAD_LEFT); ?><br>
                                <strong>Ngày Đặt:</strong> <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?><br>
                                <strong>Trạng Thái:</strong> <?php 
                                    $s = $order['status'];
                                    if($s == 'pending') echo 'Chờ xác nhận';
                                    elseif($s == 'confirmed') echo 'Đã xác nhận';
                                    elseif($s == 'shipping') echo 'Đang giao';
                                    elseif($s == 'delivered') echo 'Đã giao';
                                    elseif($s == 'cancelled') echo 'Đã hủy';
                                ?><br>
                                <strong>Khung giờ giao:</strong> <?php echo htmlspecialchars($order['delivery_time'] ?? 'Càng sớm càng tốt'); ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="information">
                <td colspan="4">
                    <table>
                        <tr>
                            <td>
                                <strong>Thông tin Khách Hàng:</strong><br>
                                Họ Tên: <?php echo htmlspecialchars($order['customer_name']); ?><br>
                                Điện Thoại: <?php echo htmlspecialchars($order['customer_phone']); ?><br>
                                Địa Chỉ: <?php echo htmlspecialchars($order['customer_address']); ?>
                            </td>
                            <td>
                                <strong>Phương thức thanh toán:</strong><br>
                                <?php echo $order['payment_method'] === 'qr' ? 'Chuyển khoản (QR)' : 'Thanh toán lúc nhận (COD)'; ?><br>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="heading">
                <td>Sản Phẩm</td>
                <td class="text-center">Số lượng</td>
                <td class="text-right">Đơn giá</td>
                <td class="text-right">Thành tiền</td>
            </tr>

            <?php 
            $subtotal = 0;
            $items = $order['items'];
            $len = count($items);
            foreach ($items as $index => $item):
                $st = $item['quantity'] * $item['unit_price'];
                $subtotal += $st;
                $isLast = ($index === $len - 1) ? 'last' : '';
            ?>
            <tr class="item <?php echo $isLast; ?>">
                <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                <td class="text-center"><?php echo $item['quantity']; ?></td>
                <td class="text-right"><?php echo number_format($item['unit_price'], 0, ',', '.'); ?>đ</td>
                <td class="text-right"><?php echo number_format($st, 0, ',', '.'); ?>đ</td>
            </tr>
            <?php endforeach; ?>

            <tr class="total">
                <td colspan="3" class="text-right">Tạm tính:</td>
                <td class="text-right"><?php echo number_format($subtotal, 0, ',', '.'); ?>đ</td>
            </tr>
            <tr>
                <td colspan="3" class="text-right">Phí giao hàng:</td>
                <td class="text-right"><?php echo number_format($order['shipping_fee'], 0, ',', '.'); ?>đ</td>
            </tr>
            <?php if($order['discount_amount'] > 0): ?>
            <tr>
                <td colspan="3" class="text-right" style="color: green;">Giảm giá (Voucher):</td>
                <td class="text-right" style="color: green;">-<?php echo number_format($order['discount_amount'], 0, ',', '.'); ?>đ</td>
            </tr>
            <?php endif; ?>
            <tr class="total">
                <td colspan="3" class="text-right" style="font-size: 16px;"><strong>TỔNG TIỀN:</strong></td>
                <td class="text-right" style="font-size: 16px;"><strong><?php echo number_format($order['total_price'], 0, ',', '.'); ?>đ</strong></td>
            </tr>
        </table>
        
        <?php if (!empty($order['note'])): ?>
            <div style="margin-top: 20px; padding: 10px; background: #f9f9f9; border-left: 4px solid #0a7075;">
                <strong>📝 Ghi chú:</strong><br>
                <?php echo nl2br(htmlspecialchars($order['note'])); ?>
            </div>
        <?php endif; ?>

        <div style="margin-top: 40px; text-align: center; font-size: 12px; color: #777;">
            <p>Cảm ơn quý khách đã mua sắm tại Hải Sản Tươi Sống!</p>
            <p>Sản phẩm đã mua được cam kết tươi sống 100%. Mọi thắc mắc xin liên hệ 1900 xxxx.</p>
        </div>
    </div>
</body>
</html>

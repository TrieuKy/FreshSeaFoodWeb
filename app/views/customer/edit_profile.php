<?php ob_start(); $pageTitle = 'Chỉnh Sửa Hồ Sơ'; ?>

<div style="max-width:800px;margin:0 auto;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
        <h1 class="page-title" style="margin-bottom:0;">✏️ Chỉnh Sửa Hồ Sơ</h1>
        <a href="/banhaisan/customer/profile" class="btn btn-outline" style="font-size:0.85rem;">← Quay lại</a>
    </div>

    <div class="card" style="padding:2rem;">
        <form action="/banhaisan/customer/update_profile" method="POST" enctype="multipart/form-data">
            
            <div style="display:flex;gap:2rem;flex-wrap:wrap;">
                <!-- Cột Avatar -->
                <div style="flex:0 0 200px;text-align:center;">
                    <div style="margin-bottom:1rem;">
                        <?php if(!empty($user['avatar'])): ?>
                            <img src="/banhaisan/public/images/avatars/<?php echo htmlspecialchars($user['avatar']); ?>" style="width:120px;height:120px;border-radius:50%;object-fit:cover;border:3px solid var(--border-light);" alt="Avatar">
                        <?php else: ?>
                            <div style="width:120px;height:120px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--accent));color:white;font-size:3rem;display:flex;align-items:center;justify-content:center;margin:0 auto;font-weight:700;">
                                <?php echo strtoupper(substr($user['fullname'] ?? $user['username'], 0, 1)); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <label class="btn btn-sm btn-outline" style="cursor:pointer;display:inline-block;">
                        📷 Thay đổi ảnh
                        <input type="file" name="avatar" accept="image/*" style="display:none;" onchange="previewImage(this)">
                    </label>
                    <p style="font-size:0.75rem;color:var(--text-light);margin-top:0.5rem;">Định dạng: JPG, PNG, GIF</p>
                </div>
                
                <!-- Cột Thông tin -->
                <div style="flex:1;min-width:300px;">
                    <div class="form-group">
                        <label class="form-label" for="username">Tên đăng nhập <span style="color:var(--text-light);font-size:0.8rem;">(Không thể sửa)</span></label>
                        <input type="text" id="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" disabled style="background:#f8fafc;cursor:not-allowed;">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="fullname">Họ và tên</label>
                        <input type="text" id="fullname" name="fullname" class="form-control" value="<?php echo htmlspecialchars($user['fullname']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Giới tính</label>
                        <div style="display:flex;gap:1.5rem;align-items:center;padding:0.6rem 0;">
                            <label style="display:flex;align-items:center;gap:0.4rem;cursor:pointer;">
                                <input type="radio" name="gender" value="Nam" <?php echo ($user['gender'] == 'Nam') ? 'checked' : ''; ?>> Nam
                            </label>
                            <label style="display:flex;align-items:center;gap:0.4rem;cursor:pointer;">
                                <input type="radio" name="gender" value="Nữ" <?php echo ($user['gender'] == 'Nữ') ? 'checked' : ''; ?>> Nữ
                            </label>
                            <label style="display:flex;align-items:center;gap:0.4rem;cursor:pointer;">
                                <input type="radio" name="gender" value="Khác" <?php echo ($user['gender'] == 'Khác') ? 'checked' : ''; ?>> Khác
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="phone">Số điện thoại</label>
                        <input type="text" id="phone" name="phone" class="form-control" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="address">Địa chỉ liên hệ</label>
                        <textarea id="address" name="address" class="form-control" rows="3"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                    </div>

                    <div style="display:flex;justify-content:flex-end;gap:1rem;margin-top:2rem;">
                        <a href="/banhaisan/customer/profile" class="btn btn-ghost">Hủy</a>
                        <button type="submit" class="btn btn-accent">💾 Lưu Thay Đổi</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            let img = input.parentElement.previousElementSibling.querySelector('img');
            if(img) {
                img.src = e.target.result;
            } else {
                let div = input.parentElement.previousElementSibling;
                div.innerHTML = '<img src="' + e.target.result + '" style="width:120px;height:120px;border-radius:50%;object-fit:cover;border:3px solid var(--border-light);" alt="Avatar">';
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php $content = ob_get_clean(); include __DIR__ . '/../layout.php'; ?>

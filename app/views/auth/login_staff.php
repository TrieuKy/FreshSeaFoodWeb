<?php ob_start(); ?>

<div class="auth-form" style="border-top: 5px solid var(--danger-color);">
    <h3 style="text-align: center; margin-bottom: 1.5rem; color: var(--danger-color);">CỔNG NHÂN VIÊN & ADMIN</h3>
    
    <form action="/banhaisan/auth/process_login_staff" method="POST">
        <div class="form-group">
            <label>Mã nhân viên</label>
            <input type="text" name="code" class="form-control" placeholder="NV..." required>
        </div>
        <div class="form-group">
            <label>Mật khẩu hệ thống</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-block btn-danger">Truy cập hệ thống</button>
    </form>
</div>

<?php $content = ob_get_clean(); include __DIR__ . '/../layout.php'; ?>
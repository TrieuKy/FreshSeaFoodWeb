<?php ob_start(); $pageTitle = 'Đăng Nhập'; ?>

<div class="auth-card">
    <div class="auth-header">
        <div class="auth-icon">🦞</div>
        <h2>Chào mừng trở lại!</h2>
        <p>Đăng nhập để tiếp tục mua sắm</p>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form action="/banhaisan/auth/process_login_customer" method="POST">
        <div class="form-group">
            <label class="form-label" for="username">Tên đăng nhập</label>
            <input type="text" id="username" name="username" class="form-control" required autocomplete="username"
                   placeholder="Nhập tên đăng nhập của bạn">
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Mật khẩu</label>
            <div class="password-field">
                <input type="password" id="password" name="password" class="form-control" required autocomplete="current-password"
                       placeholder="Nhập mật khẩu">
                <button type="button" class="password-toggle" onclick="togglePass('password', this)">👁</button>
            </div>
        </div>

        <button type="submit" class="btn btn-accent btn-block" style="margin-top:0.5rem;padding:0.8rem;font-size:1rem;">
            Đăng Nhập →
        </button>
    </form>

    <p style="margin-top:1.5rem;text-align:center;font-size:0.88rem;color:var(--text-light);">
        Chưa có tài khoản? <a href="/banhaisan/auth/register" style="color:var(--primary);font-weight:700;">Đăng ký ngay</a>
    </p>
    <p style="margin-top:0.5rem;text-align:center;font-size:0.82rem;">
        <a href="/banhaisan/auth/login_staff" style="color:var(--text-light);">Đăng nhập với tư cách nhân viên</a>
    </p>
</div>

<script>
function togglePass(id, btn) {
    const input = document.getElementById(id);
    if (input.type === 'password') {
        input.type = 'text'; btn.textContent = '🙈';
    } else {
        input.type = 'password'; btn.textContent = '👁';
    }
}
</script>

<?php $content = ob_get_clean(); include __DIR__ . '/../layout.php'; ?>
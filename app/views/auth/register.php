<?php ob_start(); $pageTitle = 'Đăng Ký'; ?>

<div class="auth-card">
    <div class="auth-header">
        <div class="auth-icon">🌊</div>
        <h2>Tạo Tài Khoản Mới</h2>
        <p>Đăng ký để nhận ưu đãi và theo dõi đơn hàng</p>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form action="/banhaisan/auth/process_register" method="POST" id="registerForm">
        <div class="form-group">
            <label class="form-label" for="fullname">Họ và Tên</label>
            <input type="text" id="fullname" name="fullname" class="form-control" required
                   autocomplete="name" placeholder="Nguyễn Văn A">
        </div>

        <div class="form-group">
            <label class="form-label" for="username">Tên Đăng Nhập</label>
            <input type="text" id="username" name="username" class="form-control" required
                   autocomplete="username" placeholder="Tối thiểu 4 ký tự" minlength="4">
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Mật Khẩu</label>
            <div class="password-field">
                <input type="password" id="password" name="password" class="form-control" required
                       autocomplete="new-password" placeholder="Tối thiểu 6 ký tự" minlength="6"
                       oninput="checkPasswordStrength(this.value)">
                <button type="button" class="password-toggle" onclick="togglePass('password', this)">👁</button>
            </div>
            <!-- Password strength bar -->
            <div style="margin-top:0.4rem;height:4px;border-radius:4px;background:var(--border);overflow:hidden;">
                <div id="strengthBar" style="height:100%;width:0;transition:all 0.3s;border-radius:4px;"></div>
            </div>
            <small id="strengthText" style="font-size:0.75rem;color:var(--text-light);"></small>
        </div>

        <div class="form-group">
            <label class="form-label" for="confirm_password">Xác Nhận Mật Khẩu</label>
            <div class="password-field">
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required
                       autocomplete="new-password" placeholder="Nhập lại mật khẩu"
                       oninput="checkConfirm()">
                <button type="button" class="password-toggle" onclick="togglePass('confirm_password', this)">👁</button>
            </div>
            <small id="confirmMsg" style="font-size:0.75rem;"></small>
        </div>

        <button type="submit" class="btn btn-accent btn-block" style="margin-top:0.5rem;padding:0.8rem;font-size:1rem;">
            Tạo Tài Khoản 🎉
        </button>
    </form>

    <p style="margin-top:1.5rem;text-align:center;font-size:0.88rem;color:var(--text-light);">
        Đã có tài khoản? <a href="/banhaisan/auth/login_customer" style="color:var(--primary);font-weight:700;">Đăng nhập</a>
    </p>
</div>

<script>
function togglePass(id, btn) {
    const input = document.getElementById(id);
    input.type = input.type === 'password' ? 'text' : 'password';
    btn.textContent = input.type === 'password' ? '👁' : '🙈';
}

function checkPasswordStrength(val) {
    const bar = document.getElementById('strengthBar');
    const text = document.getElementById('strengthText');
    let score = 0;
    if (val.length >= 6) score++;
    if (val.length >= 10) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;
    const levels = [
        { pct:'20%', color:'#e74c3c', label:'Rất yếu' },
        { pct:'40%', color:'#e67e22', label:'Yếu' },
        { pct:'60%', color:'#f39c12', label:'Trung bình' },
        { pct:'80%', color:'#2980b9', label:'Tốt' },
        { pct:'100%', color:'#27ae60', label:'Rất mạnh' },
    ];
    const l = levels[Math.min(score, 4)];
    bar.style.width = l.pct;
    bar.style.background = l.color;
    text.textContent = val ? l.label : '';
    text.style.color = l.color;
}

function checkConfirm() {
    const p = document.getElementById('password').value;
    const c = document.getElementById('confirm_password').value;
    const msg = document.getElementById('confirmMsg');
    if (!c) { msg.textContent = ''; return; }
    if (p === c) {
        msg.textContent = '✅ Mật khẩu khớp';
        msg.style.color = 'var(--success)';
    } else {
        msg.textContent = '❌ Mật khẩu không khớp';
        msg.style.color = 'var(--danger)';
    }
}
</script>

<?php $content = ob_get_clean(); include __DIR__ . '/../layout.php'; ?>
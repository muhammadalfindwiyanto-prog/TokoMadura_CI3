<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin | Toko Madura Digital</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
</head>
<body class="login-page">
<div class="login-wrapper">
    <section class="login-hero">
        <div class="hero-badge">TOKO MADURA DIGITAL</div>
        <h1>Kelola produk, stok, dan pesanan pelanggan dalam satu tempat.</h1>
        <p>Website admin CodeIgniter 3 terintegrasi REST API dengan aplikasi Android.</p>
        <div class="hero-features"><span>✓ CRUD Produk</span><span>✓ Pesanan Android</span><span>✓ Stok Otomatis</span></div>
    </section>
    <section class="login-card">
        <div class="login-logo">TM</div>
        <h2>Login administrator</h2><p>Masuk untuk mengelola Toko Madura.</p>
        <?php if ($this->session->flashdata('error')): ?><div class="alert alert-danger"><?= html_escape($this->session->flashdata('error')) ?></div><?php endif; ?>
        <?= form_open('login') ?>
            <label>Username</label><input type="text" name="username" value="<?= set_value('username') ?>" placeholder="admin" autofocus><?= form_error('username') ?>
            <label>Password</label><input type="password" name="password" placeholder="Masukkan password"><?= form_error('password') ?>
            <button class="btn btn-primary btn-block" type="submit">Masuk Dashboard</button>
        <?= form_close() ?>
        <div class="demo-account"><strong>Akun admin</strong><code>admin / admin123</code></div>
    </section>
</div>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($title) ? html_escape($title).' | ' : '' ?>Toko Madura Digital</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
</head>
<body>
<div class="app-shell">
    <aside class="sidebar" id="sidebar">
        <a class="brand" href="<?= site_url('dashboard') ?>"><div class="brand-mark">TM</div><div><strong>Toko Madura</strong><small>Admin Digital</small></div></a>
        <nav class="nav-menu">
            <a class="<?= $active_menu === 'dashboard' ? 'active' : '' ?>" href="<?= site_url('dashboard') ?>"><span>⌂</span>Dashboard</a>
            <a class="<?= $active_menu === 'barang' ? 'active' : '' ?>" href="<?= site_url('barang') ?>"><span>▣</span>Produk</a>
            <a class="<?= $active_menu === 'kategori' ? 'active' : '' ?>" href="<?= site_url('kategori') ?>"><span>☷</span>Kategori</a>
            <a class="<?= $active_menu === 'pesanan' ? 'active' : '' ?>" href="<?= site_url('pesanan') ?>"><span>🧾</span>Pesanan</a>
            <a class="<?= $active_menu === 'api_docs' ? 'active' : '' ?>" href="<?= site_url('api-docs') ?>"><span>{ }</span>Dokumentasi API</a>
        </nav>
        <div class="sidebar-footer"><small>Login sebagai</small><strong><?= html_escape($this->session->userdata('nama')) ?></strong><a href="<?= site_url('logout') ?>">Keluar</a></div>
    </aside>
    <main class="main-content">
        <header class="topbar"><button class="menu-button" type="button" onclick="document.getElementById('sidebar').classList.toggle('open')">☰</button><div><h1><?= html_escape($title) ?></h1><p>Panel pengelolaan Toko Madura Digital</p></div><div class="user-chip"><span><?= strtoupper(substr((string)$this->session->userdata('nama'),0,1)) ?></span><div><strong><?= html_escape($this->session->userdata('nama')) ?></strong><small><?= ucfirst(html_escape($this->session->userdata('role'))) ?></small></div></div></header>
        <section class="page-content">
            <?php if ($this->session->flashdata('success')): ?><div class="alert alert-success"><?= html_escape($this->session->flashdata('success')) ?></div><?php endif; ?>
            <?php if ($this->session->flashdata('error')): ?><div class="alert alert-danger"><?= html_escape($this->session->flashdata('error')) ?></div><?php endif; ?>
            <?php $this->load->view($content_view); ?>
        </section>
    </main>
</div>
<script>document.querySelectorAll('[data-confirm]').forEach(function(b){b.addEventListener('click',function(e){if(!confirm(b.dataset.confirm))e.preventDefault();});});setTimeout(function(){document.querySelectorAll('.alert').forEach(function(a){a.classList.add('fade');});},4000);</script>
</body></html>

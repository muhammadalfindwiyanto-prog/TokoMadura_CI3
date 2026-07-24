<div class="stats-grid">
    <div class="stat-card"><div class="stat-icon">▣</div><div><small>Total Produk</small><strong><?= number_format($total_barang) ?></strong><span>Produk aktif di toko</span></div></div>
    <div class="stat-card"><div class="stat-icon">☷</div><div><small>Kategori</small><strong><?= number_format($total_kategori) ?></strong><span>Kelompok produk</span></div></div>
    <div class="stat-card"><div class="stat-icon">Σ</div><div><small>Total Stok</small><strong><?= number_format($total_stok) ?></strong><span>Semua satuan barang</span></div></div>
    <div class="stat-card danger"><div class="stat-icon">!</div><div><small>Pesanan Baru</small><strong><?= number_format($pesanan_baru) ?></strong><span>Perlu diproses</span></div></div>
</div>

<div class="card omzet-card"><div><small>Omzet Hari Ini</small><strong><?= rupiah($omzet_hari_ini) ?></strong></div><a class="btn btn-primary" href="<?= site_url('pesanan') ?>">Lihat Pesanan</a></div>

<div class="dashboard-grid">
    <div class="card">
        <div class="card-header"><div><h3>Pesanan Terbaru</h3><p>Transaksi dari aplikasi Android.</p></div><a class="btn btn-light btn-sm" href="<?= site_url('pesanan') ?>">Semua</a></div>
        <div class="table-responsive"><table><thead><tr><th>Kode</th><th>Pelanggan</th><th>Total</th><th>Status</th></tr></thead><tbody>
        <?php if (!$pesanan_terbaru): ?><tr><td colspan="4" class="empty">Belum ada pesanan.</td></tr><?php endif; ?>
        <?php foreach ($pesanan_terbaru as $row): ?><tr><td><a href="<?= site_url('pesanan/'.$row->id) ?>"><code><?= html_escape($row->kode_pesanan) ?></code></a></td><td><?= html_escape($row->nama_pelanggan) ?></td><td><?= rupiah($row->total) ?></td><td><?= status_pesanan_badge($row->status) ?></td></tr><?php endforeach; ?>
        </tbody></table></div>
    </div>
    <div class="card">
        <div class="card-header"><div><h3>Peringatan Stok</h3><p>Produk di bawah stok minimum.</p></div></div>
        <div class="stock-list">
        <?php if (!$stok_terendah): ?><div class="empty">Semua stok aman.</div><?php endif; ?>
        <?php foreach ($stok_terendah as $row): ?><a href="<?= site_url('barang/show/'.$row->id) ?>"><div><strong><?= html_escape($row->nama) ?></strong><small><?= html_escape($row->kode) ?> · minimum <?= $row->stok_minimum ?></small></div><span><?= $row->stok ?> <?= html_escape($row->satuan) ?></span></a><?php endforeach; ?>
        </div>
    </div>
</div>

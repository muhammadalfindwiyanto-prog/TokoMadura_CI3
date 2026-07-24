<div class="detail-grid">
    <div class="card detail-image"><img src="<?= foto_barang_url($item->foto) ?>" alt="<?= html_escape($item->nama) ?>"></div>
    <div class="card detail-card">
        <div class="card-header"><div><span class="badge badge-neutral"><?= html_escape($item->kategori_nama) ?></span><h3><?= html_escape($item->nama) ?></h3><p><code><?= html_escape($item->kode) ?></code></p></div><div><a class="btn btn-light" href="<?= site_url('barang') ?>">Kembali</a><a class="btn btn-primary" href="<?= site_url('barang/edit/'.$item->id) ?>">Edit</a></div></div>
        <div class="detail-values">
            <div><small>Harga Beli</small><strong><?= rupiah($item->harga_beli) ?></strong></div><div><small>Harga Jual</small><strong><?= rupiah($item->harga_jual) ?></strong></div>
            <div><small>Stok Saat Ini</small><strong><?= number_format($item->stok) ?> <?= html_escape($item->satuan) ?></strong></div><div><small>Stok Minimum</small><strong><?= number_format($item->stok_minimum) ?> <?= html_escape($item->satuan) ?></strong></div>
            <div><small>Status</small><strong><?= stok_badge($item->stok, $item->stok_minimum) ?></strong></div><div><small>Lokasi</small><strong><?= html_escape($item->lokasi ?: '-') ?></strong></div>
        </div>
        <div class="description"><small>Deskripsi</small><p><?= nl2br(html_escape($item->deskripsi ?: 'Tidak ada deskripsi.')) ?></p></div>
    </div>
</div>

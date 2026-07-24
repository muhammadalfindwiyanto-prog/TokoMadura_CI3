<?php $editing = !empty($item); ?>
<div class="form-card card">
    <div class="card-header"><div><h3><?= $editing ? 'Perbarui Data Produk' : 'Tambah Produk Baru' ?></h3><p>Kolom bertanda bintang wajib diisi.</p></div><a class="btn btn-light" href="<?= site_url('barang') ?>">Kembali</a></div>
    <?= form_open_multipart($editing ? 'barang/edit/'.$item->id : 'barang/create') ?>
    <div class="form-body form-grid">
        <div class="form-group"><label>Kode Produk <span>*</span></label><input type="text" name="kode" value="<?= set_value('kode', $editing ? $item->kode : '') ?>" placeholder="BRG-001"><?= form_error('kode') ?></div>
        <div class="form-group"><label>Nama Produk <span>*</span></label><input type="text" name="nama" value="<?= set_value('nama', $editing ? $item->nama : '') ?>" placeholder="Nama produk"><?= form_error('nama') ?></div>
        <div class="form-group"><label>Kategori <span>*</span></label><select name="kategori_id"><option value="">-- Pilih kategori --</option><?php foreach ($kategori as $kat): ?><option value="<?= $kat->id ?>" <?= set_select('kategori_id', $kat->id, $editing && $item->kategori_id == $kat->id) ?>><?= html_escape($kat->nama) ?></option><?php endforeach; ?></select><?= form_error('kategori_id') ?></div>
        <div class="form-group"><label>Satuan <span>*</span></label><select name="satuan"><?php foreach (array('pcs','unit','box','dus','pack','sachet','botol','roll','kg','liter','meter','rim') as $s): ?><option value="<?= $s ?>" <?= set_select('satuan', $s, $editing && $item->satuan === $s) ?>><?= strtoupper($s) ?></option><?php endforeach; ?></select><?= form_error('satuan') ?></div>
        <div class="form-group"><label>Harga Beli <span>*</span></label><input type="number" min="0" name="harga_beli" value="<?= set_value('harga_beli', $editing ? $item->harga_beli : 0) ?>"><?= form_error('harga_beli') ?></div>
        <div class="form-group"><label>Harga Jual <span>*</span></label><input type="number" min="0" name="harga_jual" value="<?= set_value('harga_jual', $editing ? $item->harga_jual : 0) ?>"><?= form_error('harga_jual') ?></div>
        <div class="form-group"><label>Jumlah Stok <span>*</span></label><input type="number" min="0" name="stok" value="<?= set_value('stok', $editing ? $item->stok : 0) ?>"><?= form_error('stok') ?></div>
        <div class="form-group"><label>Stok Minimum <span>*</span></label><input type="number" min="0" name="stok_minimum" value="<?= set_value('stok_minimum', $editing ? $item->stok_minimum : 5) ?>"><?= form_error('stok_minimum') ?></div>
        <div class="form-group"><label>Lokasi Penyimpanan</label><input type="text" name="lokasi" value="<?= set_value('lokasi', $editing ? $item->lokasi : '') ?>" placeholder="Contoh: Rak A-01"><?= form_error('lokasi') ?></div>
        <div class="form-group"><label>Foto Produk</label><input type="file" name="foto" accept="image/jpeg,image/png,image/webp"><small class="help-text">JPG/PNG/WEBP, maksimal 2 MB.</small><?php if ($editing && $item->foto): ?><img class="preview-image" src="<?= foto_barang_url($item->foto) ?>" alt="Foto lama"><?php endif; ?></div>
        <div class="form-group full"><label>Deskripsi</label><textarea name="deskripsi" rows="4" placeholder="Keterangan tambahan produk"><?= set_value('deskripsi', $editing ? $item->deskripsi : '') ?></textarea><?= form_error('deskripsi') ?></div>
    </div>
    <div class="form-footer"><a class="btn btn-light" href="<?= site_url('barang') ?>">Batal</a><button class="btn btn-primary" type="submit"><?= $editing ? 'Simpan Perubahan' : 'Simpan Produk' ?></button></div>
    <?= form_close() ?>
</div>

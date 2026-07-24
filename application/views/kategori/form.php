<?php $editing = !empty($item); ?>
<div class="form-card card">
    <div class="card-header"><div><h3><?= $editing ? 'Perbarui Kategori' : 'Kategori Baru' ?></h3><p>Isi informasi kategori dengan benar.</p></div><a class="btn btn-light" href="<?= site_url('kategori') ?>">Kembali</a></div>
    <?= form_open($editing ? 'kategori/edit/'.$item->id : 'kategori/create') ?>
    <div class="form-body">
        <div class="form-group"><label>Nama Kategori <span>*</span></label><input type="text" name="nama" value="<?= set_value('nama', $editing ? $item->nama : '') ?>" placeholder="Contoh: Elektronik"><?= form_error('nama') ?></div>
        <div class="form-group"><label>Deskripsi</label><textarea name="deskripsi" rows="4" placeholder="Keterangan singkat kategori"><?= set_value('deskripsi', $editing ? $item->deskripsi : '') ?></textarea><?= form_error('deskripsi') ?></div>
    </div>
    <div class="form-footer"><a class="btn btn-light" href="<?= site_url('kategori') ?>">Batal</a><button class="btn btn-primary" type="submit"><?= $editing ? 'Simpan Perubahan' : 'Simpan Kategori' ?></button></div>
    <?= form_close() ?>
</div>

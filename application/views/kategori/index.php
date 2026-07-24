<div class="card">
    <div class="card-header"><div><h3>Daftar Kategori</h3><p>Kategori membantu pengelompokan data produk.</p></div><a class="btn btn-primary" href="<?= site_url('kategori/create') ?>">+ Tambah Kategori</a></div>
    <div class="table-responsive"><table><thead><tr><th>No</th><th>Nama</th><th>Deskripsi</th><th>Jumlah Produk</th><th class="text-right">Aksi</th></tr></thead><tbody>
        <?php if (!$kategori): ?><tr><td colspan="5" class="empty">Belum ada kategori.</td></tr><?php endif; ?>
        <?php foreach ($kategori as $i => $row): ?><tr><td><?= $i + 1 ?></td><td><strong><?= html_escape($row->nama) ?></strong></td><td><?= html_escape($row->deskripsi ?: '-') ?></td><td><span class="badge badge-neutral"><?= (int) $row->jumlah_barang ?> produk</span></td><td class="actions text-right"><a class="btn btn-light btn-sm" href="<?= site_url('kategori/edit/'.$row->id) ?>">Edit</a><?= form_open('kategori/delete/'.$row->id, array('class'=>'inline-form')) ?><button class="btn btn-danger btn-sm" data-confirm="Hapus kategori ini?" type="submit">Hapus</button><?= form_close() ?></td></tr><?php endforeach; ?>
    </tbody></table></div>
</div>

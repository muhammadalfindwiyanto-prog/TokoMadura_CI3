<div class="card">
    <div class="card-header card-header-wrap"><div><h3>Daftar Produk</h3><p>Kelola informasi dan ketersediaan stok.</p></div><a class="btn btn-primary" href="<?= site_url('barang/create') ?>">+ Tambah Produk</a></div>
    <div class="toolbar">
        <?= form_open('barang', array('method'=>'get', 'class'=>'search-form')) ?><input type="search" name="q" value="<?= html_escape($keyword) ?>" placeholder="Cari kode, nama, atau kategori..."><button class="btn btn-light" type="submit">Cari</button><?php if ($keyword): ?><a class="btn btn-ghost" href="<?= site_url('barang') ?>">Reset</a><?php endif; ?><?= form_close() ?>
        <span class="result-count"><?= count($barang) ?> data ditemukan</span>
    </div>
    <div class="table-responsive"><table><thead><tr><th>Produk</th><th>Kode</th><th>Kategori</th><th>Harga Jual</th><th>Stok</th><th>Status</th><th class="text-right">Aksi</th></tr></thead><tbody>
        <?php if (!$barang): ?><tr><td colspan="7" class="empty">Data produk tidak ditemukan.</td></tr><?php endif; ?>
        <?php foreach ($barang as $row): ?><tr>
            <td><div class="item-cell"><img src="<?= foto_barang_url($row->foto) ?>" alt=""><div><strong><?= html_escape($row->nama) ?></strong><small><?= html_escape($row->lokasi ?: 'Lokasi belum diisi') ?></small></div></div></td>
            <td><code><?= html_escape($row->kode) ?></code></td><td><?= html_escape($row->kategori_nama) ?></td><td><?= rupiah($row->harga_jual) ?></td><td><strong><?= number_format($row->stok) ?></strong> <?= html_escape($row->satuan) ?></td><td><?= stok_badge($row->stok, $row->stok_minimum) ?></td>
            <td class="actions text-right"><a class="btn btn-light btn-sm" href="<?= site_url('barang/show/'.$row->id) ?>">Detail</a><a class="btn btn-light btn-sm" href="<?= site_url('barang/edit/'.$row->id) ?>">Edit</a><?= form_open('barang/delete/'.$row->id, array('class'=>'inline-form')) ?><button class="btn btn-danger btn-sm" data-confirm="Yakin ingin menghapus produk ini?" type="submit">Hapus</button><?= form_close() ?></td>
        </tr><?php endforeach; ?>
    </tbody></table></div>
</div>

<div class="card">
    <div class="card-header"><div><h3>Pesanan dari Android</h3><p>Kelola pesanan pelanggan dan status transaksi.</p></div></div>
    <div class="toolbar">
        <?= form_open('pesanan', array('method'=>'get', 'class'=>'search-form')) ?>
        <input type="search" name="q" value="<?= html_escape($keyword) ?>" placeholder="Cari kode atau pelanggan...">
        <select name="status">
            <option value="">Semua status</option>
            <?php foreach (array('baru'=>'Baru','diproses'=>'Diproses','selesai'=>'Selesai','dibatalkan'=>'Dibatalkan') as $key=>$label): ?>
                <option value="<?= $key ?>" <?= $status === $key ? 'selected' : '' ?>><?= $label ?></option>
            <?php endforeach; ?>
        </select>
        <button class="btn btn-light" type="submit">Filter</button>
        <?= form_close() ?>
        <span class="result-count"><?= count($pesanan) ?> pesanan</span>
    </div>
    <div class="table-responsive"><table><thead><tr><th>Kode</th><th>Pelanggan</th><th>Waktu</th><th>Total</th><th>Status</th><th class="text-right">Aksi</th></tr></thead><tbody>
    <?php if (!$pesanan): ?><tr><td colspan="6" class="empty">Pesanan tidak ditemukan.</td></tr><?php endif; ?>
    <?php foreach ($pesanan as $row): ?><tr>
        <td><code><?= html_escape($row->kode_pesanan) ?></code></td>
        <td><strong><?= html_escape($row->nama_pelanggan) ?></strong><br><small>@<?= html_escape($row->username) ?></small></td>
        <td><?= date('d/m/Y H:i', strtotime($row->created_at)) ?></td>
        <td><strong><?= rupiah($row->total) ?></strong></td>
        <td><?= status_pesanan_badge($row->status) ?></td>
        <td class="text-right"><a class="btn btn-light btn-sm" href="<?= site_url('pesanan/'.$row->id) ?>">Detail</a></td>
    </tr><?php endforeach; ?>
    </tbody></table></div>
</div>

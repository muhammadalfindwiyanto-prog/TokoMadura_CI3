<div class="order-layout">
    <div class="card">
        <div class="card-header"><div><h3><?= html_escape($order->kode_pesanan) ?></h3><p>Dibuat <?= date('d F Y H:i', strtotime($order->created_at)) ?></p></div><a class="btn btn-light" href="<?= site_url('pesanan') ?>">Kembali</a></div>
        <div class="order-summary">
            <div><small>Pelanggan</small><strong><?= html_escape($order->nama_pelanggan) ?></strong><span>@<?= html_escape($order->username) ?></span></div>
            <div><small>Metode Bayar</small><strong><?= ucfirst(html_escape($order->metode_pembayaran)) ?></strong><span>Bayar di kasir</span></div>
            <div><small>Status</small><?= status_pesanan_badge($order->status) ?></div>
            <div><small>Total</small><strong class="grand-total"><?= rupiah($order->total) ?></strong></div>
        </div>
        <div class="table-responsive"><table><thead><tr><th>Produk</th><th>Harga</th><th>Qty</th><th>Subtotal</th></tr></thead><tbody>
        <?php foreach ($details as $row): ?><tr><td><strong><?= html_escape($row->nama_barang) ?></strong></td><td><?= rupiah($row->harga) ?></td><td><?= number_format($row->qty) ?></td><td><strong><?= rupiah($row->subtotal) ?></strong></td></tr><?php endforeach; ?>
        </tbody></table></div>
        <div class="note-box"><small>Catatan pelanggan</small><p><?= html_escape($order->catatan ?: 'Tidak ada catatan.') ?></p></div>
    </div>
    <div class="card status-card">
        <div class="card-header"><div><h3>Ubah Status</h3><p>Perbarui progres transaksi.</p></div></div>
        <?= form_open('pesanan/'.$order->id.'/status') ?>
        <div class="form-body">
            <div class="form-group"><label>Status Pesanan</label><select name="status">
            <?php foreach (array('baru'=>'Baru','diproses'=>'Diproses','selesai'=>'Selesai','dibatalkan'=>'Dibatalkan') as $key=>$label): ?>
                <option value="<?= $key ?>" <?= $order->status === $key ? 'selected' : '' ?>><?= $label ?></option>
            <?php endforeach; ?></select></div>
            <p class="help-text">Saat pesanan dibatalkan, stok otomatis dikembalikan.</p>
            <button class="btn btn-primary btn-block" type="submit">Simpan Status</button>
        </div>
        <?= form_close() ?>
    </div>
</div>

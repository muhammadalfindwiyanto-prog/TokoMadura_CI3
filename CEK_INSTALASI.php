<?php
$checks = array(
    'PHP 7.4 atau lebih baru' => version_compare(PHP_VERSION, '7.4.0', '>='),
    'Folder application' => is_dir(__DIR__ . '/application'),
    'Folder system' => is_dir(__DIR__ . '/system'),
    'File database SQL' => is_file(__DIR__ . '/database/toko_madura.sql'),
    'Ekstensi mysqli' => extension_loaded('mysqli'),
    'Ekstensi json' => extension_loaded('json')
);
?><!doctype html><html lang="id"><head><meta charset="utf-8"><title>Cek Instalasi Toko Madura</title><style>body{font-family:Arial;background:#f3f6f5;padding:30px}.box{max-width:700px;margin:auto;background:white;padding:25px;border-radius:14px}li{padding:10px;margin:6px 0;background:#f8faf9}.ok{color:green}.bad{color:#c00}</style></head><body><div class="box"><h1>Cek Instalasi Toko Madura</h1><ul><?php foreach($checks as $name=>$ok): ?><li class="<?= $ok?'ok':'bad' ?>"><?= $ok?'✓':'✕' ?> <?= htmlspecialchars($name) ?></li><?php endforeach; ?></ul><p>Jika semua hijau, lanjut buka <a href="./">halaman login</a>.</p></div></body></html>

<?php
/**
 * panel.php — Satıcı idarə paneli (demo).
 * Real layihədə burada giriş (login) yoxlanmalıdır.
 */
require_once __DIR__ . '/includes/functions.php';

$me = find_store('rose-boutique');
$myProducts = products_of('rose-boutique');

$orders = [
    ['#1024', 'Aysel M.',  59, 'Təsdiqlənib', 'green',  '2 saat əvvəl'],
    ['#1023', 'Rəşad H.',  47, 'Hazırlanır',  'orange', '3 saat əvvəl'],
    ['#1022', 'Leyla Q.',  49, 'Çatdırılma',  'blue',   '4 saat əvvəl'],
    ['#1021', 'Elçin T.',  85, 'Çatdırıldı',  'gray',   '6 saat əvvəl'],
    ['#1020', 'Nərmin A.', 44, 'Çatdırıldı',  'gray',   'Dünən'],
    ['#1019', 'Orxan S.', 102, 'Ləğv edildi', 'red',    'Dünən'],
];
$statuses = ['Təsdiqlənib' => 'green', 'Hazırlanır' => 'orange', 'Çatdırılma' => 'blue', 'Çatdırıldı' => 'gray', 'Ləğv edildi' => 'red'];

$nav = [
    ['dashboard', 'İdarə Paneli', 'home'],
    ['mehsullar', 'Məhsullar', 'flower'],
    ['sifarisler', 'Sifarişlər', 'receipt'],
    ['profil', 'Mağaza Profili', 'store'],
    ['analitika', 'Analitika', 'chart'],
    ['bildirisler', 'Bildirişlər', 'bell'],
    ['parametrler', 'Parametrlər', 'gear'],
];
?><!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Satıcı paneli · <?= e(SITE_NAME) ?></title>
    <meta name="robots" content="noindex">
    <meta name="theme-color" content="#09090b">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap&subset=latin-ext" rel="stylesheet">
    <script>document.documentElement.classList.add('js');</script>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="dash">
    <aside class="dash__side" id="dashSide">
        <a class="logo" href="index.php"><?= cat_icon('qizilgul', 'red') ?><span>Flower<em>.az</em></span></a>
        <?php foreach ($nav as [$id, $label, $ic]): ?>
            <a class="item <?= $id === 'dashboard' ? 'is-on' : '' ?>" href="#<?= $id ?>" data-sec="<?= $id ?>"><?= icon($ic) ?> <?= e($label) ?></a>
        <?php endforeach; ?>
        <div class="spacer"></div>
        <a class="item" href="magaza.php?s=rose-boutique"><?= icon('store') ?> Mağazama bax</a>
        <a class="item" href="index.php"><?= icon('logout') ?> Çıxış</a>
    </aside>

    <div class="dash__main">
        <div class="dash__top">
            <button class="icon-btn dash-menu-btn" type="button" id="dashMenu" aria-label="Menyu"><?= icon('menu') ?></button>
            <h1 id="dashTitle">İdarə Paneli</h1>
            <div class="me"><i><?= bouquet_svg('red', 5) ?></i><?= e($me['name']) ?></div>
        </div>

        <div class="dash__body">
            <!-- ============ İDARƏ PANELİ ============ -->
            <section class="dsec is-active" id="sec-dashboard">
                <div class="kpis">
                    <div class="card kpi">
                        <div class="kpi__ic red"><?= icon('box') ?></div>
                        <b data-count="245">0</b><span>Ümumi Sifariş</span><span class="delta">▲ 12% keçən aya görə</span>
                    </div>
                    <div class="card kpi">
                        <div class="kpi__ic green"><?= icon('chart') ?></div>
                        <b><span data-count="18750" style="color:#fff;font-size:inherit">0</span><small>AZN</small></b><span>Ümumi Gəlir</span><span class="delta">▲ 8.4% keçən aya görə</span>
                    </div>
                    <div class="card kpi">
                        <div class="kpi__ic gold"><?= star_svg('ic') ?></div>
                        <b data-count="4.9" data-dec="1">0</b><span>Orta Reytinq</span><span class="delta">1.2K rəy</span>
                    </div>
                </div>

                <div class="card chart-card">
                    <div class="chart-card__head">
                        <h3>Gəlir Dinamikası</h3>
                        <select id="chartRange" aria-label="Dövr">
                            <option value="7">Son 7 gün</option>
                            <option value="30" selected>Son 30 gün</option>
                            <option value="90">Son 90 gün</option>
                        </select>
                    </div>
                    <div class="chart" id="chart"></div>
                </div>

                <div class="card chart-card">
                    <div class="chart-card__head"><h3>Son Sifarişlər</h3><a class="link-more" href="#sifarisler" data-sec="sifarisler">Hamısına bax <?= icon('arrow') ?></a></div>
                    <div class="table-wrap">
                        <table>
                            <thead><tr><th>№</th><th>Müştəri</th><th>Məbləğ</th><th>Status</th><th>Vaxt</th></tr></thead>
                            <tbody>
                                <?php foreach (array_slice($orders, 0, 4) as $o): ?>
                                    <tr><td><b><?= e($o[0]) ?></b></td><td><?= e($o[1]) ?></td><td><?= $o[2] ?> AZN</td><td><span class="pill <?= $o[4] ?>"><?= e($o[3]) ?></span></td><td class="muted"><?= e($o[5]) ?></td></tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- ============ MƏHSULLAR ============ -->
            <section class="dsec" id="sec-mehsullar">
                <div class="card chart-card">
                    <div class="chart-card__head">
                        <h3>Məhsullarım (<span id="prodCount"><?= count($myProducts) ?></span>)</h3>
                        <button class="btn btn-primary btn-sm" type="button" id="addProdBtn"><?= icon('plus') ?> Yeni məhsul</button>
                    </div>
                    <div class="table-wrap">
                        <table>
                            <thead><tr><th>Məhsul</th><th>Kateqoriya</th><th>Qiymət</th><th>Stok</th><th>Status</th><th></th></tr></thead>
                            <tbody id="prodRows">
                                <?php
                                $catName = array_column($categories, 'name', 'key');
                                foreach ($myProducts as $p): ?>
                                    <tr>
                                        <td><div class="cell-prod"><div class="thumb"><?= bouquet_svg($p['tone'], $p['id'] + 4) ?></div><?= e($p['name']) ?></div></td>
                                        <td class="muted"><?= e($catName[$p['cat']] ?? '') ?></td>
                                        <td><?= f((float)$p['price']) ?> AZN</td>
                                        <td><?= 8 + ($p['id'] * 7) % 40 ?></td>
                                        <td><span class="pill green">Aktiv</span></td>
                                        <td><button class="btn btn-ghost btn-sm js-del" type="button">Sil</button></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- ============ SİFARİŞLƏR ============ -->
            <section class="dsec" id="sec-sifarisler">
                <div class="card chart-card">
                    <div class="chart-card__head"><h3>Bütün sifarişlər</h3></div>
                    <div class="table-wrap">
                        <table>
                            <thead><tr><th>№</th><th>Müştəri</th><th>Məbləğ</th><th>Status</th><th>Vaxt</th></tr></thead>
                            <tbody>
                                <?php foreach ($orders as $o): ?>
                                    <tr>
                                        <td><b><?= e($o[0]) ?></b></td><td><?= e($o[1]) ?></td><td><?= $o[2] ?> AZN</td>
                                        <td>
                                            <select class="status-sel" style="width:auto;padding:7px 10px;font-size:13px" aria-label="Status">
                                                <?php foreach ($statuses as $st => $cl): ?>
                                                    <option <?= $st === $o[3] ? 'selected' : '' ?>><?= e($st) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td class="muted"><?= e($o[5]) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- ============ PROFİL ============ -->
            <section class="dsec" id="sec-profil">
                <div class="panel">
                    <h3>Mağaza profili</h3>
                    <form class="form" id="profileForm">
                        <div class="row">
                            <label>Mağaza adı<input value="<?= e($me['name']) ?>"></label>
                            <label>Telefon<input value="<?= e($me['phone']) ?>"></label>
                        </div>
                        <div class="row">
                            <label>Şəhər<input value="<?= e($me['city']) ?>"></label>
                            <label>İş saatları<input value="<?= e($me['hours']) ?>"></label>
                        </div>
                        <label>Haqqında<textarea rows="4"><?= e($me['about']) ?></textarea></label>
                        <button class="btn btn-primary" type="submit" style="justify-self:start">Yadda saxla</button>
                    </form>
                </div>
            </section>

            <!-- ============ ANALİTİKA ============ -->
            <section class="dsec" id="sec-analitika">
                <div class="dash-grid">
                    <div class="card chart-card">
                        <div class="chart-card__head"><h3>Kateqoriyalar üzrə satış</h3></div>
                        <div class="bar-list">
                            <?php foreach ([['Qızılgül', 62], ['Kompozisiyalar', 21], ['Orxideya', 9], ['Lalə', 5], ['Digər', 3]] as [$n, $v]): ?>
                                <div><header><span><?= e($n) ?></span><b><?= $v ?>%</b></header><i style="--w:<?= $v ?>%"></i></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="card chart-card">
                        <div class="chart-card__head"><h3>Mağaza göstəriciləri</h3></div>
                        <div class="info-list">
                            <div><span>Profil baxışları</span><b>12 480</b></div>
                            <div><span>Səbətə əlavə</span><b>3 204</b></div>
                            <div><span>Konversiya</span><b>7.6%</b></div>
                            <div><span>Təkrar müştəri</span><b>41%</b></div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ============ BİLDİRİŞLƏR ============ -->
            <section class="dsec" id="sec-bildirisler">
                <div class="card chart-card">
                    <div class="chart-card__head"><h3>Bildirişlər</h3><button class="btn btn-ghost btn-sm" type="button" id="markRead">Hamısını oxunmuş et</button></div>
                    <div class="notif"><span class="dot"></span><div><b>Yeni sifariş #1024</b><br><span class="muted">Aysel M. — Rəngarəng Buket, 59 AZN</span></div></div>
                    <div class="notif"><span class="dot"></span><div><b>Yeni rəy aldınız ★★★★★</b><br><span class="muted">“Buket şəkildəkindən də gözəl idi.”</span></div></div>
                    <div class="notif read"><span class="dot"></span><div><b>Mağazanız “Premium” siyahısındadır</b><br><span class="muted">Bu həftə ana səhifədə göstərilirsiniz.</span></div></div>
                </div>
            </section>

            <!-- ============ PARAMETRLƏR ============ -->
            <section class="dsec" id="sec-parametrler">
                <div class="panel">
                    <h3>Parametrlər</h3>
                    <div class="toggle-row"><div><b>Yeni sifariş bildirişləri</b><small>E-poçt və push bildirişi</small></div><label class="switch"><input type="checkbox" checked><i></i></label></div>
                    <div class="toggle-row"><div><b>Eyni gün çatdırılma</b><small>Saat 18:00-dək verilən sifarişlər üçün</small></div><label class="switch"><input type="checkbox" checked><i></i></label></div>
                    <div class="toggle-row"><div><b>Mağaza müvəqqəti bağlıdır</b><small>Məhsullar saytda gizlədilir</small></div><label class="switch"><input type="checkbox"><i></i></label></div>
                </div>
            </section>
        </div>
    </div>
</div>

<!-- Yeni məhsul modalı -->
<div class="modal" id="prodModal" hidden>
    <form class="modal__box" id="prodForm">
        <h3>Yeni məhsul əlavə et</h3>
        <label>Ad<input name="name" required placeholder="Məs: Qırmızı Qızılgüllər"></label>
        <div class="row">
            <label>Qiymət (AZN)<input name="price" type="number" min="1" step="0.5" required placeholder="49"></label>
            <label>Stok<input name="stock" type="number" min="0" value="20" required></label>
        </div>
        <div class="row">
            <label>Kateqoriya
                <select name="cat"><?php foreach ($categories as $c): ?><option value="<?= e($c['key']) ?>"><?= e($c['name']) ?></option><?php endforeach; ?></select>
            </label>
            <label>Rəng
                <select name="tone"><option value="red">Qırmızı</option><option value="white">Ağ</option><option value="pink">Çəhrayı</option><option value="mix">Qarışıq</option><option value="orchid">Bənövşəyi</option></select>
            </label>
        </div>
        <div class="modal__actions">
            <button class="btn btn-outline" type="button" id="prodCancel">Ləğv et</button>
            <button class="btn btn-primary" type="submit">Əlavə et</button>
        </div>
    </form>
</div>

<div class="toast" id="toast" role="status" aria-live="polite"></div>
<script>window.FLOWER = <?= json_encode(['palettes' => PALETTES], JSON_UNESCAPED_UNICODE) ?>;</script>
<script src="assets/js/panel.js" defer></script>
</body>
</html>

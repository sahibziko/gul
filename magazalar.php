<?php
require_once __DIR__ . '/includes/functions.php';

$page = ['title' => 'Mağazalar', 'active' => 'stores', 'desc' => 'Flower.az-dakı bütün gül mağazaları, reytinq və şəhər üzrə filtr.'];

// Kateqoriya üzrə məhsullar (ana səhifədəki kateqoriya kartlarından gəlir)
$kat = $_GET['kat'] ?? '';
$katInfo = null;
foreach ($categories as $c) { if ($c['key'] === $kat) { $katInfo = $c; } }
$katProducts = $katInfo ? array_values(array_filter($products, fn($p) => $p['cat'] === $kat)) : [];

$cities = array_values(array_unique(array_column($stores, 'city')));
sort($cities, SORT_LOCALE_STRING);

require __DIR__ . '/includes/header.php';
?>

<main>
    <section class="page-head">
        <div class="container">
            <div class="crumbs"><a href="index.php">Ana səhifə</a> / Mağazalar</div>
            <h1>Bütün Mağazalar</h1>
            <p>Yoxlanılmış satıcılar, real rəylər və eyni gün çatdırılma. Sizə uyğun mağazanı seçin.</p>
        </div>
    </section>

    <div class="container">
        <?php if ($katInfo): ?>
            <section class="section" style="padding-top:34px">
                <div class="section__head">
                    <h2><?= e($katInfo['name']) ?> kateqoriyası</h2>
                    <a class="link-more" href="magazalar.php">Filtri təmizlə <?= icon('close') ?></a>
                </div>
                <?php if ($katProducts): ?>
                    <div class="grid-4"><?php foreach ($katProducts as $p) { echo product_card($p, true); } ?></div>
                <?php else: ?>
                    <p class="muted">Bu kateqoriyada hələlik məhsul yoxdur.</p>
                <?php endif; ?>
            </section>
        <?php endif; ?>

        <div class="toolbar">
            <div class="chips" role="group" aria-label="Şəhər filtri">
                <button class="chip is-on" type="button" data-city="all">Hamısı</button>
                <?php foreach ($cities as $city): ?>
                    <button class="chip" type="button" data-city="<?= e($city) ?>"><?= e($city) ?></button>
                <?php endforeach; ?>
            </div>
            <div style="display:flex;gap:10px;flex-wrap:wrap">
                <input id="storeQ" type="search" placeholder="Mağaza adı..." style="width:200px" aria-label="Mağaza axtar">
                <select id="storeSort" aria-label="Sıralama">
                    <option value="premium">Premium əvvəl</option>
                    <option value="rating">Reytinqə görə</option>
                    <option value="name">Əlifba sırası</option>
                </select>
            </div>
        </div>
        <p class="muted" style="margin:-8px 0 18px"><b id="storeCount"><?= count($stores) ?></b> mağaza tapıldı</p>

        <div class="grid-4" id="storeList">
            <?php $i = 0; foreach ($stores as $slug => $s) { echo store_card($slug, $s, ++$i); } ?>
        </div>
        <div class="empty-state" id="storeEmpty" hidden>Heç bir mağaza tapılmadı. Axtarışı dəyişin.</div>

        <section class="section" style="padding-bottom:0">
            <div class="promo reveal" style="padding-left:34px">
                <div class="promo__bg"><?= rose_field_svg('pink', 14) ?></div>
                <div>
                    <h3>Gül mağazanız var?</h3>
                    <p>Flower.az-a qoşulun, minlərlə müştəriyə çatın. Qeydiyyat pulsuzdur.</p>
                </div>
                <a class="btn btn-primary btn-lg" href="magaza-ac.php">Mağazanı əlavə et <?= icon('arrow') ?></a>
            </div>
        </section>
    </div>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>

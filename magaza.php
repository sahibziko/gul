<?php
/**
 * magaza.php — Mağaza profil səhifəsi (əsas fayl)
 * Açılış: magaza.php?s=rose-boutique
 */
require_once __DIR__ . '/includes/functions.php';

$slug  = $_GET['s'] ?? 'rose-boutique';
$store = find_store($slug);
if (!$store) {
    http_response_code(404);
    $slug  = 'rose-boutique';
    $store = find_store($slug);
}
$items = products_of($slug);
$cats  = [];
foreach ($items as $p) { $cats[$p['cat']] = true; }

$page = [
    'title'  => $store['name'],
    'active' => 'stores',
    'desc'   => $store['name'] . ' — ' . $store['about'],
];
require __DIR__ . '/includes/header.php';
?>

<main>
    <div class="container" style="padding-top:18px">
        <div class="cover"><?= rose_field_svg($store['tone'], crc32($slug) % 90 + 1) ?></div>

        <div class="profile">
            <div class="avatar"><div><?= bouquet_svg($store['tone'], 5) ?></div></div>
            <div class="profile__info">
                <h1>
                    <?= e($store['name']) ?>
                    <?php if ($store['verified']): ?>
                        <svg class="verified" viewBox="0 0 24 24" aria-label="Təsdiqlənmiş mağaza"><path fill="currentColor" d="M12 2l2.4 1.8 3-.1 1 2.8 2.5 1.7-.9 2.9.9 2.9-2.5 1.7-1 2.8-3-.1L12 22l-2.4-1.8-3 .1-1-2.8L3.1 15.8 4 12.9 3.1 10l2.5-1.7 1-2.8 3 .1z"/><path fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M8.2 12.2l2.6 2.6 5-5.2"/></svg>
                    <?php endif; ?>
                </h1>
                <div class="profile__meta">
                    <span class="rating"><?= star_svg() ?> <b><?= number_format($store['rating'], 1) ?></b> <span>(<?= e($store['reviews_label']) ?> rəy)</span></span>
                    <span><?= e($store['products']) ?> məhsul</span>
                    <span><?= e($store['city']) ?></span>
                    <span><?= e($store['hours']) ?></span>
                </div>
            </div>
            <div class="profile__actions">
                <button class="btn btn-primary" type="button" id="followBtn" data-store="<?= e($slug) ?>">İzləmək</button>
                <a class="btn btn-outline" href="tel:<?= e(preg_replace('/\s+/', '', $store['phone'])) ?>">Zəng et</a>
            </div>
        </div>

        <div class="tabs" role="tablist">
            <button class="tab is-active" role="tab" data-tab="products" type="button">Məhsullar</button>
            <button class="tab" role="tab" data-tab="about" type="button">Haqqımızda</button>
            <button class="tab" role="tab" data-tab="reviews" type="button">Rəylər</button>
        </div>

        <!-- MƏHSULLAR -->
        <section class="tabpanel is-active" id="tab-products">
            <div class="shop">
                <aside class="side" aria-label="Kateqoriyalar">
                    <button type="button" class="is-on" data-cat="all"><span class="all-ic"><?= icon('flower') ?></span> Bütün Məhsullar</button>
                    <?php foreach ($categories as $c): if (!isset($cats[$c['key']])) continue; ?>
                        <button type="button" data-cat="<?= e($c['key']) ?>"><?= cat_icon($c['key'], $c['tone']) ?> <?= e($c['name']) ?></button>
                    <?php endforeach; ?>
                </aside>
                <div>
                    <div class="toolbar" style="margin-top:0">
                        <span class="muted"><?= count($items) ?> məhsul</span>
                        <select id="prodSort" aria-label="Sıralama">
                            <option value="def">Sıralama: Tövsiyə olunan</option>
                            <option value="low">Qiymət: aşağıdan yuxarı</option>
                            <option value="high">Qiymət: yuxarıdan aşağı</option>
                        </select>
                    </div>
                    <div class="grid-3" id="prodGrid">
                        <?php foreach ($items as $p) { echo product_card($p); } ?>
                    </div>
                    <div class="empty-state" id="prodEmpty" hidden>Bu kateqoriyada məhsul yoxdur.</div>
                </div>
            </div>
        </section>

        <!-- HAQQINDA -->
        <section class="tabpanel" id="tab-about">
            <div class="about-grid">
                <div class="panel">
                    <h3><?= e($store['name']) ?> haqqında</h3>
                    <p><?= e($store['about']) ?></p>
                </div>
                <div class="panel">
                    <h3>Məlumat</h3>
                    <div class="info-list">
                        <div><span>Şəhər</span><b><?= e($store['city']) ?></b></div>
                        <div><span>İş saatları</span><b><?= e($store['hours']) ?></b></div>
                        <div><span>Telefon</span><b><?= e($store['phone']) ?></b></div>
                        <div><span>Fəaliyyət başlanğıcı</span><b><?= (int)$store['since'] ?></b></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- RƏYLƏR -->
        <section class="tabpanel" id="tab-reviews">
            <div class="about-grid" style="grid-template-columns:1fr 2fr">
                <div class="panel rating-box">
                    <div class="big"><?= number_format($store['rating'], 1) ?></div>
                    <div class="rating" style="justify-content:center;margin-top:8px"><?= str_repeat(star_svg(), 5) ?></div>
                    <p class="muted" style="margin-top:6px"><?= e($store['reviews_label']) ?> rəy əsasında</p>
                    <div class="bars">
                        <?php foreach ([5 => 86, 4 => 9, 3 => 3, 2 => 1, 1 => 1] as $n => $w): ?>
                            <div><span><?= $n ?></span><i style="--w:<?= $w ?>%"></i><span><?= $w ?>%</span></div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div style="display:grid;gap:14px">
                    <?php foreach ($reviews as $r): ?>
                        <article class="review">
                            <div class="review__top">
                                <div class="review__av"><?= e(mb_substr($r['name'], 0, 1)) ?></div>
                                <div><b><?= e($r['name']) ?></b><br><small><?= e($r['date']) ?></small></div>
                                <div class="review__stars"><?= str_repeat(star_svg(), $r['rate']) ?></div>
                            </div>
                            <p><?= e($r['text']) ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </div>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>

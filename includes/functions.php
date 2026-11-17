<?php
declare(strict_types=1);

require_once __DIR__ . '/../data/data.php';

const PALETTES = [
    'red'    => ['#ff4b5f', '#b3122b', '#4d0613'],
    'white'  => ['#ffffff', '#e9e3d8', '#9c9488'],
    'pink'   => ['#ffc2d4', '#e5608a', '#8d2146'],
    'cream'  => ['#fff4d6', '#e8cf94', '#9a7d3c'],
    'orchid' => ['#f3b6ff', '#b84fd1', '#5a1a73'],
    'yellow' => ['#fff08a', '#f2b51f', '#9a5d06'],
    'orange' => ['#ffb37a', '#ee6a2c', '#8a2c0c'],
];
const MIX_TONES = ['red', 'pink', 'yellow', 'orange', 'white', 'orchid'];

function e(?string $s): string
{
    return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function money(float $n): string
{
    return rtrim(rtrim(number_format($n, 2, '.', ''), '0'), '.') . ' AZN';
}

function f(float $n): string
{
    return rtrim(rtrim(number_format($n, 2, '.', ''), '0'), '.');
}

/** Tək qızılgül (SVG qrupu) */
function rose_g(float $cx, float $cy, float $r, string $gradId, array $p, int $rot = 0): string
{
    $s = '<g transform="translate(' . f($cx) . ' ' . f($cy) . ') rotate(' . $rot . ')">';
    $s .= '<circle r="' . f($r) . '" fill="url(#' . $gradId . ')"/>';
    // ləçək xətləri
    for ($i = 0; $i < 6; $i++) {
        $a = $i * 60 + mt_rand(0, 25);
        $s .= '<path transform="rotate(' . $a . ')" d="M' . f(-$r * .72) . ' ' . f(-$r * .12) . ' A' . f($r * .78) . ' ' . f($r * .78)
            . ' 0 0 1 ' . f($r * .72) . ' ' . f(-$r * .12) . '" fill="none" stroke="' . $p[2] . '" stroke-opacity=".5" stroke-width="' . f(max(1.2, $r * .045)) . '" stroke-linecap="round"/>';
    }
    // mərkəz spiral
    $s .= '<circle r="' . f($r * .52) . '" fill="none" stroke="' . $p[2] . '" stroke-opacity=".55" stroke-width="' . f(max(1.2, $r * .05)) . '"/>';
    $s .= '<path d="M' . f(-$r * .3) . ' ' . f($r * .05) . ' A' . f($r * .3) . ' ' . f($r * .3) . ' 0 1 1 ' . f($r * .22) . ' ' . f($r * .2)
        . ' A' . f($r * .16) . ' ' . f($r * .16) . ' 0 1 1 ' . f(-$r * .06) . ' ' . f(-$r * .12)
        . '" fill="none" stroke="' . $p[2] . '" stroke-opacity=".75" stroke-width="' . f(max(1.2, $r * .05)) . '" stroke-linecap="round"/>';
    $s .= '<circle r="' . f($r * .06) . '" fill="' . $p[2] . '" fill-opacity=".8"/>';
    // parıltı
    $s .= '<ellipse cx="' . f(-$r * .3) . '" cy="' . f(-$r * .5) . '" rx="' . f($r * .38) . '" ry="' . f($r * .16) . '" fill="#fff" fill-opacity=".14" transform="rotate(-25)"/>';
    return $s . '</g>';
}

/** Qara qablaşdırmada qızılgül buketi */
function bouquet_svg(string $tone = 'red', int $seed = 1, string $class = ''): string
{
    static $n = 0;
    $n++;
    mt_srand($seed * 7919 + 13);
    $id = 'bq' . $n;
    $mix = ($tone === 'mix');
    $p = PALETTES[$tone] ?? PALETTES['red'];

    $o = '<svg class="' . e($class) . '" viewBox="0 -26 300 326" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><defs>';
    $tones = $mix ? MIX_TONES : [$tone];
    foreach ($tones as $t) {
        $pp = PALETTES[$t];
        $o .= '<radialGradient id="' . $id . $t . '" cx="38%" cy="32%" r="78%"><stop offset="0" stop-color="' . $pp[0] . '"/><stop offset=".58" stop-color="' . $pp[1] . '"/><stop offset="1" stop-color="' . $pp[2] . '"/></radialGradient>';
    }
    $o .= '<linearGradient id="' . $id . 'w" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#34343c"/><stop offset="1" stop-color="#0a0a0d"/></linearGradient>';
    $o .= '<linearGradient id="' . $id . 'w2" x1="1" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#23232a"/><stop offset="1" stop-color="#050506"/></linearGradient>';
    $o .= '<linearGradient id="' . $id . 'l" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#3b8a4a"/><stop offset="1" stop-color="#0f3a1c"/></linearGradient>';
    $o .= '<linearGradient id="' . $id . 'rb" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#ff3a55"/><stop offset="1" stop-color="#8a0c22"/></linearGradient>';
    $o .= '<filter id="' . $id . 's" x="-20%" y="-20%" width="140%" height="150%"><feDropShadow dx="0" dy="8" stdDeviation="9" flood-color="#000" flood-opacity=".55"/></filter>';
    $o .= '</defs>';

    // yarpaqlar
    $leaves = [[-62, 58, 38], [-34, 36, 44], [32, 38, 44], [60, 60, 38], [0, 24, 40]];
    foreach ($leaves as [$a, $ly, $lh]) {
        $o .= '<ellipse cx="150" cy="' . (92 - $lh) . '" rx="' . ($lh * .38) . '" ry="' . $lh . '" fill="url(#' . $id . 'l)" transform="rotate(' . $a . ' 150 120) translate(0 ' . ($ly - 40) . ')"/>';
    }

    // qablaşdırma (arxa)
    $o .= '<g filter="url(#' . $id . 's)">';
    $o .= '<path d="M38 112 L150 292 L262 112 Z" fill="url(#' . $id . 'w2)"/>';
    $o .= '</g>';

    // güllər
    $pos = [
        [150, 84, 44], [93, 108, 38], [207, 108, 38], [58, 148, 30], [242, 148, 30],
        [150, 138, 46], [100, 168, 36], [200, 168, 36], [150, 200, 34],
    ];
    foreach ($pos as $i => [$x, $y, $r]) {
        $x += mt_rand(-4, 4);
        $y += mt_rand(-3, 3);
        $t = $mix ? MIX_TONES[($i + $seed) % count(MIX_TONES)] : $tone;
        $o .= rose_g($x, $y, $r, $id . $t, PALETTES[$t], mt_rand(0, 360));
    }

    // qablaşdırma (ön qatlar)
    $o .= '<path d="M38 112 L150 292 L98 124 Z" fill="url(#' . $id . 'w)" fill-opacity=".96"/>';
    $o .= '<path d="M262 112 L150 292 L202 124 Z" fill="url(#' . $id . 'w)" fill-opacity=".96"/>';
    $o .= '<path d="M98 124 L150 292 L202 124 Q150 150 98 124Z" fill="#000" fill-opacity=".35"/>';

    // lent
    $o .= '<g transform="translate(150 236)">';
    $o .= '<path d="M0 0 C-26 -30 -64 -26 -62 -4 C-60 14 -26 12 0 0Z" fill="url(#' . $id . 'rb)"/>';
    $o .= '<path d="M0 0 C26 -30 64 -26 62 -4 C60 14 26 12 0 0Z" fill="url(#' . $id . 'rb)"/>';
    $o .= '<path d="M-6 6 L-34 44 L-14 38 L-4 52 L2 12Z" fill="#a8102a"/>';
    $o .= '<path d="M6 6 L34 44 L14 38 L4 52 L-2 12Z" fill="#c8102e"/>';
    $o .= '<rect x="-10" y="-9" width="20" height="18" rx="6" fill="#e11d3f"/></g>';

    return $o . '</svg>';
}

/** Geniş qızılgül sahəsi (mağaza cover, banner) */
function rose_field_svg(string $tone = 'red', int $seed = 3, string $class = ''): string
{
    static $m = 0;
    $m++;
    mt_srand($seed * 104729);
    $id = 'rf' . $m;
    $mix = ($tone === 'mix');
    $o = '<svg class="' . e($class) . '" viewBox="0 0 1200 300" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><defs>';
    foreach (($mix ? MIX_TONES : [$tone]) as $t) {
        $pp = PALETTES[$t];
        $o .= '<radialGradient id="' . $id . $t . '" cx="38%" cy="32%" r="78%"><stop offset="0" stop-color="' . $pp[0] . '"/><stop offset=".58" stop-color="' . $pp[1] . '"/><stop offset="1" stop-color="' . $pp[2] . '"/></radialGradient>';
    }
    $o .= '</defs><rect width="1200" height="300" fill="#12060a"/>';
    for ($i = 0; $i < 46; $i++) {
        $t = $mix ? MIX_TONES[$i % count(MIX_TONES)] : $tone;
        $o .= rose_g(mt_rand(-20, 1220), mt_rand(-10, 310), mt_rand(28, 74), $id . $t, PALETTES[$t], mt_rand(0, 360));
    }
    return $o . '</svg>';
}

/** Kateqoriya ikonları */
function cat_icon(string $key, string $tone = 'red'): string
{
    static $c = 0;
    $c++;
    $id = 'ci' . $c;
    $p = PALETTES[$tone] ?? PALETTES['red'];
    $grad = '<defs><radialGradient id="' . $id . '" cx="38%" cy="32%" r="78%"><stop offset="0" stop-color="' . $p[0] . '"/><stop offset=".6" stop-color="' . $p[1] . '"/><stop offset="1" stop-color="' . $p[2] . '"/></radialGradient>'
        . '<linearGradient id="' . $id . 'g" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#3b8a4a"/><stop offset="1" stop-color="#12401f"/></linearGradient></defs>';
    $svg = '<svg viewBox="0 0 80 80" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">' . $grad;
    switch ($key) {
        case 'lale':
            $svg .= '<path d="M40 78 V44" stroke="url(#' . $id . 'g)" stroke-width="3" stroke-linecap="round"/>'
                . '<path d="M40 60 C28 58 22 52 18 44 C28 46 34 50 40 56Z" fill="url(#' . $id . 'g)"/>'
                . '<path d="M22 12 C20 28 26 42 40 44 C54 42 60 28 58 12 C52 20 46 18 40 8 C34 18 28 20 22 12Z" fill="url(#' . $id . ')"/>';
            $svg .= '<path d="M22 12 C20 28 26 42 40 44 C54 42 60 28 58 12 C52 20 46 18 40 8 C34 18 28 20 22 12Z" fill="none" stroke="#000" stroke-opacity=".25"/>';
            // ikinci lalə
            $svg .= '<g transform="translate(-14 10) scale(.7)"><path d="M22 12 C20 28 26 42 40 44 C54 42 60 28 58 12 C52 20 46 18 40 8 C34 18 28 20 22 12Z" fill="url(#' . $id . ')"/></g>';
            break;
        case 'orxideya':
            $svg .= '<path d="M40 80 C40 60 44 50 40 38" stroke="url(#' . $id . 'g)" stroke-width="2.6" fill="none" stroke-linecap="round"/>';
            for ($i = 0; $i < 5; $i++) {
                $svg .= '<ellipse cx="40" cy="22" rx="12" ry="19" fill="url(#' . $id . ')" fill-opacity=".92" transform="rotate(' . ($i * 72) . ' 40 36)"/>';
            }
            $svg .= '<circle cx="40" cy="36" r="6" fill="#f7d54a"/><circle cx="40" cy="36" r="3" fill="#b8500e"/>';
            break;
        case 'suni':
            $svg .= '<path d="M40 78 V50" stroke="url(#' . $id . 'g)" stroke-width="3"/>';
            $svg .= rose_g(26, 38, 17, $id, $p, 10) . rose_g(54, 38, 17, $id, $p, 80) . rose_g(40, 24, 18, $id, $p, 40);
            break;
        case 'kompozisiya':
            $svg .= '<path d="M14 40 L40 78 L66 40Z" fill="#1c1c22" stroke="#333" />';
            $svg .= rose_g(28, 30, 16, $id, $p, 15) . rose_g(52, 30, 16, $id, $p, 60) . rose_g(40, 20, 15, $id, PALETTES['white'], 5)
                . '<circle cx="40" cy="56" r="5" fill="#e11d3f"/>';
            $svg = str_replace('fill="url(#' . $id . ')"', 'fill="url(#' . $id . ')"', $svg);
            break;
        case 'diger':
            $svg .= '<path d="M40 78 V48" stroke="url(#' . $id . 'g)" stroke-width="3"/>';
            $svg .= rose_g(40, 30, 24, $id, $p, 20);
            $svg .= '<path d="M40 64 C50 62 58 56 60 48 C50 50 44 54 40 60Z" fill="url(#' . $id . 'g)"/>';
            break;
        default: // qizilgul
            $svg .= '<path d="M40 80 V50" stroke="url(#' . $id . 'g)" stroke-width="3.2" stroke-linecap="round"/>'
                . '<path d="M40 68 C52 66 60 60 62 52 C50 54 44 58 40 64Z" fill="url(#' . $id . 'g)"/>';
            $svg .= rose_g(40, 32, 27, $id, $p, 0);
    }
    return $svg . '</svg>';
}

function star_svg(string $cls = 'star'): string
{
    return '<svg class="' . $cls . '" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 2.5l2.95 6 6.55.95-4.75 4.62 1.12 6.53L12 17.5l-5.87 3.1 1.12-6.53L2.5 9.45l6.55-.95z"/></svg>';
}

function icon(string $name): string
{
    $paths = [
        'search'  => '<circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/>',
        'cart'    => '<path d="M3 4h2.2l2.1 10.2a1 1 0 001 .8h8.6a1 1 0 001-.76L19.5 8H6"/><circle cx="9.5" cy="19" r="1.4"/><circle cx="17" cy="19" r="1.4"/>',
        'user'    => '<circle cx="12" cy="8" r="4"/><path d="M4 20c1.2-3.6 4.2-5.5 8-5.5s6.8 1.9 8 5.5"/>',
        'heart'   => '<path d="M12 20.5s-7.5-4.6-9.2-9.4C1.6 7.6 3.7 4.5 7 4.5c2 0 3.7 1.1 5 3 1.3-1.9 3-3 5-3 3.3 0 5.4 3.1 4.2 6.6-1.7 4.8-9.2 9.4-9.2 9.4z"/>',
        'arrow'   => '<path d="M5 12h14M13 6l6 6-6 6"/>',
        'left'    => '<path d="M15 5l-7 7 7 7"/>',
        'right'   => '<path d="M9 5l7 7-7 7"/>',
        'close'   => '<path d="M6 6l12 12M18 6L6 18"/>',
        'menu'    => '<path d="M4 7h16M4 12h16M4 17h16"/>',
        'crown'   => '<path d="M3 8l4.5 4L12 5l4.5 7L21 8l-2 11H5z"/>',
        'check'   => '<path d="M5 12.5l4.5 4.5L19 7.5"/>',
        'plus'    => '<path d="M12 5v14M5 12h14"/>',
        'minus'   => '<path d="M5 12h14"/>',
        'home'    => '<path d="M4 11l8-7 8 7v9a1 1 0 01-1 1h-4v-6H9v6H5a1 1 0 01-1-1z"/>',
        'flower'  => '<circle cx="12" cy="12" r="2.6"/><path d="M12 9.4C10 6 11 3 12 3s2 3 0 6.4zM12 14.6c2 3.4 1 6.4 0 6.4s-2-3-0-6.4zM9.4 12C6 10 3 11 3 12s3 2 6.4 0zM14.6 12c3.4-2 6.4-1 6.4 0s-3 2-6.4 0z"/>',
        'box'     => '<path d="M3.5 7.5L12 3l8.5 4.5v9L12 21l-8.5-4.5z"/><path d="M3.5 7.5L12 12l8.5-4.5M12 12v9"/>',
        'receipt' => '<path d="M6 3h12v18l-3-2-3 2-3-2-3 2z"/><path d="M9 8h6M9 12h6"/>',
        'store'   => '<path d="M4 9l1.5-5h13L20 9M4 9v11h16V9M4 9c0 1.7 1.3 3 3 3s3-1.3 3-3c0 1.7 1.3 3 3 3s3-1.3 3-3c0 1.7 1.3 3 3 3"/>',
        'chart'   => '<path d="M4 20V4M4 20h16M8 16l3-4 3 2 5-7"/>',
        'bell'    => '<path d="M6 16V11a6 6 0 1112 0v5l1.5 2h-15zM10 21h4"/>',
        'gear'    => '<circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3M4.9 4.9l2.1 2.1M17 17l2.1 2.1M19.1 4.9L17 7M7 17l-2.1 2.1"/>',
        'logout'  => '<path d="M9 4H5a1 1 0 00-1 1v14a1 1 0 001 1h4M16 8l4 4-4 4M20 12H9"/>',
        'telegram'=> '<path d="M21 4L3 11l6 2 2 6 3-4 5 4z"/>',
        'insta'   => '<rect x="4" y="4" width="16" height="16" rx="5"/><circle cx="12" cy="12" r="3.6"/><circle cx="17" cy="7" r=".6" fill="currentColor"/>',
        'whatsapp'=> '<path d="M4 20l1.2-4A8 8 0 1112 20a8 8 0 01-4-1.1z"/><path d="M9 9c0 3 3 6 6 6l1-1.5-2-1-1 .7c-1-.4-2-1.4-2.4-2.4l.7-1-1-2z"/>',
        'yt'      => '<rect x="3" y="6" width="18" height="12" rx="4"/><path d="M10.5 9.5v5l4-2.5z" fill="currentColor"/>',
        'truck'   => '<path d="M3 6h11v10H3zM14 9h4l3 3v4h-7"/><circle cx="7" cy="17.5" r="1.6"/><circle cx="17" cy="17.5" r="1.6"/>',
        'shield'  => '<path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6z"/><path d="M8.5 12l2.5 2.5 4.5-5"/>',
        'gift'    => '<rect x="3" y="8" width="18" height="4" rx="1"/><path d="M5 12v8h14v-8M12 8v12M12 8c-2.5 0-4.5-1-4.5-2.6S10 3 12 8c2-5 4.5-3.6 4.5-2.6S14.5 8 12 8z"/>',
    ];
    $d = $paths[$name] ?? '';
    return '<svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $d . '</svg>';
}

function find_store(string $slug): ?array
{
    global $stores;
    return $stores[$slug] ?? null;
}

function products_of(string $slug): array
{
    global $products;
    return array_values(array_filter($products, fn($p) => $p['store'] === $slug));
}

/** Məhsul kartı */
function product_card(array $p, bool $showStore = false): string
{
    global $stores;
    $storeName = $stores[$p['store']]['name'] ?? '';
    ob_start(); ?>
    <article class="card product reveal" data-id="<?= (int)$p['id'] ?>" data-cat="<?= e($p['cat']) ?>" data-price="<?= (int)$p['price'] ?>" data-name="<?= e($p['name']) ?>">
        <div class="product__media">
            <?= bouquet_svg($p['tone'], (int)$p['id'] + 4, 'product__art') ?>
            <button class="fav" type="button" aria-label="Sevimlilərə əlavə et" data-fav="p<?= (int)$p['id'] ?>"><?= icon('heart') ?></button>
        </div>
        <div class="product__body">
            <h3 class="product__name"><?= e($p['name']) ?></h3>
            <?php if ($showStore): ?><a class="product__store" href="magaza.php?s=<?= e($p['store']) ?>"><?= e($storeName) ?></a><?php endif; ?>
            <div class="product__price"><?= f((float)$p['price']) ?> AZN</div>
            <button class="btn btn-primary btn-block js-add" type="button"
                    data-id="<?= (int)$p['id'] ?>" data-name="<?= e($p['name']) ?>" data-price="<?= (float)$p['price'] ?>" data-tone="<?= e($p['tone']) ?>">
                Səbətə Əlavə Et
            </button>
        </div>
    </article>
    <?php
    return (string)ob_get_clean();
}

/** Mağaza kartı */
function store_card(string $slug, array $s, int $seed = 1): string
{
    ob_start(); ?>
    <article class="card store reveal" data-name="<?= e(mb_strtolower($s['name'])) ?>" data-rating="<?= $s['rating'] ?>" data-city="<?= e($s['city']) ?>" data-premium="<?= $s['premium'] ? 1 : 0 ?>">
        <div class="store__bg"><?= bouquet_svg($s['tone'], $seed + 20, 'store__art') ?></div>
        <div class="store__shade"></div>
        <button class="fav" type="button" aria-label="Sevimlilərə əlavə et" data-fav="s-<?= e($slug) ?>"><?= icon('heart') ?></button>
        <?php if ($s['premium']): ?><span class="badge-premium"><?= icon('crown') ?> Premium</span><?php endif; ?>
        <div class="store__body">
            <h3><?= e($s['name']) ?></h3>
            <div class="rating"><?= star_svg() ?> <b><?= number_format($s['rating'], 1) ?></b> <span>(<?= e($s['reviews_label']) ?> rəy)</span></div>
            <div class="store__meta"><?= e($s['products']) ?> məhsul · <?= e($s['city']) ?></div>
            <a class="btn btn-light btn-block" href="magaza.php?s=<?= e($slug) ?>">Mağazanı Ziyarət Et <?= icon('arrow') ?></a>
        </div>
    </article>
    <?php
    return (string)ob_get_clean();
}

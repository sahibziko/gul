<?php
require_once __DIR__ . '/includes/functions.php';

$page = ['title' => 'Ana səhifə', 'active' => 'home'];
$slides = [
    ['tone' => 'red',   'glow' => 'rgba(225,29,63,.5)',  'h' => "Sevginizi\nGüllərlə ifadə edin",   'p' => "Ən gözəl güllər, ən etibarlı mağazalar\nindi bir yerdə!", 'cta' => 'Alış-verişə Başla', 'href' => 'magazalar.php'],
    ['tone' => 'white', 'glow' => 'rgba(255,255,255,.22)', 'h' => "Zərifliyin\nağ rəngi",            'p' => "Toy, nişan və xüsusi günlər üçün\nağ qızılgül kolleksiyası.",    'cta' => 'Kolleksiyaya Bax', 'href' => 'magaza.php?s=nargiz-gullari'],
    ['tone' => 'pink',  'glow' => 'rgba(255,120,160,.4)', 'h' => "Pudra çalarlarında\nromantika",    'p' => "Yumşaq tonlar, incə qablaşdırma,\nəbədi təəssürat.",             'cta' => 'Buketlərə Bax', 'href' => 'magaza.php?s=flower-dream'],
    ['tone' => 'mix',   'glow' => 'rgba(255,170,60,.35)', 'h' => "Rəngarəng\nəhval buketləri",       'p' => "Hər gününüzə rəng qatan\nmövsümi kompozisiyalar.",              'cta' => 'Kəşf Et', 'href' => 'magaza.php?s=gul-dunyasi'],
];
$recommended = array_slice($stores, 0, 4, true);
$popular = array_slice(array_filter($products, fn($p) => $p['store'] === 'rose-boutique'), 0, 4);

require __DIR__ . '/includes/header.php';
?>

<main>
    <!-- HERO -->
    <section class="hero" aria-label="Əsas təkliflər">
        <div class="petals" aria-hidden="true"></div>
        <button class="hero__arrow prev" type="button" aria-label="Əvvəlki"><?= icon('left') ?></button>
        <button class="hero__arrow next" type="button" aria-label="Növbəti"><?= icon('right') ?></button>
        <div class="hero__slides">
            <?php foreach ($slides as $i => $s): ?>
                <div class="slide <?= $i === 0 ? 'is-active' : '' ?>" style="--glow: <?= e($s['glow']) ?>">
                    <div class="slide__inner">
                        <div class="slide__text">
                            <h1><?= nl2br(e($s['h'])) ?></h1>
                            <p><?= nl2br(e($s['p'])) ?></p>
                            <a class="btn btn-primary btn-lg" href="<?= e($s['href']) ?>"><?= e($s['cta']) ?> <?= icon('arrow') ?></a>
                        </div>
                        <div class="slide__art"><?= bouquet_svg($s['tone'], $i + 2) ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="hero__dots"></div>
    </section>

    <!-- KATEQORİYALAR -->
    <div class="container" id="kateqoriyalar">
        <div class="cats">
            <?php foreach ($categories as $c): ?>
                <a class="cat reveal" href="magazalar.php?kat=<?= e($c['key']) ?>">
                    <?= cat_icon($c['key'], $c['tone']) ?>
                    <span><?= e($c['name']) ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- MAĞAZALAR -->
    <section class="section container" aria-labelledby="h-stores">
        <div class="section__head">
            <h2 id="h-stores">Tövsiyə Olunan Mağazalar</h2>
            <a class="link-more" href="magazalar.php">Bütün mağazalara bax <?= icon('arrow') ?></a>
        </div>
        <div class="grid-4">
            <?php $i = 0; foreach ($recommended as $slug => $s) { echo store_card($slug, $s, ++$i); } ?>
        </div>
    </section>

    <!-- PROMO -->
    <section class="section container" aria-label="Kampaniya">
        <div class="promo reveal">
            <div class="promo__bg"><?= rose_field_svg('red', 9) ?></div>
            <svg class="promo__gift" viewBox="0 0 200 180" aria-hidden="true">
                <defs>
                    <linearGradient id="gb" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#b3122b"/><stop offset="1" stop-color="#4d0613"/></linearGradient>
                    <linearGradient id="gr" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#ff4b5f"/><stop offset="1" stop-color="#a30f26"/></linearGradient>
                </defs>
                <rect x="24" y="78" width="152" height="92" rx="8" fill="url(#gb)"/>
                <rect x="14" y="58" width="172" height="28" rx="8" fill="#c8102e"/>
                <rect x="88" y="58" width="24" height="112" fill="url(#gr)"/>
                <path d="M100 56 C70 8 24 20 44 48 C58 66 94 58 100 56Z" fill="url(#gr)"/>
                <path d="M100 56 C130 8 176 20 156 48 C142 66 106 58 100 56Z" fill="url(#gr)"/>
                <circle cx="100" cy="56" r="11" fill="#e11d3f"/>
            </svg>
            <div>
                <h3>Hər sifarişə<br>xüsusi hədiyyə</h3>
                <p>Sevdiklərinizə daha çox sevgi gətirin</p>
            </div>
            <a class="btn btn-primary btn-lg" href="magazalar.php">Detalları Öyrən <?= icon('arrow') ?></a>
        </div>
    </section>

    <!-- POPULYAR -->
    <section class="section container" aria-labelledby="h-pop">
        <div class="section__head">
            <h2 id="h-pop">Ən Populyar Güllər</h2>
            <a class="link-more" href="magaza.php?s=rose-boutique">Bütün məhsullara bax <?= icon('arrow') ?></a>
        </div>
        <div class="grid-4">
            <?php foreach ($popular as $p) { echo product_card($p); } ?>
        </div>
    </section>

    <!-- ÜSTÜNLÜKLƏR -->
    <section class="section container" aria-label="Üstünlüklər">
        <div class="grid-3">
            <div class="perk reveal"><?= icon('truck') ?><div><b>2 saata çatdırılma</b><span>Bakı daxilində eyni gün, sürətli və diqqətli kuryer xidməti.</span></div></div>
            <div class="perk reveal"><?= icon('shield') ?><div><b>Yoxlanılmış mağazalar</b><span>Hər satıcı təsdiqdən keçir, rəylər real müştərilərdəndir.</span></div></div>
            <div class="perk reveal"><?= icon('gift') ?><div><b>Hədiyyə qablaşdırma</b><span>Kartoçka mesajı və xüsusi qablaşdırma seçimi hər sifarişdə.</span></div></div>
        </div>
    </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>

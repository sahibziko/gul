<?php
/**
 * Ümumi başlıq. İstifadə:
 *   $page = ['title' => '...', 'active' => 'home', 'body' => 'class', 'desc' => '...'];
 *   require __DIR__ . '/includes/header.php';
 */
require_once __DIR__ . '/functions.php';

$page = $page ?? [];
$title  = ($page['title'] ?? 'Ana səhifə') . ' · ' . SITE_NAME;
$active = $page['active'] ?? '';
$bodyCl = $page['body'] ?? '';
$desc   = $page['desc'] ?? 'Ən gözəl güllər, ən etibarlı mağazalar indi bir yerdə. Qızılgül buketləri, kompozisiyalar və eyni gün çatdırılma.';
$nav = [
    'home'      => ['index.php', 'Ana səhifə'],
    'stores'    => ['magazalar.php', 'Mağazalar'],
    'cats'      => ['index.php#kateqoriyalar', 'Kateqoriyalar'],
    'about'     => ['haqqimizda.php', 'Haqqımızda'],
    'contact'   => ['haqqimizda.php#elaqe', 'Əlaqə'],
];
?><!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title) ?></title>
    <meta name="description" content="<?= e($desc) ?>">
    <meta name="theme-color" content="#09090b">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap&subset=latin-ext" rel="stylesheet">
    <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Ccircle cx='32' cy='32' r='28' fill='%23c8102e'/%3E%3Ccircle cx='32' cy='32' r='15' fill='none' stroke='%23fff' stroke-opacity='.5' stroke-width='3'/%3E%3C/svg%3E">
    <script>document.documentElement.classList.add('js');</script>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="<?= e($bodyCl) ?>">

<header class="site-header" id="siteHeader">
    <div class="container header__row">
        <a class="logo" href="index.php" aria-label="<?= e(SITE_NAME) ?> ana səhifə">
            <?= cat_icon('qizilgul', 'red') ?>
            <span>Flower<em>.az</em></span>
        </a>

        <nav class="nav" id="mainNav" aria-label="Əsas menyu">
            <?php foreach ($nav as $key => [$href, $label]): ?>
                <a href="<?= e($href) ?>" class="<?= $active === $key ? 'is-active' : '' ?>"><?= e($label) ?></a>
            <?php endforeach; ?>
            <a class="nav__cta" href="magaza-ac.php">Mağazanı aç</a>
        </nav>

        <div class="header__tools">
            <form class="search" id="searchForm" action="magazalar.php" method="get" role="search" autocomplete="off">
                <input type="search" name="q" id="searchInput" placeholder="Gül, mağaza və s. axtar..." aria-label="Axtarış">
                <button type="submit" aria-label="Axtar"><?= icon('search') ?></button>
                <div class="search__results" id="searchResults" hidden></div>
            </form>
            <button class="icon-btn" id="cartBtn" type="button" aria-label="Səbət">
                <?= icon('cart') ?><span class="badge" id="cartCount">0</span>
            </button>
            <a class="icon-btn" href="panel.php" aria-label="Satıcı paneli"><?= icon('user') ?></a>
            <button class="icon-btn menu-btn" id="menuBtn" type="button" aria-label="Menyu" aria-expanded="false"><?= icon('menu') ?></button>
        </div>
    </div>
</header>

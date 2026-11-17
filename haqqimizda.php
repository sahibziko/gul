<?php
require_once __DIR__ . '/includes/functions.php';
$page = ['title' => 'Haqqımızda', 'active' => 'about'];
require __DIR__ . '/includes/header.php';
?>

<main>
    <section class="page-head">
        <div class="container">
            <div class="crumbs"><a href="index.php">Ana səhifə</a> / Haqqımızda</div>
            <h1>Sevgini çatdıran platforma</h1>
            <p>Flower.az gül mağazalarını və müştəriləri bir araya gətirən Azərbaycanın müasir gül bazarıdır.</p>
        </div>
    </section>

    <div class="container">
        <div class="stats-row" style="margin:30px 0">
            <div class="panel stat-big reveal"><b data-count="120" data-suffix="+">0</b><span>Əməkdaş mağaza</span></div>
            <div class="panel stat-big reveal"><b data-count="45" data-suffix="K+">0</b><span>Çatdırılmış sifariş</span></div>
            <div class="panel stat-big reveal"><b data-count="4.9" data-dec="1">0</b><span>Orta reytinq</span></div>
            <div class="panel stat-big reveal"><b data-count="2" data-suffix=" saat">0</b><span>Orta çatdırılma</span></div>
        </div>

        <div class="about-grid" id="elaqe">
            <div class="panel">
                <h3>Bizimlə əlaqə</h3>
                <form class="form" id="contactForm">
                    <div class="row">
                        <label>Ad<input required placeholder="Adınız"></label>
                        <label>E-poçt<input type="email" required placeholder="mail@example.com"></label>
                    </div>
                    <label>Mesaj<textarea rows="4" required placeholder="Sualınızı yazın..."></textarea></label>
                    <button class="btn btn-primary" type="submit" style="justify-self:start">Göndər <?= icon('arrow') ?></button>
                </form>
            </div>
            <div class="panel">
                <h3>Əlaqə məlumatları</h3>
                <div class="info-list">
                    <div><span>Telefon</span><b>+994 12 345 67 89</b></div>
                    <div><span>E-poçt</span><b>salam@flower.az</b></div>
                    <div><span>Ünvan</span><b>Bakı, Nizami küç. 10</b></div>
                    <div><span>İş saatları</span><b>Hər gün 09:00 – 22:00</b></div>
                </div>
            </div>
        </div>

        <section class="section" id="faq">
            <div class="section__head"><h2>Tez-tez verilən suallar</h2></div>
            <div class="panel faq">
                <details open><summary>Çatdırılma nə qədər vaxt aparır?</summary><p>Bakı daxilində adətən 2 saat. Sifariş zamanı konkret vaxt aralığı seçə bilərsiniz.</p></details>
                <details><summary>Buketi qaytarmaq mümkündürmü?</summary><p>Təzə güllər üçün qaytarma yoxdur, lakin keyfiyyət problemi olarsa 24 saat ərzində əvəzləmə və ya geri ödəniş edilir.</p></details>
                <details><summary>Mağaza kimi necə qoşula bilərəm?</summary><p>“Mağazanı aç” düyməsi ilə formu doldurun. Müraciət 24 saat ərzində yoxlanılır.</p></details>
                <details><summary>Ödəniş necə aparılır?</summary><p>Kartla onlayn və ya çatdırılma zamanı nağd ödəniş mümkündür.</p></details>
            </div>
        </section>
    </div>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>

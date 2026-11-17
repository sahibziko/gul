<?php
global $stores, $products, $categories;
// Axtarış indeksi + palitralar JS üçün
$index = [];
foreach ($stores as $slug => $s) {
    $index[] = ['t' => 's', 'n' => $s['name'], 'u' => 'magaza.php?s=' . $slug, 'm' => $s['city'] . ' · ' . $s['products'] . ' məhsul'];
}
foreach ($products as $p) {
    $index[] = ['t' => 'p', 'n' => $p['name'], 'u' => 'magaza.php?s=' . $p['store'] . '#p' . $p['id'], 'm' => $stores[$p['store']]['name'] . ' · ' . f((float)$p['price']) . ' AZN'];
}
$boot = ['palettes' => PALETTES, 'index' => $index];
?>

<footer class="site-footer" id="footer">
    <div class="container footer__grid">
        <div class="footer__brand">
            <a class="logo" href="index.php"><?= cat_icon('qizilgul', 'red') ?><span>Flower<em>.az</em></span></a>
            <p>Hər zaman sevgi ilə...</p>
            <div class="social">
                <a href="#" aria-label="Instagram"><?= icon('insta') ?></a>
                <a href="#" aria-label="Telegram"><?= icon('telegram') ?></a>
                <a href="#" aria-label="WhatsApp"><?= icon('whatsapp') ?></a>
                <a href="#" aria-label="YouTube"><?= icon('yt') ?></a>
            </div>
        </div>
        <div>
            <h4>Menyu</h4>
            <a href="index.php">Ana səhifə</a>
            <a href="magazalar.php">Mağazalar</a>
            <a href="index.php#kateqoriyalar">Kateqoriyalar</a>
            <a href="haqqimizda.php">Haqqımızda</a>
            <a href="haqqimizda.php#elaqe">Əlaqə</a>
        </div>
        <div>
            <h4>Müştəri üçün</h4>
            <a href="haqqimizda.php#faq">Çatdırılma</a>
            <a href="haqqimizda.php#faq">Qaytarma</a>
            <a href="haqqimizda.php#faq">Tez-tez verilən suallar</a>
            <a href="#">İstifadə şərtləri</a>
            <a href="#">Məxfilik siyasəti</a>
        </div>
        <div>
            <h4>Yeniliklərdən xəbərdar olun</h4>
            <form class="newsletter" id="newsletter">
                <input type="email" required placeholder="E-poçt ünvanınızı daxil edin" aria-label="E-poçt">
                <button class="btn btn-primary" type="submit" aria-label="Abunə ol"><?= icon('arrow') ?></button>
            </form>
            <a class="footer__seller" href="magaza-ac.php">Satıcısınız? Mağazanızı əlavə edin <?= icon('arrow') ?></a>
        </div>
    </div>
    <div class="container footer__bottom">
        <span>© <?= date('Y') ?> Flower.az. Bütün hüquqlar qorunur.</span>
        <span class="made"><?= icon('heart') ?> Sevgi ilə hazırlanıb</span>
    </div>
</footer>

<!-- Səbət -->
<div class="overlay" id="overlay" hidden></div>
<aside class="cart" id="cart" aria-label="Səbət" aria-hidden="true">
    <div class="cart__head">
        <h3>Səbətim</h3>
        <button class="icon-btn" type="button" id="cartClose" aria-label="Bağla"><?= icon('close') ?></button>
    </div>

    <div class="cart__view" id="cartView">
        <div class="cart__items" id="cartItems"></div>
        <div class="cart__empty" id="cartEmpty" hidden>
            <?= cat_icon('qizilgul', 'red') ?>
            <p>Səbətiniz boşdur</p>
            <a class="btn btn-outline" href="magazalar.php">Alış-verişə başla</a>
        </div>
        <div class="cart__foot" id="cartFoot">
            <div class="cart__total"><span>Ümumi Məbləğ:</span><b id="cartTotal">0 AZN</b></div>
            <button class="btn btn-primary btn-block" type="button" id="checkoutBtn">Sifarişi Tamamla <?= icon('arrow') ?></button>
            <button class="btn btn-outline btn-block" type="button" id="clearCart">Səbəti Təmizlə</button>
        </div>
    </div>

    <form class="cart__checkout" id="checkoutForm" hidden>
        <h4>Çatdırılma məlumatları</h4>
        <label>Ad, soyad<input required name="name" placeholder="Məs: Aysel Məmmədova"></label>
        <label>Telefon<input required name="phone" inputmode="tel" placeholder="+994 50 000 00 00"></label>
        <label>Ünvan<input required name="address" placeholder="Şəhər, küçə, mənzil"></label>
        <label>Çatdırılma vaxtı
            <select name="time"><option>Ən qısa vaxtda (2 saat)</option><option>Bu gün 18:00 – 20:00</option><option>Sabah 10:00 – 12:00</option></select>
        </label>
        <label>Kartoçka mesajı (istəyə bağlı)<textarea name="note" rows="2" placeholder="Sevgi ilə..."></textarea></label>
        <button class="btn btn-primary btn-block" type="submit">Sifarişi Təsdiqlə</button>
        <button class="btn btn-ghost btn-block" type="button" id="backToCart">← Səbətə qayıt</button>
    </form>

    <div class="cart__done" id="cartDone" hidden>
        <div class="done__icon"><?= icon('check') ?></div>
        <h4>Sifarişiniz qəbul olundu!</h4>
        <p>Sifariş nömrəsi: <b id="orderNo">#1025</b><br>Mağaza ilə əlaqə saxlanılacaq.</p>
        <button class="btn btn-primary" type="button" id="doneClose">Bağla</button>
    </div>
</aside>

<div class="toast" id="toast" role="status" aria-live="polite"></div>

<script>window.FLOWER = <?= json_encode($boot, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) ?>;</script>
<script src="assets/js/app.js" defer></script>
<?php if (!empty($page['scripts'])): foreach ((array)$page['scripts'] as $sc): ?>
<script src="<?= e($sc) ?>" defer></script>
<?php endforeach; endif; ?>
</body>
</html>

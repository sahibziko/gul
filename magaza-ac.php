<?php
/**
 * magaza-ac.php — Satıcı qeydiyyat formu.
 * Müraciətlər data/store_requests.json faylına yazılır (demo). Real layihədə verilənlər bazasına yazın.
 */
session_start();
require_once __DIR__ . '/includes/functions.php';

if (empty($_SESSION['csrf'])) { $_SESSION['csrf'] = bin2hex(random_bytes(16)); }

$errors = [];
$ok = false;
$old = ['name' => '', 'owner' => '', 'phone' => '', 'email' => '', 'city' => 'Bakı', 'address' => '', 'about' => '', 'cats' => []];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach (['name', 'owner', 'phone', 'email', 'city', 'address', 'about'] as $k) {
        $old[$k] = trim((string)($_POST[$k] ?? ''));
    }
    $allowed = array_column($categories, 'key');
    $old['cats'] = array_values(array_intersect($allowed, (array)($_POST['cats'] ?? [])));

    if (!hash_equals($_SESSION['csrf'], (string)($_POST['csrf'] ?? ''))) { $errors[] = 'Sessiya vaxtı bitdi, səhifəni yeniləyin.'; }
    if (!empty($_POST['website'])) { $errors[] = 'Sorğu rədd edildi.'; } // honeypot
    if (mb_strlen($old['name']) < 3 || mb_strlen($old['name']) > 60) { $errors[] = 'Mağaza adı 3–60 simvol olmalıdır.'; }
    if (mb_strlen($old['owner']) < 3) { $errors[] = 'Sahibkarın adını daxil edin.'; }
    if (!preg_match('/^\+?[0-9\s\-()]{9,18}$/', $old['phone'])) { $errors[] = 'Telefon nömrəsi düzgün deyil.'; }
    if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) { $errors[] = 'E-poçt ünvanı düzgün deyil.'; }
    if (mb_strlen($old['address']) < 5) { $errors[] = 'Ünvanı daxil edin.'; }
    if (!$old['cats']) { $errors[] = 'Ən azı bir kateqoriya seçin.'; }
    if (empty($_POST['terms'])) { $errors[] = 'İstifadə şərtləri ilə razılaşmalısınız.'; }

    if (!$errors) {
        $file = __DIR__ . '/data/store_requests.json';
        $fp = fopen($file, 'c+');
        if ($fp && flock($fp, LOCK_EX)) {
            $raw = stream_get_contents($fp);
            $all = $raw ? (json_decode($raw, true) ?: []) : [];
            $all[] = $old + ['id' => count($all) + 1, 'status' => 'gözləyir', 'date' => date('c')];
            ftruncate($fp, 0); rewind($fp);
            fwrite($fp, json_encode($all, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            fflush($fp); flock($fp, LOCK_UN); fclose($fp);
            $ok = true;
            $_SESSION['csrf'] = bin2hex(random_bytes(16));
        } else {
            $errors[] = 'Müraciəti yazmaq mümkün olmadı (data/ qovluğuna yazma icazəsi verin).';
        }
    }
}

$page = ['title' => 'Mağazanı əlavə et', 'active' => '', 'desc' => 'Flower.az-da öz gül mağazanızı açın və satışa başlayın.'];
require __DIR__ . '/includes/header.php';
?>

<main>
    <section class="page-head">
        <div class="container">
            <div class="crumbs"><a href="index.php">Ana səhifə</a> / Mağazanı əlavə et</div>
            <h1>Mağazanızı Flower.az-a əlavə edin</h1>
            <p>Formu doldurun, müraciətinizi 24 saat ərzində yoxlayıb satıcı panelinizi aktivləşdirək.</p>
        </div>
    </section>

    <div class="container">
        <div class="form-wrap">
            <div class="perks">
                <div class="perk reveal"><?= icon('store') ?><div><b>Öz mağaza səhifəniz</b><span>Logo, cover, məhsullar, rəylər — hamısı bir profildə.</span></div></div>
                <div class="perk reveal"><?= icon('chart') ?><div><b>Satıcı paneli</b><span>Sifarişlər, gəlir qrafiki və analitika real vaxtda.</span></div></div>
                <div class="perk reveal"><?= icon('truck') ?><div><b>Çatdırılma dəstəyi</b><span>Öz kuryerinizlə və ya platformanın çatdırılma şəbəkəsi ilə.</span></div></div>
                <div class="perk reveal"><?= icon('shield') ?><div><b>Təhlükəsiz ödəniş</b><span>Sifarişlər 48 saat ərzində hesabınıza köçürülür.</span></div></div>
            </div>

            <div class="panel">
                <?php if ($ok): ?>
                    <div class="alert alert-ok">
                        <b>Müraciətiniz qəbul olundu!</b><br>
                        Komandamız tezliklə <?= e($old['email']) ?> ünvanına cavab göndərəcək.
                    </div>
                    <p style="margin-top:18px"><a class="btn btn-primary" href="panel.php">Demo satıcı panelinə bax <?= icon('arrow') ?></a></p>
                <?php else: ?>
                    <?php if ($errors): ?>
                        <div class="alert alert-err" role="alert" style="margin-bottom:16px">
                            <?php foreach ($errors as $er): ?><div>• <?= e($er) ?></div><?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <form class="form" method="post" novalidate>
                        <input type="hidden" name="csrf" value="<?= e($_SESSION['csrf']) ?>">
                        <input class="hp" type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true">
                        <div class="row">
                            <label>Mağaza adı *<input name="name" value="<?= e($old['name']) ?>" placeholder="Məs: Rose Boutique" required></label>
                            <label>Sahibkarın adı *<input name="owner" value="<?= e($old['owner']) ?>" placeholder="Ad Soyad" required></label>
                        </div>
                        <div class="row">
                            <label>Telefon *<input name="phone" value="<?= e($old['phone']) ?>" inputmode="tel" placeholder="+994 50 000 00 00" required></label>
                            <label>E-poçt *<input type="email" name="email" value="<?= e($old['email']) ?>" placeholder="mail@magaza.az" required></label>
                        </div>
                        <div class="row">
                            <label>Şəhər
                                <select name="city">
                                    <?php foreach (['Bakı', 'Sumqayıt', 'Gəncə', 'Mingəçevir', 'Lənkəran', 'Şəki', 'Digər'] as $c): ?>
                                        <option <?= $old['city'] === $c ? 'selected' : '' ?>><?= e($c) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                            <label>Ünvan *<input name="address" value="<?= e($old['address']) ?>" placeholder="Küçə, bina" required></label>
                        </div>
                        <div>
                            <div style="font-size:13px;font-weight:500;color:#cfcaca;margin-bottom:8px">Kateqoriyalar *</div>
                            <div class="checks">
                                <?php foreach ($categories as $c): ?>
                                    <label><input type="checkbox" name="cats[]" value="<?= e($c['key']) ?>" <?= in_array($c['key'], $old['cats'], true) ? 'checked' : '' ?>> <?= e($c['name']) ?></label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <label>Mağaza haqqında<textarea name="about" rows="3" placeholder="Qısa təsvir..."><?= e($old['about']) ?></textarea></label>
                        <label style="display:flex;grid-auto-flow:column;gap:10px;align-items:center;justify-content:start">
                            <input type="checkbox" name="terms" style="width:16px;height:16px;accent-color:#e11d3f"> İstifadə şərtləri ilə razıyam
                        </label>
                        <button class="btn btn-primary btn-lg" type="submit">Müraciət göndər <?= icon('arrow') ?></button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>

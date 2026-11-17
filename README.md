# Flower.az — gül satışı marketplace şablonu (PHP)

Qaranlıq + qırmızı, 2026 üslubunda çoxsatıcılı gül mağazası nümunəsi.

## İşə salmaq
PHP 8.0+ lazımdır. Qovluqda:

    php -S localhost:8000

Sonra brauzerdə: http://localhost:8000/index.php

## Səhifələr
| Fayl | Təsvir |
|---|---|
| `index.php` | Ana səhifə: slider, kateqoriyalar, mağazalar, populyar güllər |
| `magaza.php?s=rose-boutique` | **Əsas fayl** — mağaza profili (məhsullar / haqqımızda / rəylər) |
| `magazalar.php` | Bütün mağazalar: axtarış, şəhər filtri, sıralama |
| `magaza-ac.php` | Satıcı qeydiyyat formu (`data/store_requests.json`-a yazır) |
| `panel.php` | Satıcı paneli: statistika, gəlir qrafiki, məhsul əlavə et, sifarişlər |
| `haqqimizda.php` | Haqqımızda, əlaqə, FAQ |

Səbət (çəkmə, miqdar, sifariş formu) bütün səhifələrdə işləyir və `localStorage`-da saxlanır.

## Məlumatı dəyişmək
`data/data.php` — mağazalar, məhsullar, kateqoriyalar. Real layihədə verilənlər bazasına keçirin.
Şəkillər SVG ilə kodla çəkilir (`includes/functions.php`); real foto üçün `bouquet_svg()` yerinə `<img>` qoyun.

## Qeyd
Bu demodur: panel girişsizdir, sifarişlər serverə göndərilmir. Canlıya çıxarmazdan əvvəl autentifikasiya, DB və ödəniş əlavə edin.

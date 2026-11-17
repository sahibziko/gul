<?php
/**
 * Nümunə məlumatlar. Real layihədə bunlar verilənlər bazasından (MySQL/SQLite) gələcək.
 * Yeni mağaza / məhsul əlavə etmək üçün sadəcə massivə sətir əlavə edin.
 */

const SITE_NAME = 'Flower.az';

$categories = [
    ['key' => 'qizilgul',      'name' => 'Qızılgül',     'tone' => 'red'],
    ['key' => 'lale',          'name' => 'Lalə',         'tone' => 'pink'],
    ['key' => 'orxideya',      'name' => 'Orxideya',     'tone' => 'orchid'],
    ['key' => 'suni',          'name' => 'Süni Güllər',  'tone' => 'cream'],
    ['key' => 'kompozisiya',   'name' => 'Kompozisiyalar', 'tone' => 'mix'],
    ['key' => 'diger',         'name' => 'Digər',        'tone' => 'red'],
];

$stores = [
    'rose-boutique' => [
        'name' => 'Rose Boutique', 'rating' => 4.9, 'reviews' => 1200, 'reviews_label' => '1.2K',
        'products' => '99+', 'premium' => true, 'verified' => true, 'tone' => 'red', 'city' => 'Bakı',
        'since' => 2019, 'phone' => '+994 50 123 45 67', 'hours' => '09:00 – 22:00',
        'about' => 'Rose Boutique 2019-cu ildən Bakıda premium qızılgül buketləri və xüsusi kompozisiyalar hazırlayır. Hər buket təzə gülləri ilə əl işidir və sifarişdən sonra 2 saat ərzində çatdırılır.',
    ],
    'flower-dream' => [
        'name' => 'Flower Dream', 'rating' => 4.8, 'reviews' => 876, 'reviews_label' => '876',
        'products' => '78', 'premium' => false, 'verified' => true, 'tone' => 'pink', 'city' => 'Bakı',
        'since' => 2020, 'phone' => '+994 55 222 33 44', 'hours' => '10:00 – 21:00',
        'about' => 'Pudra və pastel tonlu buketlər üzrə ixtisaslaşmış zərif mağaza. Toy və ad günü kompozisiyaları.',
    ],
    'gul-dunyasi' => [
        'name' => 'Gül Dünyası', 'rating' => 4.7, 'reviews' => 742, 'reviews_label' => '742',
        'products' => '120', 'premium' => false, 'verified' => true, 'tone' => 'mix', 'city' => 'Sumqayıt',
        'since' => 2017, 'phone' => '+994 70 555 66 77', 'hours' => '08:00 – 23:00',
        'about' => 'Geniş çeşid, əlverişli qiymət. Rəngarəng buketlər və mövsümi gül kompozisiyaları.',
    ],
    'bloom-store' => [
        'name' => 'Bloom Store', 'rating' => 4.9, 'reviews' => 630, 'reviews_label' => '630',
        'products' => '95', 'premium' => false, 'verified' => true, 'tone' => 'red', 'city' => 'Bakı',
        'since' => 2021, 'phone' => '+994 51 888 99 00', 'hours' => '09:00 – 22:00',
        'about' => 'Qırmızı və bordo qızılgül buketlərində ixtisaslaşıb. Eyni gün çatdırılma.',
    ],
    'nargiz-gullari' => [
        'name' => 'Nərgiz Güllər', 'rating' => 4.6, 'reviews' => 410, 'reviews_label' => '410',
        'products' => '54', 'premium' => false, 'verified' => false, 'tone' => 'white', 'city' => 'Gəncə',
        'since' => 2022, 'phone' => '+994 77 321 00 11', 'hours' => '09:00 – 20:00',
        'about' => 'Ağ və krem tonlu güllər, gəlin buketləri və dekorativ kompozisiyalar.',
    ],
    'orxideya-house' => [
        'name' => 'Orxideya House', 'rating' => 4.8, 'reviews' => 389, 'reviews_label' => '389',
        'products' => '61', 'premium' => true, 'verified' => true, 'tone' => 'orchid', 'city' => 'Bakı',
        'since' => 2018, 'phone' => '+994 50 700 80 90', 'hours' => '10:00 – 20:00',
        'about' => 'Orxideya, lalə və eksklüziv ev bitkiləri. Hədiyyə qablaşdırması ilə.',
    ],
];

// id, ad, qiymət (AZN), mağaza, kateqoriya, ton, reytinq
$products = [
    ['id' => 1,  'name' => 'Qırmızı Qızılgüllər',   'price' => 49, 'store' => 'rose-boutique', 'cat' => 'qizilgul',    'tone' => 'red',    'rating' => 4.9],
    ['id' => 2,  'name' => 'Ağ Qızılgüllər',         'price' => 44, 'store' => 'rose-boutique', 'cat' => 'qizilgul',    'tone' => 'white',  'rating' => 4.8],
    ['id' => 3,  'name' => 'Pudra Qızılgüllər',      'price' => 47, 'store' => 'rose-boutique', 'cat' => 'qizilgul',    'tone' => 'pink',   'rating' => 4.9],
    ['id' => 4,  'name' => 'Rəngarəng Buket',        'price' => 59, 'store' => 'rose-boutique', 'cat' => 'kompozisiya', 'tone' => 'mix',    'rating' => 4.7],
    ['id' => 5,  'name' => 'Bordo Qızılgüllər',      'price' => 51, 'store' => 'rose-boutique', 'cat' => 'qizilgul',    'tone' => 'red',    'rating' => 4.9],
    ['id' => 6,  'name' => 'Lalə Buketi',            'price' => 38, 'store' => 'rose-boutique', 'cat' => 'lale',        'tone' => 'pink',   'rating' => 4.6],
    ['id' => 7,  'name' => 'Orxideya Kompozisiyası', 'price' => 72, 'store' => 'rose-boutique', 'cat' => 'orxideya',    'tone' => 'orchid', 'rating' => 4.8],
    ['id' => 8,  'name' => 'Krem Güllər',            'price' => 42, 'store' => 'rose-boutique', 'cat' => 'suni',        'tone' => 'cream',  'rating' => 4.5],
    ['id' => 9,  'name' => 'Qutuda Güllər',          'price' => 85, 'store' => 'rose-boutique', 'cat' => 'kompozisiya', 'tone' => 'red',    'rating' => 5.0],
    ['id' => 10, 'name' => 'Mini Buket',             'price' => 29, 'store' => 'rose-boutique', 'cat' => 'diger',       'tone' => 'red',    'rating' => 4.7],
    ['id' => 11, 'name' => 'Pastel Buket',           'price' => 46, 'store' => 'flower-dream',  'cat' => 'qizilgul',    'tone' => 'pink',   'rating' => 4.8],
    ['id' => 12, 'name' => 'Toy Kompozisiyası',      'price' => 120,'store' => 'flower-dream',  'cat' => 'kompozisiya', 'tone' => 'white',  'rating' => 4.9],
    ['id' => 13, 'name' => 'Lalə Səbəti',            'price' => 54, 'store' => 'flower-dream',  'cat' => 'lale',        'tone' => 'pink',   'rating' => 4.7],
    ['id' => 14, 'name' => 'Yaz Buketi',             'price' => 52, 'store' => 'gul-dunyasi',   'cat' => 'kompozisiya', 'tone' => 'mix',    'rating' => 4.6],
    ['id' => 15, 'name' => 'Sarı Qızılgüllər',       'price' => 40, 'store' => 'gul-dunyasi',   'cat' => 'qizilgul',    'tone' => 'mix',    'rating' => 4.5],
    ['id' => 16, 'name' => 'Qırmızı Lalələr',        'price' => 35, 'store' => 'gul-dunyasi',   'cat' => 'lale',        'tone' => 'red',    'rating' => 4.7],
    ['id' => 17, 'name' => 'Klassik Qırmızı',        'price' => 55, 'store' => 'bloom-store',   'cat' => 'qizilgul',    'tone' => 'red',    'rating' => 4.9],
    ['id' => 18, 'name' => 'Premium Bordo',          'price' => 68, 'store' => 'bloom-store',   'cat' => 'qizilgul',    'tone' => 'red',    'rating' => 4.9],
    ['id' => 19, 'name' => 'Gəlin Buketi',           'price' => 90, 'store' => 'nargiz-gullari','cat' => 'kompozisiya', 'tone' => 'white',  'rating' => 4.6],
    ['id' => 20, 'name' => 'Ağ Orxideya',            'price' => 78, 'store' => 'orxideya-house','cat' => 'orxideya',    'tone' => 'white',  'rating' => 4.8],
    ['id' => 21, 'name' => 'Bənövşəyi Orxideya',     'price' => 82, 'store' => 'orxideya-house','cat' => 'orxideya',    'tone' => 'orchid', 'rating' => 4.9],
];

$reviews = [
    ['name' => 'Aysel M.',  'rate' => 5, 'text' => 'Buket şəkildəkindən də gözəl idi. Çatdırılma vaxtında gəldi, güllər tam təzə.', 'date' => '2 gün əvvəl'],
    ['name' => 'Rəşad H.',  'rate' => 5, 'text' => 'Ad günü üçün sifariş verdim. Qablaşdırma çox təsirli idi, yenə sifariş verəcəm.', 'date' => '1 həftə əvvəl'],
    ['name' => 'Leyla Q.',  'rate' => 4, 'text' => 'Qiymət/keyfiyyət balansı yaxşıdır. Kuryer çox nəzakətli idi.', 'date' => '2 həftə əvvəl'],
    ['name' => 'Elçin T.',  'rate' => 5, 'text' => 'Ən yaxşı qızılgül mağazası. Bir neçə dəfə sifariş vermişəm, heç vaxt məyus etməyiblər.', 'date' => '1 ay əvvəl'],
];

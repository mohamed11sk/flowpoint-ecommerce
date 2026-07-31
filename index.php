<?php
// ============================================================
//  index.php — الصفحة الرئيسية للمتجر
// ============================================================
include 'includes/function.php';

$sliders      = fetch_active_sliders();
$products     = fetch_products();       // category_name + category_id من JOIN
$specialDeals = fetch_special_deals();
$categories   = fetch_categories();     // تجيب الأيقونة من DB أو fallback تلقائي

// ── دالة الأيقونة الاحتياطية (تُستدعى لو icon فاضي في DB) ──
function get_category_icon(string $name): string
{
    $map = [
        'صوصات'   => 'fa-bottle-droplet',
        'صوص'     => 'fa-bottle-droplet',
        'صلصات'   => 'fa-bottle-droplet',
        'صلصة'    => 'fa-bottle-droplet',
        'كاتشب'   => 'fa-bottle-droplet',
        'مايونيز' => 'fa-bottle-droplet',
        'مخللات'  => 'fa-jar',
        'مخلل'    => 'fa-jar',
        'عسل'     => 'fa-jar',
        'مربى'    => 'fa-jar',
        'خل'      => 'fa-flask-vial',
        'زيت'     => 'fa-oil-can',
        'زيوت'    => 'fa-oil-can',
        'دهن'     => 'fa-drumstick-bite',
        'دهون'    => 'fa-drumstick-bite',
        'بهارات'  => 'fa-pepper-hot',
        'توابل'   => 'fa-pepper-hot',
    ];
    foreach ($map as $keyword => $icon) {
        if (mb_strpos($name, $keyword) !== false) return $icon;
    }
    return 'fa-tag';
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>متجرنا - تجربة تسوق رائعة</title>
    <meta name="description" content="تسوق أفضل المنتجات من مخلات وصوصات وزيوت ودهون بأسعار مميزة">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/search.css">
    <link rel="stylesheet" href="css/products-page.css">
    <link rel="stylesheet" href="css/filter.css">
</head>
<body>

    <?php include 'includes/header.php'; ?>

    <!-- ===== زر التمرير للأعلى ===== -->
    <button id="scrollToTop" class="scroll-top-btn" aria-label="العودة للأعلى" hidden>
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- ===== الشرائح الدعائية ===== -->
    <div class="container">
        <div class="hero-slider">
            <?php foreach ($sliders as $i => $slide): ?>
                <div class="slide<?= $i === 0 ? ' active' : '' ?>"
                     style="background-image: linear-gradient(rgba(0,0,0,0.35), rgba(0,0,0,0.35)),
                            url('<?= htmlspecialchars($slide['image_url']) ?>')">
                    <div class="slide-content">
                        <h2><?= htmlspecialchars($slide['title']) ?></h2>
                        <p><?= nl2br(htmlspecialchars($slide['description'])) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="slider-nav">
                <?php
                $dotCount = count($sliders) ?: 3;
                for ($d = 0; $d < $dotCount; $d++):
                ?>
                    <div class="slider-dot<?= $d === 0 ? ' active' : '' ?>"></div>
                <?php endfor; ?>
            </div>
        </div>
    </div>

    <!-- ===== شريط فلترة التصنيفات ===== -->
    <div class="container">
        <div class="category-filter-bar">

            <div class="filter-label-row">
                <span class="filter-bar-label">
                    <i class="fas fa-sliders-h"></i>
                    تصفية حسب
                </span>
            </div>

            <div class="category-chips">
                <button class="cat-chip active" data-category="all" data-category-name="جميع المنتجات" type="button">
                    <span class="chip-icon">
                        <i class="fas fa-border-all"></i>
                    </span>
                    <span class="chip-label">جميع المنتجات</span>
                </button>

                <?php foreach ($categories as $cat):
                    // لو icon موجود في DB استخدمه، غير كده استخدم الـ fallback
                    $icon = !empty(trim($cat['icon'] ?? ''))
                        ? $cat['icon']
                        : get_category_icon($cat['name']);
                ?>  
                    <button class="cat-chip" data-category="<?= (int)$cat['id'] ?>" data-category-name="<?= htmlspecialchars($cat['name']) ?>" type="button">
                        <span class="chip-icon">
                            <i class="fas <?= htmlspecialchars($icon) ?>"></i>
                        </span>
                        <span class="chip-label"><?= htmlspecialchars($cat['name']) ?></span>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- ===== قسم المنتجات ===== -->
    <div class="container" id="products-container">
        <div class="products-section">

            <!-- رأس القسم -->
            <div class="section-header">
                <div class="section-title-wrapper">
                    <div class="section-title-icon" id="sectionIcon">
                        <i class="fas fa-border-all"></i>
                    </div>
                    <h2 class="section-title" id="sectionTitle">جميع المنتجات</h2>
                </div>
                <span class="products-count-badge" id="productsCount">
                    <?= count($products) ?> منتج
                </span>
            </div>

            <!-- شريط الترتيب -->
            <div class="products-toolbar">
                <div></div>
                <select class="sort-select-styled" id="sort-by">
                    <option value="default">الترتيب الافتراضي</option>
                    <option value="low-high">السعر: من الأقل للأعلى</option>
                    <option value="high-low">السعر: من الأعلى للأقل</option>
                    <option value="rating">الأعلى تقييماً</option>
                    <option value="discount">أكبر خصم</option>
                </select>
            </div>

            <!-- شبكة المنتجات -->
            <div class="products-grid" id="products-grid">

                <?php foreach ($products as $i => $p):
                    $catId     = (int)($p['category_id'] ?? 0);
                    $price     = (float)($p['current_price'] ?? 0);
                    $rating    = (float)($p['rating'] ?? 0);
                    $discount  = (float)($p['discount_percent'] ?? 0);
                    $fullStars = (int)floor($rating);
                    $halfStar  = ($rating - $fullStars) >= 0.5;
                ?>
                <div class="product-card fade-in"
                     data-id="<?= (int)($p['id'] ?? $i + 1) ?>"
                     data-category="<?= $catId ?>"
                     data-price="<?= $price ?>"
                     data-rating="<?= $rating ?>"
                     data-discount="<?= $discount ?>"
                     style="animation-delay: <?= $i * 0.04 ?>s">

                    <a href="details.php?id=<?= (int)($p['id'] ?? $i + 1) ?>">
                        <?php if (!empty($p['badge'])): ?>
                            <span class="product-badge new"><?= htmlspecialchars($p['badge']) ?></span>
                        <?php endif; ?>
                        <div class="product-image">
                            <img src="<?= htmlspecialchars($p['image'] ?? '') ?>"
                                 alt="<?= htmlspecialchars($p['title'] ?? '') ?>"
                                 loading="lazy">
                        </div>
                    </a>

                    <div class="product-info">
                        <h3 class="product-title"><?= htmlspecialchars($p['title'] ?? '') ?></h3>

                        <div class="product-rating">
                            <?php for ($s = 0; $s < $fullStars; $s++): ?>
                                <i class="fas fa-star"></i>
                            <?php endfor; ?>
                            <?php if ($halfStar): ?>
                                <i class="fas fa-star-half-alt"></i>
                            <?php endif; ?>
                            <span class="rating-value"><?= number_format($rating, 1) ?></span>
                        </div>

                        <div class="product-price">
                            <?php if (!empty($p['old_price'])): ?>
                                <span class="old-price"><?= htmlspecialchars($p['old_price']) ?></span>
                            <?php endif; ?>
                            <span class="current-price"><?= htmlspecialchars($p['current_price'] ?? '') ?></span>
                            <?php if ($discount > 0): ?>
                                <span class="discount">-<?= (int)$discount ?>%</span>
                            <?php endif; ?>
                        </div>

                        <div class="product-actions">
                            <button class="wishlist" aria-label="أضف للمفضلة">
                                <i class="far fa-heart"></i>
                            </button>
                            <button class="add-to-cart">أضف للسلة</button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>

                <!-- رسالة لا توجد نتائج -->
                <div class="no-results-msg" id="noResultsMsg" hidden>
                    <i class="fas fa-search"></i>
                    <h3>لا توجد منتجات في هذا التصنيف</h3>
                    <p>جرّب تصنيفاً آخر أو عُد لجميع المنتجات</p>
                </div>

            </div><!-- /.products-grid -->

            <!-- Pagination -->
            <nav class="pagination" id="pagination" aria-label="تنقل بين الصفحات"></nav>

        </div><!-- /.products-section -->
    </div><!-- /.container #products-container -->

    <!-- ===== قسم العروض الخاصة ===== -->
    <?php if (!empty($specialDeals)): ?>
    <section class="special-deals-section">
        <div class="container">
            <div class="section-header">
                <h2><i class="fas fa-fire"></i> العروض الخاصة والمجنونة</h2>
            </div>
            <div class="deals-grid">
                <?php foreach ($specialDeals as $deal): ?>
                <div class="deal-card">
                    <div class="deal-image">
                        <img src="<?= htmlspecialchars($deal['image']) ?>"
                             alt="<?= htmlspecialchars($deal['title']) ?>">
                        <?php if (!empty($deal['end_date'])): ?>
                            <div class="deal-timer" data-end-date="<?= htmlspecialchars($deal['end_date']) ?>">
                                <i class="fas fa-clock"></i>
                                <span class="countdown">--:--:--</span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="deal-info">
                        <h3><?= htmlspecialchars($deal['title']) ?></h3>
                        <div class="deal-price">
                            <span class="original"><?= htmlspecialchars($deal['old_price']) ?></span>
                            <span class="current"><?= htmlspecialchars($deal['current_price']) ?></span>
                        </div>
                        <div class="deal-discount">توفير <?= (int)$deal['discount_percent'] ?>%</div>
                        <a href="details.php?id=<?= (int)$deal['id'] ?>" class="deal-button">عرض التفاصيل</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- ===== التذييل ===== -->
    <footer class="footer">
        <?php include 'includes/footer.php'; ?>
    </footer>

    <!-- إشعار السلة -->
    <div id="cart-notification" class="cart-notification" aria-live="polite"></div>

    <!-- Scripts -->
    <script src="js/common.js"></script>
    <script src="js/main.js"></script>
    <script src="js/filter.js"></script>

</body>
</html>
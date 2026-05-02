<?php
include 'function.php';

$sliders = fetch_active_sliders();
$products = fetch_products();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>متجر إلكتروني - تجربة تسوق رائعة</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
   <?php include 'header.php'; ?>
   
<!-- سله التسوق  -->
     <!-- زر التمرير للأعلى -->
<div id="scrollToTop" style="display: none; position: fixed; bottom: 20px; left: 20px; z-index: 99;">
    <i class="fas fa-arrow-up" style="font-size: 24px; background: #ff9900; color: white; padding: 10px; border-radius: 50%; cursor: pointer; border:   2px solid black;"></i>
</div>

<!-- سلة التسوق موجودة في header.php -->
    

    
    <!-- الشرائح الدعائية -->
    <div class="container">
        <div class="hero-slider">
            <?php if (count($sliders) > 0): ?>
                <?php foreach ($sliders as $i => $slide): ?>
                    <div class="slide<?= $i === 0 ? ' active' : '' ?>" style="background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('<?= htmlspecialchars($slide['image_url']) ?>')">
                        <div class="slide-content">
                            <h2><?= htmlspecialchars($slide['title']) ?></h2>
                            <p><?= nl2br(htmlspecialchars($slide['description'])) ?></p>
                            <?php if (!empty($slide['link'])): ?>
                                <a href="<?= htmlspecialchars($slide['link']) ?>" class="btn">تسوق الآن</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="slide active" style="background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('https://images.unsplash.com/photo-1505740420928-5e560c06d30e?ixlib=rb-4.0.3')">
                    <div class="slide-content">
                        <h2>خصومات الصيف الكبرى</h2>
                        <p>استمتع بخصومات تصل إلى 50% على أحدث المنتجات في جميع الفئات. العرض ساري لفترة محدودة!</p>
                        <a href="#" class="btn">تسوق الآن</a>
                    </div>
                </div>
            <?php endif; ?>
            <div class="slider-nav">
                <?php if (count($sliders) > 0): ?>
                    <?php foreach ($sliders as $i => $slide): ?>
                        <div class="slider-dot<?= $i === 0 ? ' active' : '' ?>"></div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="slider-dot active"></div>
                    <div class="slider-dot"></div>
                    <div class="slider-dot"></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- نظام تصنيف المنتجات -->
    <div class="container">
        <div class="product-filter">
            <div class="filter-section">
                <span class="filter-label">تصنيف حسب:</span>
                <select class="filter-select" id="sort-by">
                    <option value="popular">الأكثر شعبية</option>
                    <option value="newest">الأحدث</option>
                    <option value="low-high">السعر: من الأقل للأعلى</option>
                    <option value="high-low">السعر: من الأعلى للأقل</option>
                </select>
            </div>
            
            <div class="filter-section">
                <span class="filter-label">عرض:</span>
                <select class="filter-select" id="per-page">
                    <option value="12">12 منتج</option>
                    <option value="24">24 منتج</option>
                    <option value="36">36 منتج</option>
                </select>
            </div>
        </div>
    </div>
    
    <!-- قسم المنتجات المميزة -->
    <div class="container" id="products-container">
        <div class="products-section">
            <div class="section-header">
                <h2 class="section-title">منتجات مميزة</h2>
                <a href="#" class="view-all">عرض الكل</a>
            </div>
            
            <div class="products-grid" id="products-grid">
             
                <!-- منتج 1 -->
              <!-- ______________________________________________________________    -->
               <?php foreach($products as $index => $product):?>
                <div class="product-card" data-id="<?php echo $product['id'] ?? ($index + 1); ?>">
                  <a href="details.php?id=<?php echo $product['id'] ?? ($index + 1); ?>">
                    <span class="product-badge new">
                        <?php echo $product['badge']; ?>
                    </span>
                    <div class="product-image">
                        <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['title']; ?>">
                    </div>

</a>

                    <div class="product-info">
                        <h3 class="product-title"><?php echo $product['title']; ?></h3>
                        <div class="product-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <i class="far fa-star"></i>
                            <?php echo $product['rating']; ?>
                        </div>
                        <div class="product-price">
                            <span class="old-price"><?php echo $product['old_price']; ?></span>
                            <span class="current-price"><?php echo $product['current_price']; ?></span>
                            <span class="discount">-<?php echo $product['discount_percent']; ?>%</span>
                        </div>
                        <div class="product-actions">
                            <button class="wishlist"><i class="far fa-heart"></i></button>
                            <button class="add-to-cart">أضف للسلة</button>
                        </div>
                    </div>
                </div>
                <?php endforeach?>


           
            </div>
            
            <!-- نظام تقليب الصفحات -->
            <div class="pagination" id="pagination">
                <!-- سيتم تعبئة أزرار الصفحات بواسطة الجافاسكريبت -->
            </div>
        </div>
    </div>
    
    <!-- صفحة تفاصيل المنتج -->
    <div class="container">
        <div class="product-details-page" id="product-details-page">
            <!-- سيتم تعبئتها بواسطة الجافاسكريبت -->
        </div>
    </div>
    
    <!-- قسم العروض الخاصة -->
    <div class="container">
        <div class="deals-section">
            <div class="section-header">
                <h2 class="section-title">عروض خاصة</h2>
                <div class="timer">
                    <i class="fas fa-clock"></i>
                    ينتهي خلال: <span id="deal-timer">12:45:22</span>
                </div>
            </div>
            
            <div class="products-grid" id="deals-grid">
                <!-- سيتم تعبئة المنتجات بواسطة الجافاسكريبت -->
                 <?php foreach($products as $index => $product):?>
                <div class="product-card" data-id="<?php echo $product['id'] ?? ($index + 100); ?>">
                    <span class="product-badge new">
                        <?php echo $product['badge']; ?>
                    </span>
                    <div class="product-image">
                        <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['title']; ?>">
                    </div>
                    <div class="product-info">
                        <h3 class="product-title"><?php echo $product['title']; ?></h3>
                        <div class="product-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <i class="far fa-star"></i>
                            <?php echo $product['rating']; ?>
                        </div>
                        <div class="product-price">
                            <span class="old-price"><?php echo $product['old_price']; ?></span>
                            <span class="current-price"><?php echo $product['current_price']; ?></span>
                            <span class="discount">-<?php echo $product['discount_percent']; ?>%</span>
                        </div>
                        <div class="product-actions">
                            <button class="wishlist"><i class="far fa-heart"></i></button>
                            <button class="add-to-cart">أضف للسلة</button>
                        </div>
                    </div>
                </div>
                <?php endforeach?>


            </div>
        </div>
    </div>
    
    <!-- التذييل -->
    <footer class="footer">
        <?php include 'footer.php';?>
       
    </footer>
    <div id="cart-notification" class="cart-notification"></div>
    <script src="js/common.js"></script>
    <script src="js/main.js"></script>

</body>
</html>

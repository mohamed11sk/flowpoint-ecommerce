<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل المنتج - متجرنا</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background: #f6f8fa;
            font-family: 'Tajawal', sans-serif;
        }
        .details-container {
            max-width: 1100px;
            margin: 40px auto;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(44,62,80,0.10), 0 1.5px 6px rgba(44,62,80,0.08);
            padding: 40px 30px;
            animation: fadeIn 0.7s;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(40px);}
            to { opacity: 1; transform: translateY(0);}
        }
        .details-flex {
            display: flex;
            gap: 40px;
            flex-wrap: wrap;
        }
        .details-image-gallery {
            flex: 1 1 340px;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-width: 280px;
            min-height: 340px;
        }
        .main-image-box {
            background: linear-gradient(135deg, #f8fafc 60%, #f0f4f8 100%);
            border-radius: 14px;
            min-height: 340px;
            min-width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 12px rgba(44,62,80,0.06);
            margin-bottom: 18px;
        }
        .main-image-box img {
            max-width: 100%;
            max-height: 340px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(44,62,80,0.08);
            background: #fff;
            transition: 0.2s;
        }
        .thumbnails {
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .thumbnail {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            overflow: hidden;
            border: 2px solid transparent;
            cursor: pointer;
            transition: border 0.2s;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .thumbnail.active, .thumbnail:hover {
            border: 2px solid var(--primary-color, #e83a3a);
        }
        .thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
        }
        .details-info {
            flex: 2 1 400px;
            padding: 0 10px;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 25px;
            color: var(--secondary-color, #2d6cdf);
            text-decoration: none;
            font-weight: bold;
            font-size: 16px;
            transition: color 0.2s;
        }
        .back-link:hover {
            color: var(--primary-color, #e83a3a);
        }
        .details-title {
            font-size: 2.2rem;
            margin-bottom: 18px;
            color: var(--primary-color, #e83a3a);
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .details-rating {
            color: #ffb400;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            font-size: 1.2rem;
        }
        .details-rating i {
            margin-left: 2px;
        }
        .details-price {
            display: flex;
            align-items: center;
            margin-bottom: 22px;
            gap: 12px;
        }
        .current-price {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary-color, #e83a3a);
        }
        .old-price {
            text-decoration: line-through;
            color: #aaa;
            font-size: 1.1rem;
        }
        .discount {
            color: #e83a3a;
            font-weight: bold;
            font-size: 1.1rem;
            background: #ffeaea;
            padding: 2px 10px;
            border-radius: 6px;
        }
        .details-description {
            margin-bottom: 28px;
            line-height: 1.9;
            color: #444;
            font-size: 1.1rem;
            background: #f8fafc;
            border-radius: 8px;
            padding: 18px 20px;
        }
        .specs-section {
            margin-bottom: 28px;
        }
        .specs-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }
        .spec-item {
            display: flex;
            margin-bottom: 6px;
            font-size: 1rem;
        }
        .spec-label {
            font-weight: bold;
            min-width: 110px;
            color: var(--secondary-color, #2d6cdf);
        }
        .spec-value {
            color: #555;
        }
        .details-actions {
            display: flex;
            gap: 16px;
            margin-top: 35px;
            flex-wrap: wrap;
        }
        .add-to-cart-btn {
            background: linear-gradient(90deg, var(--primary-color, #e83a3a) 60%, var(--secondary-color, #2d6cdf) 100%);
            color: white;
            border: none;
            padding: 14px 32px;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.2s, box-shadow 0.2s;
            font-weight: bold;
            font-size: 1.1rem;
            box-shadow: 0 2px 8px rgba(44,62,80,0.08);
            flex-grow: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .add-to-cart-btn:hover {
            background: linear-gradient(90deg, var(--secondary-color, #2d6cdf) 60%, var(--primary-color, #e83a3a) 100%);
            box-shadow: 0 4px 16px rgba(44,62,80,0.13);
        }
        .wishlist-btn {
            width: 52px;
            height: 52px;
            background: #f0f2f2;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            transition: background 0.2s, color 0.2s;
            color: #e83a3a;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(44,62,80,0.07);
        }
        .wishlist-btn:hover, .wishlist-btn.active {
            background: #ffeaea;
            color: #ff0000;
        }
        @media (max-width: 992px) {
            .details-flex {
                flex-direction: column;
                gap: 25px;
            }
            .details-image-gallery {
                min-height: 220px;
            }
        }
        @media (max-width: 576px) {
            .details-container {
                padding: 15px 5px;
            }
            .details-title {
                font-size: 1.3rem;
            }
            .add-to-cart-btn {
                font-size: 1rem;
                padding: 10px 10px;
            }
            .thumbnails {
                gap: 5px;
            }
        }
        .not-found {
            text-align: center;
            color: #e83a3a;
            font-size: 1.5rem;
            margin: 60px 0;
        }
    </style>
</head>
<body>
    <?php include 'function.php'; ?>
    <?php include 'header.php'; ?>
    <?php
        $product = null;
        $gallery = [];
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $product = fetch_product_by_id($id);
            $images = $product ? fetch_product_images($id) : [];

            // تجهيز مصفوفة الصور: إذا لم توجد صور إضافية، استخدم صورة المنتج الأساسية
            if (!empty($images)) {
                foreach ($images as $img) {
                    if (!empty($img['image_path'])) {
                        $gallery[] = $img['image_path'];
                    }
                }
            }

            if (empty($gallery) && !empty($product['image'])) {
                $gallery[] = $product['image'];
            }
        }
    ?>
    <div class="container">
        <?php if(!$product): ?>
            <div class="not-found">عذراً، المنتج غير موجود!</div>
        <?php else: ?>
        <div class="details-container">
            <a href="index.php" class="back-link"><i class="fas fa-arrow-right"></i> رجوع للمنتجات</a>
            <div class="details-flex">
                <div class="details-image-gallery">
                    <div class="main-image-box">
                        <img id="mainProductImage" src="<?php echo htmlspecialchars($gallery[0]); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>">
                    </div>
                    <?php if(count($gallery) > 1): ?>
                    <div class="thumbnails">
                        <?php foreach($gallery as $idx => $imgPath): ?>
                            <div class="thumbnail<?php echo $idx === 0 ? ' active' : ''; ?>" onclick="changeMainImage('<?php echo htmlspecialchars($imgPath); ?>', this)">
                                <img src="<?php echo htmlspecialchars($imgPath); ?>" alt="صورة المنتج">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="details-info">
                    <h2 class="details-title"><?php echo htmlspecialchars($product['title']); ?></h2>
                    <div class="details-rating">
                        <?php
                        $rating = isset($product['rating']) ? floatval($product['rating']) : 0;
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i <= $rating) echo '<i class="fas fa-star"></i>';
                            elseif ($i - $rating < 1) echo '<i class="fas fa-star-half-alt"></i>';
                            else echo '<i class="far fa-star"></i>';
                        }
                        ?>
                        <span style="margin-right:10px;font-size:1rem;color:#888;">(<?php echo $product['rating'] ?? '0'; ?>)</span>
                    </div>
                    <div class="details-price">
                        <span class="current-price"><?php echo $product['current_price']; ?> ر.س</span>
                        <?php if (!empty($product['old_price'])): ?>
                            <span class="old-price"><?php echo $product['old_price']; ?> ر.س</span>
                        <?php endif; ?>
                        <?php if (!empty($product['discount_percent'])): ?>
                            <span class="discount">-<?php echo $product['discount_percent']; ?>%</span>
                        <?php endif; ?>
                    </div>
                    <div class="details-description">
                        <?php echo !empty($product['description']) ? htmlspecialchars($product['description']) : 'لا يوجد وصف متاح لهذا المنتج.'; ?>
                    </div>
                    <div class="specs-section">
                        <div class="specs-grid">
                            <div class="spec-item"><span class="spec-label">التصنيف:</span> <span class="spec-value"><?php echo $product['category'] ?? '-'; ?></span></div>
                            <div class="spec-item"><span class="spec-label">العلامة التجارية:</span> <span class="spec-value"><?php echo $product['brand'] ?? '-'; ?></span></div>
                            <div class="spec-item"><span class="spec-label">الحالة:</span> <span class="spec-value"><?php echo $product['badge'] ?? '-'; ?></span></div>
                            <!-- أضف المزيد من المواصفات حسب قاعدة بياناتك -->
                        </div>
                    </div>
                    <div class="details-actions">
                        <button class="add-to-cart-btn" onclick="add_product_to_cart({
                            id: '<?php echo $product['id']; ?>',
                            title: '<?php echo addslashes($product['title']); ?>',
                            price: '<?php echo $product['current_price']; ?>',
                            oldPrice: '<?php echo $product['old_price']; ?>',
                            image: '<?php echo $gallery[0]; ?>'
                        })"><i class="fas fa-shopping-cart"></i> أضف للسلة</button>
                        <button class="wishlist-btn" onclick="addToWishlist({
                            id: '<?php echo $product['id']; ?>',
                            title: '<?php echo addslashes($product['title']); ?>',
                            price: '<?php echo $product['current_price']; ?>',
                            oldPrice: '<?php echo $product['old_price']; ?>',
                            image: '<?php echo $gallery[0]; ?>'
                        })"><i class="far fa-heart"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <?php include 'footer.php'; ?>
    <script src="js/cart.js"></script>
    <script src="js/wishlist.js"></script>
    <script src="js/favorite.js"></script>

    <script>
        // مصفوفة الصور من PHP إلى جافاسكريبت
        var galleryImages = <?php echo json_encode($gallery); ?>;
        var currentIndex = 0;
        var sliderInterval = null;

        function showImage(idx) {
            var mainImg = document.getElementById('mainProductImage');
            mainImg.src = galleryImages[idx];
            // تحديث active على المصغرات
            var thumbs = document.querySelectorAll('.thumbnail');
            thumbs.forEach(function(th, i){
                th.classList.toggle('active', i === idx);
            });
            currentIndex = idx;
        }

        function changeMainImage(src, el) {
            var idx = galleryImages.indexOf(src);
            if(idx !== -1) showImage(idx);
            // إيقاف السلايدر مؤقتًا عند التبديل اليدوي
            stopSlider();
        }

        function nextImage() {
            var nextIdx = (currentIndex + 1) % galleryImages.length;
            showImage(nextIdx);
        }

        function startSlider() {
            if(galleryImages.length > 1) {
                sliderInterval = setInterval(nextImage, 3000);
            }
        }

        function stopSlider() {
            clearInterval(sliderInterval);
        }

        // تشغيل السلايدر عند تحميل الصفحة
        window.addEventListener('DOMContentLoaded', function() {
            startSlider();
            // تم حذف كود الماوس: لا توقف السلايدر عند المرور أو الخروج من الصورة
        });
    </script>
</body>
</html>    


<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>قائمة المفضلة - متجرنا</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/favorite.css">

</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container">
        <div class="wishlist-page">
            <div class="wishlist-header">
                <h1 class="wishlist-title">قائمة المفضلة</h1>
                <p class="wishlist-subtitle">المنتجات التي أعجبتك في مكان واحد</p>
            </div>

            <div class="wishlist-grid">
                <!-- سيتم إضافة العناصر هنا ديناميكياً -->
            </div>

            <div class="empty-wishlist" style="display: none;">
                <i class="fas fa-heart-broken"></i>
                <h3>قائمة المفضلة فارغة</h3>
                <p>لم تقم بإضافة أي منتجات إلى قائمة المفضلة بعد</p>
                <a href="index.php" class="continue-shopping">
                    <i class="fas fa-shopping-bag"></i>
                    تصفح المنتجات
                </a>
            </div>

            <div class="wishlist-actions">
                <button class="add-all-to-cart">
                    <i class="fas fa-shopping-cart"></i>
                    إضافة الكل للسلة
                </button>
                <button class="clear-wishlist">
                    <i class="fas fa-trash"></i>
                    مسح القائمة
                </button>
            </div>
        </div>
    </div>

    <div id="notification" class="notification"></div>

    <footer class="footer">
        <?php include 'includes/footer.php';?>
    </footer>


    <script src="js/favorite.js"></script>


</body>
</html>
<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/function.php';

// التحقق من الطلب
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// الحصول على البيانات
$input = json_decode(file_get_contents('php://input'), true);
$categoryId = isset($input['category_id']) ? (int)$input['category_id'] : 0;

try {
    // جلب المنتجات
    if ($categoryId === 0) {
        $products = fetch_products();
        $categoryName = 'منتجات مميزة';
    } else {
        $products = fetch_products_by_category($categoryId);
        $categoryInfo = get_category_info($categoryId);
        $categoryName = $categoryInfo['name'] ?? 'المنتجات';
    }

    // بناء HTML للمنتجات
    $html = '';
    if (count($products) > 0) {
        foreach ($products as $index => $product) {
            $badgeClass = '';
            if (strpos($product['badge'], 'جديد') !== false) {
                $badgeClass = 'new';
            }
            
            $html .= '
                <div class="product-card" data-id="' . htmlspecialchars($product['id']) . '">
                    <a href="details.php?id=' . htmlspecialchars($product['id']) . '">
                        <span class="product-badge ' . $badgeClass . '">' . htmlspecialchars($product['badge']) . '</span>
                        <div class="product-image">
                            <img src="' . htmlspecialchars($product['image']) . '" alt="' . htmlspecialchars($product['title']) . '" loading="lazy">
                        </div>
                    </a>
                    <div class="product-info">
                        <h3 class="product-title">' . htmlspecialchars($product['title']) . '</h3>
                        <div class="product-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <i class="far fa-star"></i>
                            ' . htmlspecialchars($product['rating']) . '
                        </div>
                        <div class="product-price">
                            <span class="old-price">' . htmlspecialchars($product['old_price']) . '</span>
                            <span class="current-price">' . htmlspecialchars($product['current_price']) . '</span>
                            <span class="discount">-' . htmlspecialchars($product['discount_percent']) . '%</span>
                        </div>
                        <div class="product-actions">
                            <button class="wishlist"><i class="far fa-heart"></i></button>
                            <button class="add-to-cart">أضف للسلة</button>
                        </div>
                    </div>
                </div>
            ';
        }
    }

    echo json_encode([
        'success' => true,
        'products' => $products,
        'html' => $html,
        'category_name' => $categoryName,
        'count' => count($products)
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage()
    ]);
}
?>

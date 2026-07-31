<?php
require_once __DIR__ . '/connection.php';

function fetch_products(): array
{
    global $conn;

    // نجلب كل المنتجات مع اسم التصنيف من جدول categories (إن وُجد)
    $sql = "SELECT p.*,
                   COALESCE(c.name, p.badge, '') AS category_name
            FROM products p
            LEFT JOIN categories c ON c.id = p.category_id
            ORDER BY p.id ASC";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * جلب المنتجات حسب اسم التصنيف (يُستخدم من القائمة المنسدلة في index.php)
 * يبحث في عمود category (إن وُجد) أو badge أو title
 */
function fetch_products_by_category_name(string $categoryName, string $sortBy = 'popular'): array
{
    global $conn;

    $sortSQL = match($sortBy) {
        'newest'           => 'created_at DESC',
        'low-high'         => 'current_price ASC',
        'high-low'         => 'current_price DESC',
        'rating'           => 'rating DESC',
        'most-sold'        => 'rating_count DESC',
        'highest-discount' => 'discount_percent DESC',
        default            => 'rating DESC, id DESC'
    };

    // نحاول الفلترة بعمود category أولاً، ثم badge كبديل
    $searchTerm = '%' . $categoryName . '%';

    // نتحقق إذا كان عمود category موجوداً في الجدول
    $cols = $conn->query("SHOW COLUMNS FROM products LIKE 'category'")->fetchAll();
    if (!empty($cols)) {
        $sql = "SELECT * FROM products
                WHERE category LIKE ?
                ORDER BY {$sortSQL}";
    } else {
        // fallback: ابحث في badge أو title
        $sql = "SELECT * FROM products
                WHERE badge LIKE ? OR title LIKE ?
                ORDER BY {$sortSQL}";
    }

    $stmt = $conn->prepare($sql);

    if (!empty($cols)) {
        $stmt->execute([$searchTerm]);
    } else {
        $stmt->execute([$searchTerm, $searchTerm]);
    }

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * جلب كل المنتجات مع إمكانية الترتيب
 */
function fetch_all_products_sorted(string $sortBy = 'popular'): array
{
    global $conn;

    $sortSQL = match($sortBy) {
        'newest'           => 'created_at DESC',
        'low-high'         => 'current_price ASC',
        'high-low'         => 'current_price DESC',
        'rating'           => 'rating DESC',
        'most-sold'        => 'rating_count DESC',
        'highest-discount' => 'discount_percent DESC',
        default            => 'rating DESC, id DESC'
    };

    $sql = "SELECT * FROM products ORDER BY {$sortSQL}";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function fetch_product_by_id(int $id): ?array
{
    global $conn;
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

function fetch_product_images(int $productId): array
{
    global $conn;
    $sql = "SELECT * FROM product_images WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$productId]);
    return $stmt->fetchAll();
}

function fetch_active_sliders(): array
{
    global $conn;
    $sql = "SELECT * FROM sliders WHERE is_active = 1 ORDER BY sort_order ASC, id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

// دالة البحث المتقدمة - مثل Amazon
function search_products(string $query, string $sortBy = 'popular', int $limit = 50): array
{
    global $conn;
    
    // تنظيف البحث
    $query = trim($query);
    if (strlen($query) < 1) {
        return [];
    }
    
    // البحث في جميع الحقول المهمة
    $searchTerm = '%' . $query . '%';
    
    $sortSQL = match($sortBy) {
        'newest' => 'created_at DESC',
        'low-high' => 'current_price ASC',
        'high-low' => 'current_price DESC',
        'rating' => 'rating DESC',
        default => 'rating DESC, id DESC'
    };
    
    // البحث في العنوان والـ Badge والسعر
    $sql = "SELECT * FROM products 
            WHERE title LIKE ? 
               OR badge LIKE ? 
               OR CAST(current_price AS CHAR) LIKE ?
               OR CAST(old_price AS CHAR) LIKE ?
            ORDER BY {$sortSQL} 
            LIMIT " . intval($limit);
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
    return $stmt->fetchAll();
}

// دالة للحصول على اقتراحات البحث الفورية
function get_search_suggestions(string $query, int $limit = 15): array
{
    global $conn;
    
    $query = trim($query);
    if (strlen($query) < 1) {
        return [];
    }
    
    $searchTerm = '%' . $query . '%';
    
    // البحث عن المنتجات الكاملة للعرض الفوري
    $sql = "SELECT id, title, image, current_price, old_price, badge, rating 
            FROM products 
            WHERE title LIKE ? OR badge LIKE ?
            ORDER BY rating DESC, id DESC
            LIMIT " . intval($limit);
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([$searchTerm, $searchTerm]);
    return $stmt->fetchAll();
}

// ==================== الدوال الجديدة الاحترافية ====================

// الحصول على التصنيفات
function fetch_categories(): array
{
    global $conn;
    $sql = "SELECT * FROM categories WHERE is_active = 1 ORDER BY sort_order ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

// الحصول على المنتجات حسب التصنيف
function fetch_products_by_category(int $categoryId, string $sortBy = 'popular'): array
{
    global $conn;
    
    $sortSQL = match($sortBy) {
        'newest' => 'p.created_at DESC',
        'low-high' => 'p.current_price ASC',
        'high-low' => 'p.current_price DESC',
        'rating' => 'p.rating DESC',
        default => 'p.rating DESC, p.id DESC'
    };
    
    $sql = "SELECT p.* FROM products p 
            WHERE p.category_id = ? AND p.in_stock = 1
            ORDER BY {$sortSQL}";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([$categoryId]);
    return $stmt->fetchAll();
}

// الحصول على العروض الخاصة النشطة
function fetch_active_deals(): array
{
    global $conn;
    $sql = "SELECT sd.*, p.* FROM special_deals sd
            JOIN products p ON sd.product_id = p.id
            WHERE sd.is_active = 1 
            AND sd.end_date > NOW()
            ORDER BY sd.priority ASC
            LIMIT 10";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

// الحصول على المنتجات المميزة
function fetch_featured_products(): array
{
    global $conn;
    $sql = "SELECT * FROM products 
            WHERE is_featured = 1 AND in_stock = 1
            ORDER BY rating DESC
            LIMIT 12";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

// البحث والفلترة المتقدم
function filter_products(
    ?int $categoryId = null,
    ?float $minPrice = null,
    ?float $maxPrice = null,
    ?float $minRating = null,
    string $sortBy = 'popular',
    int $limit = 50
): array {
    global $conn;
    
    $conditions = [];
    $params = [];
    
    // الفئة
    if ($categoryId !== null) {
        $conditions[] = "p.category_id = ?";
        $params[] = $categoryId;
    }
    
    // السعر
    if ($minPrice !== null) {
        $conditions[] = "p.current_price >= ?";
        $params[] = $minPrice;
    }
    if ($maxPrice !== null) {
        $conditions[] = "p.current_price <= ?";
        $params[] = $maxPrice;
    }
    
    // التقييم
    if ($minRating !== null) {
        $conditions[] = "p.rating >= ?";
        $params[] = $minRating;
    }
    
    // المخزون
    $conditions[] = "p.in_stock = 1";
    
    // الترتيب
    $sortSQL = match($sortBy) {
        'newest' => 'p.created_at DESC',
        'low-high' => 'p.current_price ASC',
        'high-low' => 'p.current_price DESC',
        'rating' => 'p.rating DESC',
        'most-sold' => 'p.rating_count DESC',
        'highest-discount' => 'p.discount_percent DESC',
        default => 'p.rating DESC, p.id DESC'
    };
    
    $whereSQL = implode(' AND ', $conditions);
    
    $sql = "SELECT p.* FROM products p 
            WHERE {$whereSQL}
            ORDER BY {$sortSQL}
            LIMIT " . intval($limit);
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

// الحصول على معلومات الفئة
function get_category_info(int $categoryId): ?array
{
    global $conn;
    $sql = "SELECT * FROM categories WHERE id = ? AND is_active = 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$categoryId]);
    return $stmt->fetch() ?: null;
}

// الحصول على إحصائيات المتجر
function get_store_statistics(): array
{
    global $conn;
    
    $totalProducts = $conn->query("SELECT COUNT(*) FROM products WHERE in_stock = 1")->fetchColumn();
    $totalCategories = $conn->query("SELECT COUNT(*) FROM categories WHERE is_active = 1")->fetchColumn();
    $activeDeals = $conn->query("SELECT COUNT(*) FROM special_deals WHERE is_active = 1 AND end_date > NOW()")->fetchColumn();
    $avgRating = $conn->query("SELECT AVG(rating) FROM products")->fetchColumn();
    
    return [
        'total_products' => $totalProducts,
        'total_categories' => $totalCategories,
        'active_deals' => $activeDeals,
        'average_rating' => round($avgRating, 1)
    ];
}


function fetch_special_deals(): array
{
    global $conn;

    $sql = "SELECT * FROM products WHERE is_deal = 1" ;

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
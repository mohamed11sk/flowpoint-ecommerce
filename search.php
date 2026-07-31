<?php
header('Content-Type: application/json; charset=utf-8');

try {
    require_once 'includes/connection.php';
    require_once 'includes/function.php';

    $action = $_GET['action'] ?? 'suggestions';
    $query = $_GET['q'] ?? '';
    $sortBy = $_GET['sort'] ?? 'popular';

    if (empty($query) || strlen(trim($query)) < 1) {
        http_response_code(200);
        echo json_encode(['success' => true, 'results' => [], 'count' => 0]);
        exit;
    }

    if ($action === 'suggestions') {
        // الاقتراحات الفورية - عرض المنتجات مباشرة
        $results = get_search_suggestions($query, 8);
        
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'results' => $results,
            'count' => count($results)
        ]);
    } else {
        // البحث الكامل
        $results = search_products($query, $sortBy, 50);
        
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'query' => htmlspecialchars($query),
            'results' => $results,
            'count' => count($results)
        ]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>

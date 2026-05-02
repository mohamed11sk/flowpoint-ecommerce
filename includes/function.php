<?php
require_once __DIR__ . '/connection.php';

function fetch_products(): array
{
    global $conn;
    $sql = "SELECT * FROM products";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
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


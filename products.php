<?php
/**
 * products.php — تم دمج هذه الصفحة مع index.php
 * أي رابط قديم يُعاد توجيهه تلقائياً للصفحة الرئيسية
 */
$category = isset($_GET['category']) ? '?scrollTo=products&cat=' . urlencode($_GET['category']) : '';
header('Location: index.php' . $category, true, 301);
exit;

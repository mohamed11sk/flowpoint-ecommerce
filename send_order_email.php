<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    date_default_timezone_set('Africa/Cairo'); 
    $current_time = date('Y-m-d H:i:s T'); 

    // استقبال بيانات الطلب من FormData
    $orderData = isset($_POST['orderData']) ? json_decode($_POST['orderData'], true) : null;
    
    if (!$orderData) {
        echo json_encode(['success' => false, 'message' => 'بيانات الطلب غير صحيحة']);
        exit;
    }

    // استخراج البيانات
    $customerName = $orderData['firstName'] . ' ' . $orderData['lastName'];
    $orderNumber = $orderData['orderNumber'];
    $orderItems = $orderData['items'];
    $orderTotal = $orderData['total'];
    $shippingAddress = $orderData['shippingAddress'];
    $paymentMethod = $orderData['paymentMethod'];
    $customerPhone = $orderData['phone'];
    $customerEmail = $orderData['email'];

    $success = true;
    $errorMessage = '';

    try {
        // ===== الإيميل الأول: للعميل (تأكيد الطلب) =====
        $customerMail = new PHPMailer(true);
        $customerMail->isSMTP();
        $customerMail->Host       = 'smtp.gmail.com';
        $customerMail->SMTPAuth   = true;
        $customerMail->Username   = 'srorr8872@gmail.com';
        $customerMail->Password   = 'mmtx fdpa zabi jjrt';
        $customerMail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $customerMail->Port       = 465;

        $customerMail->setFrom('srorr8872@gmail.com', 'Our Online Store');
        $customerMail->addAddress($customerEmail, $customerName);
        $customerMail->isHTML(true);
        $customerMail->Subject = 'Order Confirmation - ' . $orderNumber;

        // تحديد رابط الموقع تلقائياً (http/https + الدومين أو الـIP)
        $siteUrl = (isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http')) . '://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . '/';

        // إنشاء قائمة المنتجات للعميل (استخدم صورة المنتج الحقيقية كرابط URL)
        $itemsHtml = '';
        foreach ($orderItems as $item) {
            // إذا كان مسار الصورة يبدأ بـ http أو https اعتبره كامل، وإلا أضف رابط الموقع
            if (isset($item['image']) && !empty($item['image'])) {
                if (preg_match('/^https?:\/\//', $item['image'])) {
                    $imageUrl = $item['image'];
                } else {
                    $imageUrl = $siteUrl . ltrim($item['image'], '/');
                }
            } else {
                // صورة افتراضية إذا لم تتوفر صورة
                $imageUrl = $siteUrl . 'image/default-avatar.png';
            }
            $itemsHtml .= '
            <tr>
                <td style="padding: 15px; border-bottom: 1px solid #e9ecef; vertical-align: top;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="width: 70px; padding-right: 12px; vertical-align: top;">
                                <div style="width: 70px; height: 70px; border-radius: 8px; overflow: hidden; background: #f8f9fa; border: 1px solid #e9ecef; display: flex; align-items: center; justify-content: center;">
                                    <img src="' . htmlspecialchars($imageUrl) . '" alt="Product Image" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                            </td>
                            <td style="vertical-align: top;">
                                <div style="font-weight: 600; color: #ff6b35; margin-bottom: 6px; font-size: 15px; line-height: 1.3;">' . htmlspecialchars($item['title']) . '</div>
                                <div style="color: #6c757d; font-size: 13px; font-weight: 500;">Quantity: ' . $item['quantity'] . '</div>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="padding: 15px; text-align: center; border-bottom: 1px solid #ffe5e5; color: #ff6b35; font-weight: 600; font-size: 15px; vertical-align: top;">
                    ' . $item['price'] . '
                </td>
            </tr>';
        }

        // ===== Build order items table with total row for customer (English) =====
        $itemsTableRows = $itemsHtml . '
            <tr>
                <td colspan="2" style="padding: 18px; text-align: left; background: #fff3e0; color: #ff6b35; font-weight: bold; font-size: 17px; border-top: 2px solid #ffe5e5; border-bottom: 2px solid #ffe5e5; border-radius: 0 0 12px 12px;">
                    Total: $' . number_format($orderTotal, 2) . '
                </td>
            </tr>';

        $customerMessage = '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Order Confirmation</title>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
            <style type="text/css">
                * {
                    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
                    box-sizing: border-box;
                }
                body {
                    margin: 0;
                    padding: 20px;
                    background: #f8f9fa;
                }
                .email-wrapper {
                    max-width: 700px;
                    margin: 0 auto;
                    background: #ffffff;
                    border-radius: 16px;
                    overflow: hidden;
                    box-shadow: 0 8px 24px rgba(0,0,0,0.08);
                }
                .header {
                    background: linear-gradient(135deg, #ff8c42, #ff6b35);
                    padding: 32px;
                    text-align: center;
                }
                .success-icon {
                    margin-bottom: 20px;
                }
                .success-icon i {
                    font-size: 4rem !important;
                    color: green !important;
                }
                .header-title {
                    color: white;
                    font-size: 28px;
                    margin: 0 0 15px 0;
                    font-weight: 700;
                    letter-spacing: -0.5px;
                }
                .header-subtitle {
                    color: rgba(255,255,255,0.95);
                    font-size: 18px;
                    margin-bottom: 25px;
                    font-weight: 500;
                }
                .order-number {
                    background: rgba(255,255,255,0.15);
                    display: inline-block;
                    padding: 8px 20px;
                    border-radius: 20px;
                    font-weight: 600;
                    font-size: 16px;
                    color: white;
                }
                .content {
                    padding: 40px;
                    text-align: center;
                    background: #ffffff;
                }
                .items-section {
                    margin: 0 0 40px 0;
                }
                .items-title {
                    color: #ff6b35;
                    font-size: 20px;
                    font-weight: 600;
                    margin-bottom: 20px;
                    text-align: center;
                }
                .items-table {
                    width: 100%;
                    border-collapse: collapse;
                    background: white;
                    border-radius: 12px;
                    overflow: hidden;
                    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
                    border: 1px solid #ffe5e5;
                    margin: 0;
                }
                .items-table th {
                    background: linear-gradient(135deg, #ff8c42, #ff6b35);
                    color: white;
                    padding: 20px;
                    text-align: left;
                    font-weight: 600;
                    font-size: 15px;
                    border: none;
                }
                .items-table th:last-child {
                    text-align: center;
                }
                .items-table td {
                    font-size: 15px;
                }
                .greeting {
                    text-align: center;
                    margin-bottom: 40px;
                    padding: 30px;
                    background: #fff9f9;
                    border: 1px solid #ffe5e5;
                    border-radius: 12px;
                }
                .greeting h2 {
                    color: #ff6b35;
                    font-size: 28px;
                    margin-bottom: 15px;
                    font-weight: 700;
                    letter-spacing: -0.3px;
                }
                .greeting p {
                    color: #6c757d;
                    font-size: 16px;
                    margin: 0;
                    line-height: 1.7;
                    max-width: 500px;
                    margin: 0 auto;
                }
                .order-details {
                    background: #fff9f9;
                    border: 1px solid #ffe5e5;
                    border-radius: 12px;
                    padding: 20px;
                    margin: 25px 0;
                    text-align: left;
                }
                .detail-row {
                    display: flex;
                    justify-content: space-between;
                    margin: 12px 0;
                    color: #555;
                }
                .detail-row:last-child {
                    border-bottom: none;
                }
                .detail-label {
                    font-weight: 600;
                    color: #555;
                    font-size: 15px;
                }
                .detail-value {
                    color: #ff6b35;
                    font-weight: 600;
                    font-size: 15px;
                }
                .footer {
                    background: #f8f9fa;
                    padding: 30px;
                    text-align: center;
                    border-top: 1px solid #e9ecef;
                }
                .footer p {
                    color: #6c757d;
                    font-size: 14px;
                    margin: 0;
                    line-height: 1.6;
                }
                .footer a {
                    color: #ff6b35;
                    text-decoration: none;
                    font-weight: 600;
                    display: inline-block;
                    margin-top: 18px;
                    padding: 12px 32px;
                    background: linear-gradient(135deg, #ff8c42, #ff6b35);
                    border-radius: 8px;
                    font-size: 16px;
                    transition: background 0.2s;
                }
                .footer a:hover {
                    background: linear-gradient(135deg, #ff6b35, #ff8c42);
                    text-decoration: underline;
                }
                @media (max-width: 600px) {
                    .email-wrapper {
                        margin: 10px;
                        border-radius: 12px;
                    }
                    .header {
                        padding: 20px;
                    }
                    .header-title {
                        font-size: 24px;
                    }
                    .header-subtitle {
                        font-size: 16px;
                    }
                    .content {
                        padding: 20px;
                    }
                    .greeting h2 {
                        font-size: 24px;
                    }
                    .items-table th,
                    .items-table td {
                        padding: 10px;
                        font-size: 14px;
                    }
                    .detail-row {
                        flex-direction: column;
                        gap: 5px;
                    }
                }
            </style>
        </head>
        <body>
            <div class="email-wrapper">
                <!-- Header -->
                <div class="header">
                    <div class="success-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h1 class="header-title">Order Confirmed!</h1>
                    <p class="header-subtitle">Thank you for your order. We have received your request and will process it shortly.</p>
                    <div class="order-number">Order #' . $orderNumber . '</div>
                </div>
                <!-- Items Section (First) -->
                <div class="items-section">
                    <h3 class="items-title">Order Items</h3>
                    <table class="items-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            ' . $itemsTableRows . '
                        </tbody>
                    </table>
                </div>
                <!-- Content -->
                <div class="content">
                    <div class="greeting">
                        <h2>Hello ' . htmlspecialchars($customerName) . '!</h2>
                        <p>Your order has been successfully placed. We are excited to fulfill your request and will keep you updated on the progress.</p>
                    </div>
                    <!-- Order Details -->
                    <div class="order-details">
                        <div class="detail-row">
                            <span class="detail-label">Order Number:</span>
                            <span class="detail-value">' . $orderNumber . '</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Payment Method:</span>
                            <span class="detail-value">' . htmlspecialchars($paymentMethod) . '</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Order Date:</span>
                            <span class="detail-value">' . $current_time . '</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Total Amount:</span>
                            <span class="detail-value">$' . number_format($orderTotal, 2) . '</span>
                        </div>
                    </div>
                </div>
                <!-- Footer -->
                <div class="footer">
                    <p>If you have any questions about your order, please don\'t hesitate to contact us.</p>
                    <p>Thank you for choosing our store!</p>
                    <a href="https://flowpoint.wuaze.com/" target="_blank">Continue Shopping</a>
                </div>
            </div>
        </body>
        </html>';

        $customerMail->Body = $customerMessage;
        $customerMail->send();

        // ===== الإيميل الثاني: لصاحب المتجر (تفاصيل الطلب + صورة التحويل) =====
        $storeMail = new PHPMailer(true);
        $storeMail->isSMTP();
        $storeMail->Host       = 'smtp.gmail.com';
        $storeMail->SMTPAuth   = true;
        $storeMail->Username   = 'srorr8872@gmail.com';
        $storeMail->Password   = 'mmtx fdpa zabi jjrt';
        $storeMail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $storeMail->Port       = 465;

        $storeMail->setFrom('srorr8872@gmail.com', 'Store Orders');
        $storeMail->addAddress('srorr8872@gmail.com', 'Store Owner');
        $storeMail->isHTML(true);
        $storeMail->Subject = 'New Order #' . $orderNumber;

        // إنشاء قائمة المنتجات لصاحب المتجر مع صورة المنتج
        $storeItemsHtml = '';
        foreach ($orderItems as $item) {
            // نفس منطق الصورة المستخدم للعميل
            if (isset($item['image']) && !empty($item['image'])) {
                if (preg_match('/^https?:\/\//', $item['image'])) {
                    $imageUrl = $item['image'];
                } else {
                    $imageUrl = $siteUrl . ltrim($item['image'], '/');
                }
            } else {
                $imageUrl = $siteUrl . 'image/default-avatar.png';
            }
            $storeItemsHtml .= '<li style="display: flex; align-items: center; margin-bottom: 12px;">
                <img src="' . htmlspecialchars($imageUrl) . '" alt="صورة المنتج" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px; margin-left: 12px; border: 1px solid #eee;">
                <div>
                    <div style="font-weight: 600; color: #ff6b35; font-size: 15px;">' . htmlspecialchars($item['title']) . '</div>
                    <div style="color: #6c757d; font-size: 13px;">Quantity: ' . $item['quantity'] . ' - Price: ' . $item['price'] . '</div>
                </div>
            </li>';
        }

        $storeMessage = '
        <!DOCTYPE html>
        <html lang="ar" dir="rtl">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>طلب جديد</title>
            <style>
                body { font-family: Tahoma, Arial, "Cairo", sans-serif; margin: 0; padding: 20px; background: #f4f4f4; direction: rtl; }
                .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
                .header { background: linear-gradient(135deg, #ff6b35, #ff8c42); color: white; padding: 30px; text-align: center; }
                .header h1 { margin: 0; font-size: 24px; }
                .content { padding: 30px; }
                .order-info { background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
                .info-row { display: flex; justify-content: space-between; margin: 10px 0; }
                .label { font-weight: bold; color: #333; }
                .value { color: #ff6b35; font-weight: 600; }
                .items-list { background: #fff9f9; padding: 20px; border-radius: 8px; border: 1px solid #ffe5e5; }
                .items-list ul { margin: 0; padding-right: 20px; }
                .items-list li { margin: 8px 0; color: #555; }
                .footer { background: #f8f9fa; padding: 20px; text-align: center; color: #666; font-size: 14px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>🛒 تم استلام طلب جديد</h1>
                </div>
                <div class="content">
                    <div class="order-info">
                        <div class="info-row">
                            <span class="label">رقم الطلب:</span>
                            <span class="value">' . $orderNumber . '</span>
                        </div>
                        <div class="info-row">
                            <span class="label">اسم العميل:</span>
                            <span class="value">' . htmlspecialchars($customerName) . '</span>
                        </div>
                        <div class="info-row">
                            <span class="label">رقم الهاتف:</span>
                            <span class="value">' . htmlspecialchars($customerPhone) . '</span>
                        </div>
                        <div class="info-row">
                            <span class="label">البريد الإلكتروني:</span>
                            <span class="value">' . htmlspecialchars($customerEmail) . '</span>
                        </div>
                        <div class="info-row">
                            <span class="label">عنوان التوصيل:</span>
                            <span class="value">' . htmlspecialchars($shippingAddress) . '</span>
                        </div>
                        <div class="info-row">
                            <span class="label">طريقة الدفع:</span>
                            <span class="value">' . htmlspecialchars($paymentMethod) . '</span>
                        </div>
                        <div class="info-row">
                            <span class="label">المبلغ الكلي:</span>
                            <span class="value">$' . number_format($orderTotal, 2) . '</span>
                        </div>
                        <div class="info-row">
                            <span class="label">تاريخ الطلب:</span>
                            <span class="value">' . $current_time . '</span>
                        </div>
                    </div>
                    
                    <div class="items-list">
                        <h3 style="color: #ff6b35; margin-top: 0;">المنتجات المطلوبة:</h3>
                        <ul>' . $storeItemsHtml . '</ul>
                    </div>
                    
                    <p style="color: #666; font-style: italic; margin-top: 20px;">
                        <strong>ملاحظة:</strong> صورة التحويل مرفقة بهذا البريد (إن وجدت).
                    </p>
                </div>
                <div class="footer">
                    <p>هذا إشعار آلي من متجرك الإلكتروني.</p>
                </div>
            </div>
        </body>
        </html>';

        $storeMail->Body = $storeMessage;

        // إرفاق صورة التحويل لصاحب المتجر فقط
        if (isset($_FILES['paymentScreenshot']) && $_FILES['paymentScreenshot']['error'] == UPLOAD_ERR_OK) {
            $storeMail->addAttachment($_FILES['paymentScreenshot']['tmp_name'], $_FILES['paymentScreenshot']['name']);
        }
        if (isset($_FILES['instapayScreenshot']) && $_FILES['instapayScreenshot']['error'] == UPLOAD_ERR_OK) {
            $storeMail->addAttachment($_FILES['instapayScreenshot']['tmp_name'], $_FILES['instapayScreenshot']['name']);
        }

        $storeMail->send();

        echo json_encode(['success' => true, 'message' => 'تم إرسال الطلب بنجاح']);

    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'خطأ في الإرسال: ' . $e->getMessage()]);
    }
    exit;
} else {
    echo json_encode(['success' => false, 'message' => 'طريقة طلب غير صحيحة']);
}
?> 
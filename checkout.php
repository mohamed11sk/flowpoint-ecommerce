<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إتمام الشراء - متجرنا</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="checkout.css">
</head>
<body>
    <div class="checkout-container">
        <!-- شريط التقدم -->
        <div class="progress-bar">
            <div class="progress-step active">
                <i class="fas fa-shopping-cart"></i>
                <span>السلة</span>
            </div>
            <div class="progress-step active">
                <i class="fas fa-credit-card"></i>
                <span>الدفع</span>
            </div>
            <div class="progress-step">
                <i class="fas fa-check-circle"></i>
                <span>التأكيد</span>
            </div>
        </div>

        <!-- عنوان الصفحة -->
        <div class="checkout-header">
            <h1>إتمام الشراء</h1>
            <p>أكمل طلبك بسهولة وأمان</p>
        </div>

        <!-- محتوى إفراغ السلة -->
        <div id="empty-cart" class="empty-cart" style="display: none;">
            <i class="fas fa-shopping-cart"></i>
            <h2>السلة فارغة</h2>
            <p>لا توجد منتجات في السلة لإتمام عملية الشراء</p>
            <a href="index.php" class="btn btn-primary">العودة للتسوق</a>
        </div>

        <!-- نموذج إتمام الشراء -->
        <div id="checkout-form" class="checkout-grid">
            <!-- نموذج البيانات -->
            <div class="checkout-form">
                <!-- بيانات العميل -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-user"></i>
                        بيانات العميل
                    </h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">الاسم الأول</label>
                            <input type="text" class="form-input" id="firstName" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">الاسم الأخير</label>
                            <input type="text" class="form-input" id="lastName" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">البريد الإلكتروني</label>
                        <input type="email" class="form-input" id="email" placeholder="example@gmail.com" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">رقم الهاتف</label>
                        <input type="tel" class="form-input" id="phone" required>
                    </div>
                </div>

                <!-- عنوان التوصيل -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-map-marker-alt"></i>
                        عنوان التوصيل
                    </h3>
                    <div class="form-group">
                        <label class="form-label">العنوان الكامل</label>
                        <input type="text" class="form-input" id="address" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">المدينة</label>
                            <input type="text" class="form-input" id="city" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" >الرمز البريدي</label>
                            <input type="text" class="form-input" id="postalCode">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">الدولة</label>
                        <select class="form-select" id="country" required>
                            <option value="">اختر الدولة</option>
                            <option value="EG">مصر</option>
                            <option value="SA">المملكة العربية السعودية</option>
                            <option value="AE">الإمارات العربية المتحدة</option>
                            <option value="KW">الكويت</option>
                            <option value="QA">قطر</option>
                            <option value="BH">البحرين</option>
                            <option value="OM">عمان</option>
                        </select>
                    </div>
                </div>

                <!-- طرق الدفع -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-credit-card"></i>
                        طريقة الدفع
                    </h3>
                    <div class="payment-methods">
                        <div class="payment-method" data-method="instapay">
                            <i class="fas fa-wallet"></i>
                            <div>انستا باي</div>
                        </div>
                        <div class="payment-method" data-method="vodafone">
                            <i class="fas fa-mobile-alt"></i>
                            <div>فودافون كاش</div>
                        </div>
                        <div class="payment-method" data-method="cash">
                            <i class="fas fa-money-bill-wave"></i>
                            <div>الدفع عند الاستلام</div>
                        </div>
                    </div>
                    
                    <!-- تفاصيل انستا باي -->
                    <div id="instapay-details" class="instapay-details" style="display: none;">
                        <div class="instapay-info">
                            <div class="info-box">
                                <i class="fas fa-info-circle"></i>
                                <div>
                                    <h4>تعليمات الدفع عبر انستا باي</h4>
                                    <ol>
                                        <li>اضغط على الرابط أدناه للانتقال إلى صفحة الدفع</li>
                                        <li>أكمل عملية الدفع عبر انستا باي</li>
                                        <li>احتفظ بسكرين شوت من عملية الدفع</li>
                                        <li>قم برفع السكرين شوت أدناه</li>
                                        <li>اضغط على "إتمام الطلب"</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">رابط الدفع عبر انستا باي</label>
                            <div class="payment-link-container">
                                <a href="https://ipn.eg/S/ahmedelsayedahmed1999/instapay/5FGTah" target="_blank" class="payment-link">
                                    <i class="fas fa-wallet"></i>
                                    الدفع عبر انستا باي
                                </a>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">سكرين شوت الدفع</label>
                            <div class="file-upload-container">
                                <input type="file" id="instapayScreenshot" accept="image/*" class="file-input">
                                <div class="file-upload-area" id="instapayUploadArea">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <p>اضغط هنا لرفع سكرين شوت الدفع</p>
                                    <span>أو اسحب الصورة هنا</span>
                                </div>
                                <div class="uploaded-file" id="instapayUploadedFile" style="display: none;">
                                    <img id="instapayPreviewImage" src="" alt="سكرين شوت الدفع">
                                    <button type="button" class="remove-file" id="instapayRemoveFile">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- تفاصيل فودافون كاش -->
                    <div id="vodafone-details" class="vodafone-details" style="display: none;">
                        <div class="vodafone-info">
                            <div class="info-box">
                                <i class="fas fa-info-circle"></i>
                                <div>
                                    <h4>تعليمات الدفع عبر فودافون كاش</h4>
                                    <ol>
                                        <li>قم بتحويل المبلغ المطلوب إلى رقم: <strong>01070676079</strong></li>
                                        <li>احتفظ بسكرين شوت من عملية التحويل</li>
                                        <li>قم برفع السكرين شوت أدناه</li>
                                        <li>اضغط على "إتمام الطلب"</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">رقم الهاتف المستخدم في التحويل</label>
                            <input type="tel" class="form-input" id="vodafonePhone" placeholder="01xxxxxxxxx">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">سكرين شوت الدفع</label>
                            <div class="file-upload-container">
                                <input type="file" id="paymentScreenshot" accept="image/*" class="file-input">
                                <div class="file-upload-area" id="uploadArea">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <p>اضغط هنا لرفع سكرين شوت الدفع</p>
                                    <span>أو اسحب الصورة هنا</span>
                                </div>
                                <div class="uploaded-file" id="uploadedFile" style="display: none;">
                                    <img id="previewImage" src="" alt="سكرين شوت الدفع">
                                    <button type="button" class="remove-file" id="removeFile">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ملخص الطلب -->
            <div class="order-summary">
                <h3 class="summary-title">ملخص الطلب</h3>
                <div class="cart-items" id="cart-items">
                    <!-- سيتم ملؤها ديناميكياً -->
                </div>
                <div class="summary-totals">
                    <div class="total-row">
                        <span>المجموع الفرعي</span>
                        <span id="subtotal">$0</span>
                    </div>
                    <div class="total-row">
                        <span>الشحن</span>
                        <span id="shipping">عند الاستلام</span>
                    </div>
                    <div class="total-row">
                        <span>الضريبة</span>
                        <span id="tax">$0</span>
                    </div>
                    <div class="total-row final">
                        <span>المجموع الكلي</span>
                        <span id="total">$0</span>
                    </div>
                </div>
                <div class="checkout-actions">
                    <a href="index.php" class="btn btn-secondary">العودة للتسوق</a>
                    <button class="btn btn-primary" id="complete-order">إتمام الطلب</button>
                </div>
            </div>
        </div>

        <!-- شاشة التحميل -->
        <div id="loading" class="loading" style="display: none;">
            <div class="spinner"></div>
            <h3>جاري معالجة طلبك...</h3>
            <p>يرجى الانتظار ولا تغلق الصفحة</p>
        </div>

        <!-- رسالة النجاح -->
        <div id="success-message" class="success-message" style="display: none;">
            <i class="fas fa-check-circle"></i>
            <h2>تم إتمام الطلب بنجاح!</h2>
            <p>رقم الطلب: <strong id="order-number"></strong></p>
            <div id="email-status" class="email-status success">
                <i class="fas fa-check-circle"></i> تم إرسال تأكيد الطلب إلى بريدك الإلكتروني
            </div>
            <a href="index.php" class="btn btn-primary">العودة للصفحة الرئيسية</a>
        </div>
    </div>

    <script src="js/common.js"></script>
    <script src="js/checkout.js"></script>
</body>
</html>

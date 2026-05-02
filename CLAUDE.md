# متجر Amazone الإلكترونية - دليل المشروع

## 📁 بنية المشروع

```
Amazone/
├── css/                    # جميع ملفات CSS
│   ├── style.css          # الأسلوب الرئيسي
│   ├── checkout.css       # صفحة الدفع
│   ├── details.css        # صفحة تفاصيل المنتج
│   └── favorite.css       # صفحة المفضلة (المفضلة)
│
├── js/                    # ملفات JavaScript
│   ├── common.js          # الدوال المشتركة (السلة، المفضلة، الإشعارات)
│   ├── main.js            # منطق الصفحة الرئيسية
│   ├── checkout.js        # منطق صفحة الدفع
│   └── favorite.js        # منطق المفضلة
│
├── includes/              # ملفات PHP المشتركة
│   ├── connection.php     # اتصال قاعدة البيانات
│   ├── function.php       # الدوال الأساسية
│   ├── header.php         # رأس الصفحة
│   └── footer.php         # تذييل الصفحة
│
├── image/                 # صور المشروع
│
├── PHPMailer/            # مكتبة PHPMailer للبريد الإلكتروني
│
├── صفحات رئيسية/
│   ├── index.php         # الصفحة الرئيسية
│   ├── details.php       # تفاصيل المنتج
│   ├── checkout.php      # صفحة الدفع
│   ├── favorite.php      # صفحة المفضلة
│   ├── profile.php       # الملف الشخصي
│   ├── login.html        # صفحة تسجيل الدخول
│   └── send_order_email.php  # إرسال بريد تأكيد الطلب
│
└── ملفات إضافية/
    ├── ecommerce_store.sql    # ملف قاعدة البيانات
    ├── BRD_Ecommerce_Project.pdf  # وثيقة المشروع
    └── README.md              # الملف الأساسي

```

## 🎯 المكونات الرئيسية

### 1. قاعدة البيانات (`includes/connection.php`)
- استخدام PDO للاتصال الآمن
- قاعدة البيانات: `ecommerce_store`
- الخادم المحلي: `localhost`

### 2. الدوال الأساسية (`includes/function.php`)
- `fetch_products()` - الحصول على جميع المنتجات
- `fetch_product_by_id()` - الحصول على تفاصيل منتج محدد
- `fetch_product_images()` - صور المنتج
- `fetch_active_sliders()` - الشرائح الإعلانية

### 3. واجهة المستخدم

#### السلة (Cart)
- إضافة/إزالة المنتجات
- تحديث الكمية
- حفظ في `localStorage`
- عرض الإجمالي

#### المفضلة (Wishlist/Favorites)
- إضافة/إزالة المنتجات المفضلة
- حفظ في `localStorage`
- عداد المنتجات

#### الدفع (Checkout)
- معلومات العميل
- بيانات الشحن
- طريقة الدفع
- إرسال بريد تأكيد

## 🔧 تحديثات البنية الأخيرة

### تم تنظيم:
1. ✅ جميع ملفات CSS في مجلد `css/`
2. ✅ تصحيح اسم الملف: `favoreite.css` → `favorite.css`
3. ✅ حذف ملفات غير ضرورية (`email.php`, `js/cart.js`, `js/wishlist.js`)
4. ✅ تحديث جميع المراجع في HTML/PHP إلى `includes/`
5. ✅ حذف الملفات الوسيطة (Legacy wrapper files)

## 📝 معايير الكود

### JavaScript
- **common.js** - جميع الدوال المشتركة
- **main.js** - منطق الصفحة الرئيسية فقط
- جميع الدوال تُصدَّر عبر `window` للاستخدام العام

### PHP
- استخدام `include` للملفات المرئية (header/footer)
- استخدام `require_once` للدوال والاتصالات
- آخر سطر فارغ في جميع الملفات

### CSS
- استخدام متغيرات CSS: `--primary-color`, `--secondary-color`
- تحديث الاستجابة (responsive) بـ `@media`
- تنظيم الأنماط بالوحدات (header, footer, products, cart)

## 🚀 النقاط المهمة

1. **قاعدة البيانات** محفوظة في `ecommerce_store.sql`
2. **البريد الإلكتروني** يُرسل عبر `send_order_email.php` باستخدام Gmail
3. **التخزين المحلي** يُستخدم للسلة والمفضلة
4. **Lazy Loading** محتمل في قوائم المنتجات

## 🔐 المتطلبات الأمنية

- التحقق من المدخلات في `send_order_email.php`
- استخدام `htmlspecialchars()` لمنع XSS
- حفظ بيانات حساسة بشكل آمن

## 📞 الاتصال والدعم

- البريد الإلكتروني: srorr8872@gmail.com
- رقم الهاتف: +201141572180

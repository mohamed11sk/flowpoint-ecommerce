// checkout.js - وظائف صفحة إتمام الشراء

// تهيئة الصفحة
document.addEventListener('DOMContentLoaded', function() {
    loadCartItems();
    setupPaymentMethods();
    setupFormValidation();
    setupInstapayInputs();
    setupFileUpload();
    
    // تهيئة شريط التقدم
    initializeProgressBar();
});

// دالة تهيئة شريط التقدم
function initializeProgressBar() {
    const progressSteps = document.querySelectorAll('.progress-step');
    
    // تعيين الخطوة الأولى كمكتملة (خضراء) - لأن المستخدم وصل للسلة
    progressSteps[0].classList.add('completed');
    progressSteps[0].classList.remove('active');
    
    // تعيين الخطوة الثانية كنشطة (برتقالية) - لأن المستخدم في صفحة الدفع
    progressSteps[1].classList.add('active');
    progressSteps[1].classList.remove('completed');
    
    // إزالة الحالة النشطة من الخطوة الثالثة
    progressSteps[2].classList.remove('active', 'completed');
}

// تحميل عناصر السلة
function loadCartItems() {
    const cartItems = JSON.parse(localStorage.getItem('cart') || '[]');
    const cartContainer = document.getElementById('cart-items');
    const emptyCart = document.getElementById('empty-cart');
    const checkoutForm = document.getElementById('checkout-form');

    if (cartItems.length === 0) {
        emptyCart.style.display = 'block';
        checkoutForm.style.display = 'none';
        return;
    }

    let subtotal = 0;
    cartContainer.innerHTML = '';

    cartItems.forEach(item => {
        const price = parseFloat(item.price.replace(/[^\d.]/g, ''));
        const quantity = parseInt(item.quantity);
        const itemTotal = price * quantity;
        subtotal += itemTotal;

        const itemElement = document.createElement('div');
        itemElement.className = 'cart-item';
        itemElement.innerHTML = `
            <img src="${item.image}" alt="${item.title}" class="item-image">
            <div class="item-details">
                <div class="item-title">${item.title}</div>
                <div class="item-price">${item.price}</div>
                <div class="item-quantity">الكمية: ${quantity}</div>
            </div>
        `;
        cartContainer.appendChild(itemElement);
    });

    updateTotals(subtotal);
}

// تحديث المجاميع
function updateTotals(subtotal) {
    const shipping = 0; // الشحن عند استلام الطلب
    const tax = 0; // لا توجد ضريبة
    const total = subtotal + shipping + tax;

    document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;
    document.getElementById('shipping').textContent = `مجاني`;
    document.getElementById('tax').textContent = `$0`;
    document.getElementById('total').textContent = `$${subtotal.toFixed(2)}`;
}

// إعداد طرق الدفع
function setupPaymentMethods() {
    const paymentMethods = document.querySelectorAll('.payment-method');
    const instapayDetails = document.getElementById('instapay-details');
    const vodafoneDetails = document.getElementById('vodafone-details');

    paymentMethods.forEach(method => {
        method.addEventListener('click', function() {
            // إزالة التحديد من جميع الطرق
            paymentMethods.forEach(m => m.classList.remove('selected'));
            
            // تحديد الطريقة المختارة
            this.classList.add('selected');

            // إخفاء جميع التفاصيل أولاً
            instapayDetails.style.display = 'none';
            vodafoneDetails.style.display = 'none';
            removeRequiredFromInstapayFields();
            removeRequiredFromVodafoneFields();

            // إظهار التفاصيل المناسبة
            if (this.dataset.method === 'instapay') {
                instapayDetails.style.display = 'block';
                addRequiredToInstapayFields();
            } else if (this.dataset.method === 'vodafone') {
                vodafoneDetails.style.display = 'block';
                addRequiredToVodafoneFields();
            }

            // إزالة تأثير الخطأ عند اختيار طريقة دفع
            paymentMethods.forEach(method => {
                method.classList.remove('error');
            });
        });
    });
}

// إضافة الحقول المطلوبة لانستا باي
function addRequiredToInstapayFields() {
    const instapayFields = ['instapayScreenshot'];
    instapayFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.setAttribute('required', 'required');
        }
    });
}

// إزالة الحقول المطلوبة من انستا باي
function removeRequiredFromInstapayFields() {
    const instapayFields = ['instapayScreenshot'];
    instapayFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.removeAttribute('required');
        }
    });
}

// إضافة الحقول المطلوبة لفودافون كاش
function addRequiredToVodafoneFields() {
    const vodafoneFields = ['vodafonePhone', 'paymentScreenshot'];
    vodafoneFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.setAttribute('required', 'required');
        }
    });
}

// إزالة الحقول المطلوبة من فودافون كاش
function removeRequiredFromVodafoneFields() {
    const vodafoneFields = ['vodafonePhone', 'paymentScreenshot'];
    vodafoneFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.removeAttribute('required');
        }
    });
}

// إعداد حقول انستا باي
function setupInstapayInputs() {
    // لا توجد حقول إدخال خاصة بانستا باي - فقط رفع الملف
    // سيتم التعامل مع رفع الملف في setupFileUpload
}

// التحقق من صحة النموذج
function setupFormValidation() {
    const form = document.querySelector('.checkout-form');
    const inputs = form.querySelectorAll('input, select');

    inputs.forEach(input => {
        // التحقق عند فقدان التركيز
        input.addEventListener('blur', function() {
            validateField(this);
        });
        
        // التحقق عند الكتابة
        input.addEventListener('input', function() {
            clearFieldError(this);
        });

        // التحقق عند التغيير
        input.addEventListener('change', function() {
            validateField(this);
        });
    });
}

// إزالة رسالة الخطأ من الحقل
function clearFieldError(field) {
    if (field.classList.contains('error')) {
        field.classList.remove('error');
        removeErrorMessage(field);
    }
}

// إزالة رسالة الخطأ
function removeErrorMessage(field) {
    const errorMsg = field.parentNode.querySelector('.error-message');
    if (errorMsg) {
        errorMsg.remove();
    }
}

// إضافة رسالة خطأ
function addErrorMessage(field, message) {
    removeErrorMessage(field);
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.textContent = message;
    errorDiv.style.color = '#dc3545';
    errorDiv.style.fontSize = '0.85rem';
    errorDiv.style.marginTop = '5px';
    errorDiv.style.display = 'block';
    field.parentNode.appendChild(errorDiv);
}

function validateField(field) {
    const value = field.value.trim();
    let isValid = true;
    let errorMessage = '';

    // إزالة الأنماط السابقة
    field.style.borderColor = '#e1e5e9';
    field.classList.remove('error');
    removeErrorMessage(field);

    // التحقق من الحقول المطلوبة
    if (field.hasAttribute('required') && !value) {
        field.style.borderColor = '#dc3545';
        field.classList.add('error');
        errorMessage = 'هذا الحقل مطلوب';
        isValid = false;
    }

    // التحقق من الاسم الأول
    if (field.id === 'firstName' && value) {
        if (value.length < 2) {
            field.style.borderColor = '#dc3545';
            field.classList.add('error');
            errorMessage = 'الاسم الأول يجب أن يكون حرفين على الأقل';
            isValid = false;
        }
    }

    // التحقق من الاسم الأخير
    if (field.id === 'lastName' && value) {
        if (value.length < 2) {
            field.style.borderColor = '#dc3545';
            field.classList.add('error');
            errorMessage = 'الاسم الأخير يجب أن يكون حرفين على الأقل';
            isValid = false;
        }
    }

    // التحقق من البريد الإلكتروني
    if (field.type === 'email' && value) {
        const emailRegex = /^[a-zA-Z0-9._%+-]+@gmail\.com$/;
        if (!emailRegex.test(value)) {
            field.style.borderColor = '#dc3545';
            field.classList.add('error');
            errorMessage = 'يرجى إدخال بريد Gmail صحيح';
            isValid = false;
        }
    }

    // التحقق من رقم الهاتف
    if (field.type === 'tel' && value) {
        const phoneRegex = /^01[0-9]{9}$/;
        if (!phoneRegex.test(value)) {
            field.style.borderColor = '#dc3545';
            field.classList.add('error');
            errorMessage = 'يرجى إدخال رقم هاتف مصري صحيح (11 رقم)';
            isValid = false;
        }
    }

    // التحقق من العنوان
    if (field.id === 'address' && value) {
        if (value.length < 10) {
            field.style.borderColor = '#dc3545';
            field.classList.add('error');
            errorMessage = 'العنوان يجب أن يكون 10 أحرف على الأقل';
            isValid = false;
        }
    }

    // التحقق من المدينة
    if (field.id === 'city' && value) {
        if (value.length < 2) {
            field.style.borderColor = '#dc3545';
            field.classList.add('error');
            errorMessage = 'اسم المدينة يجب أن يكون حرفين على الأقل';
            isValid = false;
        }
    }

    // التحقق من الرمز البريدي (اختياري)
    if (field.id === 'postalCode' && value) {
        const postalRegex = /^\d{5}$/;
        if (!postalRegex.test(value)) {
            field.style.borderColor = '#dc3545';
            field.classList.add('error');
            errorMessage = 'الرمز البريدي يجب أن يكون 5 أرقام';
            isValid = false;
        }
    }

    // التحقق من الدولة
    if (field.id === 'country' && value) {
        if (value === '') {
            field.style.borderColor = '#dc3545';
            field.classList.add('error');
            errorMessage = 'يرجى اختيار الدولة';
            isValid = false;
        }
    }

    // التحقق من رقم فودافون كاش
    if (field.id === 'vodafonePhone' && value) {
        const vodafoneRegex = /^01[0-9]{9}$/;
        if (!vodafoneRegex.test(value)) {
            field.style.borderColor = '#dc3545';
            field.classList.add('error');
            errorMessage = 'يرجى إدخال رقم فودافون كاش صحيح';
            isValid = false;
        }
    }

    // التحقق من سكرين شوت انستا باي
    if (field.id === 'instapayScreenshot' && value) {
        // التحقق من أن الملف تم رفعه
        if (!field.files || field.files.length === 0) {
            field.style.borderColor = '#dc3545';
            field.classList.add('error');
            errorMessage = 'يرجى رفع سكرين شوت الدفع';
            isValid = false;
        }
    }

    // إضافة رسالة الخطأ إذا كان هناك خطأ
    if (!isValid && errorMessage) {
        addErrorMessage(field, errorMessage);
    }

    return isValid;
}

// إتمام الطلب
document.getElementById('complete-order').addEventListener('click', function() {
    if (validateForm()) {
        processOrder();
    } else {
        showNotification('يرجى ملء جميع الحقول المطلوبة بشكل صحيح');
    }
});

function validateForm() {
    const form = document.querySelector('.checkout-form');
    const inputs = form.querySelectorAll('input, select');
    let isValid = true;
    let errorCount = 0;
    let firstErrorField = null;

    // التحقق من جميع الحقول المطلوبة
    inputs.forEach(input => {
        if (input.hasAttribute('required') || input.value.trim() !== '') {
            if (!validateField(input)) {
                isValid = false;
                errorCount++;
                // حفظ أول حقل خطأ
                if (!firstErrorField) {
                    firstErrorField = input;
                }
            }
        }
    });

    // التحقق من اختيار طريقة دفع
    const selectedPayment = document.querySelector('.payment-method.selected');
    if (!selectedPayment) {
        showNotification('اختر طريقة للدفع');
        // إضافة تأثير بصري على طرق الدفع
        const paymentMethods = document.querySelectorAll('.payment-method');
        const paymentMethodsContainer = document.querySelector('.payment-methods');
        paymentMethods.forEach(method => {
            method.classList.add('error');
        });
        if (paymentMethodsContainer) {
            paymentMethodsContainer.classList.add('error');
        }
        isValid = false;
        errorCount++;
        if (!firstErrorField) {
            firstErrorField = paymentMethodsContainer;
        }
    } else {
        // إزالة التأثير البصري إذا تم اختيار طريقة دفع
        const paymentMethods = document.querySelectorAll('.payment-method');
        const paymentMethodsContainer = document.querySelector('.payment-methods');
        paymentMethods.forEach(method => {
            method.classList.remove('error');
        });
        if (paymentMethodsContainer) {
            paymentMethodsContainer.classList.remove('error');
        }
    }

    // التحقق من الحقول الإضافية لانستا باي إذا كانت مختارة
    if (selectedPayment && selectedPayment.dataset.method === 'instapay') {
        const instapayFields = ['instapayScreenshot'];
        instapayFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field && !validateField(field)) {
                isValid = false;
                errorCount++;
                if (!firstErrorField) {
                    firstErrorField = field;
                }
            }
        });

        // التحقق من رفع سكرين شوت الدفع
        const fileInput = document.getElementById('instapayScreenshot');
        if (fileInput && !fileInput.files[0]) {
            showNotification('يرجى رفع سكرين شوت الدفع');
            isValid = false;
            errorCount++;
            if (!firstErrorField) {
                firstErrorField = fileInput;
            }
        }
    }

    // التحقق من الحقول الإضافية لفودافون كاش إذا كانت مختارة
    if (selectedPayment && selectedPayment.dataset.method === 'vodafone') {
        const vodafoneFields = ['vodafonePhone'];
        vodafoneFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field && !validateField(field)) {
                isValid = false;
                errorCount++;
                if (!firstErrorField) {
                    firstErrorField = field;
                }
            }
        });

        // التحقق من رفع سكرين شوت الدفع
        const fileInput = document.getElementById('paymentScreenshot');
        if (fileInput && !fileInput.files[0]) {
            showNotification('يرجى رفع سكرين شوت الدفع');
            isValid = false;
            errorCount++;
            if (!firstErrorField) {
                firstErrorField = fileInput;
            }
        }
    }

    // إذا كان هناك أخطاء، توجيه المستخدم للحقل الأول
    if (!isValid && firstErrorField) {
        // تمرير سلس للحقل الأول
        firstErrorField.scrollIntoView({ 
            behavior: 'smooth', 
            block: 'center' 
        });
        
        // إضافة تأثير بصري للحقل
        firstErrorField.classList.add('highlight-error');
        setTimeout(() => {
            firstErrorField.classList.remove('highlight-error');
        }, 2000);
        
        // تركيز على الحقل
        setTimeout(() => {
            firstErrorField.focus();
        }, 500);
    }

    // رسالة إجمالية للأخطاء
    if (!isValid && errorCount > 0) {
        const errorText = errorCount === 1 ? 'خطأ واحد' : `${errorCount} أخطاء`;
        showNotification(`يوجد ${errorText} في النموذج. تم التوجيه للحقل الأول.`);
    }

    return isValid;
}

function processOrder() {
    const checkoutForm = document.getElementById('checkout-form');
    const loading = document.getElementById('loading');
    const completeButton = document.getElementById('complete-order');

    // حماية إضافية: إذا كان الزر معطل بالفعل لا تنفذ الدالة
    if (completeButton.disabled) return;

    // تعطيل الزر لمنع النقر المتكرر
    completeButton.disabled = true;
    completeButton.textContent = 'جاري المعالجة...';

    // تحديث شريط التقدم للخطوة الثانية
    updateProgressBarToPayment();

    // إظهار شاشة التحميل
    checkoutForm.style.display = 'none';
    loading.style.display = 'block';

    // جمع بيانات الطلب
    const orderData = collectOrderData();

    // تجهيز FormData
    const formData = new FormData();
    formData.append('orderData', JSON.stringify(orderData));

    // تحديد طريقة الدفع
    const selectedPayment = document.querySelector('.payment-method.selected');
    if (selectedPayment) {
        if (selectedPayment.dataset.method === 'instapay') {
            const instapayScreenshotInput = document.getElementById('instapayScreenshot');
            if (instapayScreenshotInput && instapayScreenshotInput.files[0]) {
                formData.append('instapayScreenshot', instapayScreenshotInput.files[0]);
            }
        } else if (selectedPayment.dataset.method === 'vodafone') {
            const vodafoneScreenshotInput = document.getElementById('paymentScreenshot');
            if (vodafoneScreenshotInput && vodafoneScreenshotInput.files[0]) {
                formData.append('paymentScreenshot', vodafoneScreenshotInput.files[0]);
            }
        }
    }

    // إرسال بيانات الطلب إلى الخادم
    fetch('send_order_email.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        loading.style.display = 'none';
        showSuccessMessage(data.success, data.message);
        // مسح السلة
        localStorage.removeItem('cart');
        updateCartCount();
        // إعادة تفعيل الزر
        completeButton.disabled = false;
        completeButton.textContent = 'إتمام الطلب';
    })
    .catch(error => {
        console.error('Error:', error);
        loading.style.display = 'none';
        showSuccessMessage(false, 'حدث خطأ في معالجة الطلب');
        // إعادة تفعيل الزر
        completeButton.disabled = false;
        completeButton.textContent = 'إتمام الطلب';
    });
}

// جمع بيانات الطلب
function collectOrderData() {
    const cartItems = JSON.parse(localStorage.getItem('cart') || '[]');
    const orderNumber = 'ORD-' + Date.now();
    
    // حساب المجموع
    let total = 0;
    cartItems.forEach(item => {
        const price = parseFloat(item.price.replace(/[^\d.]/g, ''));
        const quantity = parseInt(item.quantity);
        total += price * quantity;
    });

    // تحديد طريقة الدفع
    const selectedPayment = document.querySelector('.payment-method.selected');
    let paymentMethod = 'غير محدد';
    if (selectedPayment) {
        const method = selectedPayment.dataset.method;
        if (method === 'instapay') paymentMethod = 'انستا باي';
        else if (method === 'vodafone') paymentMethod = 'فودافون كاش';
        else if (method === 'cash') paymentMethod = 'الدفع عند الاستلام';
    }

    // تجميع عنوان التوصيل
    const address = document.getElementById('address').value;
    const city = document.getElementById('city').value;
    const postalCode = document.getElementById('postalCode').value;
    const country = document.getElementById('country').value;
    const shippingAddress = `${address}, ${city}${postalCode ? ', ' + postalCode : ''}, ${country}`;

    return {
        orderNumber: orderNumber,
        firstName: document.getElementById('firstName').value,
        lastName: document.getElementById('lastName').value,
        email: document.getElementById('email').value,
        phone: document.getElementById('phone').value,
        items: cartItems,
        total: total,
        shippingAddress: shippingAddress,
        paymentMethod: paymentMethod
    };
}

function showSuccessMessage(emailSuccess = true, emailMessage = '') {
    // تحديث شريط التقدم
    updateProgressBar();
    
    const successMessage = document.getElementById('success-message');
    const orderNumber = 'ORD-' + Date.now();
    
    document.getElementById('order-number').textContent = orderNumber;
    
    // تحديث رسالة البريد الإلكتروني
    const emailStatusElement = document.getElementById('email-status');
    if (emailStatusElement) {
        if (emailSuccess) {
            emailStatusElement.innerHTML = '<i class="fas fa-check-circle"></i> تم إرسال تأكيد الطلب إلى بريدك الإلكتروني';
            emailStatusElement.className = 'email-status success';
        } else {
            emailStatusElement.innerHTML = '<i class="fas fa-exclamation-triangle"></i> ' + emailMessage;
            emailStatusElement.className = 'email-status error';
        }
    }
    
    successMessage.style.display = 'block';
    
    // إخفاء النموذج وشاشة التحميل
    document.getElementById('checkout-form').style.display = 'none';
    document.getElementById('loading').style.display = 'none';
    
    // مسح السلة
    localStorage.removeItem('cart');
    
    // حفظ بيانات العميل
    saveCustomerData();
}

// دالة تحديث شريط التقدم للخطوة الثانية
function updateProgressBarToPayment() {
    const progressSteps = document.querySelectorAll('.progress-step');
    
    // تحديث الخطوة الأولى لتكون مكتملة (خضراء)
    progressSteps[0].classList.add('completed');
    progressSteps[0].classList.remove('active');
    
    // تحديث الخطوة الثانية لتكون مكتملة (خضراء) - لأن المستخدم أكمل الدفع
    progressSteps[1].classList.add('completed');
    progressSteps[1].classList.remove('active');
    
    // تحديث الخطوة الثالثة لتكون نشطة (برتقالية) - لأن المستخدم في مرحلة التأكيد
    progressSteps[2].classList.add('active');
    progressSteps[2].classList.remove('completed');
}

// دالة تحديث شريط التقدم للخطوة الأخيرة
function updateProgressBar() {
    const progressSteps = document.querySelectorAll('.progress-step');
    
    // تحديث جميع الخطوات لتكون مكتملة (خضراء)
    progressSteps.forEach((step, index) => {
        step.classList.add('completed');
        step.classList.remove('active');
    });
    
    // إضافة تأثير بصري للخطوة الأخيرة بعد فترة قصيرة
    setTimeout(() => {
        const lastStep = progressSteps[2];
        if (lastStep) {
            lastStep.classList.add('active');
            lastStep.classList.remove('completed');
        }
    }, 200);
    
    // إعادة الخطوة الأخيرة للون الأخضر بعد التأثير
    setTimeout(() => {
        const lastStep = progressSteps[2];
        if (lastStep) {
            lastStep.classList.add('completed');
            lastStep.classList.remove('active');
        }
    }, 800);
}

// حفظ بيانات العميل في localStorage
function saveCustomerData() {
    const customerData = {
        firstName: document.getElementById('firstName').value,
        lastName: document.getElementById('lastName').value,
        email: document.getElementById('email').value,
        phone: document.getElementById('phone').value,
        address: document.getElementById('address').value,
        city: document.getElementById('city').value,
        postalCode: document.getElementById('postalCode').value || '',
        country: document.getElementById('country').value
    };
    
    localStorage.setItem('customerData', JSON.stringify(customerData));
}

// تحميل بيانات العميل من localStorage
function loadCustomerData() {
    const savedData = localStorage.getItem('customerData');
    if (savedData) {
        const customerData = JSON.parse(savedData);
        Object.keys(customerData).forEach(key => {
            const field = document.getElementById(key);
            if (field) {
                field.value = customerData[key] || '';
            }
        });
    }
}

// حفظ البيانات عند تغيير الحقول
document.addEventListener('DOMContentLoaded', function() {
    const formFields = ['firstName', 'lastName', 'email', 'phone', 'address', 'city', 'postalCode', 'country'];
    
    formFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.addEventListener('change', saveCustomerData);
        }
    });
    
    // تحميل البيانات المحفوظة
    loadCustomerData();
});

// إعداد رفع الملفات
function setupFileUpload() {
    // إعداد رفع الملفات لفودافون كاش
    const vodafoneFileInput = document.getElementById('paymentScreenshot');
    const vodafoneUploadArea = document.getElementById('uploadArea');
    const vodafoneUploadedFile = document.getElementById('uploadedFile');
    const vodafonePreviewImage = document.getElementById('previewImage');
    const vodafoneRemoveFile = document.getElementById('removeFile');

    if (vodafoneFileInput && vodafoneUploadArea) {
        setupFileUploadForMethod(vodafoneFileInput, vodafoneUploadArea, vodafoneUploadedFile, vodafonePreviewImage, vodafoneRemoveFile);
    }

    // إعداد رفع الملفات لانستا باي
    const instapayFileInput = document.getElementById('instapayScreenshot');
    const instapayUploadArea = document.getElementById('instapayUploadArea');
    const instapayUploadedFile = document.getElementById('instapayUploadedFile');
    const instapayPreviewImage = document.getElementById('instapayPreviewImage');
    const instapayRemoveFile = document.getElementById('instapayRemoveFile');

    if (instapayFileInput && instapayUploadArea) {
        setupFileUploadForMethod(instapayFileInput, instapayUploadArea, instapayUploadedFile, instapayPreviewImage, instapayRemoveFile);
    }
}

function setupFileUploadForMethod(fileInput, uploadArea, uploadedFile, previewImage, removeFile) {
    if (!fileInput || !uploadArea) return;

    // النقر على منطقة الرفع
    uploadArea.addEventListener('click', () => {
        fileInput.click();
    });

    // تغيير الملف
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            handleFileUpload(file, uploadArea, uploadedFile, previewImage);
        }
    });

    // سحب وإفلات الملفات
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            handleFileUpload(files[0], uploadArea, uploadedFile, previewImage);
        }
    });

    // إزالة الملف
    if (removeFile) {
        removeFile.addEventListener('click', function() {
            fileInput.value = '';
            uploadArea.style.display = 'block';
            uploadedFile.style.display = 'none';
            previewImage.src = '';
        });
    }

    function handleFileUpload(file, uploadArea, uploadedFile, previewImage) {
        // التحقق من نوع الملف
        if (!file.type.startsWith('image/')) {
            showNotification('يرجى رفع ملف صورة فقط');
            return;
        }

        // التحقق من حجم الملف (5MB كحد أقصى)
        if (file.size > 5 * 1024 * 1024) {
            showNotification('حجم الملف كبير جداً. الحد الأقصى 5MB');
            return;
        }

        // عرض معاينة الصورة
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            uploadArea.style.display = 'none';
            uploadedFile.style.display = 'block';
        };
        reader.readAsDataURL(file);

        showNotification('تم رفع الصورة بنجاح');
    }
}

// تصدير الدوال للاستخدام العام
window.loadCartItems = loadCartItems;
window.updateTotals = updateTotals;
window.setupPaymentMethods = setupPaymentMethods;
window.validateForm = validateForm;
window.processOrder = processOrder;
window.setupFileUpload = setupFileUpload; 
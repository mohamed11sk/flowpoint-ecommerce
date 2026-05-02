// common.js - جميع الوظائف المشتركة للسلة والمفضلة والإشعارات

// ================== السلة ==================
function add_product_to_cart(productData) {
    const cartContent = document.querySelector('.cart_content');
    if (!cartContent) return;
    const existingItem = cartContent.querySelector(`[data-product-id="${productData.id}"]`);
    if (existingItem) {
        const quantityInput = existingItem.querySelector('.cart_quantity');
        quantityInput.value = parseInt(quantityInput.value) + 1;
        quantityInput.dispatchEvent(new Event('change'));
        showNotification('تم تحديث كمية المنتج في السلة');
        saveCartToLocalStorage();
        updateCartCount();
        return;
    }
    const cartBox = document.createElement("div");
    cartBox.classList.add("cart_box");
    cartBox.dataset.productId = productData.id;
    cartBox.innerHTML = `
        <img class="cart_img" src="${productData.image}" alt="${productData.title}" />
        <div class="detail_box">
            <div class="cart_product_title">${productData.title}</div>
            <div class="price_box">
                <div class="cart_price">${productData.price}</div>
                ${productData.oldPrice ? `<div class="cart_old_price">${productData.oldPrice}</div>` : ''}
            </div>
            <div class="quantity_box">
                <button class="qty_btn minus">-</button>
                <input type="number" value="1" min="1" class="cart_quantity" />
                <button class="qty_btn plus">+</button>
            </div>
        </div>
        <button class="cart_remove" title="إزالة من السلة">
            <i class="fas fa-trash"></i>
        </button>
    `;
    cartContent.appendChild(cartBox);
    setupCartItemEvents(cartBox);
    cartBox.scrollIntoView({ behavior: 'smooth' });
    saveCartToLocalStorage();
    updateCartCount();
    showNotification('تم إضافة المنتج إلى السلة');
}

function setupCartItemEvents(cartBox) {
    const quantityInput = cartBox.querySelector('.cart_quantity');
    const minusBtn = cartBox.querySelector('.qty_btn.minus');
    const plusBtn = cartBox.querySelector('.qty_btn.plus');
    const removeBtn = cartBox.querySelector('.cart_remove');
    minusBtn.addEventListener('click', () => {
        const currentValue = parseInt(quantityInput.value);
        if (currentValue > 1) {
            quantityInput.value = currentValue - 1;
            quantityInput.dispatchEvent(new Event('change'));
        }
    });
    plusBtn.addEventListener('click', () => {
        quantityInput.value = parseInt(quantityInput.value) + 1;
        quantityInput.dispatchEvent(new Event('change'));
    });
    quantityInput.addEventListener('change', () => {
        if (parseInt(quantityInput.value) < 1) {
            quantityInput.value = 1;
        }
        update_total();
        saveCartToLocalStorage();
    });
    removeBtn.addEventListener('click', (e) => {
        e.preventDefault();
        cartBox.classList.add('removing');
        setTimeout(() => {
            cartBox.remove();
            update_total();
            updateCartCount();
            showNotification('تم إزالة المنتج من السلة');
            saveCartToLocalStorage();
        }, 300);
    });
}

function update_total() {
    var cart_content = document.getElementsByClassName("cart_content")[0];
    if (!cart_content) return;
    var cartBoxes = cart_content.getElementsByClassName("cart_box");
    var total = 0;
    for (var i = 0; i < cartBoxes.length; i++) {
        var cart_box = cartBoxes[i];
        var priceElement = cart_box.getElementsByClassName("cart_price")[0];
        var quantityElement = cart_box.getElementsByClassName("cart_quantity")[0];
        var price = parseFloat(priceElement.innerText.replace("$", "").replace("ر.س", "").trim());
        var quantity = quantityElement.value;
        total = total + price * quantity;
        total = Math.round(total * 100) / 100;
    }
    var totalPriceElem = document.getElementsByClassName("total_price")[0];
    if (totalPriceElem)
        totalPriceElem.innerText = "المجموع: $" + total;
}

function updateCartCount() {
    // الحصول على العناصر من التخزين المحلي
    const savedCart = JSON.parse(localStorage.getItem('cart') || '[]');
    const count = savedCart.length;
    // تحديث العداد في جميع العناصر التي تعرض عدد عناصر السلة
    const cartCountElements = document.querySelectorAll('.cart-count');
    cartCountElements.forEach(element => {
      element.textContent = count;
    });
  }

function saveCartToLocalStorage() {
    const cartContent = document.querySelector('.cart_content');
    if (!cartContent) return;
    const cartItems = Array.from(cartContent.querySelectorAll('.cart_box')).map(box => {
        return {
            id: box.dataset.productId,
            title: box.querySelector('.cart_product_title').innerText,
            price: box.querySelector('.cart_price').innerText,
            image: box.querySelector('.cart_img').src,
            quantity: box.querySelector('.cart_quantity').value
        };
    });
    localStorage.setItem('cart', JSON.stringify(cartItems));
    updateCartCount();
}

function loadCartFromLocalStorage() {
    const cartContent = document.querySelector('.cart_content');
    if (!cartContent) return;
    cartContent.innerHTML = '';
    const savedCart = localStorage.getItem('cart');
    if (savedCart) {
        const cartItems = JSON.parse(savedCart);
        cartItems.forEach(item => {
            add_product_to_cart({
                id: item.id,
                title: item.title,
                price: item.price,
                oldPrice: item.oldPrice,
                image: item.image
            });
            const quantityInput = document.querySelector(`[data-product-id="${item.id}"] .cart_quantity`);
            if (quantityInput) {
                quantityInput.value = item.quantity;
                quantityInput.dispatchEvent(new Event('change'));
            }
        });
    }
}

// ================== المفضلة ==================
function addToWishlist(productData) {
    const savedWishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
    const exists = savedWishlist.some(item => item.id === productData.id);
    if (!exists) {
        savedWishlist.push(productData);
        localStorage.setItem('wishlist', JSON.stringify(savedWishlist));
        showNotification('تم إضافة المنتج إلى المفضلة');
    }
    updateWishlistCounter();
    return !exists;
}

function removeFromWishlist(productId) {
    const savedWishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
    const newWishlist = savedWishlist.filter(item => item.id !== productId);
    localStorage.setItem('wishlist', JSON.stringify(newWishlist));
    showNotification('تم إزالة المنتج من المفضلة');
    updateWishlistCounter();
}

function updateWishlistCounter() {
    const savedWishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
    const counters = document.querySelectorAll('.wishlist-count');
    counters.forEach(counter => {
        counter.textContent = savedWishlist.length;
    });
}

function getWishlistItems() {
    return JSON.parse(localStorage.getItem('wishlist') || '[]');
}

function clearWishlist() {
    localStorage.setItem('wishlist', '[]');
    updateWishlistCounter();
    document.querySelectorAll('.wishlist i').forEach(icon => {
        icon.classList.remove('fas');
        icon.classList.add('far');
    });
}

function isInWishlist(productId) {
    const savedWishlist = getWishlistItems();
    return savedWishlist.some(item => item.id === productId);
}

function updateWishlistIcons() {
    try {
        const savedWishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
        updateWishlistCounter();
        document.querySelectorAll('.product-card').forEach(card => {
            const productId = card.dataset.id;
            const heartIcon = card.querySelector('.wishlist i');
            if (heartIcon) {
                if (savedWishlist.some(item => item.id === productId)) {
                    heartIcon.classList.remove('far');
                    heartIcon.classList.add('fas');
                } else {
                    heartIcon.classList.remove('fas');
                    heartIcon.classList.add('far');
                }
            }
        });
    } catch (error) {
        console.error('خطأ في تحديث أيقونات المفضلة:', error);
    }
}

// ================== إشعارات عامة ==================
function showNotification(message) {
    let notification = document.getElementById('cart-notification') || document.getElementById('notification');
    if (!notification) {
        notification = document.createElement('div');
        notification.id = 'cart-notification';
        notification.style.position = 'fixed';
        notification.style.top = '20px';
        notification.style.left = '50%';
        notification.style.transform = 'translateX(-50%)';
        notification.style.background = '#222';
        notification.style.color = '#fff';
        notification.style.padding = '10px 20px';
        notification.style.borderRadius = '8px';
        notification.style.zIndex = '9999';
        notification.style.display = 'none';
        document.body.appendChild(notification);
    }
    notification.textContent = message;
    notification.style.display = 'block';
    notification.classList.add('show');
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            notification.style.display = 'none';
            notification.textContent = '';
        }, 300);
    }, 2000);
}

// ================== تحديث العدادات ==================
function updateCounters() {
    updateCartCount();
    updateWishlistCounter();
}

document.addEventListener('DOMContentLoaded', updateCounters);
window.addEventListener('pageshow', function() {
  loadCartFromLocalStorage();
});
window.addEventListener('load', updateCounters);
window.addEventListener('popstate', updateCounters);

// تصدير الدوال للاستخدام في كل الصفحات
window.add_product_to_cart = add_product_to_cart;
window.loadCartFromLocalStorage = loadCartFromLocalStorage;
window.updateCartCount = updateCartCount;
window.update_total = update_total;
window.addToWishlist = addToWishlist;
window.removeFromWishlist = removeFromWishlist;
window.updateWishlistCounter = updateWishlistCounter;
window.getWishlistItems = getWishlistItems;
window.clearWishlist = clearWishlist;
window.isInWishlist = isInWishlist;
window.showNotification = showNotification;
window.updateCounters = updateCounters;
window.updateWishlistIcons = updateWishlistIcons; 
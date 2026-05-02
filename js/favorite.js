
let persisted = false;

// وظائف المفضلة العامة أصبحت في common.js
// هنا فقط الأكواد الخاصة بعرض المفضلة في الصفحة

window.addEventListener('pageshow', function() {
  updateWishlistCounter();
});

window.addEventListener('popstate', function(event) {
    refreshWishlistState();
    updateCartCount();
});

function refreshWishlistState() {
    const wishlistGrid = document.querySelector('.wishlist-grid');
    if (wishlistGrid) {
        wishlistGrid.innerHTML = '';
        loadWishlistItems();
        updateEmptyState();
        updateWishlistCounter();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    loadCartFromLocalStorage();
    loadWishlistItems();
    updateEmptyState();
    updateWishlistCounter();
    document.querySelector('.add-all-to-cart').addEventListener('click', addAllToCart);
    document.querySelector('.clear-wishlist').addEventListener('click', clearWishlist);
});

function loadWishlistItems() {
    const wishlistGrid = document.querySelector('.wishlist-grid');
    const items = getWishlistItems();
    items.forEach(item => {
        const itemElement = createWishlistItemElement(item);
        wishlistGrid.appendChild(itemElement);
    });
    updateEmptyState();
    updateWishlistCounter();
}

function createWishlistItemElement(item) {
    const div = document.createElement('div');
    div.className = 'wishlist-item';
    div.dataset.productId = item.id;
    div.innerHTML = `
        <div class="wishlist-item-image">
            <img src="${item.image}" alt="${item.title}">
        </div>
        <div class="wishlist-item-details">
            <h3 class="wishlist-item-title">${item.title}</h3>
            <div class="wishlist-item-price">
                <span class="current-price">${item.price}</span>
                ${item.oldPrice ? `<span class="old-price">${item.oldPrice}</span>` : ''}
            </div>
        </div>
        <div class="wishlist-item-actions">
            <button class="add-to-cart" onclick="addToCartFromWishlist(this)">
                <i class="fas fa-shopping-cart"></i>
                إضافة للسلة
            </button>
            <button class="remove-from-wishlist" onclick="removeFromWishlist('${item.id}')">
                <i class="fas fa-trash"></i>
                إزالة
            </button>
        </div>
    `;
    return div;
}

function addToCartFromWishlist(button) {
    const wishlistItem = button.closest('.wishlist-item');
    const productData = {
        id: wishlistItem.dataset.productId,
        title: wishlistItem.querySelector('.wishlist-item-title').textContent,
        price: wishlistItem.querySelector('.current-price').textContent,
        oldPrice: wishlistItem.querySelector('.old-price')?.textContent,
        image: wishlistItem.querySelector('img').src
    };
    add_product_to_cart(productData);
    updateCartCount();
    const cartContent = document.querySelector('.cart_content');
    if (cartContent) {
        cartContent.innerHTML = '';
        loadCartFromLocalStorage();
    }
    showNotification('تم إضافة المنتج إلى السلة');
}

function removeFromWishlist(productId) {
    const wishlistItem = document.querySelector(`.wishlist-item[data-product-id="${productId}"]`);
    if (!wishlistItem) return;
    
    wishlistItem.classList.add('removing');
    setTimeout(() => {
        wishlistItem.remove();
        // تحديث localStorage
        const savedWishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
        const updatedWishlist = savedWishlist.filter(item => item.id !== productId);
        localStorage.setItem('wishlist', JSON.stringify(updatedWishlist));
        
        // تحديث العداد في الهيدر
        const wishlistCount = document.querySelector('.wishlist-count');
        if (wishlistCount) {
            wishlistCount.textContent = updatedWishlist.length;
        }
        
        // تحديث حالة القائمة الفارغة
        updateEmptyState();
        showNotification('تم إزالة المنتج من المفضلة');
    }, 300);
}

function addAllToCart() {
    const wishlistItems = document.querySelectorAll('.wishlist-item');
    wishlistItems.forEach(item => {
        const addToCartBtn = item.querySelector('.add-to-cart');
        addToCartBtn.click();
    });
    showNotification('تم إضافة جميع المنتجات إلى السلة');
}

function clearWishlist() {
    const wishlistGrid = document.querySelector('.wishlist-grid');
    const items = wishlistGrid.querySelectorAll('.wishlist-item');
    items.forEach(item => {
        item.classList.add('removing');
    });
    setTimeout(() => {
        wishlistGrid.innerHTML = '';
        localStorage.setItem('wishlist', '[]');
        const wishlistCount = document.querySelector('.wishlist-count');
        if (wishlistCount) {
            wishlistCount.textContent = '0';
        }
        updateEmptyState();
        showNotification('تم مسح قائمة المفضلة');
    }, 300);
}

function updateEmptyState() {
    const wishlistGrid = document.querySelector('.wishlist-grid');
    const emptyState = document.querySelector('.empty-wishlist');
    const wishlistActions = document.querySelector('.wishlist-actions');
    if (wishlistGrid.children.length === 0) {
        emptyState.style.display = 'block';
        wishlistActions.style.display = 'none';
    } else {
        emptyState.style.display = 'none';
        wishlistActions.style.display = 'flex';
    }
}

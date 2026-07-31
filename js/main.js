// main.js — منطق الصفحة الرئيسية فقط، الدوال المشتركة في common.js

/* ── Scroll to Top ─────────────────────────────────────── */
const scrollToTopBtn = document.getElementById('scrollToTop');
if (scrollToTopBtn) {
    window.addEventListener('scroll', function () {
        scrollToTopBtn.style.display = window.scrollY > 300 ? 'block' : 'none';
    });
    scrollToTopBtn.addEventListener('click', function () {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
}

/* ── الشرائح الدعائية ──────────────────────────────────── */
const slides = document.querySelectorAll('.slide');
const dots   = document.querySelectorAll('.slider-dot');
let currentSlide = 0;

function showSlide(index) {
    if (!slides.length) return;
    slides.forEach(s => s.classList.remove('active'));
    dots.forEach(d  => d.classList.remove('active'));
    currentSlide = (index + slides.length) % slides.length;
    slides[currentSlide].classList.add('active');
    if (dots[currentSlide]) dots[currentSlide].classList.add('active');
}

if (slides.length > 1) {
    dots.forEach((dot, i) => dot.addEventListener('click', () => showSlide(i)));
    setInterval(() => showSlide(currentSlide + 1), 5000);
}

/* ── أزرار السلة والمفضلة في بطاقات المنتجات ──────────── */
document.addEventListener('DOMContentLoaded', function () {

    // تحميل السلة من التخزين المحلي
    if (typeof loadCartFromLocalStorage === 'function') loadCartFromLocalStorage();
    if (typeof updateWishlistIcons     === 'function') updateWishlistIcons();

    /* تفويض الأحداث (Event Delegation) — يعمل حتى مع الكروت المُرتّبة ديناميكياً */
    const grid = document.getElementById('products-grid');
    if (!grid) return;

    grid.addEventListener('click', function (e) {
        const cartBtn     = e.target.closest('.add-to-cart');
        const wishlistBtn = e.target.closest('.wishlist');

        /* ── زر أضف للسلة ── */
        if (cartBtn) {
            e.preventDefault();
            e.stopPropagation();
            const card = cartBtn.closest('.product-card');
            if (!card) return;

            const productData = {
                id:       card.dataset.id,
                title:    card.dataset.title    || card.querySelector('.product-title')?.innerText || '',
                price:    card.dataset.price
                              ? card.dataset.price + ' ر.س'
                              : (card.querySelector('.current-price')?.innerText || '0'),
                oldPrice: card.dataset.oldPrice || card.querySelector('.old-price')?.innerText || '',
                image:    card.dataset.image    || card.querySelector('.product-image img')?.src || ''
            };

            if (typeof add_product_to_cart === 'function') {
                add_product_to_cart(productData);
                // تأثير بصري على الزر
                cartBtn.textContent = '✓ تم الإضافة';
                cartBtn.style.background = '#27ae60';
                setTimeout(() => {
                    cartBtn.textContent = 'أضف للسلة';
                    cartBtn.style.background = '';
                }, 1800);
            }
        }

        /* ── زر المفضلة ── */
        if (wishlistBtn) {
            e.preventDefault();
            e.stopPropagation();
            const card = wishlistBtn.closest('.product-card');
            if (!card) return;

            const productData = {
                id:       card.dataset.id,
                title:    card.dataset.title    || card.querySelector('.product-title')?.innerText || '',
                price:    card.dataset.price
                              ? card.dataset.price + ' ر.س'
                              : (card.querySelector('.current-price')?.innerText || '0'),
                oldPrice: card.dataset.oldPrice || card.querySelector('.old-price')?.innerText || '',
                image:    card.dataset.image    || card.querySelector('.product-image img')?.src || ''
            };

            const icon   = wishlistBtn.querySelector('i');
            const inList = typeof isInWishlist === 'function' && isInWishlist(card.dataset.id);

            if (inList) {
                if (typeof removeFromWishlist === 'function') removeFromWishlist(card.dataset.id);
                if (icon) { icon.classList.replace('fas', 'far'); }
                wishlistBtn.classList.remove('active');
            } else {
                if (typeof addToWishlist === 'function') addToWishlist(productData);
                if (icon) { icon.classList.replace('far', 'fas'); }
                wishlistBtn.classList.add('active');
            }

            if (typeof updateWishlistIcons === 'function') updateWishlistIcons();
        }
    });

    /* ── تحديث أيقونات المفضلة عند التحميل ── */
    if (typeof getWishlistItems === 'function') {
        const saved = getWishlistItems();
        document.querySelectorAll('.product-card').forEach(card => {
            if (saved.some(i => i.id === card.dataset.id)) {
                const icon = card.querySelector('.wishlist i');
                if (icon) {
                    icon.classList.replace('far', 'fas');
                    card.querySelector('.wishlist')?.classList.add('active');
                }
            }
        });
    }
});

window.addEventListener('pageshow', function () {
    if (typeof loadCartFromLocalStorage === 'function') loadCartFromLocalStorage();
    if (typeof updateWishlistCounter    === 'function') updateWishlistCounter();
});
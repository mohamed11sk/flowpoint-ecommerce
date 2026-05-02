// main.js - منطق الصفحة الخاص فقط، كل الدوال العامة في common.js

// متغيرات نظام تقليب الصفحات
let currentPage = 1;
let productsPerPage = 12;
let totalPages = 1;

// Scroll to top button
let scrollToTopBtn = document.getElementById("scrollToTop");
window.onscroll = function () {
  if (window.scrollY > 300) {
    scrollToTopBtn.style.display = "block";
  } else {
    scrollToTopBtn.style.display = "none";
  }
};
scrollToTopBtn.onclick = function () {
  window.scroll({
    left: 0,
    top: 0,
    behavior: "smooth"
  });
};

// نظام الشرائح الدعائية
let currentSlide = 0;
const slides = document.querySelectorAll('.slide');
const dots = document.querySelectorAll('.slider-dot');
const totalSlides = slides.length;

function showSlide(index) {
  slides.forEach(slide => slide.classList.remove('active'));
  dots.forEach(dot => dot.classList.remove('active'));
  slides[index].classList.add('active');
  dots[index].classList.add('active');
  currentSlide = index;
}

function nextSlide() {
  showSlide((currentSlide + 1) % totalSlides);
}

dots.forEach((dot, index) => {
  dot.addEventListener('click', () => {
    showSlide(index);
  });
});

setInterval(nextSlide, 5000);

// عداد تنازلي للعروض
function startDealTimer() {
  let timeLeft = 12 * 60 * 60 + 45 * 60 + 22;
  const timerElement = document.getElementById('deal-timer');
  const timer = setInterval(() => {
    if (timeLeft <= 0) {
      clearInterval(timer);
      timerElement.textContent = "انتهى العرض!";
      return;
    }
    const hours = Math.floor(timeLeft / 3600);
    const minutes = Math.floor((timeLeft % 3600) / 60);
    const seconds = timeLeft % 60;
    timerElement.textContent = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    timeLeft--;
  }, 1000);
}

document.addEventListener('DOMContentLoaded', () => {
  loadCartFromLocalStorage(); // تحميل السلة من التخزين المحلي عند كل تحميل للصفحة
  startDealTimer();
  updateWishlistIcons(); // من common.js

  // إضافة مستمعي الأحداث للمنتجات
  const productCards = document.querySelectorAll('.product-card');
  productCards.forEach(card => {
    card.addEventListener('click', function(e) {
      if (!e.target.closest('.wishlist') && !e.target.closest('.add-to-cart')) {
        // showProductDetails(product.id);
      }
    });
    // زر المفضلة
    const wishlistBtn = card.querySelector('.wishlist');
    wishlistBtn?.addEventListener('click', function(e) {
      e.stopPropagation();
      e.preventDefault();
      const productCard = this.closest('.product-card');
      const productData = {
        id: productCard.dataset.id,
        title: productCard.querySelector('.product-title').innerText,
        price: productCard.querySelector('.current-price').innerText,
        oldPrice: productCard.querySelector('.old-price')?.innerText,
        image: productCard.querySelector('.product-image img').src
      };
      const heartIcon = this.querySelector('i');
      const isAdding = heartIcon.classList.contains('far');
      if (isAdding) {
        if (addToWishlist(productData)) {
          heartIcon.classList.remove('far');
          heartIcon.classList.add('fas');
          this.classList.add('active');
        }
      } else {
        removeFromWishlist(productData.id);
        heartIcon.classList.remove('fas');
        heartIcon.classList.add('far');
        this.classList.remove('active');
      }
      updateWishlistIcons();
    });
    // زر إضافة للسلة
    const addToCartBtn = card.querySelector('.add-to-cart');
    addToCartBtn?.addEventListener('click', function(e) {
      e.stopPropagation();
      e.preventDefault();
      const productCard = this.closest('.product-card');
      const productData = {
        id: productCard.dataset.id,
        title: productCard.querySelector('.product-title').innerText,
        price: productCard.querySelector('.current-price').innerText,
        oldPrice: productCard.querySelector('.old-price')?.innerText,
        image: productCard.querySelector('.product-image img').src
      };
      add_product_to_cart(productData); // من common.js
    });
  });

  document.getElementById('sort-by')?.addEventListener('change', () => {
    // fetchAndRenderProducts(1);
  });
  document.getElementById('per-page')?.addEventListener('change', () => {
    // fetchAndRenderProducts(1);
  });
});

window.addEventListener('pageshow', function() {
  loadCartFromLocalStorage();
  updateWishlistCounter();
});
// ================ نظام البحث المتقدم (مثل Amazon) ================

class AdvancedSearch {
    constructor() {
        this.searchInput = document.getElementById('search-input');
        this.searchBtn = document.getElementById('search-btn');
        this.suggestionsBox = document.getElementById('search-suggestions');
        this.debounceTimer = null;
        this.currentQuery = '';
        this.selectedIndex = -1;
        
        if (this.searchInput) {
            this.init();
        }
    }
    
    init() {
        // البحث الفوري (Real-time Search)
        this.searchInput.addEventListener('input', (e) => {
            clearTimeout(this.debounceTimer);
            const query = e.target.value.trim();
            
            if (query.length === 0) {
                this.suggestionsBox.style.display = 'none';
                return;
            }
            
            // تأخير بسيط (100ms) قبل البحث
            this.debounceTimer = setTimeout(() => {
                this.performLiveSearch(query);
            }, 100);
        });
        
        // البحث عند الضغط على Enter
        this.searchInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                const query = this.searchInput.value.trim();
                if (query.length > 0) {
                    this.performFullSearch(query);
                }
            }
        });
        
        // زر البحث
        this.searchBtn.addEventListener('click', () => {
            const query = this.searchInput.value.trim();
            if (query.length > 0) {
                this.performFullSearch(query);
            }
        });
        
        // إغلاق عند الضغط خارجها
        document.addEventListener('click', (e) => {
            if (!this.searchInput.contains(e.target) && !this.suggestionsBox.contains(e.target)) {
                this.suggestionsBox.style.display = 'none';
            }
        });

        // التنقل بالأسهم في النتائج
        this.searchInput.addEventListener('keydown', (e) => {
            const items = this.suggestionsBox.querySelectorAll('.suggestion-item');
            
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                this.selectedIndex = Math.min(this.selectedIndex + 1, items.length - 1);
                this.updateSelectedItem(items);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                this.selectedIndex = Math.max(this.selectedIndex - 1, -1);
                this.updateSelectedItem(items);
            }
        });
    }
    
    // البحث الحي (مثل Amazon)
    async performLiveSearch(query) {
        try {
            const url = `search.php?action=suggestions&q=${encodeURIComponent(query)}`;
            const response = await fetch(url);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            if (data.success && data.results && data.results.length > 0) {
                this.displayLiveResults(data.results, query);
                this.selectedIndex = -1;
            } else {
                this.suggestionsBox.style.display = 'none';
            }
        } catch (error) {
            console.error('خطأ في البحث الفوري:', error);
            this.suggestionsBox.style.display = 'none';
        }
    }
    
    // عرض النتائج الحية في Dropdown
    displayLiveResults(products, query) {
        let html = '<div class="suggestions-list">';
        
        products.forEach((product, index) => {
            const price = parseFloat(product.current_price).toFixed(2);
            const oldPrice = product.old_price ? parseFloat(product.old_price).toFixed(2) : null;
            
            // تمييز الكلمة المبحوث عنها
            const highlighted = product.title.replace(
                new RegExp(`(${query})`, 'gi'),
                '<strong>$1</strong>'
            );
            
            html += `
                <div class="suggestion-item" data-id="${product.id}" data-title="${product.title}">
                    <div class="suggestion-image">
                        <img src="${product.image}" alt="${product.title}" loading="lazy">
                    </div>
                    <div class="suggestion-info">
                        <div class="suggestion-title">${highlighted}</div>
                        <div class="suggestion-meta">
                            <span class="suggestion-price">${price} ر.س</span>
                            ${oldPrice ? `<span class="suggestion-old-price">${oldPrice} ر.س</span>` : ''}
                            ${product.badge ? `<span class="suggestion-badge">${product.badge}</span>` : ''}
                        </div>
                    </div>
                </div>
            `;
        });
        
        html += '</div>';
        this.suggestionsBox.innerHTML = html;
        this.suggestionsBox.style.display = 'block';
        
        // إضافة مستمع الأحداث
        document.querySelectorAll('.suggestion-item').forEach(item => {
            item.addEventListener('click', () => {
                const productId = item.dataset.id;
                window.location.href = `details.php?id=${productId}`;
            });
        });
    }
    
    // البحث الكامل
    async performFullSearch(query) {
        this.currentQuery = query;
        this.suggestionsBox.style.display = 'none';
        
        // إظهار رسالة التحميل
        this.showLoadingModal();
        
        try {
            const url = `search.php?action=search&q=${encodeURIComponent(query)}&sort=popular`;
            const response = await fetch(url);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            // إزالة رسالة التحميل
            const loadingModal = document.querySelector('.search-results-modal.loading');
            if (loadingModal) loadingModal.remove();
            
            if (data.success && data.results && data.results.length > 0) {
                this.displayFullResults(data);
            } else {
                this.showNoResults(query);
            }
        } catch (error) {
            console.error('خطأ في البحث الكامل:', error);
            
            // إزالة رسالة التحميل
            const loadingModal = document.querySelector('.search-results-modal.loading');
            if (loadingModal) loadingModal.remove();
            
            // عرض رسالة خطأ
            const modal = document.createElement('div');
            modal.innerHTML = `
                <div class="search-results-modal">
                    <div class="results-container no-results">
                        <i class="fas fa-exclamation-circle"></i>
                        <h2>حدث خطأ</h2>
                        <p>عذراً، حدث خطأ في البحث. يرجى محاولة مرة أخرى.</p>
                        <p class="no-results-hint">${error.message}</p>
                        <button class="close-results" id="close-results">إغلاق</button>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
            
            modal.querySelector('#close-results').addEventListener('click', () => {
                modal.remove();
                this.searchInput.focus();
            });
        }
    }
    
    // عرض النتائج الكاملة
    displayFullResults(data) {
        let html = `
            <div class="search-results-modal">
                <div class="results-container">
                    <div class="results-header">
                        <h2>نتائج البحث عن: <span>"${data.query}"</span></h2>
                        <p class="results-count">وجدنا <strong>${data.count}</strong> منتج</p>
                    </div>
                    <div class="results-grid">
        `;
        
        data.results.forEach(product => {
            const price = parseFloat(product.current_price).toFixed(2);
            const oldPrice = product.old_price ? parseFloat(product.old_price).toFixed(2) : null;
            
            html += `
                <div class="search-product-card">
                    <a href="details.php?id=${product.id}" class="product-link">
                        <div class="product-image-box">
                            <img src="${product.image}" alt="${product.title}" loading="lazy">
                            ${product.badge ? `<span class="product-badge">${product.badge}</span>` : ''}
                        </div>
                        <div class="product-details">
                            <h3>${product.title}</h3>
                            <div class="product-rating">
                                <i class="fas fa-star"></i> ${product.rating}
                            </div>
                            <div class="product-prices">
                                ${oldPrice ? `<span class="old-price">${oldPrice} ر.س</span>` : ''}
                                <span class="current-price">${price} ر.س</span>
                            </div>
                        </div>
                    </a>
                </div>
            `;
        });
        
        html += `
                    </div>
                    <button class="close-results" id="close-results">إغلاق</button>
                </div>
            </div>
        `;
        
        const modal = document.createElement('div');
        modal.innerHTML = html;
        document.body.appendChild(modal);
        
        const closeBtn = modal.querySelector('#close-results');
        closeBtn.addEventListener('click', () => {
            modal.remove();
            this.searchInput.focus();
        });
        
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.remove();
                this.searchInput.focus();
            }
        });
    }
    
    // عرض رسالة "لا توجد نتائج"
    showNoResults(query) {
        const modal = document.createElement('div');
        modal.innerHTML = `
            <div class="search-results-modal">
                <div class="results-container no-results">
                    <i class="fas fa-search"></i>
                    <h2>لا توجد نتائج</h2>
                    <p>عذراً، لم نجد منتجات تطابق: <strong>"${query}"</strong></p>
                    <p class="no-results-hint">جرب كلمات بحث مختلفة</p>
                    <button class="close-results" id="close-results">إغلاق</button>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
        
        modal.querySelector('#close-results').addEventListener('click', () => {
            modal.remove();
            this.searchInput.focus();
        });
    }
    
    // عرض رسالة التحميل
    showLoadingModal() {
        const modal = document.createElement('div');
        modal.innerHTML = `
            <div class="search-results-modal loading">
                <div class="spinner"></div>
                <p>جاري البحث...</p>
            </div>
        `;
        document.body.appendChild(modal);
    }

    // تحديث العنصر المختار
    updateSelectedItem(items) {
        items.forEach((item, idx) => {
            if (idx === this.selectedIndex) {
                item.classList.add('active');
                item.scrollIntoView({ block: 'nearest' });
            } else {
                item.classList.remove('active');
            }
        });
    }
}

// تشغيل النظام عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', () => {
    new AdvancedSearch();
});

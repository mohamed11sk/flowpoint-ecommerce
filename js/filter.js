/* ============================================================
   filter.js — نظام فلترة وترتيب وتصفح المنتجات (Client-side)
   ============================================================ */

(function () {
    'use strict';

    /* ── ثوابت ────────────────────────────────────────────── */
    const ITEMS_PER_PAGE = 12;

    /* ── خريطة الأيقونات لكل تصنيف (تطابق PHP) ───────────── */
    const ICON_MAP = {
        'صوصات'   : 'fa-bottle-droplet',
        'صوص'     : 'fa-bottle-droplet',
        'صلصات'   : 'fa-bottle-droplet',
        'صلصة'    : 'fa-bottle-droplet',
        'كاتشب'   : 'fa-bottle-droplet',
        'مايونيز' : 'fa-bottle-droplet',
        'مخللات'  : 'fa-jar',
        'مخلل'    : 'fa-jar',
        'عسل'     : 'fa-jar',
        'مربى'    : 'fa-jar',
        'خل'      : 'fa-flask-vial',
        'زيت'     : 'fa-oil-can',
        'زيوت'    : 'fa-oil-can',
        'دهن'     : 'fa-drumstick-bite',
        'دهون'    : 'fa-drumstick-bite',
        'بهارات'  : 'fa-pepper-hot',
        'توابل'   : 'fa-pepper-hot',
    };

    /* ── حالة التطبيق ─────────────────────────────────────── */
    const state = {
        category : 'all',
        sortBy   : 'default',
        page     : 1,
    };

    /* ── مراجع عناصر الـ DOM ───────────────────────────────── */
    let allCards, grid, noResultsMsg, productsCount,
        sectionTitle, sectionIcon, sortSelect, paginationEl;

    /* ── تهيئة ────────────────────────────────────────────── */
    function init() {
        allCards      = Array.from(document.querySelectorAll('#products-grid .product-card'));
        grid          = document.getElementById('products-grid');
        noResultsMsg  = document.getElementById('noResultsMsg');
        productsCount = document.getElementById('productsCount');
        sectionTitle  = document.getElementById('sectionTitle');
        sectionIcon   = document.getElementById('sectionIcon');
        sortSelect    = document.getElementById('sort-by');
        paginationEl  = document.getElementById('pagination');

        // أحداث الـ Chips
        document.querySelectorAll('.cat-chip').forEach(chip => {
            chip.addEventListener('click', onChipClick);
        });

        // حدث الترتيب
        sortSelect.addEventListener('change', function () {
            state.sortBy = this.value;
            state.page   = 1;
            render();
        });

        // عرض أولي
        render();

        // Countdown للعروض الخاصة
        initCountdowns();

        // زر التمرير للأعلى
        initScrollTop();
    }

    /* ── اختيار تصنيف ─────────────────────────────────────── */
    function onChipClick() {
        document.querySelectorAll('.cat-chip').forEach(c => c.classList.remove('active'));
        this.classList.add('active');
        state.category = this.dataset.category;
        state.page     = 1;
        render();
        scrollToProducts();
    }

    /* ── الدالة الرئيسية للعرض ────────────────────────────── */
    function render() {
        // 1. فلترة
        let visible = allCards.filter(card =>
            state.category === 'all'
                ? true
                : card.dataset.category === String(state.category)
        );

        // 2. ترتيب
        visible = sortCards(visible, state.sortBy);

        // 3. إخفاء كل الكروت
        allCards.forEach(c => c.classList.add('hidden-card'));

        // 4. عرض الصفحة أو رسالة لا توجد نتائج
        if (visible.length === 0) {
            noResultsMsg.hidden = false;
            paginationEl.innerHTML = '';
        } else {
            noResultsMsg.hidden = true;
            renderPage(visible);
            renderPagination(visible);
        }

        // 5. تحديث العداد والعنوان
        productsCount.textContent = visible.length + ' منتج';
        updateSectionTitle();
    }

    /* ── ترتيب الكروت ─────────────────────────────────────── */
    function sortCards(cards, by) {
        return [...cards].sort((a, b) => {
            switch (by) {
                case 'low-high' : return +a.dataset.price    - +b.dataset.price;
                case 'high-low' : return +b.dataset.price    - +a.dataset.price;
                case 'rating'   : return +b.dataset.rating   - +a.dataset.rating;
                case 'discount' : return +b.dataset.discount - +a.dataset.discount;
                default         : return 0;
            }
        });
    }

    /* ── عرض صفحة واحدة ───────────────────────────────────── */
    function renderPage(visible) {
        const start = (state.page - 1) * ITEMS_PER_PAGE;
        const end   = start + ITEMS_PER_PAGE;

        visible.forEach((card, idx) => {
            if (idx >= start && idx < end) {
                card.classList.remove('hidden-card');
                card.style.animationDelay = (idx % ITEMS_PER_PAGE * 0.04) + 's';
                card.classList.remove('fade-in');
                void card.offsetWidth; // إعادة تشغيل الأنيميشن
                card.classList.add('fade-in');
                grid.appendChild(card);
            }
        });
    }

    /* ── بناء Pagination ────────────────────────────────────── */
    function renderPagination(visible) {
        const total = Math.ceil(visible.length / ITEMS_PER_PAGE);
        paginationEl.innerHTML = '';

        if (total <= 1) return;

        // زر السابق
        paginationEl.appendChild(
            makePageBtn('<i class="fas fa-chevron-right"></i>', state.page === 1, () => {
                if (state.page > 1) {
                    state.page--;
                    renderPage(visible);
                    renderPagination(visible);
                    scrollToProducts();
                }
            })
        );

        // أزرار الأرقام
        for (let p = 1; p <= total; p++) {
            const pageNum = p;
            const btn = makePageBtn(p, false, () => {
                state.page = pageNum;
                renderPage(visible);
                renderPagination(visible);
                scrollToProducts();
            });
            if (p === state.page) btn.classList.add('active');
            paginationEl.appendChild(btn);
        }

        // زر التالي
        paginationEl.appendChild(
            makePageBtn('<i class="fas fa-chevron-left"></i>', state.page === total, () => {
                if (state.page < total) {
                    state.page++;
                    renderPage(visible);
                    renderPagination(visible);
                    scrollToProducts();
                }
            })
        );
    }

    /* ── صنع زر pagination ─────────────────────────────────── */
    function makePageBtn(content, disabled, onClick) {
        const btn = document.createElement('button');
        btn.className  = 'page-btn' + (disabled ? ' disabled' : '');
        btn.innerHTML  = content;
        btn.type       = 'button';
        if (!disabled) btn.addEventListener('click', onClick);
        return btn;
    }

    /* ── تحديث عنوان القسم وأيقونته ───────────────────────── */
    function updateSectionTitle() {
        const activeChip = document.querySelector('.cat-chip.active');
        if (!activeChip) return;

        const label = activeChip.dataset.categoryName || activeChip.querySelector('.chip-label')?.textContent.trim() || activeChip.textContent.trim();
        sectionTitle.textContent = label;

        const chipIcon = activeChip.querySelector('.chip-icon i') || activeChip.querySelector('i');
        if (chipIcon) {
            sectionIcon.innerHTML = `<i class="${chipIcon.className}"></i>`;
        }
    }

    /* ── التمرير لقسم المنتجات ─────────────────────────────── */
    function scrollToProducts() {
        const container = document.getElementById('products-container');
        if (!container) return;
        const top = container.getBoundingClientRect().top + window.scrollY - 80;
        window.scrollTo({ top, behavior: 'smooth' });
    }

    /* ── زر التمرير للأعلى ─────────────────────────────────── */
    function initScrollTop() {
        const btn = document.getElementById('scrollToTop');
        if (!btn) return;

        window.addEventListener('scroll', () => {
            btn.hidden = window.scrollY < 300;
        });

        btn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    /* ── عداد تنازلي للعروض الخاصة ────────────────────────── */
    function initCountdowns() {
        document.querySelectorAll('.deal-timer').forEach(timer => {
            const endDate = new Date(timer.dataset.endDate).getTime();
            const cdEl    = timer.querySelector('.countdown');
            if (!cdEl) return;

            function tick() {
                const diff = endDate - Date.now();

                if (diff <= 0) {
                    cdEl.textContent = 'انتهى العرض';
                    const dealCard = timer.closest('.deal-card');
                    const dealBtn  = dealCard?.querySelector('.deal-button');
                    if (dealBtn) {
                        dealBtn.textContent = 'انتهى العرض';
                        dealBtn.classList.add('disabled');
                        dealBtn.removeAttribute('href');
                    }
                    dealCard?.classList.add('expired');
                    return; // إيقاف التحديث
                }

                const d = Math.floor(diff / 86_400_000);
                const h = String(Math.floor((diff % 86_400_000) / 3_600_000)).padStart(2, '0');
                const m = String(Math.floor((diff % 3_600_000)  /    60_000)).padStart(2, '0');
                const s = String(Math.floor((diff %    60_000)  /     1_000)).padStart(2, '0');

                cdEl.textContent = d > 0
                    ? `${d} يوم ${h}:${m}:${s}`
                    : `${h}:${m}:${s}`;
            }

            tick();
            const timer_id = setInterval(() => {
                tick();
                // إيقاف الـ interval لو انتهى الوقت
                if (new Date(timer.dataset.endDate).getTime() - Date.now() <= 0) {
                    clearInterval(timer_id);
                }
            }, 1_000);
        });
    }

    /* ── تشغيل بعد اكتمال الـ DOM ──────────────────────────── */
    document.addEventListener('DOMContentLoaded', init);

})();
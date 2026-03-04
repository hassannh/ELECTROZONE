'use strict';

/* ────────────────────────────────────────────────────────────
   ELECTROZONE JS  –  Premium Storefront Interactions
   ──────────────────────────────────────────────────────────── */

document.addEventListener('DOMContentLoaded', () => {

    /* ── 1. MOBILE DRAWER ──────────────────────────────────── */
    const hamburger = document.getElementById('hamburger');
    const drawer = document.getElementById('mobileDrawer');
    const overlay = document.getElementById('mobileOverlay');
    const closeBtn = document.getElementById('drawerClose');

    function openDrawer() {
        if (!drawer) return;
        drawer.classList.add('open');
        overlay.classList.add('open');
        hamburger?.classList.add('active');
        hamburger?.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';
    }
    function closeDrawer() {
        if (!drawer) return;
        drawer.classList.remove('open');
        overlay.classList.remove('open');
        hamburger?.classList.remove('active');
        hamburger?.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
    }

    hamburger?.addEventListener('click', () =>
        drawer?.classList.contains('open') ? closeDrawer() : openDrawer()
    );
    overlay?.addEventListener('click', closeDrawer);
    closeBtn?.addEventListener('click', closeDrawer);
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDrawer(); });


    /* ── 2. LANGUAGE DROPDOWN ──────────────────────────────── */
    const langToggle = document.getElementById('langToggle');
    const langDropdown = document.getElementById('langDropdown');

    langToggle?.addEventListener('click', (e) => {
        e.stopPropagation();
        const isOpen = langDropdown.classList.toggle('open');
        langToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });
    document.addEventListener('click', e => {
        if (!langToggle?.contains(e.target) && !langDropdown?.contains(e.target)) {
            langDropdown?.classList.remove('open');
            langToggle?.setAttribute('aria-expanded', 'false');
        }
    });


    /* ── 3. LIVE SEARCH AUTOCOMPLETE ─────────────────────── */
    const searchInput = document.getElementById('searchInput'); // Desktop
    const searchDropdown = document.getElementById('searchDropdown'); // Desktop
    const searchResults = document.getElementById('searchResults'); // Desktop

    const mobileSearchInput = document.getElementById('mobileSearchInput');
    const mobileSearchInputWrapper = document.getElementById('mobileSearchInputWrapper');
    const mobileSearchDropdown = document.getElementById('mobileSearchDropdown');
    const mobileSearchResults = document.getElementById('mobileSearchResults');

    const autocompleteUrl = document.querySelector('meta[name="autocomplete-url"]')?.content;
    const searchForm = document.getElementById('searchForm');

    let debounceTimer;

    function clearDropdown(dropdown, results) {
        dropdown?.classList.remove('open');
        if (results) results.innerHTML = '';
    }

    // Global toggle for Expanding Mobile Search
    window.toggleMobileSearch = () => {
        if (!mobileSearchInputWrapper) return;
        const isActive = mobileSearchInputWrapper.classList.toggle('active');
        if (isActive) {
            setTimeout(() => mobileSearchInput?.focus(), 300);
        } else {
            if (mobileSearchInput) mobileSearchInput.value = '';
            clearDropdown(mobileSearchDropdown, mobileSearchResults);
        }
    };

    // Generalized search listener
    function initSearch(input, dropdown, results) {
        if (!input) return;

        input.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            const q = input.value.trim();

            if (q.length < 2) {
                clearDropdown(dropdown, results);
                return;
            }

            debounceTimer = setTimeout(async () => {
                try {
                    const res = await fetch(`${autocompleteUrl}?q=${encodeURIComponent(q)}`);
                    const data = await res.json();
                    if (!results) return;

                    if (data.length === 0) {
                        results.innerHTML = `<div class="p-8 text-center text-light font-bold">لا توجد نتائج / No results found</div>`;
                    } else {
                        const isRtl = document.documentElement.dir === 'rtl';
                        const html = data.map(item => `
                            <a class="flex items-center gap-4 p-4 border-b border-border/40 hover:bg-surface transition-colors" href="${item.url}">
                                <div class="w-12 h-12 bg-surface rounded-lg overflow-hidden shrink-0">
                                    <img src="${item.image || '/images/placeholder.png'}" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="font-bold text-dark truncate">${item.name}</div>
                                    <div class="text-[0.7rem] font-bold text-light uppercase tracking-tight">${item.brand}</div>
                                </div>
                                <div class="font-black text-primary">${item.price}</div>
                            </a>
                        `).join('');

                        results.innerHTML = html + `
                            <a class="block p-4 text-center bg-primary/5 text-primary font-black uppercase tracking-widest text-xs hover:bg-primary/10 transition-colors" href="${searchForm?.action}?query=${encodeURIComponent(q)}">
                                ${isRtl ? 'عرض كل النتائج ←' : 'View all results →'}
                            </a>`;
                    }
                    dropdown?.classList.add('open');
                } catch (err) {
                    clearDropdown(dropdown, results);
                }
            }, 280);
        });

        input.addEventListener('keydown', e => { if (e.key === 'Escape') clearDropdown(dropdown, results); });
    }

    // Initialize both
    initSearch(searchInput, searchDropdown, searchResults);
    initSearch(mobileSearchInput, mobileSearchDropdown, mobileSearchResults);

    // Close dropdown on outside click
    document.addEventListener('click', e => {
        if (searchInput && !searchInput.contains(e.target) && !searchDropdown?.contains(e.target)) {
            clearDropdown(searchDropdown, searchResults);
        }
        if (mobileSearchInput && !mobileSearchInput.contains(e.target) && !mobileSearchDropdown?.contains(e.target) && !e.target.closest('button[onclick*="toggleMobileSearch"]')) {
            if (mobileSearchInputWrapper?.classList.contains('active')) toggleMobileSearch();
        }
    });


    /* ── 4. STICKY HEADER SHADOW ───────────────────────────── */
    const siteHeader = document.getElementById('siteHeader');
    window.addEventListener('scroll', () => {
        if (!siteHeader) return;
        siteHeader.style.boxShadow = window.scrollY > 10
            ? '0 4px 30px rgba(0,0,0,.12)'
            : '';
    }, { passive: true });


    /* ── 5. BACK TO TOP ────────────────────────────────────── */
    const scrollTopBtn = document.getElementById('scrollTop');
    window.addEventListener('scroll', () => {
        if (!scrollTopBtn) return;
        if (window.scrollY > 300) {
            scrollTopBtn.style.opacity = '1';
            scrollTopBtn.style.pointerEvents = 'auto';
        } else {
            scrollTopBtn.style.opacity = '0';
            scrollTopBtn.style.pointerEvents = 'none';
        }
    }, { passive: true });
    scrollTopBtn?.addEventListener('click', () =>
        window.scrollTo({ top: 0, behavior: 'smooth' })
    );


    /* ── 6. FLASH MESSAGE AUTO-DISMISS ─────────────────────── */
    document.querySelectorAll('.alert').forEach(el => {
        setTimeout(() => {
            el.style.transition = 'opacity .5s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        }, 4000);
    });


    /* ── 7. QUANTITY INPUT GUARDS ────────────────────────────── */
    document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('change', () => {
            const min = parseInt(input.min) || 1;
            const max = parseInt(input.max) || 999;
            if (parseInt(input.value) < min) input.value = min;
            if (parseInt(input.value) > max) input.value = max;
        });
    });


    /* ── 7. AJAX ADD TO CART ────────────────────────────────── */
    document.addEventListener('submit', async (e) => {
        const form = e.target;
        if (!form.action || !form.action.includes('/cart/add')) return;

        e.preventDefault();
        const btn = form.querySelector('button[type="submit"]');
        if (!btn || btn.disabled) return;

        const originalHtml = btn.innerHTML;
        const width = btn.offsetWidth;
        btn.style.width = `${width}px`;
        btn.disabled = true;
        btn.innerHTML = `<svg class="animate-spin h-5 w-5 mx-auto" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>`;

        try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await response.json();

            if (data.success) {
                updateCartBadge(data);
                btn.classList.remove('bg-primary');
                btn.classList.add('bg-accent');
                btn.innerHTML = `✅ Added`;
                setTimeout(() => {
                    btn.classList.remove('bg-accent');
                    btn.classList.add('bg-primary');
                    btn.innerHTML = originalHtml;
                    btn.disabled = false;
                    btn.style.width = '';
                }, 2000);
            }
        } catch (err) {
            btn.innerHTML = originalHtml;
            btn.disabled = false;
            btn.style.width = '';
        }
    });

    /* ── 8. CART COUNT LIVE UPDATE ───────────────────────────── */
    function updateCartBadge(data) {
        const badge = document.getElementById('cartBadge'); // Mobile
        const badgeTop = document.getElementById('cartCountTop'); // Desktop
        const totalHeader = document.getElementById('cartTotalHeader'); // Desktop

        const count = data.count ?? 0;
        const total = data.total ?? '0.00 MAD';

        if (badge) badge.textContent = count;
        if (badgeTop) badgeTop.textContent = count;
        if (totalHeader) totalHeader.textContent = total;
    }

    // Poll cart count unobtrusively every time page becomes visible
    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'visible') {
            fetch('/cart/count')
                .then(r => r.json())
                .then(d => updateCartBadge(d))
                .catch(() => { });
        }
    });


    /* ── 9. LAZY IMAGE LOADING ─────────────────────────────── */
    if ('IntersectionObserver' in window) {
        const imgObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    const src = img.dataset.src;
                    if (src) { img.src = src; img.removeAttribute('data-src'); }
                    imgObserver.unobserve(img);
                }
            });
        }, { rootMargin: '100px' });
        document.querySelectorAll('img[data-src]').forEach(img => imgObserver.observe(img));
    }

    /* ── 10. TOPBAR DYNAMIC UPDATES ───────────────────────────── */
    const locEl = document.getElementById('topbarLocation');
    const timeEl = document.getElementById('topbarDateTime');
    const locale = document.documentElement.lang || 'en';

    // 10.1 Live Clock
    function updateClock() {
        if (!timeEl) return;
        const now = new Date();
        const options = {
            weekday: 'short',
            day: 'numeric',
            month: 'short',
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        };
        try {
            const formatter = new Intl.DateTimeFormat(locale, options);
            timeEl.textContent = formatter.format(now);
        } catch (err) {
            timeEl.textContent = now.toLocaleString();
        }
    }
    if (timeEl) {
        updateClock();
        setInterval(updateClock, 30000); // update every 30s
    }

    // 10.2 Auto-detect Location
    async function detectLocation() {
        if (!locEl) return;
        try {
            // Use IP-API for quick IP-based geo-detection without permission prompt
            const res = await fetch('http://ip-api.com/json/');
            const data = await res.json();
            if (data && data.status === 'success') {
                locEl.innerHTML = `📍 ${data.city}, ${data.country}`;
            } else {
                // Fallback to static if API fails
                locEl.innerHTML = `📍 Akka, Morocco`;
            }
        } catch (err) {
            // Silent fallback
            locEl.innerHTML = `📍 Akka, Morocco`;
        }
    }
    if (locEl) detectLocation();

});

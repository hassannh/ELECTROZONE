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
    const searchInput = document.getElementById('searchInput');
    const searchDropdown = document.getElementById('searchDropdown');
    const searchResults = document.getElementById('searchResults');
    const autocompleteUrl = document.querySelector('meta[name="autocomplete-url"]')?.content;
    const searchForm = document.getElementById('searchForm');

    let debounceTimer;

    function clearDropdown() {
        searchDropdown?.classList.remove('open');
        if (searchResults) searchResults.innerHTML = '';
    }

    searchInput?.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        const q = searchInput.value.trim();
        if (q.length < 2) { clearDropdown(); return; }

        debounceTimer = setTimeout(async () => {
            try {
                const res = await fetch(`${autocompleteUrl}?q=${encodeURIComponent(q)}`);
                const data = await res.json();

                if (!searchResults) return;

                if (data.length === 0) {
                    searchResults.innerHTML = `<div class="search-no-results">لا توجد نتائج / No results found</div>`;
                } else {
                    const isRtl = document.documentElement.dir === 'rtl';
                    searchResults.innerHTML = data.map(item => `
                        <a class="search-result-item" href="${item.url}">
                            <div>
                                <div class="search-result-name">${item.name}</div>
                                <div class="search-result-brand">${item.brand}</div>
                            </div>
                            <div class="search-result-price">${item.price}</div>
                        </a>
                    `).join('')
                        + `<a class="search-view-all" href="${searchForm?.action}?query=${encodeURIComponent(q)}">
                        ${isRtl ? 'عرض كل النتائج ←' : 'View all results →'}
                       </a>`;
                }
                searchDropdown?.classList.add('open');
            } catch (err) {
                clearDropdown();
            }
        }, 280);
    });

    // Close dropdown on outside click
    document.addEventListener('click', e => {
        if (!searchInput?.contains(e.target) && !searchDropdown?.contains(e.target)) {
            clearDropdown();
        }
    });

    // Keyboard navigation in dropdown
    searchInput?.addEventListener('keydown', e => {
        if (e.key === 'Escape') clearDropdown();
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


    /* ── 8. CART COUNT LIVE UPDATE ───────────────────────────── */
    function updateCartBadge(count) {
        const badge = document.getElementById('cartBadge');
        const countTop = document.getElementById('cartCountTop');
        if (badge) badge.textContent = count;
        if (countTop) countTop.textContent = count;
    }

    // Poll cart count unobtrusively every time page becomes visible
    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'visible') {
            fetch('/cart/count')
                .then(r => r.json())
                .then(d => updateCartBadge(d.count ?? 0))
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

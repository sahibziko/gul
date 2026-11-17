/* Flower.az — ümumi JS: səbət, slider, axtarış, sevimlilər, mağaza filtrləri */
(() => {
    'use strict';
    const $ = (s, r = document) => r.querySelector(s);
    const $$ = (s, r = document) => [...r.querySelectorAll(s)];
    const store = {
        get(k, d) { try { return JSON.parse(localStorage.getItem(k)) ?? d; } catch { return d; } },
        set(k, v) { try { localStorage.setItem(k, JSON.stringify(v)); } catch { /* private mode */ } },
    };
    const P = (window.FLOWER && window.FLOWER.palettes) || {};
    const fmt = n => (Math.round(n * 100) / 100).toString().replace(/\.0+$/, '') + ' AZN';
    const norm = s => s.toLowerCase().replace(/ə/g, 'e').replace(/ı/g, 'i').replace(/ö/g, 'o').replace(/ü/g, 'u').replace(/ş/g, 's').replace(/ç/g, 'c').replace(/ğ/g, 'g');
    const esc = s => String(s).replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));

    /* ---------- Toast ---------- */
    let toastT;
    function toast(msg) {
        const t = $('#toast'); if (!t) return;
        t.textContent = msg; t.classList.add('is-show');
        clearTimeout(toastT); toastT = setTimeout(() => t.classList.remove('is-show'), 2400);
    }
    window.flowerToast = toast;

    /* ---------- Header ---------- */
    const header = $('#siteHeader');
    const onScroll = () => header && header.classList.toggle('is-scrolled', scrollY > 10);
    addEventListener('scroll', onScroll, { passive: true }); onScroll();

    const menuBtn = $('#menuBtn'), nav = $('#mainNav');
    menuBtn && menuBtn.addEventListener('click', () => {
        const open = nav.classList.toggle('is-open');
        menuBtn.setAttribute('aria-expanded', open);
    });
    nav && $$('a', nav).forEach(a => a.addEventListener('click', () => nav.classList.remove('is-open')));

    /* ---------- Axtarış ---------- */
    const sInput = $('#searchInput'), sBox = $('#searchResults');
    if (sInput && sBox && window.FLOWER) {
        let hl = -1;
        const render = () => {
            const q = norm(sInput.value.trim());
            if (!q) { sBox.hidden = true; return; }
            const hits = window.FLOWER.index.filter(i => norm(i.n).includes(q)).slice(0, 7);
            sBox.innerHTML = hits.length
                ? hits.map(h => `<a href="${esc(h.u)}"><b>${esc(h.n)}</b><small>${h.t === 's' ? 'Mağaza' : 'Məhsul'} · ${esc(h.m)}</small></a>`).join('')
                : '<div class="none">Nəticə tapılmadı</div>';
            sBox.hidden = false; hl = -1;
        };
        sInput.addEventListener('input', render);
        sInput.addEventListener('focus', render);
        sInput.addEventListener('keydown', e => {
            const links = $$('a', sBox);
            if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                e.preventDefault();
                if (!links.length) return;
                hl = (hl + (e.key === 'ArrowDown' ? 1 : -1) + links.length) % links.length;
                links.forEach((l, i) => l.classList.toggle('is-hl', i === hl));
            } else if (e.key === 'Enter' && hl > -1) { e.preventDefault(); location.href = links[hl].href; }
            else if (e.key === 'Escape') sBox.hidden = true;
        });
        document.addEventListener('click', e => { if (!e.target.closest('#searchForm')) sBox.hidden = true; });
    }

    /* ---------- Səbət ---------- */
    let cart = store.get('flowerCart', []);
    const cartEl = $('#cart'), overlay = $('#overlay');
    const views = { view: $('#cartView'), checkout: $('#checkoutForm'), done: $('#cartDone') };

    function thumb(tone) {
        const pal = P[tone] || P.red || ['#ff4b5f', '#b3122b', '#4d0613'];
        const id = 't' + Math.random().toString(36).slice(2, 7);
        const c = (x, y, r) => `<circle cx="${x}" cy="${y}" r="${r}" fill="url(#${id})"/><circle cx="${x}" cy="${y}" r="${r * .5}" fill="none" stroke="${pal[2]}" stroke-opacity=".55" stroke-width="1.2"/>`;
        return `<svg viewBox="0 0 80 80"><defs><radialGradient id="${id}" cx="38%" cy="32%" r="78%"><stop offset="0" stop-color="${pal[0]}"/><stop offset=".6" stop-color="${pal[1]}"/><stop offset="1" stop-color="${pal[2]}"/></radialGradient></defs>`
            + `<path d="M12 34 L40 78 L68 34Z" fill="#1a1a1f"/>${c(40, 22, 14)}${c(24, 34, 12)}${c(56, 34, 12)}${c(40, 42, 12)}<path d="M12 34 L40 78 L26 36Z" fill="#0b0b0d"/><path d="M68 34 L40 78 L54 36Z" fill="#0b0b0d"/><circle cx="40" cy="62" r="4" fill="#e11d3f"/></svg>`;
    }

    function saveCart() { store.set('flowerCart', cart); renderCart(); }
    function renderCart() {
        const items = $('#cartItems'); if (!items) return;
        const count = cart.reduce((a, i) => a + i.qty, 0);
        const total = cart.reduce((a, i) => a + i.qty * i.price, 0);
        const badge = $('#cartCount');
        if (badge) { badge.textContent = count; badge.dataset.n = count; }
        items.innerHTML = cart.map(i => `
            <div class="ci" data-id="${i.id}">
                <div class="ci__img">${thumb(i.tone)}</div>
                <div>
                    <div class="ci__name">${esc(i.name)}</div>
                    <div class="ci__price">${fmt(i.price)}</div>
                    <div class="ci__qty">
                        <button type="button" data-act="dec" aria-label="Azalt"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M5 12h14"/></svg></button>
                        <span>${i.qty}</span>
                        <button type="button" data-act="inc" aria-label="Artır"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg></button>
                    </div>
                </div>
                <div class="ci__sum"><span>${fmt(i.price * i.qty)}</span><button type="button" class="ci__rm" data-act="rm">Sil</button></div>
            </div>`).join('');
        $('#cartTotal').textContent = fmt(total);
        $('#cartEmpty').hidden = cart.length > 0;
        $('#cartFoot').hidden = cart.length === 0;
        items.hidden = cart.length === 0;
    }
    function showView(name) { Object.entries(views).forEach(([k, el]) => el && (el.hidden = k !== name)); }
    function openCart() {
        showView('view'); renderCart();
        overlay.hidden = false; requestAnimationFrame(() => { overlay.classList.add('is-open'); cartEl.classList.add('is-open'); });
        cartEl.setAttribute('aria-hidden', 'false'); document.body.classList.add('no-scroll');
    }
    function closeCart() {
        overlay.classList.remove('is-open'); cartEl.classList.remove('is-open');
        cartEl.setAttribute('aria-hidden', 'true'); document.body.classList.remove('no-scroll');
        setTimeout(() => { overlay.hidden = true; }, 300);
    }
    function addToCart(d) {
        const ex = cart.find(i => i.id === d.id);
        ex ? ex.qty++ : cart.push({ ...d, qty: 1 });
        saveCart();
        const b = $('#cartCount'); b.classList.add('bump'); setTimeout(() => b.classList.remove('bump'), 250);
        toast(`“${d.name}” səbətə əlavə olundu`);
    }
    window.flowerAddToCart = addToCart;

    document.addEventListener('click', e => {
        const add = e.target.closest('.js-add');
        if (add) {
            addToCart({ id: add.dataset.id, name: add.dataset.name, price: parseFloat(add.dataset.price), tone: add.dataset.tone });
            const o = add.textContent; add.textContent = '✓ Əlavə olundu'; add.disabled = true;
            setTimeout(() => { add.textContent = o; add.disabled = false; }, 1100);
        }
    });
    $('#cartBtn') && $('#cartBtn').addEventListener('click', openCart);
    $('#cartClose') && $('#cartClose').addEventListener('click', closeCart);
    overlay && overlay.addEventListener('click', closeCart);
    addEventListener('keydown', e => { if (e.key === 'Escape' && cartEl && cartEl.classList.contains('is-open')) closeCart(); });
    $('#cartItems') && $('#cartItems').addEventListener('click', e => {
        const b = e.target.closest('[data-act]'); if (!b) return;
        const id = b.closest('.ci').dataset.id, it = cart.find(i => i.id === id); if (!it) return;
        if (b.dataset.act === 'inc') it.qty++;
        if (b.dataset.act === 'dec') it.qty = Math.max(0, it.qty - 1);
        if (b.dataset.act === 'rm') it.qty = 0;
        cart = cart.filter(i => i.qty > 0); saveCart();
    });
    $('#clearCart') && $('#clearCart').addEventListener('click', () => { cart = []; saveCart(); });
    $('#checkoutBtn') && $('#checkoutBtn').addEventListener('click', () => showView('checkout'));
    $('#backToCart') && $('#backToCart').addEventListener('click', () => showView('view'));
    $('#doneClose') && $('#doneClose').addEventListener('click', closeCart);
    $('#checkoutForm') && $('#checkoutForm').addEventListener('submit', e => {
        e.preventDefault();
        const n = 1025 + Math.floor(Math.random() * 400);
        $('#orderNo').textContent = '#' + n;
        cart = []; saveCart(); e.target.reset(); showView('done');
    });
    renderCart();

    /* ---------- Sevimlilər ---------- */
    let favs = new Set(store.get('flowerFavs', []));
    $$('.fav').forEach(b => {
        b.classList.toggle('is-on', favs.has(b.dataset.fav));
        b.addEventListener('click', e => {
            e.preventDefault(); e.stopPropagation();
            const k = b.dataset.fav, on = !favs.has(k);
            on ? favs.add(k) : favs.delete(k);
            store.set('flowerFavs', [...favs]);
            b.classList.toggle('is-on', on); b.classList.remove('pop'); void b.offsetWidth; b.classList.add('pop');
            toast(on ? 'Sevimlilərə əlavə olundu' : 'Sevimlilərdən çıxarıldı');
        });
    });

    /* ---------- Hero slider ---------- */
    const slides = $$('.slide');
    if (slides.length) {
        const dotsBox = $('.hero__dots'); let cur = 0, timer;
        slides.forEach((_, i) => { const d = document.createElement('button'); d.type = 'button'; d.setAttribute('aria-label', 'Slayd ' + (i + 1)); d.addEventListener('click', () => go(i, true)); dotsBox.appendChild(d); });
        const dots = $$('button', dotsBox);
        function go(i, user) {
            cur = (i + slides.length) % slides.length;
            slides.forEach((s, k) => s.classList.toggle('is-active', k === cur));
            dots.forEach((d, k) => d.classList.toggle('is-active', k === cur));
            if (user) restart();
        }
        function restart() { clearInterval(timer); timer = setInterval(() => go(cur + 1), 6000); }
        $('.hero__arrow.prev').addEventListener('click', () => go(cur - 1, true));
        $('.hero__arrow.next').addEventListener('click', () => go(cur + 1, true));
        let sx = null; const hero = $('.hero');
        hero.addEventListener('touchstart', e => { sx = e.touches[0].clientX; }, { passive: true });
        hero.addEventListener('touchend', e => { if (sx === null) return; const dx = e.changedTouches[0].clientX - sx; if (Math.abs(dx) > 50) go(cur + (dx < 0 ? 1 : -1), true); sx = null; });
        hero.addEventListener('mouseenter', () => clearInterval(timer)); hero.addEventListener('mouseleave', restart);
        go(0); restart();

        // yağan ləçəklər
        const petals = $('.petals');
        if (petals && !matchMedia('(prefers-reduced-motion: reduce)').matches) {
            for (let i = 0; i < 16; i++) {
                const p = document.createElement('i'); p.className = 'petal';
                p.style.left = Math.random() * 100 + '%'; p.style.setProperty('--dx', (Math.random() * 160 - 40) + 'px');
                p.style.animationDuration = 7 + Math.random() * 9 + 's'; p.style.animationDelay = -Math.random() * 14 + 's';
                p.style.transform = `scale(${.5 + Math.random() * .9})`; petals.appendChild(p);
            }
        }
    }

    /* ---------- Reveal ---------- */
    const rv = $$('.reveal');
    if ('IntersectionObserver' in window) {
        const io = new IntersectionObserver(es => es.forEach(en => {
            if (en.isIntersecting) { en.target.style.transitionDelay = (en.target.dataset.d || 0) + 'ms'; en.target.classList.add('is-in'); io.unobserve(en.target); setTimeout(() => { en.target.style.transitionDelay = ''; }, 900); }
        }), { threshold: .08, rootMargin: '0px 0px -30px 0px' });
        rv.forEach((el, i) => { el.dataset.d = (i % 4) * 70; io.observe(el); });
    } else rv.forEach(el => el.classList.add('is-in'));

    /* ---------- Newsletter ---------- */
    $('#newsletter') && $('#newsletter').addEventListener('submit', e => { e.preventDefault(); e.target.reset(); toast('Abunə oldunuz. Təşəkkürlər!'); });

    /* ---------- Mağazalar siyahısı: axtarış / şəhər / sıralama ---------- */
    const sl = $('#storeList');
    if (sl) {
        const cards = $$('.store', sl), qIn = $('#storeQ'), sort = $('#storeSort'), chips = $$('.chip[data-city]');
        const url = new URLSearchParams(location.search); let city = 'all';
        if (url.get('q')) qIn.value = url.get('q');
        function apply() {
            const q = norm(qIn.value.trim());
            let shown = cards.filter(c => (city === 'all' || c.dataset.city === city) && (!q || norm(c.dataset.name).includes(q)));
            const key = sort.value;
            shown.sort((a, b) => key === 'rating' ? b.dataset.rating - a.dataset.rating : key === 'name' ? a.dataset.name.localeCompare(b.dataset.name, 'az') : b.dataset.premium - a.dataset.premium);
            cards.forEach(c => c.style.display = 'none');
            shown.forEach(c => { c.style.display = ''; sl.appendChild(c); c.classList.add('is-in'); });
            $('#storeEmpty').hidden = shown.length > 0;
            $('#storeCount').textContent = shown.length;
        }
        qIn.addEventListener('input', apply); sort.addEventListener('change', apply);
        chips.forEach(c => c.addEventListener('click', () => { chips.forEach(x => x.classList.remove('is-on')); c.classList.add('is-on'); city = c.dataset.city; apply(); }));
        apply();
    }

    /* ---------- Mağaza profili: tab, kateqoriya, sıralama, izləmə ---------- */
    const tabs = $$('.tab');
    if (tabs.length) {
        const go = name => { tabs.forEach(t => t.classList.toggle('is-active', t.dataset.tab === name)); $$('.tabpanel').forEach(p => p.classList.toggle('is-active', p.id === 'tab-' + name)); };
        tabs.forEach(t => t.addEventListener('click', () => go(t.dataset.tab)));
        const sideBtns = $$('.side button'), prods = $$('#prodGrid .product'), sortSel = $('#prodSort');
        function filt() {
            const on = $('.side button.is-on'); const cat = on ? on.dataset.cat : 'all'; let n = 0;
            prods.forEach(p => { const ok = cat === 'all' || p.dataset.cat === cat; p.style.display = ok ? '' : 'none'; if (ok) { n++; p.classList.add('is-in'); } });
            $('#prodEmpty').hidden = n > 0;
        }
        sideBtns.forEach(b => b.addEventListener('click', () => { sideBtns.forEach(x => x.classList.remove('is-on')); b.classList.add('is-on'); filt(); }));
        sortSel && sortSel.addEventListener('change', () => {
            const g = $('#prodGrid'), v = sortSel.value;
            [...prods].sort((a, b) => v === 'low' ? a.dataset.price - b.dataset.price : v === 'high' ? b.dataset.price - a.dataset.price : a.dataset.id - b.dataset.id).forEach(p => g.appendChild(p));
        });
        const fb = $('#followBtn');
        if (fb) {
            const key = 'follow-' + fb.dataset.store; let on = !!store.get(key, false);
            const paint = () => { fb.textContent = on ? '✓ İzlənilir' : 'İzləmək'; fb.classList.toggle('is-following', on); };
            fb.addEventListener('click', () => { on = !on; store.set(key, on); paint(); toast(on ? 'Mağazanı izləyirsiniz' : 'İzləmə dayandırıldı'); }); paint();
        }
        if (location.hash.startsWith('#p')) {
            const el = $('[data-id="' + location.hash.slice(2) + '"]');
            if (el) setTimeout(() => { el.classList.add('is-in', 'is-flash'); el.scrollIntoView({ behavior: 'smooth', block: 'center' }); }, 300);
        }
        filt();
    }

    /* ---------- Say sayğacı ---------- */
    $$('[data-count]').forEach(el => {
        const end = parseFloat(el.dataset.count), dec = parseInt(el.dataset.dec || 0, 10), suf = el.dataset.suffix || '';
        const t0 = performance.now(), dur = 1200;
        const step = t => { const k = Math.min(1, (t - t0) / dur), v = end * (1 - Math.pow(1 - k, 3)); el.textContent = v.toLocaleString('en-US', { minimumFractionDigits: dec, maximumFractionDigits: dec }) + suf; if (k < 1) requestAnimationFrame(step); };
        requestAnimationFrame(step);
    });

    /* ---------- Əlaqə formu ---------- */
    $('#contactForm') && $('#contactForm').addEventListener('submit', e => { e.preventDefault(); e.target.reset(); toast('Mesajınız göndərildi. Tezliklə cavab verəcəyik.'); });
})();

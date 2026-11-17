/* Flower.az — satıcı paneli JS: bölmələr, gəlir qrafiki, məhsul əlavəsi, status dəyişmə */
(() => {
    'use strict';
    const $ = (s, r = document) => r.querySelector(s);
    const $$ = (s, r = document) => [...r.querySelectorAll(s)];
    const P = (window.FLOWER && window.FLOWER.palettes) || {};
    const esc = s => String(s).replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));

    let tT;
    const toast = m => { const t = $('#toast'); t.textContent = m; t.classList.add('is-show'); clearTimeout(tT); tT = setTimeout(() => t.classList.remove('is-show'), 2300); };

    /* ---------- Bölmələr ---------- */
    const titles = { dashboard: 'İdarə Paneli', mehsullar: 'Məhsullar', sifarisler: 'Sifarişlər', profil: 'Mağaza Profili', analitika: 'Analitika', bildirisler: 'Bildirişlər', parametrler: 'Parametrlər' };
    function show(id) {
        if (!titles[id]) id = 'dashboard';
        $$('.dsec').forEach(s => s.classList.toggle('is-active', s.id === 'sec-' + id));
        $$('.dash__side .item[data-sec]').forEach(a => a.classList.toggle('is-on', a.dataset.sec === id));
        $('#dashTitle').textContent = titles[id];
        $('#dashSide').classList.remove('is-open');
        if (id === 'dashboard') drawChart();
        scrollTo({ top: 0 });
    }
    $$('[data-sec]').forEach(a => a.addEventListener('click', e => { e.preventDefault(); history.replaceState(null, '', '#' + a.dataset.sec); show(a.dataset.sec); }));
    $('#dashMenu').addEventListener('click', () => $('#dashSide').classList.toggle('is-open'));

    /* ---------- Gəlir qrafiki ---------- */
    const chartEl = $('#chart'), rangeSel = $('#chartRange');
    function series(n) {
        // deterministik "təsadüfi" məlumat
        let s = 7 + n, out = [], v = 6200;
        const rnd = () => (s = (s * 9301 + 49297) % 233280) / 233280;
        for (let i = 0; i < n; i++) { v += (rnd() - .42) * 1900 + 160; v = Math.max(1500, Math.min(16800, v)); out.push(Math.round(v / 10) * 10); }
        out[n - 1] = 12450;
        return out;
    }
    function smooth(pts) {
        let d = `M${pts[0][0]},${pts[0][1]}`;
        for (let i = 0; i < pts.length - 1; i++) {
            const p0 = pts[i - 1] || pts[i], p1 = pts[i], p2 = pts[i + 1], p3 = pts[i + 2] || p2, t = .18;
            d += ` C${p1[0] + (p2[0] - p0[0]) * t},${p1[1] + (p2[1] - p0[1]) * t} ${p2[0] - (p3[0] - p1[0]) * t},${p2[1] - (p3[1] - p1[1]) * t} ${p2[0]},${p2[1]}`;
        }
        return d;
    }
    function drawChart() {
        if (!chartEl || !chartEl.offsetWidth) return;
        const n = parseInt(rangeSel.value, 10), data = series(n);
        const W = chartEl.clientWidth, H = chartEl.clientHeight, L = 44, R = 16, T = 18, B = 26;
        const max = 17000, x = i => L + (W - L - R) * i / (n - 1), y = v => T + (H - T - B) * (1 - v / max);
        const pts = data.map((v, i) => [x(i), y(v)]);
        const line = smooth(pts), area = `${line} L${x(n - 1)},${H - B} L${x(0)},${H - B}Z`;
        const grid = [0, 5000, 10000, 15000].map(v => `<line x1="${L}" x2="${W - R}" y1="${y(v)}" y2="${y(v)}" stroke="rgba(255,255,255,.07)"/><text x="${L - 10}" y="${y(v) + 4}" text-anchor="end" fill="#8d898d" font-size="11">${v ? v / 1000 + 'K' : '0K'}</text>`).join('');
        const step = Math.ceil(n / 6);
        const xl = data.map((_, i) => i % step === 0 || i === n - 1 ? `<text x="${x(i)}" y="${H - 6}" text-anchor="middle" fill="#8d898d" font-size="11">${n - 1 - i === 0 ? 'Bu gün' : (n - 1 - i) + ' g'}</text>` : '').join('');
        chartEl.innerHTML = `<svg viewBox="0 0 ${W} ${H}" role="img" aria-label="Gəlir qrafiki">
            <defs><linearGradient id="ga" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#e11d3f" stop-opacity=".38"/><stop offset="1" stop-color="#e11d3f" stop-opacity="0"/></linearGradient></defs>
            ${grid}${xl}
            <path d="${area}" fill="url(#ga)" opacity="0"><animate attributeName="opacity" from="0" to="1" dur=".9s" fill="freeze"/></path>
            <path id="gl" d="${line}" fill="none" stroke="#e11d3f" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/>
            <line id="gx" y1="${T}" y2="${H - B}" stroke="rgba(255,255,255,.2)" stroke-dasharray="4 4" opacity="0"/>
            <circle id="gd" r="5.5" fill="#09090b" stroke="#ff4d62" stroke-width="2.4" cx="${pts[n - 1][0]}" cy="${pts[n - 1][1]}"/>
        </svg><div class="tip" id="gt" style="left:${pts[n - 1][0]}px;top:${pts[n - 1][1]}px;opacity:1">12,450 AZN</div>`;
        const path = $('#gl', chartEl), len = path.getTotalLength();
        path.style.strokeDasharray = len; path.style.strokeDashoffset = len;
        path.animate([{ strokeDashoffset: len }, { strokeDashoffset: 0 }], { duration: 1100, easing: 'cubic-bezier(.22,.8,.24,1)', fill: 'forwards' });

        const dot = $('#gd', chartEl), tip = $('#gt', chartEl), gx = $('#gx', chartEl);
        chartEl.onmousemove = e => {
            const r = chartEl.getBoundingClientRect(), mx = e.clientX - r.left;
            const i = Math.max(0, Math.min(n - 1, Math.round((mx - L) / (W - L - R) * (n - 1))));
            dot.setAttribute('cx', pts[i][0]); dot.setAttribute('cy', pts[i][1]);
            gx.setAttribute('x1', pts[i][0]); gx.setAttribute('x2', pts[i][0]); gx.style.opacity = 1;
            tip.style.left = pts[i][0] + 'px'; tip.style.top = pts[i][1] + 'px'; tip.style.opacity = 1;
            tip.textContent = data[i].toLocaleString('en-US') + ' AZN';
        };
        chartEl.onmouseleave = () => { gx.style.opacity = 0; dot.setAttribute('cx', pts[n - 1][0]); dot.setAttribute('cy', pts[n - 1][1]); tip.style.left = pts[n - 1][0] + 'px'; tip.style.top = pts[n - 1][1] + 'px'; tip.textContent = '12,450 AZN'; };
    }
    rangeSel.addEventListener('change', drawChart);
    let rz; addEventListener('resize', () => { clearTimeout(rz); rz = setTimeout(drawChart, 150); });

    /* ---------- Say sayğacı ---------- */
    $$('[data-count]').forEach(el => {
        const end = parseFloat(el.dataset.count), dec = parseInt(el.dataset.dec || 0, 10), t0 = performance.now();
        const step = t => { const k = Math.min(1, (t - t0) / 1200), v = end * (1 - Math.pow(1 - k, 3)); el.textContent = v.toLocaleString('en-US', { minimumFractionDigits: dec, maximumFractionDigits: dec }); if (k < 1) requestAnimationFrame(step); };
        requestAnimationFrame(step);
    });

    /* ---------- Məhsul əlavə et / sil ---------- */
    const modal = $('#prodModal'), rows = $('#prodRows'), cnt = $('#prodCount');
    const catNames = { qizilgul: 'Qızılgül', lale: 'Lalə', orxideya: 'Orxideya', suni: 'Süni Güllər', kompozisiya: 'Kompozisiyalar', diger: 'Digər' };
    function thumb(tone) {
        const pal = P[tone] || P.red, id = 't' + Math.random().toString(36).slice(2, 7);
        const c = (x, y, r) => `<circle cx="${x}" cy="${y}" r="${r}" fill="url(#${id})"/>`;
        return `<svg viewBox="0 0 80 80"><defs><radialGradient id="${id}" cx="38%" cy="32%" r="78%"><stop offset="0" stop-color="${pal[0]}"/><stop offset=".6" stop-color="${pal[1]}"/><stop offset="1" stop-color="${pal[2]}"/></radialGradient></defs><path d="M12 34 L40 78 L68 34Z" fill="#1a1a1f"/>${c(40, 22, 14)}${c(24, 34, 12)}${c(56, 34, 12)}${c(40, 42, 12)}<path d="M12 34 L40 78 L26 36Z" fill="#0b0b0d"/><path d="M68 34 L40 78 L54 36Z" fill="#0b0b0d"/></svg>`;
    }
    $('#addProdBtn').addEventListener('click', () => { modal.hidden = false; $('#prodForm [name=name]').focus(); });
    $('#prodCancel').addEventListener('click', () => { modal.hidden = true; });
    modal.addEventListener('click', e => { if (e.target === modal) modal.hidden = true; });
    addEventListener('keydown', e => { if (e.key === 'Escape') modal.hidden = true; });
    $('#prodForm').addEventListener('submit', e => {
        e.preventDefault();
        const f = new FormData(e.target), tr = document.createElement('tr');
        tr.innerHTML = `<td><div class="cell-prod"><div class="thumb">${thumb(f.get('tone'))}</div>${esc(f.get('name'))}</div></td><td class="muted">${esc(catNames[f.get('cat')] || '')}</td><td>${esc(f.get('price'))} AZN</td><td>${esc(f.get('stock'))}</td><td><span class="pill green">Aktiv</span></td><td><button class="btn btn-ghost btn-sm js-del" type="button">Sil</button></td>`;
        rows.prepend(tr); cnt.textContent = rows.children.length;
        e.target.reset(); modal.hidden = true; toast('Məhsul əlavə olundu (demo: səhifə yenilənəndə silinir)');
    });
    rows.addEventListener('click', e => {
        const b = e.target.closest('.js-del'); if (!b) return;
        b.closest('tr').remove(); cnt.textContent = rows.children.length; toast('Məhsul silindi');
    });

    /* ---------- Sifariş statusu ---------- */
    $$('.status-sel').forEach(s => s.addEventListener('change', () => toast('Status yeniləndi: ' + s.value)));

    /* ---------- Digər ---------- */
    $('#profileForm').addEventListener('submit', e => { e.preventDefault(); toast('Profil yadda saxlanıldı'); });
    $('#markRead').addEventListener('click', () => { $$('.notif').forEach(n => n.classList.add('read')); toast('Hamısı oxunmuş kimi işarələndi'); });
    $$('.switch input').forEach(i => i.addEventListener('change', () => toast(i.checked ? 'Aktiv edildi' : 'Deaktiv edildi')));

    show(location.hash.slice(1) || 'dashboard');
    addEventListener('hashchange', () => show(location.hash.slice(1)));
})();

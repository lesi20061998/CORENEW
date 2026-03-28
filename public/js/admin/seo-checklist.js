window.SEOCheck = (function () {

    function switchTab(id, tab) {
        document.querySelectorAll('#' + id + '_wrap .seo-tab').forEach(function (t) {
            t.classList.toggle('active', t.dataset.tab === tab);
        });
        document.querySelectorAll('#' + id + '_wrap .seo-tab-panel').forEach(function (p) {
            p.classList.toggle('hidden', !p.id.endsWith('_tab_' + tab));
        });
    }

    function run(id) {
        var wrap = document.getElementById(id + '_wrap');
        if (!wrap) return;

        var kwEl = document.getElementById(id + '_keyword');
        var kw = kwEl ? kwEl.value.trim().toLowerCase() : '';

        var kwCount = document.getElementById(id + '_kw_count');
        if (kwCount) kwCount.textContent = kw.length + '/100';

        var kwOut = document.getElementById(id + '_kw_out');
        if (kwOut) kwOut.value = kw;

        var slugEl      = document.querySelector('[name="slug"]');
        var metaTitleEl = document.querySelector('[name="meta_title"]');
        var metaDescEl  = document.querySelector('[name="meta_description"]');

        var slug      = slugEl      ? slugEl.value.trim()      : '';
        var metaTitle = metaTitleEl ? metaTitleEl.value.trim() : '';
        var metaDesc  = metaDescEl  ? metaDescEl.value.trim()  : '';

        var contentText = '', contentHtml = '';
        var editorEl = document.querySelector('.vtmed-content');
        if (editorEl) {
            contentHtml = editorEl.innerHTML;
            contentText = editorEl.innerText || editorEl.textContent || '';
        }
        var wordCount = contentText.trim() ? contentText.trim().split(/\s+/).length : 0;

        var parser = new DOMParser();
        var doc = parser.parseFromString(contentHtml, 'text/html');
        var imgs = doc.querySelectorAll('img');

        var imgWithKwAlt = 0;
        imgs.forEach(function (img) {
            if (kw && (img.alt || '').toLowerCase().includes(kw)) imgWithKwAlt++;
        });

        var internalLinks = doc.querySelectorAll('a[href]').length;

        var headings = doc.querySelectorAll('h2,h3,h4');
        var headingWithKw = 0;
        headings.forEach(function (h) {
            if (kw && h.textContent.toLowerCase().includes(kw)) headingWithKw++;
        });

        var kwDensity = 0;
        if (kw && wordCount > 0) {
            var escaped = kw.split(/\s+/).map(function (w) {
                return w.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            }).join('\\s+');
            var re = new RegExp(escaped, 'gi');
            var m = contentText.match(re);
            kwDensity = m ? Math.round((m.length / wordCount) * 1000) / 10 : 0;
        }

        var paras = doc.querySelectorAll('p');
        var longParas = 0;
        paras.forEach(function (p) {
            if (p.textContent.trim().split(/\s+/).length > 150) longParas++;
        });

        var allChecks = {
            seo_check_kw_set:           { label: 'Đặt Từ khóa tập trung cho nội dung này.', pass: kw.length > 0 },
            seo_check_kw_in_title:      { label: 'Thêm Từ khóa chính vào tiêu đề SEO.', pass: !!(kw && metaTitle.toLowerCase().includes(kw)) },
            seo_check_kw_title_start:   { label: 'Sử dụng từ khóa chính gần đầu tiêu đề SEO.', pass: !!(kw && metaTitle.length > 0 && metaTitle.toLowerCase().indexOf(kw) < 20) },
            seo_check_title_length:     { label: 'Tiêu đề ' + metaTitle.length + ' ký tự' + (metaTitle.length === 0 ? ' (ngắn). Cố gắng có được 70 ký tự' : metaTitle.length < 40 ? ' (ngắn). Cố gắng có được 70 ký tự' : metaTitle.length > 70 ? ' (dài). Nên dưới 70 ký tự' : ' (tốt).'), pass: metaTitle.length >= 40 && metaTitle.length <= 70 },
            seo_check_kw_in_meta:       { label: 'Thêm Từ khóa tập trung vào Mô tả meta SEO của bạn.', pass: !!(kw && metaDesc.toLowerCase().includes(kw)) },
            seo_check_meta_desc_length: { label: 'Mô tả meta SEO có ' + metaDesc.length + ' ký tự' + (metaDesc.length === 0 ? ' (ngắn). Cố gắng thành 160 ký tự' : metaDesc.length < 120 ? ' (ngắn). Cố gắng thành 160 ký tự' : metaDesc.length > 160 ? ' (dài). Nên dưới 160 ký tự' : ' (tốt).'), pass: metaDesc.length >= 120 && metaDesc.length <= 160 },
            seo_check_kw_in_url:        { label: 'Sử dụng từ khóa chính trong URL.', pass: !!(kw && slug.toLowerCase().includes(kw.replace(/\s+/g, '-'))) },
            seo_check_url_length:       { label: 'Url có ' + slug.length + ' ký tự' + (slug.length > 75 ? ' (dài). Nên dưới 75 ký tự.' : slug.length === 0 ? ' (ngắn).' : ' (tốt).'), pass: slug.length > 0 && slug.length <= 75 },
            seo_check_kw_content_start: { label: 'Sử dụng từ khóa chính ở đầu nội dung của bạn.', pass: !!(kw && contentText.toLowerCase().substring(0, 200).includes(kw)) },
            seo_check_kw_in_content:    { label: 'Sử dụng từ khóa chính trong nội dung.', pass: !!(kw && contentText.toLowerCase().includes(kw)) },
            seo_check_word_count:       { label: 'Nội dung phải dài 600-2500 từ. (Hiện tại: ' + wordCount + ' từ)', pass: wordCount >= 600 && wordCount <= 2500 },
            seo_check_internal_links:   { label: 'Thêm liên kết nội bộ vào nội dung của bạn.', pass: internalLinks > 0 },
            seo_check_kw_in_headings:   { label: 'Sử dụng từ khóa chính trong (các) tiêu đề phụ như H2, H3, H4, v.v..', pass: headingWithKw > 0 },
            seo_check_kw_in_alt:        { label: 'Thêm từ khóa vào thuộc tính alt của hình ảnh.', pass: imgWithKwAlt > 0 },
            seo_check_kw_density:       { label: 'Mật độ từ khóa là ' + kwDensity + '%. Nhắm đến khoảng 1% Mật độ từ khóa.', pass: kwDensity >= 0.5 && kwDensity <= 2.5 },
            seo_check_short_paras:      { label: 'Thêm các đoạn văn ngắn và súc tích để dễ đọc và UX hơn.', pass: longParas === 0 && paras.length > 0 },
            seo_check_has_image:        { label: 'Thêm một vài hình ảnh để làm cho nội dung của bạn hấp dẫn.', pass: imgs.length > 0 },
        };

        var enabledKeys = JSON.parse(wrap.dataset.keys || '[]');
        var html = '';
        enabledKeys.forEach(function (key) {
            var c = allChecks[key];
            if (!c) return;
            html += '<div class="seo-check-item">'
                + '<span class="seo-check-icon ' + (c.pass ? 'pass' : 'fail') + '">'
                + (c.pass ? '<i class="fa-solid fa-check"></i>' : '<i class="fa-solid fa-xmark"></i>')
                + '</span><span>' + c.label + '</span></div>';
        });

        var listEl = document.getElementById(id + '_checklist');
        if (listEl) listEl.innerHTML = html || '<p class="text-xs text-gray-400">Không có tiêu chí nào được bật.</p>';
    }

    function init(id) {
        ['title', 'name', 'slug', 'meta_title', 'meta_description'].forEach(function (n) {
            var el = document.querySelector('[name="' + n + '"]');
            if (el) el.addEventListener('input', function () { run(id); });
        });

        var observer = new MutationObserver(function () { run(id); });
        document.querySelectorAll('.vtmed-content').forEach(function (el) {
            observer.observe(el, { childList: true, subtree: true, characterData: true });
        });

        run(id);
    }

    return { switchTab: switchTab, run: run, init: init };
})();

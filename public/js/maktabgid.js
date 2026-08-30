(function () {
    "use strict";

    var CAT_LABEL = { maktab: "maktab", bogcha: "bogʻcha", markaz: "oʻquv markazi", mutaxassis: "mutaxassis" };

    /* ---------------- shared: favorite heart toggle (real API, backend.md Phase 4) ---------------- */
    function csrfToken() {
        var meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.content : "";
    }

    function jsonFetch(url, method, data) {
        var isFormData = typeof FormData !== "undefined" && data instanceof FormData;
        var headers = { "Accept": "application/json", "X-CSRF-TOKEN": csrfToken() };
        if (!isFormData) headers["Content-Type"] = "application/json";

        return fetch(url, {
            method: method,
            headers: headers,
            body: data === undefined ? undefined : (isFormData ? data : JSON.stringify(data)),
        }).then(function (res) {
            return res.json().catch(function () { return {}; }).then(function (body) {
                return { ok: res.ok, status: res.status, body: body };
            });
        });
    }

    document.addEventListener("click", function (e) {
        var fav = e.target.closest(".js-fav");
        if (!fav) return;
        e.stopPropagation();

        var card = fav.closest("[data-id]");
        var institutionId = card ? card.dataset.id : null;

        if (!institutionId) {
            fav.classList.toggle("on");
            return;
        }

        var willBeOn = !fav.classList.contains("on");
        var url = "/ajax/favorites/" + institutionId;

        jsonFetch(url, willBeOn ? "POST" : "DELETE").then(function (res) {
            if (res.status === 401 || res.status === 403) {
                var openBtn = document.getElementById("js-kirish-btn");
                if (openBtn) openBtn.click();
                return;
            }
            if (res.ok) fav.classList.toggle("on", willBeOn);
        });
    });

    /* "Suhbat boshlash" (maktab profili sidebar) — mavjud suhbatni ochadi yoki
       yangisini yaratib, parent yoki teacher kabinetining Suhbatlar sahifasiga
       o'tkazadi. Redirect manzilini backend beradi (ConversationController@start,
       ADR-0003) — rol (parent/teacher) bilan mos sahifaga tushish uchun, JS
       o'zi yo'lni taxmin qilmaydi. */
    document.addEventListener("click", function (e) {
        var btn = e.target.closest(".js-start-chat-btn");
        if (!btn) return;

        var institutionId = btn.dataset.institutionId;
        if (!institutionId) return;

        btn.disabled = true;

        jsonFetch("/ajax/conversations", "POST", { institution_id: institutionId }).then(function (res) {
            btn.disabled = false;

            if (res.status === 401 || res.status === 403) {
                var openBtn = document.getElementById("js-kirish-btn");
                if (openBtn) openBtn.click();
                return;
            }

            if (res.ok && res.body && res.body.redirect) {
                window.location.href = res.body.redirect;
            }
        });
    });

    /* ===================== YANDEX MAPS ===================== */
    var YANDEX_CENTER = [41.311081, 69.240562];

    function initYandexResultsMap(mapEl, onReady, onPinClick) {
        if (!mapEl || !window.ymaps) return;
        ymaps.ready(function () {
            var schools = JSON.parse(mapEl.dataset.schools || "[]");
            var map = new ymaps.Map(mapEl, { center: YANDEX_CENTER, zoom: 11, controls: [] });
            var placemarks = {};

            schools.forEach(function (s) {
                var id = String(s.id);
                var placemark = new ymaps.Placemark(
                    [s.lat, s.lng],
                    { id: id, cat: s.cat, iconContent: s.price },
                    { preset: "islands#grayStretchyIcon", cursor: "pointer" }
                );
                placemark.events.add("click", function () { onPinClick(id); });
                placemarks[id] = placemark;
                map.geoObjects.add(placemark);
            });

            var zoomIn = document.getElementById("js-map-zoom-in");
            var zoomOut = document.getElementById("js-map-zoom-out");
            var locate = document.getElementById("js-map-locate");
            if (zoomIn) zoomIn.addEventListener("click", function () { map.setZoom(map.getZoom() + 1, { checkZoomRange: true }); });
            if (zoomOut) zoomOut.addEventListener("click", function () { map.setZoom(map.getZoom() - 1, { checkZoomRange: true }); });
            if (locate) locate.addEventListener("click", function () { map.setCenter(YANDEX_CENTER, 11); });

            onReady(placemarks);
        });
    }

    function initYandexSingleMap(mapEl) {
        if (!mapEl || !window.ymaps) return;
        var lat = parseFloat(mapEl.dataset.lat);
        var lng = parseFloat(mapEl.dataset.lng);
        if (isNaN(lat) || isNaN(lng)) return;

        ymaps.ready(function () {
            var map = new ymaps.Map(mapEl, { center: [lat, lng], zoom: 14, controls: [] });
            map.behaviors.disable(["drag", "scrollZoom"]);
            map.geoObjects.add(new ymaps.Placemark(
                [lat, lng],
                { iconContent: mapEl.dataset.label || "" },
                { preset: "islands#blackStretchyIcon" }
            ));
        });
    }

    initYandexSingleMap(document.getElementById("js-yandex-map-single"));

    /* ===================== DESKTOP RESULTS ===================== */
    var grid = document.getElementById("js-results-grid");
    if (grid) {
        var cards = Array.prototype.slice.call(document.querySelectorAll(".js-scard"));
        var pins = {};
        var pinsReady = false;
        var activePinId = null;

        function setPinPreset(id, preset) {
            if (pins[id]) pins[id].options.set("preset", preset);
        }

        initYandexResultsMap(
            document.getElementById("js-yandex-map"),
            function (placemarks) {
                pins = placemarks;
                pinsReady = true;
                render();
            },
            function (id) {
                if (activePinId) setPinPreset(activePinId, "islands#grayStretchyIcon");
                activePinId = id;
                setPinPreset(id, "islands#blueStretchyIcon");
            }
        );
        var cardList = document.getElementById("js-card-list");
        var emptyBox = document.getElementById("js-empty");
        var countEl = document.getElementById("js-results-count");
        var mapTag = document.getElementById("js-map-tag");
        var queryInput = document.getElementById("js-query");
        var districtSelect = document.getElementById("js-district");
        var sortSelect = document.getElementById("js-sort");
        var satSwitch = document.getElementById("js-filter-sat");
        var distanceRow = document.getElementById("js-filter-distance");
        var priceRow = document.getElementById("js-filter-price");
        var districtList = document.getElementById("js-filter-districts");
        var districtsClearBtn = document.getElementById("js-districts-clear");
        var resetBtn = document.getElementById("js-reset");
        var emptyResetBtn = document.getElementById("js-empty-reset");
        var searchGoBtn = document.getElementById("js-search-go");
        var catSelect = document.getElementById("js-cat-select");

        var DISTANCE_MAX = { "1": 1, "3": 3, "5": 5, "5+": Infinity };
        var PRICE_RANGE = {
            lt2: [0, 2000000], "2-3.5": [2000000, 3500000], "3.5-5": [3500000, 5000000],
            "5-7": [5000000, 7000000], "7+": [7000000, Infinity],
        };

        var STATE_DEFAULTS = { cat: "maktab", sort: "rel" };
        var state = { cat: "maktab", query: "", district: "", sat: false, distance: "", price: "", districts: [], sort: "rel", spec: "" };

        /* Sahifalash — har sahifada 20 tadan natija, istalgan sahifaga toʻgʻridan-toʻgʻri oʻtish mumkin. */
        var PAGE_SIZE = 20;
        var currentPage = 1;
        var paginationEl = document.getElementById("js-pagination");

        /* Qidiruv/filtr holati URL query-parametrlariga yoziladi (refreshdan keyin
           yoʻqolib qolmasin uchun) — sahifa qayta yuklanganda shu yerdan tiklanadi. */
        function restoreStateFromURL() {
            var params = new URLSearchParams(window.location.search);

            if (params.has("cat")) state.cat = params.get("cat");
            if (params.has("q")) {
                state.query = params.get("q");
                if (queryInput) queryInput.value = state.query;
            }
            if (params.has("district")) {
                state.district = params.get("district");
                if (districtSelect) districtSelect.value = state.district;
            }
            if (params.has("districts")) {
                state.districts = params.get("districts").split(",").filter(Boolean);
                if (districtList) districtList.querySelectorAll(".dist-item").forEach(function (item) {
                    item.classList.toggle("on", state.districts.indexOf(item.dataset.value) !== -1);
                });
                if (districtsClearBtn) districtsClearBtn.hidden = state.districts.length === 0;
            }
            if (params.has("sat")) {
                state.sat = params.get("sat") === "1";
                if (satSwitch) satSwitch.classList.toggle("on", state.sat);
            }
            if (params.has("distance")) {
                state.distance = params.get("distance");
                if (distanceRow) distanceRow.querySelectorAll(".chip").forEach(function (c) { c.classList.toggle("on", c.dataset.value === state.distance); });
            }
            if (params.has("price")) {
                state.price = params.get("price");
                if (priceRow) priceRow.querySelectorAll(".chip").forEach(function (c) { c.classList.toggle("on", c.dataset.value === state.price); });
            }
            if (params.has("sort")) {
                state.sort = params.get("sort");
                if (sortSelect) sortSelect.value = state.sort;
            }
            if (params.has("spec")) {
                state.spec = params.get("spec");
                document.querySelectorAll(".js-spec-tile").forEach(function (t) { t.classList.toggle("on", t.dataset.spec === state.spec); });
            }
            if (params.has("page")) currentPage = parseInt(params.get("page"), 10) || 1;
        }

        function syncURLFromState() {
            var params = new URLSearchParams();

            if (state.cat && state.cat !== STATE_DEFAULTS.cat) params.set("cat", state.cat);
            if (state.query) params.set("q", state.query);
            if (state.district) params.set("district", state.district);
            if (state.districts.length) params.set("districts", state.districts.join(","));
            if (state.sat) params.set("sat", "1");
            if (state.distance) params.set("distance", state.distance);
            if (state.price) params.set("price", state.price);
            if (state.sort && state.sort !== STATE_DEFAULTS.sort) params.set("sort", state.sort);
            if (state.spec) params.set("spec", state.spec);
            if (currentPage > 1) params.set("page", String(currentPage));

            var qs = params.toString();
            var url = window.location.pathname + (qs ? "?" + qs : "") + window.location.hash;
            window.history.replaceState(null, "", url);
        }

        function setActiveCatButtons() {
            document.querySelectorAll(".js-cat").forEach(function (b) {
                b.classList.toggle("on", b.dataset.cat === state.cat);
            });
            if (catSelect) catSelect.value = state.cat;
        }

        function applyFilters() {
            return cards.filter(function (card) {
                if (card.dataset.cat !== state.cat) return false;
                if (state.query && card.dataset.name.indexOf(state.query.toLowerCase()) === -1) return false;
                if (state.district && card.dataset.district !== state.district) return false;
                if (state.districts.length && state.districts.indexOf(card.dataset.district) === -1) return false;
                if (state.spec && (card.dataset.specs || "").split(",").indexOf(state.spec) === -1) return false;
                if (state.sat && card.dataset.sat !== "1") return false;
                if (state.distance) {
                    var max = DISTANCE_MAX[state.distance];
                    var d = parseFloat(card.dataset.dist);
                    if (state.distance === "5+") { if (d <= 5) return false; }
                    else if (d > max) return false;
                }
                if (state.price) {
                    var range = PRICE_RANGE[state.price];
                    var p = parseFloat(card.dataset.price);
                    if (!(p >= range[0] && p < range[1])) return false;
                }
                return true;
            });
        }

        function sortCards(list) {
            var sorters = {
                // "Tavsiya etiladi" — admin panelidan (admin/institutions) belgilangan
                // tartib (kichigi oldin), reyting emas.
                rel: function (a, b) { return parseFloat(a.dataset.order) - parseFloat(b.dataset.order); },
                priceA: function (a, b) { return parseFloat(a.dataset.price) - parseFloat(b.dataset.price); },
                priceD: function (a, b) { return parseFloat(b.dataset.price) - parseFloat(a.dataset.price); },
                dist: function (a, b) { return parseFloat(a.dataset.dist) - parseFloat(b.dataset.dist); },
                rating: function (a, b) { return parseFloat(b.dataset.rating) - parseFloat(a.dataset.rating); },
            };
            return list.slice().sort(sorters[state.sort] || sorters.rel);
        }

        function render() {
            // Filtr/toifa/saralash oʻzgarganda — yangi qidiruv, birinchi sahifadan boshlanadi.
            currentPage = 1;
            paint();
        }

        // Kartochka fon rasmini faqat u aynan koʻrsatilayotgan sahifada boʻlganda yuklaydi
        // (barchasini bir vaqtda ochish yuzlab parallel soʻrov yuboradi).
        function applyCardMedia(card) {
            var media = card.querySelector(".scard-media");
            if (media && media.dataset.bg && !media.dataset.bgApplied) {
                media.style.background = media.dataset.bg;
                media.dataset.bgApplied = "1";
            }
        }

        function scrollToResultsHead() {
            var resultsHead = document.getElementById("js-results-head");
            if (resultsHead) resultsHead.scrollIntoView({ behavior: "smooth", block: "start" });
        }

        function goToPage(page) {
            currentPage = page;
            paint();
            scrollToResultsHead();
        }

        function paginationButton(label, page, opts) {
            opts = opts || {};
            var btn = document.createElement("button");
            btn.type = "button";
            btn.textContent = label;
            if (opts.on) { btn.classList.add("on"); btn.setAttribute("aria-current", "page"); }
            if (opts.disabled) {
                btn.disabled = true;
            } else {
                btn.addEventListener("click", function () { goToPage(page); });
            }
            return btn;
        }

        function renderPagination(totalPages) {
            if (!paginationEl) return;
            paginationEl.innerHTML = "";
            if (totalPages <= 1) { paginationEl.style.display = "none"; return; }
            paginationEl.style.display = "";

            paginationEl.appendChild(paginationButton("‹", currentPage - 1, { disabled: currentPage === 1 }));

            var pages = [];
            for (var p = 1; p <= totalPages; p++) {
                if (p === 1 || p === totalPages || Math.abs(p - currentPage) <= 1) pages.push(p);
            }
            var prev = 0;
            pages.forEach(function (p) {
                if (prev && p - prev > 1) {
                    var dots = document.createElement("span");
                    dots.className = "pg-ellipsis";
                    dots.textContent = "…";
                    paginationEl.appendChild(dots);
                }
                paginationEl.appendChild(paginationButton(String(p), p, { on: p === currentPage }));
                prev = p;
            });

            paginationEl.appendChild(paginationButton("›", currentPage + 1, { disabled: currentPage === totalPages }));
        }

        function paint() {
            setActiveCatButtons();

            var matched = sortCards(applyFilters());
            var matchedIds = matched.map(function (c) { return c.dataset.id; });
            var totalPages = Math.max(1, Math.ceil(matched.length / PAGE_SIZE));
            if (currentPage > totalPages) currentPage = totalPages;
            var start = (currentPage - 1) * PAGE_SIZE;
            var shown = matched.slice(start, start + PAGE_SIZE);

            // DOM tartibi butun moslashgan roʻyxat boʻyicha saqlanadi (sahifa almashganda
            // qayta tartiblash shart boʻlmasin uchun) — faqat koʻrinish (display) sahifalanadi.
            cards.forEach(function (card) { card.style.display = "none"; });
            matched.forEach(function (card) { cardList.appendChild(card); });
            shown.forEach(function (card) {
                card.style.display = "";
                applyCardMedia(card);
            });

            if (pinsReady) {
                Object.keys(pins).forEach(function (id) {
                    pins[id].options.set("visible", matchedIds.indexOf(id) !== -1);
                });
            }

            countEl.textContent = matched.length + " ta " + CAT_LABEL[state.cat];
            if (mapTag) mapTag.lastChild.textContent = " " + matched.length + " ta natija xaritada";
            emptyBox.style.display = matched.length === 0 ? "" : "none";
            cardList.style.display = matched.length === 0 ? "none" : "";

            renderPagination(totalPages);
            syncURLFromState();
        }

        document.querySelectorAll(".js-cat").forEach(function (btn) {
            btn.addEventListener("click", function () {
                state.cat = btn.dataset.cat;
                render();
                scrollToResultsHead();
            });
        });

        if (catSelect) catSelect.addEventListener("change", function () { state.cat = catSelect.value; render(); });

        document.querySelectorAll(".js-spec-tile").forEach(function (tile) {
            tile.addEventListener("click", function () {
                var picked = tile.dataset.spec;
                state.spec = state.spec === picked ? "" : picked;
                document.querySelectorAll(".js-spec-tile").forEach(function (t) {
                    t.classList.toggle("on", t.dataset.spec === state.spec);
                });
                render();
                scrollToResultsHead();
            });
        });

        if (queryInput) queryInput.addEventListener("input", function () { state.query = queryInput.value.trim(); render(); });
        if (districtSelect) districtSelect.addEventListener("change", function () { state.district = districtSelect.value; render(); });
        if (sortSelect) sortSelect.addEventListener("change", function () { state.sort = sortSelect.value; render(); });
        if (searchGoBtn) searchGoBtn.addEventListener("click", scrollToResultsHead);

        if (satSwitch) satSwitch.addEventListener("click", function () {
            state.sat = !state.sat;
            satSwitch.classList.toggle("on", state.sat);
            render();
        });

        if (distanceRow) distanceRow.querySelectorAll(".chip").forEach(function (chip) {
            chip.addEventListener("click", function () {
                state.distance = state.distance === chip.dataset.value ? "" : chip.dataset.value;
                distanceRow.querySelectorAll(".chip").forEach(function (c) { c.classList.toggle("on", c.dataset.value === state.distance); });
                render();
            });
        });

        if (priceRow) priceRow.querySelectorAll(".chip").forEach(function (chip) {
            chip.addEventListener("click", function () {
                state.price = state.price === chip.dataset.value ? "" : chip.dataset.value;
                priceRow.querySelectorAll(".chip").forEach(function (c) { c.classList.toggle("on", c.dataset.value === state.price); });
                render();
            });
        });

        function syncDistrictsClear() {
            if (districtsClearBtn) districtsClearBtn.hidden = state.districts.length === 0;
        }

        if (districtList) districtList.querySelectorAll(".dist-item").forEach(function (item) {
            item.addEventListener("click", function () {
                var d = item.dataset.value;
                var idx = state.districts.indexOf(d);
                if (idx === -1) state.districts.push(d); else state.districts.splice(idx, 1);
                item.classList.toggle("on", idx === -1);
                syncDistrictsClear();
                render();
            });
        });

        if (districtsClearBtn) districtsClearBtn.addEventListener("click", function () {
            state.districts = [];
            if (districtList) districtList.querySelectorAll(".dist-item.on").forEach(function (i) { i.classList.remove("on"); });
            syncDistrictsClear();
            render();
        });

        function resetFilters() {
            state.sat = false; state.distance = ""; state.price = ""; state.districts = []; state.district = "";
            if (satSwitch) satSwitch.classList.remove("on");
            if (districtSelect) districtSelect.value = "";
            [distanceRow, priceRow].forEach(function (row) { if (row) row.querySelectorAll(".chip.on").forEach(function (c) { c.classList.remove("on"); }); });
            if (districtList) districtList.querySelectorAll(".dist-item.on").forEach(function (i) { i.classList.remove("on"); });
            syncDistrictsClear();
            render();
        }

        if (resetBtn) resetBtn.addEventListener("click", resetFilters);
        if (emptyResetBtn) emptyResetBtn.addEventListener("click", resetFilters);

        /* hover/active sync between cards and map pins */
        cards.forEach(function (card) {
            card.addEventListener("mouseenter", function () {
                setPinPreset(card.dataset.id, "islands#blackStretchyIcon");
            });
            card.addEventListener("mouseleave", function () {
                setPinPreset(card.dataset.id, card.dataset.id === activePinId ? "islands#blueStretchyIcon" : "islands#grayStretchyIcon");
            });
        });

        restoreStateFromURL();
        paint();
    }

    /* ===================== HERO SEARCH MODE (O'zim qidiraman / AI tanlab bersin) ===================== */
    var modeBtns = Array.prototype.slice.call(document.querySelectorAll(".js-mode-btn"));
    if (modeBtns.length) {
        var modePanels = Array.prototype.slice.call(document.querySelectorAll(".js-mode-panel"));

        function setSearchMode(mode) {
            modeBtns.forEach(function (b) { b.classList.toggle("on", b.dataset.mode === mode); });
            modePanels.forEach(function (p) { p.hidden = p.dataset.mode !== mode; });
        }

        modeBtns.forEach(function (btn) {
            btn.addEventListener("click", function () { setSearchMode(btn.dataset.mode); });
        });

        document.querySelectorAll(".js-mode-back").forEach(function (btn) {
            btn.addEventListener("click", function () { setSearchMode(btn.dataset.mode); });
        });
    }

    /* ===================== AD BANNER CAROUSEL ===================== */
    var adCarousel = document.querySelector(".js-ad-carousel");
    if (adCarousel) {
        var adSlides = Array.prototype.slice.call(adCarousel.querySelectorAll(".js-ad-slide"));
        var adDots = Array.prototype.slice.call(adCarousel.querySelectorAll(".js-ad-dot"));
        var adIndex = 0;
        var adTimer = null;
        var AD_INTERVAL = 3000;

        function showAdSlide(i) {
            adIndex = (i + adSlides.length) % adSlides.length;
            adSlides.forEach(function (s, idx) { s.classList.toggle("on", idx === adIndex); });
            adDots.forEach(function (d, idx) { d.classList.toggle("on", idx === adIndex); });
        }

        function stopAdAutoplay() {
            if (adTimer) { clearInterval(adTimer); adTimer = null; }
        }

        function startAdAutoplay() {
            if (adSlides.length < 2) return;
            stopAdAutoplay();
            adTimer = setInterval(function () { showAdSlide(adIndex + 1); }, AD_INTERVAL);
        }

        adDots.forEach(function (dot, i) {
            dot.addEventListener("click", function () {
                showAdSlide(i);
                startAdAutoplay();
            });
        });

        adCarousel.addEventListener("mouseenter", stopAdAutoplay);
        adCarousel.addEventListener("mouseleave", startAdAutoplay);

        showAdSlide(0);
        startAdAutoplay();
    }

    /* ===================== MOBILE SHELL ===================== */
    var mobileShell = document.querySelector(".mobile-shell");
    if (mobileShell) {
        var mCatBtns = mobileShell.querySelectorAll(".js-m-cat");
        var mPanels = mobileShell.querySelectorAll(".js-m-panel");
        mCatBtns.forEach(function (btn) {
            btn.addEventListener("click", function () {
                mCatBtns.forEach(function (b) { b.classList.toggle("on", b === btn); });
                mPanels.forEach(function (p) { p.hidden = p.dataset.cat !== btn.dataset.cat; });
            });
        });
    }

    /* ===================== DETAIL PAGE: modals ===================== */
    var modals = Array.prototype.slice.call(document.querySelectorAll(".js-modal"));
    if (modals.length) {
        function openModal(id) {
            var modal = document.getElementById(id);
            if (!modal) return;
            modal.hidden = false;
            document.body.classList.add("modal-open");
        }
        function closeModal(modal) {
            modal.hidden = true;
            document.body.classList.remove("modal-open");
        }

        document.querySelectorAll("[data-modal-open]").forEach(function (btn) {
            btn.addEventListener("click", function () { openModal(btn.dataset.modalOpen); });
        });

        modals.forEach(function (modal) {
            modal.addEventListener("click", function (e) {
                if (e.target === modal) closeModal(modal);
            });
            modal.querySelectorAll(".js-modal-close").forEach(function (btn) {
                btn.addEventListener("click", function () { closeModal(modal); });
            });
        });

        document.addEventListener("keydown", function (e) {
            if (e.key !== "Escape") return;
            modals.forEach(function (modal) { if (!modal.hidden) closeModal(modal); });
        });
    }

    /* generic "fake" success-swap forms that still have no backend target
       (masalan careers.blade.php dagi vakansiya/rezyume post modali — Phase 6+) */
    document.querySelectorAll(".js-fake-form").forEach(function (form) {
        form.addEventListener("submit", function (e) {
            e.preventDefault();
            var wrap = form.closest(".js-inline-enroll") || form.parentElement;
            if (!wrap) return;
            form.style.display = "none";
            var head = wrap.querySelector(".js-fake-form-head");
            if (head) head.style.display = "none";
            var success = wrap.querySelector(".js-fake-success");
            if (success) success.style.display = "";
        });
    });

    /* generic ikki-variantli tanlov qatori (masalan farzand qo'shish/tahrirlash
       modalidagi "Jinsi" — O'g'il/Qiz), har bir .choice-row ichida faqat bitta
       .choice-btn "on" bo'lishi mumkin. */
    document.querySelectorAll(".choice-row").forEach(function (row) {
        row.querySelectorAll(".choice-btn").forEach(function (btn) {
            btn.addEventListener("click", function () {
                row.querySelectorAll(".choice-btn").forEach(function (b) { b.classList.toggle("on", b === btn); });
            });
        });
    });

    /* generic ko'p-variantli chip tanlovi (masalan farzand qo'shish/tahrirlash
       modalidagi "Qiziqishlari") — faqat vizual, saqlashda hali o'qilmaydi. */
    document.querySelectorAll('[id^="add-child-interests"], [id^="edit-child-interests-"]').forEach(function (wrap) {
        wrap.querySelectorAll(".chip").forEach(function (chip) {
            chip.addEventListener("click", function () { chip.classList.toggle("on"); });
        });
    });

    /* real ariza (excursion/enrollment) forms: POST /ajax/applications, real DB ga saqlanadi */
    document.querySelectorAll(".js-application-form").forEach(function (form) {
        form.addEventListener("submit", function (e) {
            e.preventDefault();

            var data = {};
            var els = form.elements;
            for (var i = 0; i < els.length; i++) {
                if (els[i].name) data[els[i].name] = els[i].value;
            }

            var oldError = form.querySelector(".js-app-error");
            if (oldError) oldError.remove();

            var submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) submitBtn.disabled = true;

            jsonFetch("/ajax/applications", "POST", data).then(function (res) {
                if (submitBtn) submitBtn.disabled = false;

                if (!res.ok) {
                    var msg = "Xatolik yuz berdi. Maʼlumotlarni tekshirib qayta urining.";
                    if (res.body) {
                        if (res.body.errors) {
                            var firstKey = Object.keys(res.body.errors)[0];
                            if (firstKey) msg = res.body.errors[firstKey][0];
                        } else if (res.body.message) {
                            msg = res.body.message;
                        }
                    }
                    var box = document.createElement("div");
                    box.className = "js-app-error";
                    box.style.cssText = "color:#dc2626;font-size:13px;font-weight:600;margin-top:10px";
                    box.textContent = msg;
                    form.appendChild(box);
                    return;
                }

                var wrap = form.closest(".js-inline-enroll") || form.parentElement;
                if (!wrap) return;
                form.style.display = "none";
                var head = wrap.querySelector(".js-fake-form-head");
                if (head) head.style.display = "none";
                var success = wrap.querySelector(".js-fake-success");
                if (success) success.style.display = "";
            });
        });
    });

    /* real vakansiyaga ariza formasi: POST /ajax/vacancies/{id}/apply (vacancy.blade.php,
       teacher/vacancies.blade.php — mehmon ham yubora oladi, .js-application-form bilan
       bir xil andoza — ADR-0002, Faza 2). FormData ishlatiladi — rezyume fayli ham
       shu forma orqali yuboriladi. */
    document.querySelectorAll(".js-vacancy-apply-form").forEach(function (form) {
        form.addEventListener("submit", function (e) {
            e.preventDefault();

            var vacancyId = form.dataset.vacancyId;
            if (!vacancyId) return;

            var data = new FormData(form);

            var oldError = form.querySelector(".js-app-error");
            if (oldError) oldError.remove();

            var submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) submitBtn.disabled = true;

            jsonFetch("/ajax/vacancies/" + vacancyId + "/apply", "POST", data).then(function (res) {
                if (submitBtn) submitBtn.disabled = false;

                if (!res.ok) {
                    var msg = "Xatolik yuz berdi. Maʼlumotlarni tekshirib qayta urining.";
                    if (res.body) {
                        if (res.body.errors) {
                            var firstKey = Object.keys(res.body.errors)[0];
                            if (firstKey) msg = res.body.errors[firstKey][0];
                        } else if (res.body.message) {
                            msg = res.body.message;
                        }
                    }
                    var box = document.createElement("div");
                    box.className = "js-app-error";
                    box.style.cssText = "color:#dc2626;font-size:13px;font-weight:600;margin-top:10px";
                    box.textContent = msg;
                    form.appendChild(box);
                    return;
                }

                var wrap = form.closest(".js-inline-enroll") || form.parentElement;
                if (!wrap) return;
                form.style.display = "none";
                var head = wrap.querySelector(".js-fake-form-head");
                if (head) head.style.display = "none";
                var success = wrap.querySelector(".js-fake-success");
                if (success) success.style.display = "";
            });
        });
    });

    /* real "yangi mavzu" formasi: POST /ajax/forum/threads, muvaffaqiyatli
       bo'lsa yangi mavzu sahifasiga o'tkaziladi (forum.blade.php — ADR-0002, Faza 2). */
    document.querySelectorAll(".js-thread-form").forEach(function (form) {
        form.addEventListener("submit", function (e) {
            e.preventDefault();

            var data = {};
            var els = form.elements;
            for (var i = 0; i < els.length; i++) {
                if (els[i].name) data[els[i].name] = els[i].value;
            }

            var oldError = form.querySelector(".js-app-error");
            if (oldError) oldError.remove();

            var submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) submitBtn.disabled = true;

            jsonFetch("/ajax/forum/threads", "POST", data).then(function (res) {
                if (submitBtn) submitBtn.disabled = false;

                if (!res.ok) {
                    var msg = "Xatolik yuz berdi. Maʼlumotlarni tekshirib qayta urining.";
                    if (res.body) {
                        if (res.body.errors) {
                            var firstKey = Object.keys(res.body.errors)[0];
                            if (firstKey) msg = res.body.errors[firstKey][0];
                        } else if (res.body.message) {
                            msg = res.body.message;
                        }
                    }
                    var box = document.createElement("div");
                    box.className = "js-app-error";
                    box.style.cssText = "color:#dc2626;font-size:13px;font-weight:600;margin-top:10px";
                    box.textContent = msg;
                    form.appendChild(box);
                    return;
                }

                var id = res.body.thread && res.body.thread.id;
                window.location.href = id ? "/forum/" + id : "/forum";
            });
        });
    });

    /* real javob formasi: POST /ajax/forum/threads/{id}/replies (forum-thread.blade.php).
       Muvaffaqiyatli bo'lsa sahifa qayta yuklanadi — yangi javob serverdan real
       tartibda chizilishi uchun (institution gallery bilan bir xil andoza). */
    document.querySelectorAll(".js-reply-form").forEach(function (form) {
        form.addEventListener("submit", function (e) {
            e.preventDefault();

            var threadId = form.dataset.threadId;
            if (!threadId) return;

            var data = {};
            var els = form.elements;
            for (var i = 0; i < els.length; i++) {
                if (els[i].name) data[els[i].name] = els[i].value;
            }

            var oldError = form.querySelector(".js-app-error");
            if (oldError) oldError.remove();

            var submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) submitBtn.disabled = true;

            jsonFetch("/ajax/forum/threads/" + threadId + "/replies", "POST", data).then(function (res) {
                if (res.status === 401 || res.status === 403) {
                    var kirish = document.getElementById("js-kirish-btn");
                    if (kirish) kirish.click();
                    if (submitBtn) submitBtn.disabled = false;
                    return;
                }

                if (!res.ok) {
                    if (submitBtn) submitBtn.disabled = false;
                    var msg = "Xatolik yuz berdi. Maʼlumotlarni tekshirib qayta urining.";
                    if (res.body) {
                        if (res.body.errors) {
                            var firstKey = Object.keys(res.body.errors)[0];
                            if (firstKey) msg = res.body.errors[firstKey][0];
                        } else if (res.body.message) {
                            msg = res.body.message;
                        }
                    }
                    var box = document.createElement("div");
                    box.className = "js-app-error";
                    box.style.cssText = "color:#dc2626;font-size:13px;font-weight:600;margin-top:10px";
                    box.textContent = msg;
                    form.appendChild(box);
                    return;
                }

                window.location.reload();
            });
        });
    });

    /* forum layk (mavzu/javob): POST /ajax/forum/threads/{id}/like yoki
       /ajax/forum/replies/{id}/like — toggle, natija darhol sonda ko'rinadi. */
    document.querySelectorAll(".js-forum-like").forEach(function (btn) {
        btn.addEventListener("click", function () {
            var threadId = btn.dataset.threadId;
            var replyId = btn.dataset.replyId;
            var url = threadId ? "/ajax/forum/threads/" + threadId + "/like" : "/ajax/forum/replies/" + replyId + "/like";

            jsonFetch(url, "POST").then(function (res) {
                if (res.status === 401 || res.status === 403) {
                    var kirish = document.getElementById("js-kirish-btn");
                    if (kirish) kirish.click();
                    return;
                }
                if (!res.ok) return;

                btn.classList.toggle("on", !!res.body.liked);
                var countEl = btn.querySelector(".js-like-count");
                if (countEl && typeof res.body.likes === "number") countEl.textContent = res.body.likes;
            });
        });
    });

    /* ===================== AUTH MODAL & SESSION (real backend, backend.md §5) ===================== */
    (function () {
        /* --- dropdown helpers (auth holatidan mustaqil) --- */
        var _userMenu = document.getElementById("js-user-menu");
        var _langMenu = document.getElementById("js-lang-menu");
        var _navLinks = document.getElementById("js-nav-links");
        var _navBurger = document.getElementById("js-nav-burger");

        function closeAll() {
            if (_userMenu) _userMenu.style.display = "none";
            if (_langMenu) _langMenu.style.display = "none";
            if (_navLinks) _navLinks.classList.remove("on");
            if (_navBurger) {
                _navBurger.classList.remove("on");
                _navBurger.setAttribute("aria-expanded", "false");
            }
        }

        if (_navBurger && _navLinks) {
            _navBurger.addEventListener("click", function (e) {
                e.stopPropagation();
                var open = _navLinks.classList.contains("on");
                closeAll();
                if (!open) {
                    _navLinks.classList.add("on");
                    _navBurger.classList.add("on");
                    _navBurger.setAttribute("aria-expanded", "true");
                }
            });
            _navLinks.addEventListener("click", function (e) { e.stopPropagation(); });
        }

        var userMenuBtn = document.getElementById("js-user-menu-btn");
        if (userMenuBtn && _userMenu) {
            userMenuBtn.addEventListener("click", function (e) {
                e.stopPropagation();
                var open = _userMenu.style.display !== "none";
                closeAll();
                if (!open) _userMenu.style.display = "";
            });
        }

        /* Til tanlash endi haqiqiy havolalar (/til/{locale}) — sahifa qayta yuklanadi,
           shu sababli faqat ochish/yopish kerak, JS orqali label almashtirish shart emas. */
        var langBtn = document.getElementById("js-lang-btn");
        if (langBtn && _langMenu) {
            langBtn.addEventListener("click", function (e) {
                e.stopPropagation();
                var open = _langMenu.style.display !== "none";
                closeAll();
                if (!open) _langMenu.style.display = "";
            });
        }

        document.addEventListener("click", function () { closeAll(); });

        /* --- auth panel switching (login/parent/institution) --- */
        document.querySelectorAll(".js-auth-switch").forEach(function (btn) {
            btn.addEventListener("click", function () {
                var target = btn.dataset.target;
                document.querySelectorAll(".auth-panel").forEach(function (p) {
                    p.style.display = p.dataset.panel === target ? "block" : "none";
                });
            });
        });

        /* --- logout: real session tugatiladi --- */
        document.addEventListener("click", function (e) {
            var btn = e.target.closest("#js-nav-logout, #js-logout-btn, #js-inst-logout, .js-logout-trigger");
            if (!btn) return;
            jsonFetch("/ajax/auth/logout", "POST", {}).then(function () {
                window.location.href = "/";
            });
        });

        /* --- muassasa kabineti: ekskursiya arizasini tasdiqlash/rad etish (real PATCH) --- */
        document.addEventListener("click", function (e) {
            var btn = e.target.closest("[data-app-status]");
            if (!btn) return;
            var row = btn.closest("[data-app-id]");
            if (!row) return;
            var id = row.dataset.appId;
            var status = btn.dataset.appStatus;

            jsonFetch("/ajax/institution/me/applications/" + id + "/status", "PATCH", { status: status }).then(function (res) {
                if (res.ok) window.location.reload();
            });
        });

        /* --- real auth form submit: login / ro'yxatdan o'tish (parent, institution) --- */
        var AUTH_ENDPOINTS = {
            login: "/ajax/auth/login",
            parent: "/ajax/auth/register/parent",
            institution: "/ajax/auth/register/institution",
            teacher: "/ajax/auth/register/teacher",
        };

        /* Muvaffaqiyatli kirish/ro'yxatdan o'tishdan so'ng rolega mos kabinetga yo'naltirish
         * ("kind" — AuthUserResource'dagi role qiymati: parent|institution|admin|teacher). Mos
         * qiymat topilmasa (masalan hali rol aniqlanmagan holatlar uchun) joriy sahifa
         * qayta yuklanadi — avvalgi xatti-harakat. */
        var ROLE_REDIRECTS = {
            parent: "/cabinet",
            institution: "/institution-cabinet",
            teacher: "/teacher-cabinet",
            admin: "/admin",
        };

        document.querySelectorAll(".js-fake-auth").forEach(function (form) {
            form.addEventListener("submit", function (e) {
                e.preventDefault();
                var mode = form.dataset.mode;
                var url = AUTH_ENDPOINTS[mode] || AUTH_ENDPOINTS.login;
                var data = {};
                var els = form.elements;
                for (var i = 0; i < els.length; i++) {
                    if (els[i].name) data[els[i].name] = els[i].value;
                }

                var oldError = form.querySelector(".js-auth-error");
                if (oldError) oldError.remove();

                var submitBtn = form.querySelector(".form-submit");
                if (submitBtn) submitBtn.disabled = true;

                jsonFetch(url, "POST", data).then(function (res) {
                    if (submitBtn) submitBtn.disabled = false;

                    if (!res.ok) {
                        var msg = "Xatolik yuz berdi. Maʼlumotlarni tekshirib qayta urining.";
                        if (res.body) {
                            if (res.body.errors) {
                                var firstKey = Object.keys(res.body.errors)[0];
                                if (firstKey) msg = res.body.errors[firstKey][0];
                            } else if (res.body.message) {
                                msg = res.body.message;
                            }
                        }
                        var box = document.createElement("div");
                        box.className = "js-auth-error";
                        box.style.cssText = "color:#dc2626;font-size:13px;font-weight:600;margin-top:10px";
                        box.textContent = msg;
                        form.appendChild(box);
                        return;
                    }

                    /* muvaffaqiyatli: rolega mos kabinetga yo'naltiriladi */
                    var kind = res.body && res.body.user && res.body.user.kind;
                    var redirectTo = ROLE_REDIRECTS[kind];
                    if (redirectTo) {
                        window.location.href = redirectTo;
                    } else {
                        window.location.reload();
                    }
                });
            });
        });

        /* --- muassasa kabineti tab almashish --- */
        var instTabBtns = Array.prototype.slice.call(document.querySelectorAll(".js-inst-tab"));
        var instPanels = Array.prototype.slice.call(document.querySelectorAll(".js-inst-panel"));
        instTabBtns.forEach(function (btn) {
            btn.addEventListener("click", function () {
                instTabBtns.forEach(function (b) { b.classList.toggle("on", b === btn); });
                instPanels.forEach(function (p) {
                    p.style.display = p.dataset.panel === btn.dataset.tab ? "block" : "none";
                });
            });
        });

        /* ===== Muassasa kabineti: jonli preview + real saqlash (boshlang'ich qiymatlar serverdan) ===== */
        var instNameField = document.getElementById("js-f-name");
        if (instNameField) {
            function fmtPrice(n) {
                if (!n) return "";
                return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
            }

            function daysSummary() {
                var abbrs = Array.prototype.slice.call(document.querySelectorAll("#js-day-rows .day-row.on"))
                    .map(function (row) { return row.dataset.abbr; });
                return abbrs.length ? abbrs.join(", ") : "Dam olish kunlari";
            }

            /* Har bir hafta kuni uchun real {on,hours} massivini yig'ib oladi
               (work_schedule, 2026-08-08) — saqlashda shu to'g'ridan-to'g'ri yuboriladi. */
            function collectWorkSchedule() {
                var schedule = {};
                Array.prototype.slice.call(document.querySelectorAll("#js-day-rows .day-row")).forEach(function (row) {
                    var day = row.dataset.day;
                    if (!day) return;
                    var toggle = row.querySelector(".js-day-toggle");
                    var input = row.querySelector(".day-hours-input");
                    schedule[day] = {
                        on: !!(toggle && toggle.classList.contains("on")),
                        hours: input ? input.value.trim() : "",
                    };
                });
                return schedule;
            }

            /* "Narxlar" jadvalidagi eng kichik narx — real saqlashda ham
               (institutions.monthly_price) xuddi shu mantiq ishlatiladi
               (Institution\ProfileController::syncPrices(), 2026-07-15). */
            function minPriceFromRows() {
                var rows = Array.prototype.slice.call(document.querySelectorAll("#js-price-rows .price-row"));
                var vals = rows.map(function (row) {
                    var inp = row.querySelectorAll("input")[1];
                    return inp ? parseInt((inp.value || "").replace(/\D/g, ""), 10) : NaN;
                }).filter(function (n) { return !isNaN(n) && n > 0; });

                return vals.length ? Math.min.apply(Math, vals) : 0;
            }

            function updatePreview() {
                var name = document.getElementById("js-f-name");
                var kind = document.getElementById("js-f-kind");
                var district = document.getElementById("js-f-district");
                var hours = document.getElementById("js-f-hours");
                var nameVal = name ? name.value : "";
                var kindVal = kind ? kind.value : "maktab";
                var kindMap = { maktab: "Xususiy maktab", bogcha: "Xususiy bog'cha", markaz: "O'quv markazi" };

                var pMono = document.getElementById("js-prev-mono");
                var pName = document.getElementById("js-prev-name");
                var pKind = document.getElementById("js-prev-kind");
                var pDays = document.getElementById("js-prev-days");
                var pDistrict = document.getElementById("js-prev-district");
                var pHours = document.getElementById("js-prev-hours");
                var pPrice = document.getElementById("js-prev-price");

                if (pMono) pMono.textContent = nameVal || "Muassasa nomi";
                if (pName) pName.textContent = nameVal || "Muassasa nomi";
                if (pKind) pKind.textContent = kindMap[kindVal] || "Xususiy maktab";
                if (pDays) pDays.textContent = daysSummary();
                if (pDistrict) pDistrict.textContent = (district && district.value) ? district.value : "Tuman";
                if (pHours) pHours.textContent = (hours && hours.value) ? hours.value : "08:00 – 18:00";
                if (pPrice) {
                    var priceVal = minPriceFromRows();
                    pPrice.innerHTML = priceVal > 0
                        ? "<b>" + fmtPrice(priceVal) + "</b> <span>so'mdan / oy</span>"
                        : "<span>Narx kelishilgan</span>";
                }
            }

            ["js-f-name", "js-f-kind", "js-f-district", "js-f-hours"].forEach(function (id) {
                var el = document.getElementById(id);
                if (el) el.addEventListener("input", updatePreview);
            });
            var priceRowsForPreview = document.getElementById("js-price-rows");
            if (priceRowsForPreview) priceRowsForPreview.addEventListener("input", updatePreview);
            updatePreview();

            /* --- kategoriya pillari: js-f-kind hidden select bilan sinxron --- */
            document.querySelectorAll("#js-kind-pills .kind-pill").forEach(function (pill) {
                pill.addEventListener("click", function () {
                    document.querySelectorAll("#js-kind-pills .kind-pill").forEach(function (p) {
                        p.classList.toggle("on", p === pill);
                    });
                    var hiddenSelect = document.getElementById("js-f-kind");
                    if (hiddenSelect) {
                        hiddenSelect.value = pill.dataset.kind;
                        hiddenSelect.dispatchEvent(new Event("input"));
                    }
                });
            });

            /* --- har bir kun qatorining toggle tugmasi: barcha 7 kun endi real
                   saqlanadi (work_schedule, 2026-08-08) — collectWorkSchedule() shu
                   qatorlardan to'liq {mon:{on,hours},...} massivini yig'ib oladi. --- */
            document.querySelectorAll(".js-day-toggle").forEach(function (btn) {
                btn.addEventListener("click", function () {
                    var on = !btn.classList.contains("on");
                    btn.classList.toggle("on", on);
                    var row = btn.closest(".day-row");
                    if (row) {
                        row.classList.toggle("on", on);
                        var input = row.querySelector(".day-hours-input");
                        if (input) input.style.display = on ? "" : "none";
                    }
                    updatePreview();
                });
            });

            /* --- telefon raqamlar ro'yxati (hozircha faqat vizual) --- */
            var phoneList = document.getElementById("js-phone-list");
            var phoneAdd = document.getElementById("js-phone-add");
            function wirePhoneDelete(row) {
                var btn = row.querySelector(".js-phone-del");
                if (btn) btn.addEventListener("click", function () { row.remove(); });
            }
            if (phoneList) {
                Array.prototype.slice.call(phoneList.querySelectorAll(".phone-row")).forEach(wirePhoneDelete);
            }
            if (phoneAdd && phoneList) {
                phoneAdd.addEventListener("click", function () {
                    var row = document.createElement("div");
                    row.className = "phone-row";
                    row.innerHTML =
                        '<span class="phone-ico"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 4h3l1.5 4-2 1.5a11 11 0 0 0 5 5l1.5-2 4 1.5v3a2 2 0 0 1-2 2A15 15 0 0 1 3 6a2 2 0 0 1 2-2z"/></svg></span>' +
                        '<span class="field-control" style="flex:1"><input type="tel" placeholder="+998 __ ___ __ __" /></span>' +
                        '<button type="button" class="phone-del js-phone-del" title="O\'chirish"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 6l12 12"/><path d="M18 6L6 18"/></svg></button>';
                    phoneList.appendChild(row);
                    wirePhoneDelete(row);
                });
            }

            /* --- narxlar jadvali qatorlari (hozircha faqat vizual) --- */
            var priceRows = document.getElementById("js-price-rows");
            var priceAdd = document.getElementById("js-price-add");
            function wirePriceDelete(row) {
                var btn = row.querySelector(".js-price-del");
                if (btn) btn.addEventListener("click", function () {
                    if (priceRows.querySelectorAll(".price-row").length > 1) row.remove();
                });
            }
            if (priceRows) {
                Array.prototype.slice.call(priceRows.querySelectorAll(".price-row")).forEach(wirePriceDelete);
            }
            if (priceAdd && priceRows) {
                priceAdd.addEventListener("click", function () {
                    var row = document.createElement("div");
                    row.className = "price-row";
                    row.innerHTML =
                        '<input type="text" placeholder="5-9-sinf" />' +
                        '<select><option>O\'zbek</option><option>Rus</option><option>Ingliz</option></select>' +
                        '<input type="text" placeholder="5 000 000" />' +
                        '<input type="text" placeholder="—" />' +
                        '<button type="button" class="price-del js-price-del" title="O\'chirish"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 6l12 12"/><path d="M18 6L6 18"/></svg></button>';
                    priceRows.appendChild(row);
                    wirePriceDelete(row);
                });
            }

            /* --- Yoʻnalishlar va dastur / Oʻquv jarayonidan lavhalar / Qabul bosqichlari:
               endi bitta pipe-matnli textarea o'rniga alohida-input qatorlar (2026-07-15).
               Saqlashda har bir guruh o'zining pipe-matn qatoriga yig'iladi (backend
               ProfileController hech narsa o'zgarmadi — teachers_text/programs_text/
               lessons_text/admission_steps_text hamon shu formatni kutadi). --- */
            function wireGenericRowGroup(listId, addId, rowClass, delClass, minRows, rowHtml) {
                var list = document.getElementById(listId);
                var addBtn = document.getElementById(addId);
                if (!list) return;

                function wireDelete(row) {
                    var btn = row.querySelector("." + delClass);
                    if (btn) btn.addEventListener("click", function () {
                        if (list.querySelectorAll("." + rowClass).length > (minRows || 0)) row.remove();
                    });
                }

                Array.prototype.slice.call(list.querySelectorAll("." + rowClass)).forEach(wireDelete);

                if (addBtn) {
                    addBtn.addEventListener("click", function () {
                        var row = document.createElement("div");
                        row.className = rowClass;
                        row.style.cssText = "display:flex;gap:10px;align-items:center";
                        row.innerHTML = rowHtml;
                        list.appendChild(row);
                        wireDelete(row);
                    });
                }
            }

            var closeIconSvg = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 6l12 12"/><path d="M18 6L6 18"/></svg>';

            wireGenericRowGroup("js-program-rows", "js-program-add", "js-program-row", "js-program-del", 0,
                '<span class="field-control" style="flex:1"><input type="text" placeholder="Masalan, Cambridge dasturi" /></span>' +
                '<span class="field-control" style="flex:1.4"><input type="text" placeholder="Xalqaro standart va sertifikat" /></span>' +
                '<button type="button" class="phone-del js-program-del" title="O\'chirish">' + closeIconSvg + '</button>');

            wireGenericRowGroup("js-lesson-rows", "js-lesson-add", "js-lesson-row", "js-lesson-del", 0,
                '<span class="field-control" style="flex:1"><input type="text" placeholder="Masalan, Matematika darsi" /></span>' +
                '<button type="button" class="phone-del js-lesson-del" title="O\'chirish">' + closeIconSvg + '</button>');

            wireGenericRowGroup("js-step-rows", "js-step-add", "js-step-row", "js-step-del", 0,
                '<span class="field-control" style="flex:1"><input type="text" placeholder="Masalan, Ariza qoldirish" /></span>' +
                '<span class="field-control" style="flex:1.4"><input type="text" placeholder="Onlayn forma orqali ariza yuborasiz" /></span>' +
                '<button type="button" class="phone-del js-step-del" title="O\'chirish">' + closeIconSvg + '</button>');

            /* Bitta qator "Sarlavha | Tavsif" pipe-matniga yig'iladi (ikki input), yoki bitta
               inputli qatorlar uchun faqat qiymati olinadi — bo'sh qatorlar e'tiborsiz qoldiriladi. */
            function collectPipeRows(rowsId, rowClass) {
                var list = document.getElementById(rowsId);
                if (!list) return "";
                return Array.prototype.slice.call(list.querySelectorAll("." + rowClass))
                    .map(function (row) {
                        var inputs = Array.prototype.slice.call(row.querySelectorAll("input"));
                        return inputs.map(function (inp) { return (inp.value || "").trim(); }).join(" | ");
                    })
                    .filter(function (line) { return line.replace(/\|/g, "").trim() !== ""; })
                    .join("\n");
            }

            /* --- qabul holati: real PATCH /ajax/institution/me/accepting --- */
            var acceptCard = document.getElementById("js-accept-card");
            var acceptToggle = document.getElementById("js-accept-toggle");
            var acceptText = document.getElementById("js-accept-text");
            var prevBadge = document.getElementById("js-prev-badge");
            if (acceptToggle) {
                acceptToggle.addEventListener("click", function () {
                    var accepting = !acceptToggle.classList.contains("on");
                    jsonFetch("/ajax/institution/me/accepting", "PATCH", { accepting: accepting }).then(function (res) {
                        if (!res.ok) return;
                        acceptToggle.classList.toggle("on", accepting);
                        if (acceptCard) acceptCard.classList.toggle("on", accepting);
                        if (acceptText) acceptText.textContent = accepting ? "Arizalar qabul qilinmoqda" : "Qabul vaqtincha yopiq";
                        if (prevBadge) prevBadge.style.display = accepting ? "" : "none";
                    });
                });
            }

            /* --- ixtisoslik chip'lari (faqat vizual — saqlashda o'qiladi) --- */
            document.querySelectorAll("#js-spec-chips .chip").forEach(function (chip) {
                chip.addEventListener("click", function () { chip.classList.toggle("on"); });
            });

            /* --- infratuzilma/qulaylik chip'lari (faqat vizual — saqlashda o'qiladi) --- */
            document.querySelectorAll("#js-facility-chips .chip").forEach(function (chip) {
                chip.addEventListener("click", function () { chip.classList.toggle("on"); });
            });

            /* --- saqlash: real PUT /ajax/institution/me --- */
            var saveBtn = document.getElementById("js-inst-save");
            var savedPill = document.getElementById("js-saved-pill");
            if (saveBtn) {
                saveBtn.addEventListener("click", function () {
                    var specs = Array.prototype.slice.call(document.querySelectorAll("#js-spec-chips .chip.on"))
                        .map(function (c) { return c.dataset.spec; });

                    var facilities = Array.prototype.slice.call(document.querySelectorAll("#js-facility-chips .chip.on"))
                        .map(function (c) { return c.dataset.facility; });

                    // "Narxlar" jadvali endi real saqlanadi — har qatordan sinf/guruh,
                    // o'quv tili, narx, chegirma yig'ib olinadi (bo'sh narxli qatorlar
                    // e'tiborsiz qoldiriladi). institutions.monthly_price serverda shular
                    // ichidan eng kichigi bilan avtomatik yangilanadi (2026-07-15).
                    var prices = Array.prototype.slice.call(document.querySelectorAll("#js-price-rows .price-row"))
                        .map(function (row) {
                            var inputs = row.querySelectorAll("input");
                            var select = row.querySelector("select");
                            var priceNum = parseInt(((inputs[1] && inputs[1].value) || "").replace(/\D/g, ""), 10);
                            return {
                                grade: inputs[0] ? inputs[0].value.trim() : "",
                                lang: select ? select.value : "",
                                price: isNaN(priceNum) ? null : priceNum,
                                discount: inputs[2] ? inputs[2].value.trim() : "",
                            };
                        })
                        .filter(function (row) { return row.grade !== "" && row.price !== null; });

                    var payload = {
                        name: (document.getElementById("js-f-name") || {}).value || "",
                        type: (document.getElementById("js-f-kind") || {}).value || "maktab",
                        lang: (document.getElementById("js-f-lang") || {}).value || "",
                        about: (document.getElementById("js-f-about") || {}).value || "",
                        district: (document.getElementById("js-f-district") || {}).value || "",
                        address: (document.getElementById("js-f-address") || {}).value || "",
                        prices: prices,
                        // Diqqat: "grades" (Sinflar/Yosh oralig'i) inputi bu sahifadan olib
                        // tashlandi, shuning uchun bu yerdan ham yubormaymiz — aks holda
                        // saqlashda mavjud qiymat bo'sh qator bilan ustidan yozilib ketardi.
                        work_schedule: collectWorkSchedule(),
                        specializations: specs,
                        facilities: facilities,
                        // Diqqat: "teachers_text" endi bu sahifada yo'q (Ustozlar bo'limi olib
                        // tashlandi — /institution-cabinet/teachers'da boshqariladi), shuning
                        // uchun bu yerdan yubormaymiz — aks holda saqlashda mavjud ustozlar
                        // ma'lumoti bo'sh qator bilan ustidan yozilib ketardi.
                        programs_text: collectPipeRows("js-program-rows", "js-program-row"),
                        lessons_text: collectPipeRows("js-lesson-rows", "js-lesson-row"),
                        // Diqqat: "videos_text" ham endi bu sahifada yo'q — "Videolar" real fayl
                        // yuklash orqali (/ajax/institution/me/media, type=video) o'z alohida
                        // formasida saqlanadi, shuning uchun bu yerdan yubormaymiz.
                        admission_steps_text: collectPipeRows("js-step-rows", "js-step-row"),
                        stat_class_size: (document.getElementById("js-f-stat1") || {}).value || "",
                        stat_experience_years: (document.getElementById("js-f-stat2") || {}).value || "",
                        stat_admission_rate: (document.getElementById("js-f-stat3") || {}).value || "",
                        stat_first_grade_seats: (document.getElementById("js-f-stat4") || {}).value || "",
                    };

                    saveBtn.disabled = true;
                    jsonFetch("/ajax/institution/me", "PUT", payload).then(function (res) {
                        saveBtn.disabled = false;

                        if (!res.ok) {
                            var msg = "Xatolik yuz berdi. Maʼlumotlarni tekshirib qayta urining.";
                            if (res.body) {
                                if (res.body.errors) {
                                    var firstKey = Object.keys(res.body.errors)[0];
                                    if (firstKey && res.body.errors[firstKey][0]) msg = res.body.errors[firstKey][0];
                                } else if (res.body.message) {
                                    msg = res.body.message;
                                }
                            }
                            alert(msg);
                            return;
                        }

                        if (savedPill) { savedPill.style.display = ""; }
                        setTimeout(function () { if (savedPill) savedPill.style.display = "none"; }, 3000);
                        alert("Maʼlumotlar muvaffaqiyatli saqlandi!");
                    });
                });
            }
        }

        /* Diqqat (2026-07-15): quyidagi bloklar (rasm/video yuklash-o'chirish,
           yutuq CRUD, vakansiya o'chirish/holat) ilgari yuqoridagi
           "if (instNameField)" ichida edi — shu sabab ular FAQAT
           institution/profile.blade.php sahifasida ishlar edi (chunki
           #js-f-name faqat o'sha sahifada bor), garchi galereya, yutuqlar,
           vakansiyalar kabi boshqa sahifalarda ham kerak bo'lsa-da. Endi
           bu kod shartsiz ishga tushadi — har bir bo'lim o'z elementi
           mavjud bo'lmagan sahifada oddiygina hech narsa qilmaydi
           (querySelectorAll bo'sh natija qaytaradi). */

        /* --- rasm yuklash: real POST /ajax/institution/me/media (multipart) --- */
            document.querySelectorAll(".js-media-upload").forEach(function (slot) {
                var input = slot.querySelector("input[type=file]");
                if (!input) return;
                input.addEventListener("change", function () {
                    var file = input.files && input.files[0];
                    if (!file) return;

                    var formData = new FormData();
                    formData.append("file", file);
                    formData.append("type", slot.dataset.mediaType || "gallery");

                    slot.classList.add("loading");

                    fetch("/ajax/institution/me/media", {
                        method: "POST",
                        headers: { "Accept": "application/json", "X-CSRF-TOKEN": csrfToken() },
                        body: formData,
                    }).then(function (res) {
                        return res.json().catch(function () { return {}; }).then(function (body) {
                            return { ok: res.ok, body: body };
                        });
                    }).then(function (result) {
                        slot.classList.remove("loading");
                        input.value = "";

                        if (!result.ok) {
                            var msg = "Rasmni yuklab boʻlmadi. Qayta urining.";
                            if (result.body) {
                                if (result.body.errors) {
                                    var firstKey = Object.keys(result.body.errors)[0];
                                    if (firstKey && result.body.errors[firstKey][0]) msg = result.body.errors[firstKey][0];
                                } else if (result.body.message) {
                                    msg = result.body.message;
                                }
                            }
                            alert(msg);
                            return;
                        }

                        /* Galereya sahifasida (data-reload="1") ro'yxat dinamik — yangi
                         * yozuv serverdan real tartibda (sort_order) qayta chizilishi
                         * uchun sahifa qayta yuklanadi (institution.gallery.blade.php). */
                        if (slot.dataset.reload === "1") {
                            window.location.reload();
                            return;
                        }

                        /* Profil sahifasidagi sobit slotlar (upload-slot) — sahifani to'liq
                         * qayta yuklamasdan slotni joyida yangilaymiz. */
                        var url = result.body.media && result.body.media.url;
                        if (url) {
                            slot.classList.add("filled");
                            slot.style.backgroundImage = "url('" + url + "')";
                            var span = slot.querySelector("span");
                            if (span) span.textContent = "Yuklandi ✓";
                        }

                        var counter = document.getElementById("js-media-count");
                        if (counter) {
                            var n = parseInt((counter.textContent.match(/\d+/) || ["0"])[0], 10) + 1;
                            counter.textContent = "(" + n + " ta yuklangan)";
                        }
                    }).catch(function () {
                        slot.classList.remove("loading");
                        alert("Tarmoq xatosi. Internet aloqasini tekshirib qayta urining.");
                    });
                });
            });

            /* --- rasm o'chirish: real DELETE /ajax/institution/me/media/{id} (galereya sahifasi) --- */
            document.querySelectorAll(".js-media-delete").forEach(function (btn) {
                btn.addEventListener("click", function () {
                    var id = btn.dataset.mediaId;
                    if (!id) return;
                    if (!window.confirm("Rasmni o'chirishni tasdiqlaysizmi?")) return;

                    btn.disabled = true;

                    fetch("/ajax/institution/me/media/" + id, {
                        method: "DELETE",
                        headers: { "Accept": "application/json", "X-CSRF-TOKEN": csrfToken() },
                    }).then(function (res) {
                        if (res.ok) {
                            window.location.reload();
                            return;
                        }
                        btn.disabled = false;
                        alert("Rasmni o'chirib bo'lmadi. Qayta urining.");
                    }).catch(function () {
                        btn.disabled = false;
                        alert("Tarmoq xatosi. Internet aloqasini tekshirib qayta urining.");
                    });
                });
            });

            /* --- "Video qo'shish": real POST /ajax/institution/me/media (type=video),
               haqiqiy fayl (multipart) yoki tashqi havola bilan (2026-07-15). --- */
            document.querySelectorAll(".js-video-form").forEach(function (form) {
                form.addEventListener("submit", function (e) {
                    e.preventDefault();

                    var errBox = form.querySelector(".js-form-error");
                    if (errBox) errBox.style.display = "none";

                    var formData = new FormData(form);
                    var submitBtn = form.querySelector(".form-submit");
                    if (submitBtn) submitBtn.disabled = true;

                    fetch("/ajax/institution/me/media", {
                        method: "POST",
                        headers: { "Accept": "application/json", "X-CSRF-TOKEN": csrfToken() },
                        body: formData,
                    }).then(function (res) {
                        return res.json().catch(function () { return {}; }).then(function (body) {
                            return { ok: res.ok, body: body };
                        });
                    }).then(function (result) {
                        if (submitBtn) submitBtn.disabled = false;

                        if (!result.ok) {
                            var msg = "Xatolik yuz berdi. Ma'lumotlarni tekshirib qayta urining.";
                            if (result.body) {
                                if (result.body.errors) {
                                    var firstKey = Object.keys(result.body.errors)[0];
                                    if (firstKey && result.body.errors[firstKey][0]) msg = result.body.errors[firstKey][0];
                                } else if (result.body.message) {
                                    msg = result.body.message;
                                }
                            }
                            if (errBox) { errBox.textContent = msg; errBox.style.display = ""; }
                            else alert(msg);
                            return;
                        }

                        window.location.reload();
                    }).catch(function () {
                        if (submitBtn) submitBtn.disabled = false;
                        if (errBox) { errBox.textContent = "Tarmoq xatosi. Internet aloqasini tekshirib qayta urining."; errBox.style.display = ""; }
                    });
                });
            });

            /* --- videoni o'chirish: real DELETE /ajax/institution/me/media/{id} --- */
            document.querySelectorAll(".js-video-delete").forEach(function (btn) {
                btn.addEventListener("click", function () {
                    var id = btn.dataset.mediaId;
                    if (!id) return;
                    if (!window.confirm("Videoni o'chirishni tasdiqlaysizmi?")) return;

                    btn.disabled = true;

                    fetch("/ajax/institution/me/media/" + id, {
                        method: "DELETE",
                        headers: { "Accept": "application/json", "X-CSRF-TOKEN": csrfToken() },
                    }).then(function (res) {
                        if (res.ok) {
                            window.location.reload();
                            return;
                        }
                        btn.disabled = false;
                        alert("Videoni o'chirib bo'lmadi. Qayta urining.");
                    }).catch(function () {
                        btn.disabled = false;
                        alert("Tarmoq xatosi. Internet aloqasini tekshirib qayta urining.");
                    });
                });
            });

            /* --- vakansiya ochish: real POST /ajax/institution/me/vacancies
               (institution-cabinet Vakansiyalar sahifasi — ADR-0002) --- */
            document.querySelectorAll(".js-vacancy-form").forEach(function (form) {
                form.addEventListener("submit", function (e) {
                    e.preventDefault();

                    var errBox = form.querySelector(".js-form-error");
                    var data = {};
                    var els = form.elements;
                    for (var i = 0; i < els.length; i++) {
                        if (els[i].name) data[els[i].name] = els[i].value;
                    }

                    var submitBtn = form.querySelector(".form-submit");
                    if (submitBtn) submitBtn.disabled = true;
                    if (errBox) errBox.style.display = "none";

                    jsonFetch("/ajax/institution/me/vacancies", "POST", data).then(function (result) {
                        if (submitBtn) submitBtn.disabled = false;

                        if (!result.ok) {
                            var msg = "Xatolik yuz berdi. Maʼlumotlarni tekshirib qayta urining.";
                            if (result.body) {
                                if (result.body.errors) {
                                    var firstKey = Object.keys(result.body.errors)[0];
                                    if (firstKey && result.body.errors[firstKey][0]) msg = result.body.errors[firstKey][0];
                                } else if (result.body.message) {
                                    msg = result.body.message;
                                }
                            }
                            if (errBox) { errBox.textContent = msg; errBox.style.display = ""; }
                            else alert(msg);
                            return;
                        }

                        window.location.reload();
                    }).catch(function () {
                        if (submitBtn) submitBtn.disabled = false;
                        alert("Tarmoq xatosi. Internet aloqasini tekshirib qayta urining.");
                    });
                });
            });

            /* --- vakansiyani o'chirish: real DELETE /ajax/institution/me/vacancies/{id}
               (institution-cabinet Vakansiyalar sahifasi — ADR-0002, Faza 1/2) --- */
            document.querySelectorAll(".js-vacancy-delete").forEach(function (btn) {
                btn.addEventListener("click", function () {
                    var id = btn.dataset.vacancyId;
                    if (!id) return;
                    if (!window.confirm("E'lonni o'chirishni tasdiqlaysizmi?")) return;

                    btn.disabled = true;

                    fetch("/ajax/institution/me/vacancies/" + id, {
                        method: "DELETE",
                        headers: { "Accept": "application/json", "X-CSRF-TOKEN": csrfToken() },
                    }).then(function (res) {
                        if (res.ok) {
                            window.location.reload();
                            return;
                        }
                        btn.disabled = false;
                        alert("E'lonni o'chirib bo'lmadi. Qayta urining.");
                    }).catch(function () {
                        btn.disabled = false;
                        alert("Tarmoq xatosi. Internet aloqasini tekshirib qayta urining.");
                    });
                });
            });

            /* --- nomzod arizasini qabul/rad qilish: real PATCH
               /ajax/institution/me/vacancy-applications/{id}/status (Nomzodlar modali — ADR-0002, Faza 2) --- */
            document.querySelectorAll(".js-vacancy-app-status").forEach(function (btn) {
                btn.addEventListener("click", function () {
                    var id = btn.dataset.applicationId;
                    var status = btn.dataset.status;
                    if (!id || !status) return;

                    btn.disabled = true;

                    jsonFetch("/ajax/institution/me/vacancy-applications/" + id + "/status", "PATCH", { status: status }).then(function (res) {
                        if (res.ok) {
                            window.location.reload();
                            return;
                        }
                        btn.disabled = false;
                        alert("Holatni o'zgartirib bo'lmadi. Qayta urining.");
                    });
                });
            });

            /* --- yutuq qo'shish/tahrirlash: real POST /ajax/institution/me/achievements
               (yaratish) va PUT .../achievements/{id} (tahrirlash) — ADR-0002, Faza 2.
               Ikkalasi ham multipart/form-data (ixtiyoriy sertifikat rasmi uchun). --- */
            function submitAchievementForm(form) {
                var errBox = form.querySelector(".js-form-error");
                var formData = new FormData(form);
                var url = form.dataset.achievementId
                    ? "/ajax/institution/me/achievements/" + form.dataset.achievementId
                    : "/ajax/institution/me/achievements";

                if (form.dataset.achievementId) formData.append("_method", "PUT");

                var submitBtn = form.querySelector(".form-submit");
                if (submitBtn) submitBtn.disabled = true;
                if (errBox) errBox.style.display = "none";

                fetch(url, {
                    method: "POST",
                    headers: { "Accept": "application/json", "X-CSRF-TOKEN": csrfToken() },
                    body: formData,
                }).then(function (res) {
                    return res.json().catch(function () { return {}; }).then(function (body) {
                        return { ok: res.ok, body: body };
                    });
                }).then(function (result) {
                    if (submitBtn) submitBtn.disabled = false;

                    if (!result.ok) {
                        var msg = "Xatolik yuz berdi. Maʼlumotlarni tekshirib qayta urining.";
                        if (result.body) {
                            if (result.body.errors) {
                                var firstKey = Object.keys(result.body.errors)[0];
                                if (firstKey && result.body.errors[firstKey][0]) msg = result.body.errors[firstKey][0];
                            } else if (result.body.message) {
                                msg = result.body.message;
                            }
                        }
                        if (errBox) { errBox.textContent = msg; errBox.style.display = ""; }
                        else alert(msg);
                        return;
                    }

                    window.location.reload();
                }).catch(function () {
                    if (submitBtn) submitBtn.disabled = false;
                    alert("Tarmoq xatosi. Internet aloqasini tekshirib qayta urining.");
                });
            }

            document.querySelectorAll(".js-achievement-form").forEach(function (form) {
                form.addEventListener("submit", function (e) {
                    e.preventDefault();
                    submitAchievementForm(form);
                });
            });

            /* --- yutuqni o'chirish: real DELETE /ajax/institution/me/achievements/{id} --- */
            document.querySelectorAll(".js-achievement-delete").forEach(function (btn) {
                btn.addEventListener("click", function () {
                    var id = btn.dataset.achievementId;
                    if (!id) return;
                    if (!window.confirm("Yutuqni o'chirishni tasdiqlaysizmi?")) return;

                    btn.disabled = true;

                    fetch("/ajax/institution/me/achievements/" + id, {
                        method: "DELETE",
                        headers: { "Accept": "application/json", "X-CSRF-TOKEN": csrfToken() },
                    }).then(function (res) {
                        if (res.ok) {
                            window.location.reload();
                            return;
                        }
                        btn.disabled = false;
                        alert("Yutuqni o'chirib bo'lmadi. Qayta urining.");
                    }).catch(function () {
                        btn.disabled = false;
                        alert("Tarmoq xatosi. Internet aloqasini tekshirib qayta urining.");
                    });
                });
            });
    }());
})();

/* ===== Muassasa "Boshqaruv paneli" dashboard: generic dropdown toggles =====
   Har qanday [data-dd-toggle="menuId"] tugmasi shu id'dagi elementni
   ochib/yopadi (tashkilot select'i va topbar'dagi foydalanuvchi menyusi
   shu orqali ishlaydi). Sahifada mos element bo'lmasa hech narsa qilmaydi. */
(function () {
    "use strict";

    var toggles = Array.prototype.slice.call(document.querySelectorAll("[data-dd-toggle]"));
    if (!toggles.length) return;

    function closeAllDD() {
        document.querySelectorAll("[data-dd-menu]").forEach(function (m) { m.hidden = true; });
    }

    toggles.forEach(function (btn) {
        var menu = document.getElementById(btn.getAttribute("data-dd-toggle"));
        if (!menu) return;
        btn.addEventListener("click", function (e) {
            e.stopPropagation();
            var open = !menu.hidden;
            closeAllDD();
            menu.hidden = open;
        });
        // Diqqat: bu yerda menyu ichidagi bosishlarni "menu.addEventListener('click', stopPropagation)"
        // bilan to'xtatib qo'yish XATO edi — shu sabab "Chiqish" (.js-logout-trigger) va boshqa
        // menyu ichidagi tugmalar hech narsa qilmasdi: ularning click hodisasi document darajasidagi
        // haqiqiy handlerlarga (logout, va h.k.) hech qachon yetib bormasdi (bubble to'xtab qolardi).
        // Endi hech narsa to'xtatilmaydi — menyu ichida real havola/tugma bosilganda o'z ishini
        // qiladi, bo'sh joy bosilsa esa pastdagi document-level closeAllDD orqali yopiladi (kutilgan holat).
    });

    document.addEventListener("click", closeAllDD);
    document.addEventListener("keydown", function (e) { if (e.key === "Escape") closeAllDD(); });
})();

/* ===== Ro'yxat sahifalari (Lidlar, Ekskursiyalar, ...): holat tab filtri + matn qidiruv.
   Umumiy komponent — .js-filter-tab (data-status), .js-filter-row (data-status, data-search)
   va ixtiyoriy .js-filter-search kirish maydoni bo'lgan har qanday sahifada ishlaydi. ===== */
(function () {
    "use strict";
    var tabs = Array.prototype.slice.call(document.querySelectorAll(".js-filter-tab"));
    var rows = Array.prototype.slice.call(document.querySelectorAll(".js-filter-row"));
    var search = document.querySelector(".js-filter-search");
    // Diqqat: faqat "rows" bo'sh (masalan hali hech qanday ariza/lid yo'q) bo'lganda ham
    // tab tugmalari bosilib turishi kerak (keyinchalik qator qo'shilganda ishlashi uchun) —
    // shuning uchun bu yerda faqat "tabs" borligi tekshiriladi, "rows" emas.
    if (!tabs.length) return;

    var activeStatus = "all";

    function applyFilter() {
        var q = search ? search.value.trim().toLowerCase() : "";
        rows.forEach(function (row) {
            var statusOk = activeStatus === "all" || row.dataset.status === activeStatus;
            var text = (row.dataset.search || "").toLowerCase();
            var searchOk = !q || text.indexOf(q) !== -1;
            row.style.display = statusOk && searchOk ? "" : "none";
        });
    }

    tabs.forEach(function (tab) {
        tab.addEventListener("click", function () {
            activeStatus = tab.dataset.status || "all";
            tabs.forEach(function (t) { t.classList.toggle("on", t === tab); });
            applyFilter();
        });
    });

    if (search) search.addEventListener("input", applyFilter);
})();

/* ===== Suhbatlar (institution/parent/teacher-cabinet, umumiy): ro'yxatni qidirish +
   tezkor javob chip'lari + real yuborish + xabarlarni "polling" bilan olish
   (ADR-0003 — Reverb/WebSocket emas, sof AJAX: har 3 sekunda GET so'raladi,
   yangi xabar bo'lsa reload'siz chatga qo'shiladi). Yuborish manzili har bir
   sahifada .js-chat-send-form'ning data-send-url atributidan olinadi; polling
   manzili esa rol-agnostik — barcha rollar uchun bir xil:
   GET /ajax/conversations/{id}/messages?after_id=N. ===== */
(function () {
    "use strict";

    var search = document.querySelector(".js-chat-search");
    var items = Array.prototype.slice.call(document.querySelectorAll(".js-chat-li"));
    if (search && items.length) {
        search.addEventListener("input", function () {
            var q = search.value.trim().toLowerCase();
            items.forEach(function (li) {
                var text = (li.dataset.search || "").toLowerCase();
                li.style.display = !q || text.indexOf(q) !== -1 ? "" : "none";
            });
        });
    }

    var input = document.getElementById("js-chat-input");
    document.querySelectorAll(".js-chat-suggest").forEach(function (btn) {
        btn.addEventListener("click", function () {
            if (!input) return;
            input.value = btn.dataset.text || "";
            input.focus();
        });
    });

    var msgsBox = document.getElementById("js-chat-msgs");
    var form = document.querySelector(".js-chat-send-form");

    function escapeHtml(str) {
        var div = document.createElement("div");
        div.textContent = str == null ? "" : str;
        return div.innerHTML;
    }

    function formatTime(iso) {
        var d = new Date(iso);
        if (isNaN(d.getTime())) return "";
        var h = String(d.getHours()).padStart(2, "0");
        var m = String(d.getMinutes()).padStart(2, "0");
        return h + ":" + m;
    }

    /* Har bir xabar (yuborilgandan keyingi javob YOKI polling natijasi) bir xil
       shaklda keladi (App\Http\Resources\MessageResource): id, sender_type,
       sender_user_id, body, created_at, mine. "mine" — shu suhbatdagi MEN
       (joriy foydalanuvchi) yozganmi, shunga qarab bubble chap/o'ngga chiqadi. */
    function appendMessage(message) {
        if (!msgsBox) return;
        if (msgsBox.querySelector('[data-message-id="' + message.id + '"]')) return; // dedupe

        var emptyState = msgsBox.querySelector(".chat-empty");
        if (emptyState) emptyState.remove();

        var row = document.createElement("div");
        row.className = "bubble-row " + (message.mine ? "me" : "them");
        row.dataset.messageId = message.id;
        row.innerHTML = '<div class="msg-bubble">' + escapeHtml(message.body) +
            '<time>' + formatTime(message.created_at) + "</time></div>";
        msgsBox.appendChild(row);
        msgsBox.scrollTop = msgsBox.scrollHeight;

        var idNum = parseInt(message.id, 10);
        if (idNum > parseInt(msgsBox.dataset.lastId || "0", 10)) {
            msgsBox.dataset.lastId = String(idNum);
        }
    }

    if (form && input) {
        form.addEventListener("submit", function (e) {
            e.preventDefault();
            var text = input.value.trim();
            if (!text) return;

            var sendUrl = form.dataset.sendUrl;
            var sendBtn = form.querySelector(".chat-send");
            if (sendBtn) sendBtn.disabled = true;

            jsonFetch(sendUrl, "POST", { body: text }).then(function (res) {
                if (sendBtn) sendBtn.disabled = false;
                if (res.ok && res.body && res.body.message) {
                    appendMessage(res.body.message);
                    input.value = "";
                    input.focus();
                } else {
                    alert("Xabarni yuborib bo'lmadi. Qayta urining.");
                }
            });
        });
    }

    /* Polling — har 3 sekunda yangi xabarlarni so'raydi. Sahifa fon rejimida
       (boshqa tab ochilganda) to'xtatiladi, qaytib ko'ringanda darhol bir marta
       so'rab, keyin intervalni qayta ishga tushiradi (server yukini kamaytirish). */
    if (msgsBox) {
        var pollUrl = "/ajax/conversations/" + msgsBox.dataset.conversationId + "/messages";
        var pollTimer = null;

        function poll() {
            var afterId = msgsBox.dataset.lastId || "0";
            jsonFetch(pollUrl + "?after_id=" + afterId, "GET").then(function (res) {
                if (res.ok && res.body && res.body.messages) {
                    res.body.messages.forEach(appendMessage);
                }
            });
        }

        function startPolling() {
            if (pollTimer) return;
            poll();
            pollTimer = setInterval(poll, 3000);
        }

        function stopPolling() {
            if (!pollTimer) return;
            clearInterval(pollTimer);
            pollTimer = null;
        }

        document.addEventListener("visibilitychange", function () {
            if (document.hidden) {
                stopPolling();
            } else {
                startPolling();
            }
        });

        if (!document.hidden) startPolling();
    }
})();

/* ===== Checkout: to'lov usuli tanlash (radio ichida yashirin, .idash-pay-item "on" holati) ===== */
(function () {
    "use strict";
    var items = Array.prototype.slice.call(document.querySelectorAll(".idash-pay-item"));
    if (!items.length) return;
    items.forEach(function (item) {
        var radio = item.querySelector('input[type="radio"]');
        if (!radio) return;
        item.addEventListener("click", function () {
            radio.checked = true;
            items.forEach(function (i) { i.classList.toggle("on", i === item); });
        });
    });
})();

/* ===== Analitika: davr tab'lari (Hafta/Oy/Yil) — hozircha faqat vizual holat, real davr filtri yo'q ===== */
(function () {
    "use strict";
    var groups = Array.prototype.slice.call(document.querySelectorAll(".idash-seg"));
    if (!groups.length) return;
    groups.forEach(function (group) {
        var btns = Array.prototype.slice.call(group.querySelectorAll(".js-seg-btn"));
        btns.forEach(function (btn) {
            btn.addEventListener("click", function () {
                btns.forEach(function (b) { b.classList.toggle("on", b === btn); });
            });
        });
    });
})();

/* ===== Ota-ona kabineti: "Profilni tahrirlash" (real PUT /ajax/me) va
   "Farzandlarim" qo'shish/tahrirlash/o'chirish (real POST/PUT/DELETE
   /ajax/children) — parent/dashboard.blade.php, parent/children.blade.php.
   AchievementController JS'idagi bilan bir xil xato ko'rsatish andozasi. ===== */
(function () {
    "use strict";

    function csrfToken() {
        var meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.content : "";
    }

    function jsonFetch(url, method, data) {
        return fetch(url, {
            method: method,
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": csrfToken(),
            },
            body: data !== undefined ? JSON.stringify(data) : undefined,
        }).then(function (res) {
            return res.json().catch(function () { return {}; }).then(function (body) {
                return { ok: res.ok, status: res.status, body: body };
            });
        });
    }

    function errorMessage(body) {
        var msg = "Xatolik yuz berdi. Ma'lumotlarni tekshirib qayta urining.";
        if (body) {
            if (body.errors) {
                var firstKey = Object.keys(body.errors)[0];
                if (firstKey && body.errors[firstKey][0]) msg = body.errors[firstKey][0];
            } else if (body.message) {
                msg = body.message;
            }
        }
        return msg;
    }

    function showFormError(form, msg) {
        var errBox = form.querySelector(".js-form-error");
        if (errBox) { errBox.textContent = msg; errBox.style.display = ""; }
        else alert(msg);
    }

    /* --- Profilni tahrirlash: real PUT /ajax/me --- */
    document.querySelectorAll(".js-parent-profile-form").forEach(function (form) {
        form.addEventListener("submit", function (e) {
            e.preventDefault();

            var errBox = form.querySelector(".js-form-error");
            if (errBox) errBox.style.display = "none";

            var data = {
                name: (form.querySelector('[name="name"]') || {}).value || "",
                phone: (form.querySelector('[name="phone"]') || {}).value || "",
                district: (form.querySelector('[name="district"]') || {}).value || "",
            };

            var submitBtn = form.querySelector(".form-submit");
            if (submitBtn) submitBtn.disabled = true;

            jsonFetch("/ajax/me", "PUT", data).then(function (res) {
                if (submitBtn) submitBtn.disabled = false;
                if (!res.ok) { showFormError(form, errorMessage(res.body)); return; }
                window.location.reload();
            }).catch(function () {
                if (submitBtn) submitBtn.disabled = false;
                showFormError(form, "Tarmoq xatosi. Internet aloqasini tekshirib qayta urining.");
            });
        });
    });

    /* --- Farzand qo'shish/tahrirlash: real POST /ajax/children (yaratish) va
       PUT /ajax/children/{id} (tahrirlash). Jinsi/qiziqishlar submit paytida
       .choice-btn.on / .chip.on'dan o'qiladi (vizual toggle allaqachon mavjud). --- */
    document.querySelectorAll(".js-child-form").forEach(function (form) {
        form.addEventListener("submit", function (e) {
            e.preventDefault();

            var errBox = form.querySelector(".js-form-error");
            if (errBox) errBox.style.display = "none";

            var genderBtn = form.querySelector(".js-child-gender .choice-btn.on");
            var interests = Array.prototype.slice
                .call(form.querySelectorAll(".js-child-interests .chip.on"))
                .map(function (c) { return c.dataset.interest; });

            var data = {
                name: (form.querySelector('[name="name"]') || {}).value || "",
                last_name: (form.querySelector('[name="last_name"]') || {}).value || "",
                age: (form.querySelector('[name="age"]') || {}).value || "",
                gender: genderBtn ? genderBtn.dataset.gender : "",
                interests: interests,
            };

            var childId = form.dataset.childId;
            var url = childId ? "/ajax/children/" + childId : "/ajax/children";
            var method = childId ? "PUT" : "POST";

            var submitBtn = form.querySelector(".form-submit");
            if (submitBtn) submitBtn.disabled = true;

            jsonFetch(url, method, data).then(function (res) {
                if (submitBtn) submitBtn.disabled = false;
                if (!res.ok) { showFormError(form, errorMessage(res.body)); return; }
                window.location.reload();
            }).catch(function () {
                if (submitBtn) submitBtn.disabled = false;
                showFormError(form, "Tarmoq xatosi. Internet aloqasini tekshirib qayta urining.");
            });
        });
    });

    /* --- Farzandni o'chirish: real DELETE /ajax/children/{id} --- */
    document.querySelectorAll(".js-child-delete").forEach(function (btn) {
        btn.addEventListener("click", function () {
            var id = btn.dataset.childId;
            if (!id) return;
            if (!window.confirm("Farzand profilini o'chirishni tasdiqlaysizmi?")) return;

            btn.disabled = true;

            fetch("/ajax/children/" + id, {
                method: "DELETE",
                headers: { "Accept": "application/json", "X-CSRF-TOKEN": csrfToken() },
            }).then(function (res) {
                if (res.ok) { window.location.reload(); return; }
                btn.disabled = false;
                alert("Farzandni o'chirib bo'lmadi. Qayta urining.");
            }).catch(function () {
                btn.disabled = false;
                alert("Tarmoq xatosi. Internet aloqasini tekshirib qayta urining.");
            });
        });
    });

    /* --- Muassasa kabineti: ko'p-filial — tashkilot almashtirish, real
       PATCH /ajax/institution/me/active (ResolvesActiveInstitution, 2026-07-15) --- */
    document.querySelectorAll(".js-org-switch").forEach(function (btn) {
        btn.addEventListener("click", function () {
            if (btn.disabled) return;
            var id = btn.dataset.institutionId;
            if (!id) return;

            btn.disabled = true;

            jsonFetch("/ajax/institution/me/active", "PATCH", { institution_id: id }).then(function (res) {
                if (!res.ok) {
                    btn.disabled = false;
                    alert(errorMessage(res.body));
                    return;
                }
                window.location.reload();
            }).catch(function () {
                btn.disabled = false;
                alert("Tarmoq xatosi. Internet aloqasini tekshirib qayta urining.");
            });
        });
    });

    /* --- Muassasa kabineti: "Yangi muassasa qo'shish", real
       POST /ajax/institution/me/organizations — qo'shilgach darhol faol bo'ladi --- */
    document.querySelectorAll(".js-org-add-form").forEach(function (form) {
        form.addEventListener("submit", function (e) {
            e.preventDefault();

            var errBox = form.querySelector(".js-form-error");
            if (errBox) errBox.style.display = "none";

            var data = {
                name: (form.querySelector('[name="name"]') || {}).value || "",
                type: (form.querySelector('[name="type"]') || {}).value || "",
                district: (form.querySelector('[name="district"]') || {}).value || "",
            };

            var submitBtn = form.querySelector(".form-submit");
            if (submitBtn) submitBtn.disabled = true;

            jsonFetch("/ajax/institution/me/organizations", "POST", data).then(function (res) {
                if (submitBtn) submitBtn.disabled = false;
                if (!res.ok) { showFormError(form, errorMessage(res.body)); return; }
                window.location.reload();
            }).catch(function () {
                if (submitBtn) submitBtn.disabled = false;
                showFormError(form, "Tarmoq xatosi. Internet aloqasini tekshirib qayta urining.");
            });
        });
    });
})();

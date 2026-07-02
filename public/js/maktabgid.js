(function () {
    "use strict";

    var CAT_LABEL = { maktab: "maktab", bogcha: "bogʻcha", markaz: "oʻquv markazi", mutaxassis: "mutaxassis" };

    /* ---------------- shared: favorite heart toggle (real API, backend.md Phase 4) ---------------- */
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

    /* ===================== DESKTOP RESULTS ===================== */
    var grid = document.getElementById("js-results-grid");
    if (grid) {
        var cards = Array.prototype.slice.call(document.querySelectorAll(".js-scard"));
        var pins = Array.prototype.slice.call(document.querySelectorAll(".js-map-pin"));
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

        var DISTANCE_MAX = { "1": 1, "3": 3, "5": 5, "5+": Infinity };
        var PRICE_RANGE = {
            lt2: [0, 2000000], "2-3.5": [2000000, 3500000], "3.5-5": [3500000, 5000000],
            "5-7": [5000000, 7000000], "7+": [7000000, Infinity],
        };

        var state = { cat: "maktab", query: "", district: "", sat: false, distance: "", price: "", districts: [], sort: "rel", spec: "" };

        function setActiveCatButtons() {
            document.querySelectorAll(".js-cat").forEach(function (b) {
                b.classList.toggle("on", b.dataset.cat === state.cat);
            });
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
                rel: function (a, b) { return parseFloat(b.dataset.rating) - parseFloat(a.dataset.rating); },
                priceA: function (a, b) { return parseFloat(a.dataset.price) - parseFloat(b.dataset.price); },
                priceD: function (a, b) { return parseFloat(b.dataset.price) - parseFloat(a.dataset.price); },
                dist: function (a, b) { return parseFloat(a.dataset.dist) - parseFloat(b.dataset.dist); },
                rating: function (a, b) { return parseFloat(b.dataset.rating) - parseFloat(a.dataset.rating); },
            };
            return list.slice().sort(sorters[state.sort] || sorters.rel);
        }

        function render() {
            setActiveCatButtons();

            var visible = sortCards(applyFilters());
            var visibleIds = visible.map(function (c) { return c.dataset.id; });

            cards.forEach(function (card) { card.style.display = "none"; });
            visible.forEach(function (card) { card.style.display = ""; cardList.appendChild(card); });

            pins.forEach(function (pin) {
                pin.style.display = visibleIds.indexOf(pin.dataset.id) !== -1 ? "" : "none";
            });

            countEl.textContent = visible.length + " ta " + CAT_LABEL[state.cat];
            if (mapTag) mapTag.lastChild.textContent = " " + visible.length + " ta natija xaritada";
            emptyBox.style.display = visible.length === 0 ? "" : "none";
            cardList.style.display = visible.length === 0 ? "none" : "";
        }

        document.querySelectorAll(".js-cat").forEach(function (btn) {
            btn.addEventListener("click", function () { state.cat = btn.dataset.cat; render(); });
        });

        document.querySelectorAll(".js-spec-tile").forEach(function (tile) {
            tile.addEventListener("click", function () {
                var picked = tile.dataset.spec;
                state.spec = state.spec === picked ? "" : picked;
                document.querySelectorAll(".js-spec-tile").forEach(function (t) {
                    t.classList.toggle("on", t.dataset.spec === state.spec);
                });
                var natijalar = document.getElementById("natijalar");
                if (natijalar) natijalar.scrollIntoView({ behavior: "smooth" });
                render();
            });
        });

        if (queryInput) queryInput.addEventListener("input", function () { state.query = queryInput.value.trim(); render(); });
        if (districtSelect) districtSelect.addEventListener("change", function () { state.district = districtSelect.value; render(); });
        if (sortSelect) sortSelect.addEventListener("change", function () { state.sort = sortSelect.value; render(); });
        if (searchGoBtn) searchGoBtn.addEventListener("click", function () {
            var target = document.getElementById("natijalar");
            if (target) target.scrollIntoView({ behavior: "smooth" });
        });

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
        function pinFor(id) { return pins.find ? pins.find(function (p) { return p.dataset.id === id; }) : null; }
        cards.forEach(function (card) {
            card.addEventListener("mouseenter", function () {
                var pin = pinFor(card.dataset.id);
                if (pin) pin.classList.add("hot");
            });
            card.addEventListener("mouseleave", function () {
                var pin = pinFor(card.dataset.id);
                if (pin) pin.classList.remove("hot");
            });
        });
        pins.forEach(function (pin) {
            pin.addEventListener("click", function () {
                pins.forEach(function (p) { p.classList.remove("active"); });
                pin.classList.add("active");
            });
        });

        render();
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

    /* ===================== AUTH MODAL & SESSION (real backend, backend.md §5) ===================== */
    (function () {
        /* --- dropdown helpers (auth holatidan mustaqil) --- */
        var _userMenu = document.getElementById("js-user-menu");
        var _langMenu = document.getElementById("js-lang-menu");

        function closeAll() {
            if (_userMenu) _userMenu.style.display = "none";
            if (_langMenu) _langMenu.style.display = "none";
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

        var langBtn = document.getElementById("js-lang-btn");
        var langLabel = document.getElementById("js-lang-label");
        if (langBtn && _langMenu) {
            langBtn.addEventListener("click", function (e) {
                e.stopPropagation();
                var open = _langMenu.style.display !== "none";
                closeAll();
                if (!open) _langMenu.style.display = "";
            });
            _langMenu.querySelectorAll("button[data-lang]").forEach(function (btn) {
                btn.addEventListener("click", function () {
                    if (langLabel) langLabel.textContent = btn.dataset.label || btn.textContent.trim();
                    _langMenu.style.display = "none";
                });
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
            var btn = e.target.closest("#js-nav-logout, #js-logout-btn, #js-inst-logout");
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

                    /* muvaffaqiyatli: sahifa qayta yuklanadi — nav/kabinet serverda to'g'ri render bo'ladi */
                    window.location.reload();
                });
            });
        });

        /* --- kabinet tab almashish (ma'lumot endi serverda render qilingan) --- */
        var tabBtns = Array.prototype.slice.call(document.querySelectorAll(".js-cab-tab"));
        var panels = Array.prototype.slice.call(document.querySelectorAll(".js-cab-panel"));
        tabBtns.forEach(function (btn) {
            btn.addEventListener("click", function () {
                tabBtns.forEach(function (b) { b.classList.toggle("on", b === btn); });
                panels.forEach(function (p) {
                    p.style.display = p.dataset.panel === btn.dataset.tab ? "block" : "none";
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

            function updatePreview() {
                var name = document.getElementById("js-f-name");
                var kind = document.getElementById("js-f-kind");
                var lang = document.getElementById("js-f-lang");
                var district = document.getElementById("js-f-district");
                var grades = document.getElementById("js-f-grades");
                var hours = document.getElementById("js-f-hours");
                var price = document.getElementById("js-f-price");
                var nameVal = name ? name.value : "";
                var kindVal = kind ? kind.value : "maktab";
                var kindMap = { maktab: "Maktab", bogcha: "Bog'cha", markaz: "O'quv markazi" };

                var gradesLabel = document.getElementById("js-f-grades-label");
                if (gradesLabel) gradesLabel.textContent = kindVal === "maktab" ? "Sinflar" : "Yosh oralig'i";

                var pMono = document.getElementById("js-prev-mono");
                var pName = document.getElementById("js-prev-name");
                var pKind = document.getElementById("js-prev-kind");
                var pLang = document.getElementById("js-prev-lang");
                var pDistrict = document.getElementById("js-prev-district");
                var pGrades = document.getElementById("js-prev-grades");
                var pHours = document.getElementById("js-prev-hours");
                var pPrice = document.getElementById("js-prev-price");

                if (pMono) pMono.textContent = nameVal ? nameVal.trim().slice(0, 2).toUpperCase() : "?";
                if (pName) pName.textContent = nameVal || "Muassasa nomi";
                if (pKind) pKind.textContent = kindMap[kindVal] || "Maktab";
                if (pLang) pLang.textContent = lang ? lang.value : "Ingliz";
                if (pDistrict) pDistrict.textContent = (district && district.value) ? district.value : "Tuman";
                if (pGrades) pGrades.textContent = (grades && grades.value) ? grades.value : "—";
                if (pHours) pHours.textContent = (hours && hours.value) ? hours.value : "08:00 – 18:00";
                if (pPrice) {
                    var priceVal = price ? parseInt(price.value, 10) : 0;
                    pPrice.innerHTML = priceVal > 0
                        ? "<b>" + fmtPrice(priceVal) + "</b> <span>so'm / oy</span>"
                        : "<span>Narx kelishilgan</span>";
                }
            }

            ["js-f-name", "js-f-kind", "js-f-lang", "js-f-district", "js-f-grades", "js-f-hours", "js-f-price"].forEach(function (id) {
                var el = document.getElementById(id);
                if (el) el.addEventListener("input", updatePreview);
            });
            updatePreview();

            /* --- shanba toggle (faqat vizual — saqlashda o'qiladi) --- */
            var satToggle = document.getElementById("js-sat-toggle");
            var satLabel = document.getElementById("js-sat-label");
            var prevSat = document.getElementById("js-prev-sat");
            if (satToggle) {
                satToggle.addEventListener("click", function () {
                    var on = !satToggle.classList.contains("on");
                    satToggle.classList.toggle("on", on);
                    if (satLabel) satLabel.textContent = on ? "Ishlaydi" : "Dam olish";
                    if (prevSat) prevSat.style.display = on ? "" : "none";
                });
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

            /* --- saqlash: real PUT /ajax/institution/me --- */
            var saveBtn = document.getElementById("js-inst-save");
            var savedPill = document.getElementById("js-saved-pill");
            if (saveBtn) {
                saveBtn.addEventListener("click", function () {
                    var specs = Array.prototype.slice.call(document.querySelectorAll("#js-spec-chips .chip.on"))
                        .map(function (c) { return c.dataset.spec; });

                    var priceEl = document.getElementById("js-f-price");
                    var priceVal = priceEl && priceEl.value ? parseInt(priceEl.value, 10) : null;

                    var payload = {
                        name: (document.getElementById("js-f-name") || {}).value || "",
                        type: (document.getElementById("js-f-kind") || {}).value || "maktab",
                        lang: (document.getElementById("js-f-lang") || {}).value || "",
                        about: (document.getElementById("js-f-about") || {}).value || "",
                        district: (document.getElementById("js-f-district") || {}).value || "",
                        address: (document.getElementById("js-f-address") || {}).value || "",
                        monthly_price: priceVal,
                        grades: (document.getElementById("js-f-grades") || {}).value || "",
                        work_hours: (document.getElementById("js-f-hours") || {}).value || "",
                        works_saturday: !!(satToggle && satToggle.classList.contains("on")),
                        specializations: specs,
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

                        /* muvaffaqiyatli: sahifani to'liq qayta yuklamasdan slotni yangilaymiz */
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
        }
    }());
})();

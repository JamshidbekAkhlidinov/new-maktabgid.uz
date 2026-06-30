(function () {
    "use strict";

    var CAT_LABEL = { maktab: "maktab", bogcha: "bogʻcha", markaz: "oʻquv markazi", mutaxassis: "mutaxassis" };

    /* ---------------- shared: favorite heart toggle ---------------- */
    document.addEventListener("click", function (e) {
        var fav = e.target.closest(".js-fav");
        if (!fav) return;
        e.stopPropagation();
        fav.classList.toggle("on");
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

    /* ===================== DETAIL PAGE: modals + fake forms ===================== */
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

    /* fake submit -> success swap (no backend target exists for these forms) */
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

    /* ===================== AUTH MODAL ===================== */
    (function () {
        /* --- helpers: localStorage user --- */
        function getUser() {
            try { return JSON.parse(localStorage.getItem("mg_user") || "null"); } catch (e) { return null; }
        }
        function saveUser(u) {
            try { localStorage.setItem("mg_user", JSON.stringify(u)); } catch (e) {}
        }
        function clearUser() {
            try { localStorage.removeItem("mg_user"); } catch (e) {}
        }

        /* --- monogram helper --- */
        function monogram(name) {
            if (!name) return "?";
            var parts = name.trim().split(/\s+/);
            if (parts.length >= 2) return (parts[0][0] + parts[1][0]).toUpperCase();
            return name.slice(0, 2).toUpperCase();
        }

        /* --- update nav buttons based on auth state --- */
        function syncNav() {
            var user = getUser();
            var kirishBtn = document.getElementById("js-kirish-btn");
            var userNav   = document.getElementById("js-user-nav");
            var navAvatar = document.getElementById("js-nav-avatar");
            var navName   = document.getElementById("js-nav-name");
            if (!kirishBtn || !userNav) return;
            if (user) {
                kirishBtn.style.display = "none";
                userNav.style.display = "";
                if (navAvatar) navAvatar.textContent = monogram(user.name || user.org || "");
                if (navName) navName.textContent = (user.name || user.org || "").split(" ")[0];
            } else {
                kirishBtn.style.display = "";
                userNav.style.display = "none";
            }
        }

        /* --- dropdown helpers --- */
        var _userMenu = document.getElementById("js-user-menu");
        var _langMenu = document.getElementById("js-lang-menu");

        function closeAll() {
            if (_userMenu) _userMenu.style.display = "none";
            if (_langMenu) _langMenu.style.display = "none";
        }

        /* user menu toggle */
        var userMenuBtn = document.getElementById("js-user-menu-btn");
        if (userMenuBtn && _userMenu) {
            userMenuBtn.addEventListener("click", function (e) {
                e.stopPropagation();
                var open = _userMenu.style.display !== "none";
                closeAll();
                if (!open) _userMenu.style.display = "";
            });
        }

        /* lang menu toggle */
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

        /* close all dropdowns when clicking outside */
        document.addEventListener("click", function () { closeAll(); });

        /* nav cabinet link */
        var navCabinetBtn = document.getElementById("js-nav-cabinet");
        if (navCabinetBtn) {
            navCabinetBtn.addEventListener("click", function () {
                var u = getUser();
                window.location.href = u && u.kind === "institution" ? "/institution-cabinet" : "/cabinet";
            });
        }

        /* --- auth panel switching --- */
        document.querySelectorAll(".js-auth-switch").forEach(function (btn) {
            btn.addEventListener("click", function () {
                var target = btn.dataset.target;
                document.querySelectorAll(".auth-panel").forEach(function (p) {
                    p.style.display = p.dataset.panel === target ? "block" : "none";
                });
            });
        });

        /* --- logout (event delegation: catches nav, cabinet, institution sidebars) --- */
        document.addEventListener("click", function (e) {
            var btn = e.target.closest("#js-nav-logout, #js-logout-btn, #js-inst-logout");
            if (!btn) return;
            clearUser();
            window.location.href = "/";
        });

        /* --- fake auth form submit --- */
        document.querySelectorAll(".js-fake-auth").forEach(function (form) {
            form.addEventListener("submit", function (e) {
                e.preventDefault();
                var mode = form.dataset.mode;
                var data = {};
                var els = form.elements;
                for (var i = 0; i < els.length; i++) {
                    if (els[i].name) data[els[i].name] = els[i].value;
                }
                var user = { kind: mode === "institution" ? "institution" : "parent" };
                if (mode === "institution") {
                    user.name = data.name || data.org || "Muassasa";
                    user.org = data.org || "";
                    user.phone = data.phone || "";
                } else {
                    user.name = data.name || "Foydalanuvchi";
                    user.phone = data.phone || "";
                    user.age = data.age || "";
                    user.district = data.district || "";
                }
                saveUser(user);
                syncNav();
                /* close modal */
                var modal = document.getElementById("auth-modal");
                if (modal) {
                    modal.hidden = true;
                    document.body.classList.remove("modal-open");
                }
                /* redirect: institution → /institution-cabinet, parent/login → /cabinet */
                window.location.href = user.kind === "institution" ? "/institution-cabinet" : "/cabinet";
            });
        });

        /* --- cabinet page init --- */
        var cabBody = document.getElementById("js-cab-body");
        var cabGuest = document.getElementById("js-cab-guest");
        if (cabBody) {
            var user = getUser();
            if (!user) {
                cabGuest.style.display = "";
            } else {
                cabBody.style.display = "";
                /* populate page head title */
                var pageHead = document.querySelector(".pagehead h1");
                if (pageHead) {
                    var firstName = (user.name || user.org || "").split(" ")[0];
                    pageHead.textContent = "Assalomu alaykum, " + firstName + "!";
                }
                /* populate rail */
                var cabAvatar = document.getElementById("js-cab-avatar");
                var cabName = document.getElementById("js-cab-name");
                var cabPhone = document.getElementById("js-cab-phone");
                if (cabAvatar) cabAvatar.textContent = monogram(user.name || user.org || "");
                if (cabName) cabName.textContent = user.name || user.org || "—";
                if (cabPhone) cabPhone.textContent = user.phone || "—";
                /* populate kv grid */
                var kvName = document.getElementById("js-kv-name");
                var kvPhone = document.getElementById("js-kv-phone");
                var kvAge = document.getElementById("js-kv-age");
                var kvDistrict = document.getElementById("js-kv-district");
                if (kvName) kvName.textContent = user.name || user.org || "—";
                if (kvPhone) kvPhone.textContent = user.phone || "—";
                if (kvAge) kvAge.textContent = user.age || "—";
                if (kvDistrict) kvDistrict.textContent = user.district || "—";
            }

            /* cabinet tab switching */
            var tabBtns = Array.prototype.slice.call(document.querySelectorAll(".js-cab-tab"));
            var panels   = Array.prototype.slice.call(document.querySelectorAll(".js-cab-panel"));
            tabBtns.forEach(function (btn) {
                btn.addEventListener("click", function () {
                    tabBtns.forEach(function (b) { b.classList.toggle("on", b === btn); });
                    panels.forEach(function (p) {
                        p.style.display = p.dataset.panel === btn.dataset.tab ? "block" : "none";
                    });
                });
            });
        }

        /* ===================== INSTITUTION CABINET ===================== */
        var instBody = document.getElementById("js-inst-body");
        var instGuest = document.getElementById("js-inst-guest");
        if (instBody) {
            var instUser = getUser();
            if (!instUser || instUser.kind !== "institution") {
                instGuest.style.display = "";
            } else {
                instBody.style.display = "";
                /* populate rail */
                var instAvatar = document.getElementById("js-inst-avatar");
                var instName = document.getElementById("js-inst-name");
                var instKind = document.getElementById("js-inst-kind");
                var kindLabels = { maktab: "Xususiy maktab", bogcha: "Xususiy bog'cha", markaz: "O'quv markazi" };
                if (instAvatar) instAvatar.textContent = monogram(instUser.org || instUser.name || "");
                if (instName) instName.textContent = instUser.org || instUser.name || "—";
                if (instKind) instKind.textContent = kindLabels[instUser.orgKind] || "Muassasa";
                /* pre-fill form from user data */
                var fName = document.getElementById("js-f-name");
                var fKind = document.getElementById("js-f-kind");
                if (fName && instUser.org) fName.value = instUser.org;
                if (fKind && instUser.orgKind) fKind.value = instUser.orgKind;
                /* update page head */
                var instHead = document.querySelector(".pagehead h1");
                if (instHead) instHead.textContent = instUser.org || "Muassasa kabineti";

                /* --- live preview helpers --- */
                function monogramInst(s) { return monogram(s || ""); }
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
                    /* grades label */
                    var gradesLabel = document.getElementById("js-f-grades-label");
                    if (gradesLabel) gradesLabel.textContent = kindVal === "maktab" ? "Sinflar" : "Yosh oralig'i";
                    /* preview fields */
                    var pMono = document.getElementById("js-prev-mono");
                    var pName = document.getElementById("js-prev-name");
                    var pKind = document.getElementById("js-prev-kind");
                    var pLang = document.getElementById("js-prev-lang");
                    var pDistrict = document.getElementById("js-prev-district");
                    var pGrades = document.getElementById("js-prev-grades");
                    var pHours = document.getElementById("js-prev-hours");
                    var pPrice = document.getElementById("js-prev-price");
                    if (pMono) pMono.textContent = monogramInst(nameVal) || "?";
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

                /* wire all form inputs to live preview */
                ["js-f-name","js-f-kind","js-f-lang","js-f-district","js-f-grades","js-f-hours","js-f-price"].forEach(function (id) {
                    var el = document.getElementById(id);
                    if (el) el.addEventListener("input", updatePreview);
                });
                updatePreview();

                /* --- sat toggle --- */
                var satToggle = document.getElementById("js-sat-toggle");
                var satLabel = document.getElementById("js-sat-label");
                var prevSat = document.getElementById("js-prev-sat");
                var satOn = true;
                if (satToggle) {
                    satToggle.addEventListener("click", function () {
                        satOn = !satOn;
                        satToggle.classList.toggle("on", satOn);
                        if (satLabel) satLabel.textContent = satOn ? "Ishlaydi" : "Dam olish";
                        if (prevSat) prevSat.style.display = satOn ? "" : "none";
                    });
                }

                /* --- accepting toggle --- */
                var acceptCard = document.getElementById("js-accept-card");
                var acceptToggle = document.getElementById("js-accept-toggle");
                var acceptText = document.getElementById("js-accept-text");
                var prevBadge = document.getElementById("js-prev-badge");
                var accepting = true;
                if (acceptToggle) {
                    acceptToggle.addEventListener("click", function () {
                        accepting = !accepting;
                        acceptToggle.classList.toggle("on", accepting);
                        if (acceptCard) acceptCard.classList.toggle("on", accepting);
                        if (acceptText) acceptText.textContent = accepting ? "Arizalar qabul qilinmoqda" : "Qabul vaqtincha yopiq";
                        if (prevBadge) prevBadge.style.display = accepting ? "" : "none";
                    });
                }

                /* --- spec chips --- */
                document.querySelectorAll("#js-spec-chips .chip").forEach(function (chip) {
                    chip.addEventListener("click", function () { chip.classList.toggle("on"); });
                });

                /* --- save button --- */
                var saveBtn = document.getElementById("js-inst-save");
                var savedPill = document.getElementById("js-saved-pill");
                if (saveBtn) {
                    saveBtn.addEventListener("click", function () {
                        if (savedPill) { savedPill.style.display = ""; }
                        setTimeout(function () { if (savedPill) savedPill.style.display = "none"; }, 3000);
                    });
                }
            }

            /* institution tabs */
            var instTabBtns = Array.prototype.slice.call(document.querySelectorAll(".js-inst-tab"));
            var instPanels   = Array.prototype.slice.call(document.querySelectorAll(".js-inst-panel"));
            instTabBtns.forEach(function (btn) {
                btn.addEventListener("click", function () {
                    instTabBtns.forEach(function (b) { b.classList.toggle("on", b === btn); });
                    instPanels.forEach(function (p) {
                        p.style.display = p.dataset.panel === btn.dataset.tab ? "block" : "none";
                    });
                });
            });

        }

        syncNav();
    }());
})();

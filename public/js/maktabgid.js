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

        var state = { cat: "maktab", query: "", district: "", sat: false, distance: "", price: "", districts: [], sort: "rel" };

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
})();

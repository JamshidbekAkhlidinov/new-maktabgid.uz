# ADR-0002: MaktabGID — 4 rol, kabinetlar va saytni to'liq dinamiklashtirish

**Status:** Accepted (Faza 1 shu ADR bilan birga amalga oshirildi)
**Sana:** 2026-07-13
**Qaror qabul qiluvchilar:** Jamshidbek (loyiha egasi)

> Bu hujjat ADR-0001 (4 rol tizimi — `/admin/roles`, `PermissionSeeder`, login redirect, demo seederlar) dan keyingi bosqich. ADR-0001 rollarni tizimga kiritdi; bu ADR kabinetlar/landing bo'yicha real audit natijasini, yetishmayotgan jadvallarni va ularni tuzatish rejasini hujjatlashtiradi.

## Context

Loyiha — Laravel 13 / PHP 8.3, SQLite (dev), Blade + vanilla JS frontend. To'rtta rol: **Parent** (ota-ona), **Institution Admin** (muassasa/manager), **Teacher** (ustoz), **Super Admin**. Har biri o'z kabinetiga ega: `/cabinet`, `/institution-cabinet`, `/teacher-cabinet`, `/admin`.

Loyiha boshida (`backend.md`) "hamma narsa mock" deb yozilgan edi, lekin amaldagi holatni to'liq audit qilganda (barcha controller/migratsiya/view'lar o'qib chiqildi) rasm ancha yaxshi chiqdi:

**Allaqachon real (DB'dan o'qiydi/yozadi):**
- Bosh sahifa katalogi, `/maktab/{id}` — `Institution` + `District` + `Specialization` + `InstitutionMedia`, `App\Support\MaktabgidData` endi **read-adapter** (docblokida yozilgan: "hammasi endi Eloquent orqali real bazadan o'qiladi", faqat Blade'lar o'zgarmasin deb eski metod imzolari saqlangan).
- `/forum`, `/forum/{id}` — `ForumThread`/`ForumReply` real, lekin **yozish yo'li yo'q** (POST endpoint umuman yo'q).
- `/blog`, `/yangiliklar` — `Article`/`News`, admin CRUD (`Admin\ArticleController`/`Admin\NewsController`) real yozadi, sahifada darhol ko'rinadi.
- `/vakansiyalar` — `Vacancy`/`Resume` o'qish tomoni real, admin CRUD real yozadi.
- Sharhlar (`Review`) — sharh ro'yxati va yulduz taqsimoti (`ratingBars`) real; LEKIN `institutions.rating`/`review_count` (katalog kartochkasidagi yulduz belgisi) — qo'lda kiritiladigan alohida ustun, `Review` jadvalidan avtomatik hisoblanmaydi (backend.md §3'da "model observer" rejalashtirilgan edi, lekin yozilmagan).
- Ota-ona kabineti (`/cabinet`) — profil, saqlanganlar, arizalar, suhbatlar to'liq real.
- Muassasa kabineti (`/institution-cabinet`) — dashboard, ekskursiyalar, suhbatlar, profil to'liq real. Lekin: **Lidlar**, **Analitika**, **O'qituvchilar**, **Yutuqlar**, **Rasmlar (galereya)**, **Vakansiyalar**, **Tariflar/checkout** — kod ichida aniq izohlangan mock.
- Ustoz kabineti (`/teacher-cabinet`) — faqat ism/telefon/tuman real (`User` dan); rezyume/vakansiya/taklif/suhbat sonlari va ro'yxatlari qattiq kodlangan.

**Muhim topilma — ataylab "pullik demo" bo'lgan joylar:** Muassasa kabinetidagi "Vakansiya ochish" (100 000 so'm) va ustoz kabinetidagi "Yangi rezyume" (30 000 so'm) formalari **ataylab** `.js-fake-form` bilan ishlaydi — bu xato emas, to'lov tizimi (Payme/Click) hali ulanmagani uchun ongli ravishda demo holatda qoldirilgan biznes-qaror. Bu ADR ularni "tuzatib" pullik bosqichni chetlab o'tmaydi — bu alohida, kelajakdagi to'lov integratsiyasi ishi.

Shu bilan bir qatorda, `/vakansiyalar` (ommaviy careers sahifasi)dagi "Rezyume joylash"/"Vakansiya e'lon qilish" modallarida pul haqida hech narsa yo'q ("moderatsiyadan so'ng ko'rinadi" deyilgan) — bu ikkinchi, tekin oqim sifatida ko'rinadi va `backend.md §6`da aynan shunday hujjatlashtirilgan: `POST /ajax/vacancies (auth)`, `POST /ajax/resumes (auth)`. Demak bu ikkalasi (tekin ommaviy forma vs pullik kabinet formasi) ataylab ikkita boshqa oqim — kelajakda mahsulot darajasida kelishilishi kerak (masalan: "bepul 1 ta e'lon, qo'shimchalari pullik"), lekin bu ADR doirasida **backend.md'dagi hujjatlashtirilgan tekin oqim** amalga oshiriladi, chunki u aniq spetsifikatsiya sifatida yozilgan.

## Decision

Ishni ikki fazaga bo'lamiz.

### Faza 1 — shu ADR bilan birga amalga oshirildi (past risk, mavjud infratuzilmaga tayanadi)

1. **`ReviewObserver`** — `Review` yaratilganda/yangilanganda/o'chirilganda tegishli `Institution.rating`/`review_count` avtomatik qayta hisoblanadi (backend.md §3'da rejalashtirilgan, endi yozildi).
2. **Muassasa kabineti → Rasmlar (galereya)** — `institution.gallery` real `InstitutionMedia::where('type','gallery')` bilan ishlaydi (yuklash/o'chirish infratuzilmasi — `Institution\MediaController`, `.js-media-upload` JS — allaqachon tayyor edi, faqat ro'yxat mock massivdan kelayotgan edi).
3. **Muassasa kabineti → Vakansiyalar (ro'yxat)** — mock 4 ta yozuv o'rniga `institution->vacancies()` orqali real ro'yxat ko'rsatiladi (yaratish formasi hali pullik-demo bo'lib qoladi — yuqoridagi izohga qarang).
4. **Ustoz kabineti → Rezyumelarim (ro'yxat)** — mock 2 ta yozuv o'rniga `Resume::where('owner_user_id', $user->id)` orqali real ro'yxat (yaratish formasi hali pullik-demo).
5. **Ommaviy `/vakansiyalar` sahifasi — real yozish yo'li**: `POST /ajax/vacancies` va `POST /ajax/resumes` (auth, backend.md §6 bo'yicha) — `careers.blade.php` dagi ikkita modal `.js-fake-form`dan real `fetch()`ga o'tkaziladi.

### Faza 2 — amalga oshirildi (rejalashtirilgan holat, keyin qanday bajarilgani "Natija" ustunida)

| # | Bo'lim | Reja | Natija |
|---|---|---|---|
| 1 | Forum yozish yo'li | `ThreadController`/`ReplyController`/`LikeController` + UI | **Bajarildi.** UI stub'lar (`.js-fav`, "Yangi mavzu" tugmasi) allaqachon markupda bor edi, faqat ulanmagan edi — real `fetch()`ga ulandi, yangi jadval kerak bo'lmadi. |
| 2 | Vakansiyaga ariza / taklif | Ustoz vakansiyaga ariza yuboradi, muassasa "Nomzodlar"da ko'radi | **Bajarildi**, rejadagidek yangi **`vacancy_applications`** jadvali bilan (`vacancy_id, teacher_user_id nullable, full_name, phone, note, status`). Mehmon ham ariza qoldira oladi (`teacher_user_id` nullable) — `applications` jadvalidagi mehmon-ariza qoidasi bilan bir xil. |
| 3 | Ustoz ↔ muassasa suhbati | `conversations`ni kengaytirish (`parent_user_id` nullable) | **Kechiktirildi.** `->change()` (nullable qilish) `doctrine/dbal` talab qiladi — loyihada o'rnatilmagan, CLAUDE.md ruxsatisiz yangi dependency qo'shishni taqiqlaydi. Yechim (dbal qo'shish yoki alohida jadval) mahsulot darajasidagi qaror, alohida so'rov bilan qaytiladi. |
| 4 | Lidlar (CRM) | Alohida `leads` jadvali | **Bajarildi, lekin rejadan farqli.** Yangi jadval kerak bo'lmadi — mavjud `applications` (`type=enrollment`) aynan shu ma'noni anglatar edi (`type=excursion` — "Ekskursiyalar"da). Shu jarayonda **bag topildi**: `excursions()` avval `type` bo'yicha filtrlamas edi, enrollment yozuvlari ham excursion ro'yxatiga aralashib ketardi — tuzatildi. |
| 5 | Analitika | Ko'rishlar hisoblagichi | **Bajarildi**, rejadagidek yangi **`institution_views`** jadvali bilan (`institution_id, viewer_user_id nullable, created_at`). "Jami ko'rishlar", haftalik dinamika, "Saqlovga qo'shildi" (mavjud `Favorite`) va "Lidga aylanish" (`Application` konversiyasi) real; trafik-manba va bola yoshi taqsimoti hali mock — bu ikkalasi uchun hech qanday hodisa yozib olinmaydi. |
| 6 | Yutuqlar | Yutuqlar galereyasi | **Bajarildi**, rejadagidek yangi **`achievements`** jadvali bilan (`institution_id, title, student_name, year, type, level, image_path/url`). Kabinetda CRUD (rasm yuklash bilan) + ommaviy `/maktab/{id}` profilida ham ko'rinadi (bo'sh bo'lsa bo'lim umuman chiqmaydi). |
| 7 | To'lov/billing | Tariflar, pullik checkout | Hali qo'lga olinmadi — alohida, katta ADR talab qiladi (Payme/Click integratsiyasi). |

## Options Considered

### Vakansiyaga ariza uchun: yangi `vacancy_applications` jadvali vs mavjud `applications`ni qayta ishlatish

| Dimension | Yangi jadval | Mavjud `applications`ni kengaytirish |
|---|---|---|
| Semantika | Toza — `applications` faqat ota-ona/ekskursiya uchun qoladi | `applications` ikki xil ma'noda ishlatiladi (child_name, parent_phone kabi ustunlar teacher kontekstida ma'nosiz bo'lib qoladi) |
| Migratsiya xavfi | Past — yangi jadval, eskisiga tegilmaydi | O'rta — mavjud ustunlarni nullable qilish, indekslarni qayta ko'rib chiqish kerak |
| Kelajakda kengayish | Oson (`resume_id`, `cover_note` kabi maydonlar qo'shish tabiiy) | Qiyin — endi ikkita concern bitta jadvalda |

**Qaror:** yangi `vacancy_applications` jadvali — semantik tozalik xavfsizlikdan ustun, chunki `applications` allaqachon `ApplicationPolicy`/`InboxController` orqali "ota-ona → muassasa" oqimiga qattiq bog'langan.

### Ustoz suhbati uchun: `conversations`ni kengaytirish vs alohida jadval

**Qaror (Faza 2 uchun tavsiya):** `conversations`ni kengaytirish — `Message`/Reverb broadcasting infratuzilmasi bitta joyda qoladi, ikkita deyarli bir xil jadval saqlashdan ko'ra arzon. Amalga oshirishda `parent_user_id`ni nullable qilib, `teacher_user_id` nullable FK qo'shish, unique constraint'ni `(COALESCE(parent_user_id,teacher_user_id), institution_id)` mantig'iga moslashtirish kerak (SQLite'da bu ilova darajasida validatsiya bilan ta'minlanadi, DB darajasida emas).

## Consequences

- **Osonlashadi:** Institution/Teacher kabinetlarining "ro'yxat" ko'rinishlari endi real ma'lumot bilan sinxron — admin panel yoki ommaviy `/vakansiyalar` orqali kiritilgan yozuv darhol tegishli kabinetda ham ko'rinadi.
- **Osonlashadi:** Katalog kartochkasidagi reyting endi har doim haqiqiy sharhlar bilan mos (drift muammosi yo'qoladi).
- **Osonlashadi:** "Lidlar" uchun yangi jadval o'rniga mavjud `applications`ni qayta ishlatish kod hajmini kamaytirdi va yo'l-yo'lakay real bag'ni (excursion/enrollment aralashib ketishi) fosh qildi.
- **Qiyinlashadi/qarz sifatida qoladi:** Pullik vakansiya/rezyume oqimi hali demo — jamoa Payme/Click integratsiyasini alohida ish sifatida rejalashtirishi kerak, aks holda foydalanuvchilar "to'ladim lekin hech narsa saqlanmadi" holatiga tushishi mumkin (hozircha bu ataylab shunday, lekin production'ga chiqishdan oldin albatta hal qilinishi shart).
- **Qarz sifatida qoladi:** Ustoz ↔ muassasa suhbati — `doctrine/dbal` yo'qligi sababli `conversations`ni kengaytirish bloklandi. Keyingi urinishda ikkita variantdan biri tanlanishi kerak: (a) `doctrine/dbal`ni loyihaga qo'shish (dependency siyosati bo'yicha alohida tasdiq talab qiladi), yoki (b) alohida `teacher_conversations` jadvali (semantik jihatdan toza, lekin Message/Reverb infratuzilmasini ikki joyda saqlash degani).
- **Qarz sifatida qoladi:** Analitikadagi trafik-manba (donut) va bola yoshi taqsimoti hali mock — hech qanday hodisada "qayerdan kelgani"/"bola yoshi" yozib olinmaydi. Real qilish uchun ko'rish hodisasiga `utm_source`/referrer yozish va ariza formasiga bola yoshi maydonini majburiy qilish kerak bo'ladi.

## Action Items

**Faza 1 (bajarildi):**
1. [x] `ReviewObserver` — Institution.rating/review_count avtomatik hisoblash
2. [x] Institution gallery → real `InstitutionMedia`
3. [x] Institution cabinet vakansiyalar ro'yxati → real `Vacancy`
4. [x] Teacher cabinet rezyumelar ro'yxati → real `Resume`
5. [x] `POST /ajax/vacancies`, `POST /ajax/resumes` + careers.blade.php real submit

**Faza 2 (bajarildi, 8-band bundan mustasno):**
6. [x] Forum yozish yo'li (thread/reply/like) + UI
7. [x] `vacancy_applications` jadvali + ustoz "Takliflar" oqimi + muassasa "Nomzodlar" real boshqaruvi
8. [ ] Ustoz ↔ muassasa suhbati (`conversations` kengaytirish) — `doctrine/dbal` yo'qligi sababli kechiktirildi, "Consequences"ga qarang
9. [x] Lidlar — yangi jadval o'rniga mavjud `applications` (`type=enrollment`) qayta ishlatildi
10. [x] Analitika — yangi `institution_views` jadvali, real ko'rishlar/saqlanganlar/konversiya
11. [x] Yutuqlar — yangi `achievements` jadvali, kabinet CRUD + ommaviy profilda ko'rinadi
12. [ ] To'lov/billing (Payme/Click) — alohida ADR talab qiladi

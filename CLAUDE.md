# CLAUDE.md — Universal loyiha qo'llanmasi (o'z-o'zini rivojlantiruvchi)

> **QOIDA #1:** Har qanday vazifani boshlashdan oldin ushbu faylni to'liq o'qi.
> Bu faylda loyiha haqida allaqachon to'plangan bilim bor — shuning uchun
> loyihani qaytadan boshidan skanerlash shart emas. Faqat aniq bir faylning
> tarkibi kerak bo'lsa, o'sha faylnigina maqsadli o'qi.
>
> **QOIDA #2:** Har bir vazifa tugagandan so'ng, shu faylni albatta yangila —
> yangi o'rgangan narsalarni, qo'shilgan/o'zgartirilgan funksiyalarni,
> topilgan qoidalarni va muammolarni tegishli bo'limga yoz. Bu faylni
> "kundalik" emas, balki loyihaning **joriy holatini aks ettiruvchi jonli
> hujjat** sifatida yurit: eskirgan, endi noto'g'ri yoki keraksiz bo'lgan
> yozuvlarni o'chir yoki yangila, faylni shishirmasdan qisqa va aniq saqla.
>
> **QOIDA #3 (birinchi ishga tushish):** Agar quyidagi bo'limlar bo'sh yoki
> `_(aniqlanmagan)_` deb belgilangan bo'lsa, loyihani tekshirib (package.json,
> composer.json, requirements.txt, go.mod, README va h.k.), shu ma'lumotlar
> bilan bo'limlarni to'ldir. Bu bir martalik "kashfiyot" bosqichi — keyingi
> safarlarda bu ma'lumot allaqachon shu yerda bo'ladi va qayta izlash
> shart bo'lmaydi.

---

## 1. Loyiha haqida umumiy ma'lumot

- **Nomi:** MaktabGID (new-maktabgid.uz)
- **Qisqacha tavsifi / maqsadi:** Bolalar uchun ta'lim muassasalarini (maktab,
  bog'cha, markaz va h.k.) qidirish/solishtirish/bog'lanish platformasi.
  Ota-ona, muassasa va ustoz uchun alohida shaxsiy kabinetlar; forum,
  blog/yangiliklar, karyera (vakansiya/rezyume) bo'limlari va real vaqtli
  chat mavjud. To'liq arxitektura va biznes-mantiq tarixi `backend.md` va
  `ADR-0002-*`/`ADR-0003-*` fayllarida hujjatlashtirilgan.
- **Til(lar) va freymvork(lar):** Backend — PHP / Laravel. Frontend — Blade
  shablonlari + Vite + Tailwind CSS + vanilla JS (`fetch()`), SPA emas.
  Avtorizatsiya uchun `spatie/laravel-permission`, ijtimoiy login uchun
  `laravel/socialite`.
- **Versiyalar:** PHP ^8.3, Laravel ^13.8, Node/Vite (package.json:
  vite ^8, tailwindcss ^4).
- **Ma'lumotlar bazasi:** SQLite — dev (`database/database.sqlite`,
  `.env: DB_CONNECTION=sqlite`) va testlarda in-memory (`phpunit.xml`).
  Prod uchun MySQL 8 tavsiya etilgan (`backend.md`), lekin hali sozlanmagan.
- **Frontend:** Server-rendered Blade sahifalar (SPA emas); interaktiv
  qismlar (forma yuborish, chat, kabinet amallari) `routes/ajax.php`dagi
  JSON endpointlarga `fetch()` orqali murojaat qiladi.
- **Autentifikatsiya/avtorizatsiya usuli:** Laravel session (cookie).
  Oddiy foydalanuvchilar (parent/institution/teacher) — telefon raqami +
  OTP orqali kiradi, OTP **SMS emas, Telegram bot** orqali yuboriladi
  (`App\Services\Otp\TelegramOtpChannel`). Admin panel (`/admin`) — email/
  parol yoki Google OAuth (Socialite). Avtorizatsiya ikki qatlamli: Spatie
  `laravel-permission` rollari (`Super Admin`, `Institution Admin`,
  `Teacher`, `Parent` — `PermissionSeeder`) admin panelni boshqaradi,
  `users.role` ustuni esa `EnsureRole` middleware orqali oddiy kabinet
  yo'nalishini (`role:parent`, `role:institution`, `role:teacher`)
  aniqlaydi. `Super Admin` uchun `Gate::before` orqali barcha huquq
  avtomatik beriladi (`AppServiceProvider::boot()`).
- **Deploy muhiti:** _(aniqlanmagan — `.htaccess` mavjud, apache/shared
  hosting ehtimoli bor, aniq CI/CD yoki server konfiguratsiyasi topilmadi)_

---

## 2. Loyiha tuzilishi (papkalar xaritasi)

```
app/
├── Console/Commands/          buyruqlar (routes/console.php orqali chaqiriladi)
├── Http/
│   ├── Controllers/
│   │   ├── Admin/             /admin panel (resource controllerlar)
│   │   ├── Auth/              OTP, ro'yxatdan o'tish, login/logout, Google OAuth
│   │   ├── Cabinet/            Parent/Institution/Teacher kabinetlari
│   │   ├── Career/             vakansiya/rezyume (ommaviy va kabinet)
│   │   ├── Forum/               forum thread/reply/like
│   │   ├── Institution/         muassasa kabineti ichki amallari (media, profil...)
│   │   ├── Telegram/            bot webhook
│   │   └── Concerns/            controllerlar uchun umumiy trait'lar
│   ├── Requests/                har bir yozuv amali uchun FormRequest
│   ├── Resources/                fetch() javoblari uchun JSON shakllantirish
│   └── Middleware/
│       ├── EnsureAdmin.php       /admin faqat "Super Admin" roliga ochiq
│       ├── EnsureRole.php        role:parent / role:institution / role:teacher
│       └── SetLocale.php         /til/{locale} bilan bog'liq ko'p tillilik
├── Enums/                        SettingKey/SettingInputType kabi "enum-driven" ro'yxatlar
│                                  (bo'lim 3/4ga qarang — admin sozlamalar shu asosda quriladi)
├── Models/                      Eloquent modellari (bo'lim 3ga qarang)
├── Observers/                   ReviewObserver, ForumLikeObserver (avtomatik hisob)
├── Policies/                    Institution/Application/Conversation/Forum policy
├── Services/
│   ├── Geo/YandexGeocodingService.php   manzil -> lat/lng (Yandex Maps API)
│   ├── Media/MediaUploadService.php     fayl yuklash (MEDIA_DISK orqali)
│   ├── Otp/                              OtpService + OtpChannel interfeysi
│   │                                      + TelegramOtpChannel (yagona real kanal)
│   └── Telegram/TelegramBotService.php   sendMessage va h.k.
└── Support/
    ├── MaktabgidData.php          eski mock-data klassi — endi "read-adapter":
    │                               Eloquent'dan o'qiydi, lekin Blade'lar
    │                               o'zgarmasin deb eski metod imzolari saqlangan
    ├── LegacyMedia.php             eski (import qilingan) media yo'llari uchun yordamchi
    └── Concerns/HasTranslatable.php  tarjima qilinadigan Eloquent maydonlari uchun trait

routes/
├── web.php        ommaviy va kabinet sahifalari (GET, Blade render)
├── ajax.php        fetch() bilan chaqiriladigan JSON endpointlar (web middleware)
├── admin.php        /admin panel (auth + admin middleware ostida)
├── telegram.php      bot webhook (CSRF'dan mustasno)
└── console.php       artisan buyruqlari

database/
├── migrations/      xronologik migratsiyalar (legacy_* ustunlar — eski
│                     `old_data_maktab.sql`/`.json`dan import uchun)
└── seeders/          PermissionSeeder (rol/permission), SettingSeeder va h.k.

resources/views/    Blade shablonlari (admin/, cabinet/ va h.k. papkalarga bo'lingan)
lang/                ko'p tillilik uchun tarjima fayllari
config/              standart Laravel konfiguratsiyasi
```

**Kalit fayllar (tez-tez murojaat qilinadigan):**
- `backend.md` — loyihaning **to'liq backend arxitektura hujjati**: rollar,
  servis qatlami, route xaritasi, texnologik qarorlar. Katta backend
  o'zgarish qilishdan oldin albatta shu faylni o'qish tavsiya etiladi.
- `ADR-0002-toliq-dinamiklashtirish.md`, `ADR-0003-real-vaqtli-chat.md` —
  qabul qilingan arxitektura qarorlari, sabablari va oqibatlari (nima
  "ataylab mock/demo" qilib qoldirilgani shu yerda tushuntirilgan).
- `app/Providers/AppServiceProvider.php` — Gate::before (Super Admin),
  observer'larni ro'yxatdan o'tkazish, rate limiter.
- `database/seeders/PermissionSeeder.php` — rol/permission nomlari uchun
  markazlashgan konstantalar (`PermissionSeeder::ROLE_*`).
- `app/Enums/SettingKey.php` — sayt darajasidagi barcha key-value
  sozlamalarning yagona ro'yxati (bo'lim 3/4ga qarang); yangi sozlama
  qo'shishda birinchi navbatda shu fayl tahrirlanadi.
- `boost.json` / `.mcp.json` — Laravel Boost MCP integratsiyasi
  (`php artisan boost:mcp`), qo'shimcha ma'lumot/schema/doc-search vositalari.

---

## 3. Ma'lumotlar modeli / sxema (agar mavjud bo'lsa)

> Har safar yangi jadval/model/entity bilan ishlanganda shu bo'limga
> qisqacha qo'sh: nomi, asosiy maydonlar, bog'lanishlar.

| Jadval/Model | Tavsif | Bog'lanishlar |
|---|---|---|
| `User` | Barcha rol turlari uchun yagona jadval (`role` ustuni: parent/institution/teacher + Spatie rol) | `Institution`ga (agar institution bo'lsa), `Child`, `Conversation`, `Application` va h.k.ga bog'lanadi |
| `Institution` | Ta'lim muassasasi profili (nomi, tavsifi, manzil, narx, reyting — ba'zi maydonlar `HasTranslatable` orqali tarjima qilinadi) | `District`, `Specialization` (M:M), `InstitutionMedia`, `InstitutionPrice`, `Review`, `Achievement`, `Vacancy` |
| `District` | Tuman/hudud spravochnigi | `Institution` (1:M) |
| `Specialization` | Muassasa yo'nalishi/mutaxassisligi | `Institution` (M:M, `institution_specialization`) |
| `InstitutionType` | Muassasa turi (bog'cha/maktab/markaz va h.k.) | `Institution`ga bog'lanadi |
| `InstitutionMedia` | Galereya rasm/video (`type`: `gallery`/`video`/`logo`) — `type='logo'` FAQAT bosh sahifa kartochkasi thumbnail'i uchun (`MaktabgidData::mapInstitution()`ning `thumb` maydoni); detail sahifada "asosiy rasm" tushunchasi yo'q, galereya faqat `type='gallery'` (`photos` maydoni) bilan ishlaydi, logo u yerda hech qachon ko'rinmaydi | `Institution`ga tegishli; `Institution::logoMedia()` orqali o'qiladi, `Admin\InstitutionMediaController::toggleLogo()` orqali belgilanadi/bekor qilinadi (2026-08-08) |
| `InstitutionPrice` | Muassasa narx bandlari | `Institution`ga tegishli |
| `Review` | Foydalanuvchi sharhi va bahosi | `ReviewObserver` orqali `Institution.rating`/`review_count` avtomatik qayta hisoblanadi |
| `Favorite` | Ota-onaning saqlangan muassasalari | `User` <-> `Institution` |
| `Application` | Ota-ona arizasi (`type`: enrollment/excursion) — "Lidlar" CRM ham shu jadvaldan | `User`(parent) -> `Institution` |
| `VacancyApplication` | Vakansiyaga ariza/nomzod | `Vacancy` <-> `User`(teacher, nullable — mehmon ham qoldira oladi) |
| `Child` | Ota-onaning bolasi | `User`(parent)ga tegishli |
| `Conversation` / `Message` | Real vaqtli chat (Laravel Reverb orqali broadcast, `MessageSent` eventi) | `Conversation` — parent<->institution (teacher tomoni ADR-0002da "kechiktirilgan" deb belgilangan) |
| `ForumThread` / `ForumReply` / `ForumLike` | Forum mavzu/javob/layk | `ForumLikeObserver` orqali `like_count` avtomatik hisoblanadi |
| `News` / `Article` | Yangiliklar va blog | Admin CRUD orqali boshqariladi |
| `Vacancy` / `Resume` | Karyera bo'limi (ish o'rni / rezyume) | `Institution` -> `Vacancy`; `Resume` foydalanuvchiga tegishli |
| `Achievement` | Muassasa yutuqlari galereyasi | `Institution`ga tegishli |
| `Advertisement` | Reklama bloklari | Admin CRUD |
| `InstitutionView` | Muassasa profili ko'rishlar hisoblagichi (analitika uchun) | `Institution` <-> `User`(nullable, mehmon ham) |
| `OtpCode` / `TelegramLink` | Telefon orqali OTP tasdiqlash va telefon<->Telegram chat_id bog'lanishi | Auth oqimida ishlatiladi |
| `Setting` | Generik key-value sozlama qatori (`key`, `value`) — `/admin/settings` orqali tahrirlanadi | Kalitlar jadvalda emas, `App\Enums\SettingKey`da qat'iy belgilanadi (label/inputType/group/maxLength/default shu yerda); `Setting::get(SettingKey::X)`/`Setting::set()` orqali o'qiladi/yoziladi. Hozirgi kalitlar: `MetaTitle`, `MetaDescription`, `OgImage`, `GoogleSiteVerification`, `YandexVerification`, `CustomJs`. Yangi sozlama qo'shish uchun jadvalga ustun qo'shish SHART EMAS — faqat enumga `case` qo'shiladi, admin forma (`admin/settings/edit.blade.php`) va `Admin\SeoSettingController` shu ro'yxat bo'yicha avtomatik ishlaydi |

---

## 4. Kod yozish qoidalari va konventsiyalar

> Loyihada kuzatilgan naqshlarni (pattern) shu yerga yoz: nomlash
> qoidalari, papka tuzilishi mantiqi, qaysi qatlamda qanday logika
> yozilishi kerakligi, formatlash/linter sozlamalari va h.k.

- **Servis qatlami:** tashqi integratsiyalar (Yandex geocoding, media
  yuklash, OTP, Telegram bot) controllerga to'g'ridan-to'g'ri emas,
  `app/Services/*` klasslar orqali yoziladi; almashtiriladigan qismlar
  interfeys orqali bind qilinadi (masalan `OtpChannel` -> `TelegramOtpChannel`,
  bind joyi `AppServiceProvider::register()`).
- **Observer pattern hisoblangan ustunlar uchun:** `Institution.rating`/
  `review_count` va Forum `like_count` kabi "hisoblangan" ustunlarni qo'lda
  yangilamang — `Review`/`ForumLike` Observer'lari (`app/Observers`)
  CRUD amalidan keyin avtomatik qayta hisoblaydi.
- **Ikki qatlamli avtorizatsiya:** Spatie `laravel-permission` rollari
  faqat `/admin` panel uchun (`EnsureAdmin` — faqat "Super Admin"); oddiy
  kabinet yo'nalishi (`role:parent`/`role:institution`/`role:teacher`)
  `User.role` ustuni + `EnsureRole` middleware orqali tekshiriladi. Yangi
  himoyalangan route qo'shganda qaysi tizim kerakligini aniqlab oling.
- **Ko'p tillilik:** ba'zi Eloquent modellarda (`Institution`,
  `Specialization`) tarjima qilinadigan maydonlar bor — `HasTranslatable`
  concern (`app/Support/Concerns`) orqali ishlaydi, migratsiyalarda
  `make_*_fields_translatable` nomlanishi bilan izlash mumkin.
- **Route joylashuvi:** oddiy sahifa (Blade render) -> `web.php`; `fetch()`
  bilan chaqiriladigan JSON amal -> `ajax.php`; admin panel resurslari ->
  `admin.php` (deyarli barchasi `Route::resource(...)->except(['show'])`
  naqshida).
- **`app/Support/MaktabgidData.php` haqida ehtiyot bo'ling:** bu klass eski
  mock-data davridan qolgan, lekin endi Eloquent'dan o'qiydigan
  "read-adapter" — eski metod imzolari ataylab saqlangan (Blade shablonlar
  o'zgarmasin deb). Yangi kod yozganda bevosita Eloquent modeldan
  foydalaning, bu klassni faqat mavjud chaqiruvlarni tushunish uchun o'qing.
- **"Pullik demo" formalar (`.js-fake-form`):** muassasa kabinetidagi
  "Vakansiya ochish" va ustoz kabinetidagi "Yangi rezyume" formalari to'lov
  tizimi (Payme/Click) hali ulanmagani uchun **ataylab** demo holatda —
  bu xato emas, "tuzatish" kerak emas (ADR-0002).
- **Yangi dependency qo'shishdan oldin ogohlantiring:** masalan
  `doctrine/dbal` loyihada yo'q va ADR-0002 bo'yicha shu sabab bilan bir
  migratsiya ataylab kechiktirilgan — yangi composer/npm paketi qo'shishdan
  oldin foydalanuvchi bilan tasdiqlashtiring.
- **Kod formatlash:** PHP uchun Laravel Pint (`vendor/bin/pint`) o'rnatilgan;
  commit qilishdan oldin ishga tushirish tavsiya etiladi.
- **"Enum-driven" admin sozlama naqshi (`App\Enums\SettingKey` misolida):**
  key-value jadval + backed enum orqali "har safar yangi maydon uchun
  migratsiya/controller/view yozish shart emas" andozasi. Enum case'i
  metadata (label, inputType, group, maxLength, default, placeholder,
  hint) tashiydi; admin Blade view `SettingKey::cases()` bo'yicha `@foreach`
  bilan formani avtomatik chizadi, controller esa cases() bo'yicha
  validatsiya qoidalarini dinamik quradi. Kelajakda shunga o'xshash
  "kengaytiriladigan sozlamalar" kerak bo'lsa (masalan boshqa singleton
  konfiglar), shu andozani takrorlang — yangi jadval/ustun o'rniga yangi
  `enum` case'i qo'shing.

---

## 5. Ishga tushirish, build va test buyruqlari

```bash
# Birinchi o'rnatish
composer install
npm install
cp .env.example .env && php artisan key:generate
php artisan migrate --seed

# Dev muhitni to'liq ishga tushirish (server + queue + log tailing + vite parallel)
composer run dev

# Yoki alohida-alohida:
php artisan serve
npm run dev                                   # Vite dev server (Tailwind 4)
php artisan queue:listen --tries=1 --timeout=0
php artisan pail --timeout=0                  # jonli log

# Frontend production build
npm run build

# Testlar
composer test                                 # yoki: php artisan test
php artisan test tests/Feature/ChatTest.php   # bitta test fayli
php artisan test --filter=ChatTest            # nom bo'yicha filtr

# Kod formatlash (PHP)
vendor/bin/pint

# Laravel Boost MCP (schema/log/doc-search vositalari uchun)
php artisan boost:mcp
```

**Eslatma:** dev muhitda DB — SQLite (`database/database.sqlite`);
testlarda in-memory SQLite (`phpunit.xml`) ishlatiladi, alohida test DB
sozlash shart emas.

---

## 6. O'rgangan narsalar va qabul qilingan yechimlar (LOG)

> **Eng muhim bo'lim.** Har bir vazifadan keyin shu yerga yoz.
> Format: sana — vazifa — nima o'rganildi — nima qilindi — eslatma.
> Bo'lim juda uzayib ketsa, eskirgan/ahamiyatsiz yozuvlarni siqib,
> muhim xulosalarni yuqoridagi bo'limlarga (2, 3, 4, 7) ko'chirib,
> shu yerdan o'chir — bu bo'lim "oxirgi holat"ni emas, "jarayon
> xotirasi"ni saqlaydi, shuning uchun davriy tozalanishi kerak.

### 2026-08-08 — Birinchi kashfiyot bosqichi (CLAUDE.md bo'limlarini to'ldirish)
- **Vazifa:** Foydalanuvchi "CLAUDE.md ni o'qi va amal qil" dedi; fayl Qoida
  #3 bo'yicha hali `_(aniqlanmagan)_` bo'limlarga to'la edi — birinchi
  ishga tushirish sifatida loyiha tekshirilib, bo'lim 1-5 to'ldirildi.
- **Nima o'rganildi:** Bu — MaktabGID (Laravel 13/PHP 8.3) ta'lim
  muassasalari katalogi loyihasi. Loyihada juda batafsil o'z hujjatlari
  bor: `backend.md` (to'liq arxitektura spetsifikatsiyasi) va
  `ADR-0002-toliq-dinamiklashtirish.md`/`ADR-0003-real-vaqtli-chat.md`
  (qabul qilingan qarorlar tarixi, nima ataylab mock qoldirilgani). Bular
  CLAUDE.md'dan ko'ra batafsilroq — katta backend ishida ularni ham o'qish
  kerak. Loyiha allaqachon ishlab turgan holatda (bo'sh skelet emas):
  4 rol, real DB, real auth, Telegram OTP, Spatie permission bilan admin
  panel — hammasi ishlaydi.
- **Qo'shilgan/o'zgargan funksiya:** Kod o'zgartirilmadi — faqat CLAUDE.md
  bo'lim 1-5 (umumiy ma'lumot, papka xaritasi, ma'lumotlar modeli, kod
  konventsiyalari, buyruqlar) real loyiha holati bilan to'ldirildi.
- **Eslatma:** Ishga tushirish paytida repo holatida commit qilinmagan
  ishlar bor edi (admin sozlamalar funksiyasi, quyidagi yozuvga qarang) —
  git holatiga tegilmadi, faqat CLAUDE.md tavsiflandi.

### 2026-08-08 — Lighthouse optimizatsiya + admin sozlamalar (SEO) funksiyasi
- **Vazifa:** Chrome DevTools orqali saytni tekshirib, Lighthouse
  ko'rsatkichlarini (Accessibility/SEO/Best Practices/Agentic Browsing)
  optimallashtirish; keyin foydalanuvchi so'rovi bilan SEO/JS
  konfiglarini `/admin/settings`dan boshqarish imkoniyati qo'shildi.
- **Nima o'rganildi:** Bosh sahifadagi maktab kartochkalari CSS
  `background:url()` orqali chiqarilgani (native `loading="lazy"` ishlamaydi)
  va serverda cheklovsiz barcha muassasa render qilinishi 300+ rasmni bir
  vaqtda yuklab, sahifani "osilib qolgan"dek qilib qo'yardi. Mobil rejimda
  `.results-grid`ning 1-ustunli grid holatida `min-width:auto` (standart
  CSS Grid xatti-harakati) tufayli gorizontal scroll paydo bo'lardi.
  Rang kontrastining aksariyati (`--ink-3`/`--primary` oq fonda) yagona
  bir nechta CSS token orqali tuzatilishi mumkin ekan.
- **Qo'shilgan/o'zgargan funksiya:**
  - `welcome.blade.php`/`maktabgid.js`: kartochka fon rasmi `data-bg`
    orqali faqat sahifada ko'rinadigan bo'lganda yuklanadi; 10 tadan
    "load more" o'rniga 20 tadan raqamli pagination (`js-pagination`);
    filtr/pagination scroll manzili `#js-results-head`ga aniqlashtirildi
    (`scroll-margin-top: 140px` — sticky panellar hisobga olindi).
  - `maktabgid.css`: `.results-grid > * { min-width: 0 }` (mobil
    gorizontal scroll tuzatildi); `--ink-3` va bir nechta joyda
    `--primary` -> `--primary-600` (WCAG AA kontrast); `.tag-new` matn
    rangi oq -> `--ink`; `.btn-tg` ko'k rangi qorong'iroq qilindi;
    `.ad-banner-dots .dot` teginish maydoni 24×24px ga kattalashtirildi.
  - Accessibility: xarita tugmalari/footer ijtimoiy havolalar/`#js-sort`ga
    `aria-label`; heading tartibi tuzatildi (`ad-banner` h3->h2, footer
    h5->h3); auth-modal va qidiruv maydonlariga to'g'ri `autocomplete`.
  - **Admin sozlamalar (`/admin/settings`, `Admin\SeoSettingController`):**
    generik `settings` (key-value) jadvali + `App\Enums\SettingKey`
    (bo'lim 3/4ga qarang) — bosh sahifa `<meta description>`, `og:*`,
    Google Search Console/Yandex Webmaster tasdiqlash kodlari va
    `</body>` oldidan chiqadigan maxsus JS kodi (Analytics/Metrika)
    shu orqali boshqariladi. `SettingSeeder` standart qiymatlarni yaratadi.
    **Diqqat:** bu funksiya birinchi marta oddiy `SeoSetting` modeli
    (belgilangan ustunlar bilan) sifatida yozilgan, so'ng foydalanuvchi
    so'rovi bilan key-value+enumga to'liq qayta qurilgan — eski
    migratsiyalar `down()` qilinib o'chirildi, faqat yangi arxitektura
    qoldi (yuqoridagi bo'lim 3dagi `Setting` yozuviga qarang).
- **Eslatma:** Best Practices (77) va Agentic Browsing (50) balli
  Yandex Maps kutubxonasining o'z kodidan (copyright havolasi, 3rd-party
  cookie) kelib chiqadi — bizning kodda tuzatib bo'lmaydi.

### 2026-08-08 — Muassasa galereyasiga "logo" (faqat kartochka thumbnail'i) belgilash
- **Vazifa:** `/admin/institutions/{slug}/media`da admin galereyadan bitta
  rasmni "logo" qilib belgilay olishi kerak. **Muhim aniqlik (bir necha
  marta qayta ko'rib chiqilgan):** logo FAQAT bosh sahifa kartochkasining
  thumbnail'i uchun ishlatiladi. Detail sahifada "asosiy rasm" degan
  tushuncha UMUMAN YO'Q — u yerda galereya oddiygina o'zining tabiiy
  tartibida (1 katta + 4 kichik, yozuv/badge'siz) chiqadi, logo esa u yerda
  hech qachon ko'rinmaydi (agar galereyaga alohida qo'shilmagan bo'lsa).
- **Nima o'rganildi:** `InstitutionMedia.type` — cheklanmagan oddiy string
  ustun (enum/DB constraint yo'q), butun loyihada faqat `where('type',
  'gallery')`/`where('type', 'video')` orqali filtrlanadi (3 ta joyda:
  `MaktabgidData::mapInstitution()`, `Admin\InstitutionMediaController`,
  `InstitutionCabinetController::gallery()`). Shu sababli yangi `type`
  qiymati (`'logo'`) qo'shish — alohida boolean ustun/migratsiya yozishdan
  ko'ra ancha kam kod bilan "galereyadan avtomatik chiqib ketish"ni bepul
  beradi (uchala joyda ham qo'shimcha filtr yozish shart bo'lmadi).
- **Yakuniy arxitektura — `MaktabgidData::mapInstitution()` ikkita alohida
  maydon qaytaradi:**
  - `photos` — FAQAT `type='gallery'` ro'yxati (logo hech qachon
    aralashmaydi) — `school-card`ning fallback holati va detail sahifadagi
    `<x-maktabgid.detail.gallery :photos="..." />` shu bilan ishlaydi
    (1-rasm katta, keyingi 4-tasi kichik grid, `photo-tile.blade.php`da
    yozuv/badge yo'q — faqat `@unless($url)` holatida, ya'ni rasm
    umuman bo'lmaganda, placeholder ikonka+yorliq ko'rinadi).
  - `thumb` — `Institution::logoMedia()?->url ?? $photos[0] ?? null` —
    FAQAT `school-card.blade.php`da (`$photoUrl = $s['thumb']`) ishlatiladi.
  - `app/Models/Institution.php`: `logoMedia()` — `media` collection'idan
    `type='logo'` yozuvni qaytaruvchi yordamchi metod.
  - `app/Http/Controllers/Admin/InstitutionMediaController.php`:
    `toggleLogo()` — bitta muassasada faqat bitta logo bo'lishini
    ta'minlaydi. Route: `PATCH admin/institutions/{institution}/media/{media}/logo`
    (`media.logo`). Admin view'da alohida "Logo" kartasi va har bir
    galereya plitkasida ⭐ "Logo qilish" tugmasi.
- **Eslatma:** Institution-cabinet'ning o'z galereya sahifasi
  (`InstitutionCabinetController::gallery()`) ham `where('type','gallery')`
  ishlatgani uchun, logo qilingan rasm u yerda ham avtomatik yashiriladi.
- **Qo'shimcha:** `admin/institutions/index.blade.php` jadvalidagi har bir
  qatorga "Galereya va videolar"/"Yutuqlar" tezkor havolalari qo'shildi.
- **Ehtiyot bo'ling — legacy import ma'lumotida ham `type='logo'` bor edi
  emas, aslida foydalanuvchi yangi "Logo qilish" tugmasini bir nechta real
  muassasada (masalan `humo-school-academy-7`) sinab ko'rib, natija
  yoqmagani uchun bekor qilgan — bu funksiya kodi to'g'ri ishlaydi,
  faqat noto'g'ri rasm tanlansa vizual natija yomon ko'rinishi mumkin
  (masalan brend-logotip/banner rasmi thumbnail sifatida yaxshi
  ko'rinmaydi) — bu kutilgan xatti-harakat, bug emas.
- **Detail sahifa galereyasidagi eski mock-data qoldig'i ham tuzatildi:**
  avval `MaktabgidData::mediaFor()`dagi statik placeholder yorliqlar
  ("Bino tashqi ko'rinishi" va h.k.) HAQIQIY rasm mavjud bo'lsa ham
  ustiga yozib chiqardi — endi `<span class="ptile-cap">` faqat
  `@unless($url)` holatida (rasm yo'qligida) ko'rinadi. `gallery.blade.php`
  endi statik 6 ta mock array o'rniga real `$photos` soniga qarab render
  qiladi — 0 ta rasm bo'lsa butun bo'lim chiqmaydi, bo'sh gradient-katakcha
  qolmaydi. `school.blade.php`dagi chaqiruv endi `:media` propini bermaydi.
- **`detail/title-card.blade.php`dagi bo'sh `<span class="tag lang">` tuzatildi:**
  `$school['lang']` bo'sh bo'lganda (masalan `institutions.lang` NULL)
  yorliq shartsiz chiqib, bo'sh pill ko'rinardi — endi `badge`/`sat`
  yorliqlari kabi `@if (!empty(...))` bilan o'ralgan. Xuddi shu naqsh
  `mobile-app.blade.php`da (`m-tag lang`, hozircha ishlatilmayotgan mobil
  ko'rinishda) ham tuzatildi.

## 7. Ma'lum muammolar, cheklovlar va texnik qarzlar

- **`doctrine/dbal` o'rnatilmagan** — shu sabab bilan ustoz<->muassasa
  suhbati uchun `conversations.parent_user_id`ni nullable qilish
  (`->change()`) ADR-0002da ataylab kechiktirilgan. Yangi dependency
  qo'shish alohida tasdiq talab qiladi.
- **To'lov tizimi (Payme/Click) ulanmagan** — vakansiya ochish (100 000
  so'm) va yangi rezyume (30 000 so'm) kabinet formalari ataylab
  `.js-fake-form` bilan demo holatda qoldirilgan; production'ga chiqishdan
  oldin albatta hal qilinishi kerak (ADR-0002 "Consequences" bo'limi).
- **Analitikada mock qismlar bor:** trafik-manba (donut) va bola yoshi
  taqsimoti hali hech qanday hodisadan yozib olinmaydi — real qilish uchun
  ko'rish hodisasiga `utm_source`/referrer va ariza formasiga bola yoshi
  maydonini qo'shish kerak bo'ladi.
- **`.env`da `BROADCAST_CONNECTION=log`** (dev) — Reverb orqali real vaqtli
  chat productionda ishlashi uchun broadcast connection va Reverb server
  sozlanishi kerak (`ADR-0003-real-vaqtli-chat.md`ga qarang).
- **Deploy/CI muhiti hujjatlashtirilmagan** — repo ichida faqat `.htaccess`
  bor, aniq server/CI konfiguratsiyasi topilmadi.

---

## 8. Keyingi ishlar / TODO

- Admin sozlamalar funksiyasi (`Setting`/`SettingKey`, bo'lim 3/6ga
  qarang) tugallangan va sinovdan o'tgan, lekin repo holatida hali
  commit qilinmagan (2026-08-08). Commit qilingach shu bandni o'chirish
  kerak.
- To'lov integratsiyasi (Payme/Click) — vakansiya/rezyume pullik
  formalarini real qilish uchun (ADR-0002, "qarz sifatida qoladi").
- Ustoz <-> muassasa suhbati (`conversations`ni kengaytirish yoki alohida
  jadval) — `doctrine/dbal` masalasi hal qilinganidan keyin (bo'lim 7ga
  qarang).

---

## 9. Ishlash tartibi (har bir vazifa uchun majburiy workflow)

1. **O'qi:** `CLAUDE.md` faylini to'liq o'qi.
2. **Tekshir:** Agar kerakli bo'lim bo'sh/eskirgan bo'lsa va vazifa uchun
   zarur bo'lsa — faqat shu qismga tegishli haqiqiy fayllarni maqsadli
   o'qi (butun loyihani qayta skanerlama).
3. **Bajar:** Vazifani amalga oshir.
4. **Yangila (majburiy):**
   - Bo'lim 6 (LOG)ga qisqa yozuv qo'sh.
   - Yangi funksiya/fayl/papka qo'shilgan bo'lsa → bo'lim 2ni yangila.
   - Yangi model/jadval bo'lsa → bo'lim 3ni yangila.
   - Yangi qoida/naqsh aniqlansa → bo'lim 4ni yangila.
   - Yangi build/test buyrug'i aniqlansa → bo'lim 5ni yangila.
   - Muammo/cheklov topilsa → bo'lim 7ni to'ldir.
   - Bajarilgan TODO bo'lsa → bo'lim 8dan o'chir; yangi aniqlangan
     ishlar bo'lsa → qo'sh.
5. **Tozala:** Fayl umumiy hajmini nazorat qil — takrorlanuvchi yoki
   endi ahamiyatsiz yozuvlarni siqib qisqartir. Maqsad: fayl qanchalik
   aniq va ixcham bo'lsa, keyingi vazifalarda shunchalik kam token
   sarflanadi.

---

## 10. Umumiy tamoyillar (barcha loyihalar uchun)

- Hech qachon taxmin bilan ish yuritma — noaniqlik bo'lsa, avval real
  faylni tekshir, keyin harakat qil.
- Mavjud kod stiliga mos yoz (formatlash, nomlash, arxitektura).
- Har doim eng kichik, aniq o'zgarish kiritishga harakat qil — keraksiz
  refaktoringdan saqlan, agar aniq so'ralmagan bo'lsa.
- Maxfiy ma'lumotlar (parollar, API kalitlar, `.env` tarkibi) ushbu
  faylga hech qachon yozilmasin.
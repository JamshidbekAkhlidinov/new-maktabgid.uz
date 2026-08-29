# MaktabGID (new-maktabgid.uz)

Bolalar uchun ta'lim muassasalarini (maktab, bog'cha, xususiy markaz, litsey va h.k.)
**qidirish, solishtirish va bog'lanish** platformasi. Ota-ona muassasani xarita, narx,
reyting va yo'nalish bo'yicha topadi, sharh qoldiradi, ariza yuboradi va muassasa bilan
real vaqtda chatlashadi. Muassasa va ustoz uchun alohida shaxsiy kabinetlar, forum,
yangiliklar/blog va karyera (vakansiya/rezyume) bo'limlari mavjud.

To'liq arxitektura hujjatlari: [`backend.md`](backend.md),
[`ADR-0002-toliq-dinamiklashtirish.md`](ADR-0002-toliq-dinamiklashtirish.md),
[`ADR-0003-real-vaqtli-chat.md`](ADR-0003-real-vaqtli-chat.md). Loyihaning joriy holati
va ish jarayoni [`CLAUDE.md`](CLAUDE.md) faylida yuritiladi.

## Texnologiyalar

| Qatlam | Texnologiya |
|---|---|
| Backend | PHP ^8.3, Laravel ^13.8 |
| Frontend | Blade shablonlar (SPA emas) + Vite + Tailwind CSS 4 + vanilla JS (`fetch()`) |
| Ma'lumotlar bazasi | SQLite (dev/test), MySQL 8 tavsiya etiladi (prod) |
| Avtorizatsiya | `spatie/laravel-permission` (admin panel rollari) + `users.role` ustuni (kabinet yo'nalishi) |
| Ijtimoiy login | `laravel/socialite` (Google OAuth — admin panel) |
| Xarita | Yandex Maps API (`YandexGeocodingService`) |
| OTP | Telegram bot orqali (SMS emas) |
| Real vaqtli chat | Laravel Reverb / broadcasting (`MessageSent` eventi) |

## Loyiha nima qiladi

- **Qidiruv va katalog** — bosh sahifada muassasalar ro'yxati filtr (tuman, tur,
  yo'nalish, narx) va saralash (reyting, admin belgilagan tartib) bilan; muassasa
  profilida galereya, narxlar, yutuqlar, sharhlar va xaritada joylashuv.
- **Ota-ona kabineti** — bolalarni qo'shish, sevimlilarga saqlash, ariza (enrollment/
  excursion) yuborish, sharh qoldirish, muassasa bilan chat.
- **Muassasa kabineti** — profil/media/narx/yutuqlarni boshqarish, kelgan arizalarni
  (Lidlar CRM) ko'rish, vakansiya joylashtirish, ota-onalar bilan chat, analitika
  (ko'rishlar, ariza konversiyasi).
- **Ustoz kabineti** — rezyume yaratish, vakansiyalarga ariza topshirish.
- **Forum** — mavzu ochish, javob yozish, layk bosish (hisoblar avtomatik yangilanadi).
- **Yangiliklar/Blog** va **Karyera** (ommaviy vakansiya/rezyume ro'yxati).
- **Admin panel (`/admin`)** — barcha resurslarni (muassasa, media, narx, forum,
  yangilik, vakansiya, foydalanuvchi va h.k.) CRUD orqali boshqarish, SEO/JS
  sozlamalari (`Setting` + `SettingKey` enum orqali kengaytiriladigan konfiguratsiya).

## Biznes-mantiq va foydalanuvchi rollari

Foydalanuvchilar bitta `users` jadvalida saqlanadi, `role` ustuni ularni ajratadi:

- **Parent (ota-ona)** — telefon raqami + OTP orqali kiradi. Bola qo'shadi, muassasa
  qidiradi, sevimlilarga saqlaydi, ariza yuboradi, sharh qoldiradi, muassasa bilan
  chatlashadi.
- **Institution (muassasa)** — telefon + OTP orqali kiradi, o'z muassasasi profilini
  boshqaradi (galereya, narx, yutuq, vakansiya), kelgan arizalarni ko'radi, ota-onalar
  bilan chatlashadi.
- **Teacher (ustoz)** — telefon + OTP orqali kiradi, rezyume yaratadi, vakansiyalarga
  ariza topshiradi.
- **Super Admin** — email/parol yoki Google OAuth orqali `/admin` panelga kiradi,
  `Gate::before` orqali barcha huquqqa ega, sayt darajasidagi hamma narsani boshqaradi.

**Avtorizatsiya ikki qatlamli ishlaydi:**
1. `spatie/laravel-permission` rollari (`Super Admin`, `Institution Admin`, `Teacher`,
   `Parent` — `PermissionSeeder`da belgilangan) — faqat `/admin` panelni himoya qiladi
   (`EnsureAdmin` middleware, faqat "Super Admin").
2. `users.role` ustuni + `EnsureRole` middleware (`role:parent`, `role:institution`,
   `role:teacher`) — oddiy foydalanuvchi kabinet yo'nalishini aniqlaydi.

**OTP autentifikatsiya** — parol yo'q, foydalanuvchi telefon raqamini kiritadi, tasdiqlash
kodi **Telegram bot** orqali yuboriladi (`App\Services\Otp\TelegramOtpChannel`), foydalanuvchi
avval botni ishga tushirib telefon raqamini bog'lashi kerak (`TelegramLink` jadvali).

**Hisoblangan ustunlar avtomatik yangilanadi** — `Institution.rating`/`review_count`
(`ReviewObserver`) va forum `like_count` (`ForumLikeObserver`) qo'lda emas, Eloquent
observer'lar orqali CRUD amalidan keyin qayta hisoblanadi.

**Ataylab "demo" qoldirilgan qismlar (bug emas):**
- Muassasa kabinetidagi "Vakansiya ochish" (100 000 so'm) va ustoz kabinetidagi
  "Yangi rezyume" (30 000 so'm) formalari — to'lov tizimi (Payme/Click) hali
  ulanmagani uchun `.js-fake-form` bilan demo holatda.
- Analitikadagi trafik-manba va bola yoshi taqsimoti diagrammalari — hali real
  hodisadan yozib olinmaydi.

Batafsil arxitektura qarorlari va sabablari uchun ADR-0002/ADR-0003 fayllariga qarang.

## Loyiha tuzilishi

```
app/
├── Http/Controllers/
│   ├── Admin/          /admin panel (resource controllerlar)
│   ├── Auth/            OTP, ro'yxatdan o'tish, login/logout, Google OAuth
│   ├── Cabinet/          Parent/Institution/Teacher kabinetlari
│   ├── Career/           vakansiya/rezyume
│   ├── Forum/             forum thread/reply/like
│   └── Telegram/          bot webhook
├── Enums/                SettingKey — kengaytiriladigan admin sozlamalar ro'yxati
├── Models/               Eloquent modellari
├── Observers/            ReviewObserver, ForumLikeObserver
├── Policies/             Institution/Application/Conversation/Forum policy
├── Services/
│   ├── Geo/               Yandex geocoding
│   ├── Media/             fayl yuklash
│   ├── Otp/               OtpService + TelegramOtpChannel
│   └── Telegram/          bot xabar yuborish
└── Support/
    └── MaktabgidData.php  eski mock-data klassi — endi Eloquent'dan o'qiydigan
                            "read-adapter" (Blade shablonlar o'zgarmasin deb saqlangan)

routes/
├── web.php        ommaviy va kabinet sahifalari (Blade render)
├── ajax.php        fetch() bilan chaqiriladigan JSON endpointlar
├── admin.php        /admin panel
└── telegram.php      bot webhook
```

To'liq papka xaritasi va ma'lumotlar modeli uchun [`CLAUDE.md`](CLAUDE.md) (bo'lim 2-3)
va [`backend.md`](backend.md) (bo'lim 2-4) fayllariga qarang.

## O'rnatish

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
```

`.env` faylida sozlanishi kerak bo'lgan asosiy o'zgaruvchilar: `DB_CONNECTION=sqlite`
(dev uchun standart), Telegram bot tokeni (OTP uchun), Yandex Maps API kaliti (xarita
uchun), Google OAuth ma'lumotlari (admin login uchun). To'liq ro'yxat uchun
[`backend.md`](backend.md#11-env-oʻzgaruvchilari--yangi-qoʻshiladiganlar) bo'limiga qarang.

## Ishga tushirish

```bash
# Server + queue + log tailing + Vite — hammasi parallel
composer run dev

# Yoki alohida-alohida:
php artisan serve
npm run dev                                   # Vite dev server (Tailwind 4)
php artisan queue:listen --tries=1 --timeout=0
php artisan pail --timeout=0                  # jonli log
```

## Build va test

```bash
npm run build                                 # frontend production build

composer test                                 # yoki: php artisan test
php artisan test tests/Feature/ChatTest.php   # bitta test fayli
php artisan test --filter=ChatTest            # nom bo'yicha filtr

vendor/bin/pint                               # PHP kod formatlash (commitdan oldin tavsiya)
```

Testlarda in-memory SQLite ishlatiladi (`phpunit.xml`) — alohida test DB sozlash shart
emas.

## Ma'lum cheklovlar

- `doctrine/dbal` o'rnatilmagan — shu sabab bilan bir migratsiya (ustoz<->muassasa
  suhbati uchun ustunni nullable qilish) ataylab kechiktirilgan.
- To'lov tizimi (Payme/Click) hali ulanmagan — yuqoridagi "demo" formalarga qarang.
- Deploy/CI muhiti hali hujjatlashtirilmagan (repo ichida faqat `.htaccess` bor).

To'liq ro'yxat uchun [`CLAUDE.md`](CLAUDE.md#7-malum-muammolar-cheklovlar-va-texnik-qarzlar)
bo'lim 7ga qarang.

## Hujjatlar

- [`CLAUDE.md`](CLAUDE.md) — loyihaning joriy holati, konventsiyalar, ish jarayoni logi.
- [`backend.md`](backend.md) — to'liq backend arxitektura spetsifikatsiyasi (rollar,
  servis qatlami, route xaritasi, DB sxemasi).
- [`ADR-0002-toliq-dinamiklashtirish.md`](ADR-0002-toliq-dinamiklashtirish.md) — mock
  ma'lumotdan real Eloquent'ga o'tish qarori va oqibatlari.
- [`ADR-0003-real-vaqtli-chat.md`](ADR-0003-real-vaqtli-chat.md) — real vaqtli chat
  (Laravel Reverb) arxitekturasi.

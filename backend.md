# MaktabGID — Backend arxitekturasi (Laravel)

> **Holat:** Tasdiqlangan qarorlar bilan yangilangan. "boshla" komandasi bilan shu hujjat asosida bosqichma-bosqich backend quriladi.
> Loyihani real holatini o'rganib chiqib tuzildi: `backend/` papkasida Laravel 13 (PHP 8.3) skeleti va barcha sahifalarning Blade shabloni allaqachon mavjud, `frontend/` esa faqat dizayn-referens (production kod emas). Hozircha **hamma narsa mock**: `app/Support/MaktabgidData.php` statik PHP massivlar qaytaradi, "auth" esa faqat brauzerning `localStorage`'ida (`mg_user`) soxta ishlaydi — real baza, real login, real forma yo'q. Ushbu hujjat shu prototipni to'liq ishlaydigan backendga aylantirish rejasi.

---

## 1. Texnologik qaror

Loyihada Laravel allaqachon tanlangan va Blade sahifalari shu asosda qurilgan (`frontend/design_handoff_maktabgid/BACKEND_SPEC.md`dagi Spring Boot taklifi amalda qo'llanilmagan — real kod Laravel'da). Shu sababli **mavjud stackni davom ettiramiz**, uni qayta yozmaymiz:

| Qatlam | Tanlov | Izoh |
|---|---|---|
| Backend | **Laravel 13 / PHP 8.3** | allaqachon skeleton bor |
| Frontend rendering | **Blade + Vite (Tailwind 4) + vanilla JS (fetch)** | hozirgi sahifalar shu tarzda; SPA'ga o'tish shart emas |
| DB (dev) | **SQLite** (`database/database.sqlite`) | allaqachon `.env`da |
| DB (prod) | **MySQL 8** | tavsiya, oson hosting topiladi |
| Auth | Laravel session (cookie) + telefon raqami orqali login | Sanctum keyinchalik mobil ilova uchun kerak bo'lsa qo'shiladi |
| **OTP yetkazish** | **Telegram bot** ✅ | SMS gateway emas — OTP kod Telegram bot orqali yuboriladi (§5) |
| **Real vaqtli chat** | **Laravel Reverb** ✅ | WebSocket, boshidanoq shu bilan quriladi (§7) |
| **Fayl saqlash** | **local (hozir) → Cloudflare R2 (keyin)** ✅ | `.env`dagi `MEDIA_DISK` bilan almashtiriladi, kod o'zgarmaydi (§8) |
| **Xarita** | **Yandex Maps** ✅ | Maps widget + Geocoding API (§9) |
| AI konsultant | **Anthropic Claude API**, server-side proxy | kalit hech qachon frontendga chiqmaydi |
| Admin panel | ❌ hozircha yo'q | keyingi versiyada alohida qaraladi |

---

## 2. Papka strukturasi

```
backend/
├── app/
│   ├── Models/
│   │   ├── User.php                      (kengaytiriladi: phone, role, age, district_id)
│   │   ├── District.php
│   │   ├── Institution.php
│   │   ├── Specialization.php
│   │   ├── InstitutionMedia.php
│   │   ├── Review.php
│   │   ├── Favorite.php
│   │   ├── Application.php
│   │   ├── OtpCode.php
│   │   ├── TelegramLink.php                  (phone ↔ telegram chat_id)
│   │   ├── Conversation.php
│   │   ├── Message.php
│   │   ├── ForumThread.php
│   │   ├── ForumReply.php
│   │   ├── ForumLike.php
│   │   ├── News.php
│   │   ├── Article.php
│   │   ├── Vacancy.php
│   │   └── Resume.php
│   │
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   ├── OtpController.php          (POST otp/request, otp/verify)
│   │   │   │   ├── RegisterParentController.php
│   │   │   │   ├── RegisterInstitutionController.php
│   │   │   │   ├── LoginController.php
│   │   │   │   └── LogoutController.php
│   │   │   ├── Telegram/
│   │   │   │   └── WebhookController.php      (bot yangilanishlarini qabul qiladi)
│   │   │   ├── Catalog/
│   │   │   │   ├── InstitutionCatalogController.php  (index/show — public katalog)
│   │   │   │   └── LookupController.php              (specializations, districts, price/distance bands)
│   │   │   ├── Institution/                    (kabinet, role=institution)
│   │   │   │   ├── ProfileController.php       (show/update — jonli preview shu yerdan, Yandex geocoding shu yerda chaqiriladi)
│   │   │   │   ├── MediaController.php         (rasm/video upload/delete — MEDIA_DISK ga yozadi)
│   │   │   │   ├── AcceptingController.php     (qabul holati toggle)
│   │   │   │   ├── InboxController.php         (kelgan arizalar)
│   │   │   │   └── StatsController.php
│   │   │   ├── FavoriteController.php
│   │   │   ├── ApplicationController.php       (parent yuboradi, institution status o'zgartiradi)
│   │   │   ├── Chat/
│   │   │   │   ├── ConversationController.php
│   │   │   │   └── MessageController.php
│   │   │   ├── Forum/
│   │   │   │   ├── ThreadController.php
│   │   │   │   ├── ReplyController.php
│   │   │   │   └── LikeController.php
│   │   │   ├── Content/
│   │   │   │   ├── NewsController.php
│   │   │   │   └── ArticleController.php
│   │   │   ├── Career/
│   │   │   │   ├── VacancyController.php
│   │   │   │   └── ResumeController.php
│   │   │   └── Ai/
│   │   │       └── ConsultController.php
│   │   │
│   │   ├── Requests/                            (har bir yozuv amali uchun FormRequest)
│   │   │   ├── Auth/RegisterParentRequest.php
│   │   │   ├── Auth/RegisterInstitutionRequest.php
│   │   │   ├── Auth/LoginRequest.php
│   │   │   ├── Institution/UpdateProfileRequest.php
│   │   │   ├── ApplicationStoreRequest.php
│   │   │   ├── Chat/MessageStoreRequest.php
│   │   │   ├── Forum/ThreadStoreRequest.php
│   │   │   ├── Forum/ReplyStoreRequest.php
│   │   │   ├── Career/VacancyStoreRequest.php
│   │   │   └── Career/ResumeStoreRequest.php
│   │   │
│   │   ├── Resources/                           (fetch() javoblari uchun JSON shakllantirish)
│   │   │   ├── InstitutionResource.php
│   │   │   ├── ConversationResource.php
│   │   │   ├── MessageResource.php
│   │   │   └── ApplicationResource.php
│   │   │
│   │   └── Middleware/
│   │       └── EnsureRole.php                   (role:parent / role:institution / role:admin)
│   │
│   ├── Policies/
│   │   ├── InstitutionPolicy.php                (faqat owner yoki admin tahrirlaydi)
│   │   ├── ApplicationPolicy.php                (status faqat muassasa egasi)
│   │   ├── ConversationPolicy.php                (faqat ishtirokchilar)
│   │   └── ForumPolicy.php
│   │
│   ├── Services/
│   │   ├── Otp/OtpService.php
│   │   ├── Otp/OtpChannel.php                    (interfeys)
│   │   ├── Otp/TelegramOtpChannel.php            (asosiy va yagona kanal)
│   │   ├── Telegram/TelegramBotService.php       (sendMessage, contact so'rovi, keyboard)
│   │   ├── Catalog/InstitutionSearchService.php  (filtr/saralash query builder)
│   │   ├── Geo/YandexGeocodingService.php        (manzil → lat/lng)
│   │   ├── Media/MediaUploadService.php          (local/R2, MEDIA_DISK orqali)
│   │   └── Ai/AiConsultantService.php            (Claude API + katalog konteksti)
│   │
│   ├── Events/
│   │   ├── MessageSent.php                       (ShouldBroadcastNow — Reverb)
│   │   └── ApplicationSubmitted.php
│   │
│   ├── Notifications/
│   │   ├── NewApplicationNotification.php        (muassasaga)
│   │   └── ApplicationStatusNotification.php      (ota-onaga)
│   │
│   └── Support/
│       └── MaktabgidData.php                     (seederga ko'chirilgach o'chiriladi)
│
├── config/
│   └── filesystems.php     (yangi `r2` disk qo'shiladi — S3-compatible driver)
│
├── routes/
│   ├── web.php          (mavjud GET sahifalar — endi Eloquent'dan o'qiydi)
│   ├── ajax.php          (fetch() bilan chaqiriladigan JSON endpoint'lar, web middleware ostida)
│   ├── telegram.php      (webhook route, CSRF'dan mustasno)
│   └── channels.php      (broadcasting ruxsatlari — chat, Reverb)
│
├── database/
│   ├── migrations/       (§4 ro'yxati)
│   ├── seeders/
│   │   ├── DatabaseSeeder.php
│   │   ├── DistrictSeeder.php
│   │   ├── SpecializationSeeder.php
│   │   ├── InstitutionSeeder.php     (MaktabgidData::schools() dan)
│   │   ├── ForumSeeder.php
│   │   ├── ContentSeeder.php         (news + articles)
│   │   └── CareerSeeder.php          (vacancies + resumes)
│   └── factories/        (testlar uchun)
│
└── tests/Feature/         (§13)
```

---

## 3. Ma'lumotlar bazasi sxemasi

### `users` (mavjud jadval kengaytiriladi)
| Ustun | Tur | Izoh |
|---|---|---|
| name | string | to'liq ism / mas'ul shaxs |
| phone | string, unique | login shu orqali |
| email | string, nullable | ixtiyoriy |
| password | string | bcrypt |
| role | enum: `parent`, `institution`, `admin` | |
| age | int, nullable | faqat parent |
| district_id | FK→districts, nullable | faqat parent |
| phone_verified_at | timestamp, nullable | OTP tasdiqlagach |
| last_login_at | timestamp, nullable | |

### `districts`
`id`, `name` (unique) — 11 ta tuman seed qilinadi (`MaktabgidData::districts()`dan).

### `institutions`
`id`, `owner_user_id` FK→users nullable, `name`, `type` enum(`maktab`,`bogcha`,`markaz`,`mutaxassis`), `about` text nullable, `lang` string, `district_id` FK, `address` string nullable, `lat`/`lng` decimal nullable (**Yandex geocoding orqali to'ldiriladi**), `monthly_price` bigint nullable (null = kelishilgan), `grades` string, `work_hours` string, `works_saturday` bool, `accepting` bool default true, `rating` decimal(2,1) default 0, `review_count` int default 0, `badge` string nullable (masalan "Premium"), timestamps.

> Eslatma: oldingi versiyada bo'lgan `map_x`/`map_y` (placeholder canvas koordinatalari) endi kerak emas — Yandex'ga o'tilgani sababli haqiqiy `lat`/`lng` ishlatiladi.

### `specializations`
`id`, `key` unique, `label`, `icon` — 10 ta seed (`stem, english, it, art, music, sport, science, olympiad, ielts, early`).

### `institution_specialization` (pivot)
`institution_id`, `specialization_id`.

### `institution_media`
`id`, `institution_id`, `type` enum(`gallery`,`lesson`,`video`), `disk` string (yozilgan paytdagi disk nomi — `local`/`r2`, migratsiya qilinsa track uchun), `url`, `caption` nullable, `duration` string nullable (video uchun), `sort_order` int default 0.

### `reviews`
`id`, `institution_id`, `user_id` FK→users, `rating` tinyint(1–5), `body` text, timestamps → saqlanganda `institutions.rating`/`review_count` qayta hisoblanadi (model observer).

### `favorites`
`id`, `user_id`, `institution_id`, unique(`user_id`,`institution_id`).

### `applications`
`id`, `institution_id`, `parent_user_id` nullable (mehmon ham yuborishi mumkin), `type` enum(`excursion`,`enrollment`), `child_name`, `child_birth_date` nullable, `child_age` nullable, `current_grade` nullable, `target_grade` nullable, `previous_school` nullable, `parent_name`, `parent_phone`, `preferred_start` string nullable, `note` text nullable, `status` enum(`pending`,`confirmed`,`rejected`) default `pending`, timestamps.

### `otp_codes`
`id`, `phone`, `code`, `purpose` enum(`register`,`login`), `expires_at`, `attempts` int default 0, `verified_at` nullable, timestamps.

### `telegram_links` — yangi
`id`, `phone` unique, `telegram_chat_id` unique, `telegram_username` nullable, `linked_at` timestamp — bot orqali "Share Contact" bosilganda yoziladi (§5).

### `conversations`
`id`, `parent_user_id`, `institution_id`, `last_message_at` nullable, unique(`parent_user_id`,`institution_id`).

### `messages`
`id`, `conversation_id`, `sender_type` enum(`parent`,`institution`), `sender_user_id`, `body` text, `read_at` nullable, timestamps.

### `forum_threads`
`id`, `category` string, `title`, `body` text, `user_id`, `view_count` int default 0, `like_count` int default 0, timestamps.

### `forum_replies`
`id`, `thread_id`, `user_id`, `body` text, `like_count` int default 0, timestamps.

### `forum_likes`
`id`, `user_id`, `likeable_id`, `likeable_type` (polymorphic — thread yoki reply), unique(`user_id`,`likeable_id`,`likeable_type`) — bitta userning 2 marta layk bosishini oldini oladi.

### `news`
`id`, `tag`, `title`, `excerpt`, `body` text, `source` nullable, `published_at`, `hot` bool default false.

### `articles`
`id`, `tag`, `title`, `excerpt`, `body` text, `author_name`, `read_minutes` int, `featured` bool default false, `published_at`.

### `vacancies`
`id`, `title`, `institution_id` nullable FK, `org_name`, `salary_range` string, `employment_type` enum(`full`,`part`,`hourly`), `specialization_key` nullable, `posted_by_user_id` nullable, `expires_at`.

### `resumes`
`id`, `full_name`, `role_title`, `experience` string, `specialization_key`, `salary_expectation` string nullable, `district_id` nullable, `languages` string, `owner_user_id` nullable, timestamps.

> Gradient ranglar (`g: [from,to]`) DB'da saqlanmaydi — hozirgidek `id % gradients.length` bilan view helperda hisoblanadi. Kategoriya bo'yicha umumiy `mediaFor()/facilities()/teachers()/admissionSteps()` presetlari ham hozircha DB'ga ko'chirilmaydi (institutsiyaga bog'liq emas, statik config sifatida qoladi).

---

## 4. Model bog'lanishlari (qisqacha)

```
User            hasOne Institution (owner), hasMany Favorite/Application/Review/ForumThread
                belongsTo District (parent uchun)
Institution     belongsTo User (owner), belongsTo District, belongsToMany Specialization,
                hasMany InstitutionMedia/Review/Favorite/Application/Vacancy
TelegramLink    (mustaqil — phone orqali User bilan mantiqiy bog'lanadi, FK shart emas)
Conversation    belongsTo User (parent), belongsTo Institution, hasMany Message
ForumThread     belongsTo User, hasMany ForumReply, morphMany ForumLike (likeable)
```

---

## 5. Auth va OTP — Telegram bot orqali

SMS gateway (Eskiz.uz) o'rniga **Telegram bot** ishlatiladi — xarajatsiz va tez ishga tushadi, lekin bitta shart bor: foydalanuvchi avval botga ulanishi kerak (Telegram bot ixtiyoriy raqamga o'zidan xabar yubora olmaydi).

**Oqim:**
1. Foydalanuvchi ro'yxatdan o'tish/kirish formasida telefon raqamini kiritadi.
2. Server `telegram_links` jadvalidan shu raqam uchun `telegram_chat_id` borligini tekshiradi.
   - **Bor bo'lsa** → OTP kod generatsiya qilinadi (`otp_codes`) va Telegram Bot API (`sendMessage`) orqali shu `chat_id`ga yuboriladi.
   - **Yo'q bo'lsa** → foydalanuvchiga "Avval Telegram botimizga ulaning" oynasi ko'rsatiladi, `https://t.me/{TELEGRAM_BOT_USERNAME}?start={token}` havolasi beriladi (`token` — bir martalik, shu raqamga bog'langan).
3. Foydalanuvchi botni ochadi → bot "📱 Telefon raqamni ulashish" tugmasini (`request_contact`) yuboradi → foydalanuvchi bosadi → Telegram bu `contact` obyektini (`phone_number` + foydalanuvchi `chat_id`) webhookka yuboradi.
4. `Telegram\WebhookController` shu `contact`ni qabul qilib `telegram_links`ga yozadi/yangilaydi va botga "✅ Ulandi, saytga qayting" javobini yuboradi.
5. Frontend (yoki foydalanuvchi "OTP qayta yuborish" tugmasini bossa) endi 2-qadamdagi "bor" holatiga tushadi va kod yuboriladi.
6. `OtpController@verify` — kod, muddat (`expires_at`), urinishlar sonini (`attempts`) tekshiradi, to'g'ri bo'lsa `phone_verified_at` belgilanadi va session ochiladi.

**Paket:** `irazasyed/telegram-bot-sdk` (Laravel bilan tayyor integratsiya — webhook, klaviatura tugmalari, `sendMessage`).

**Qo'shimcha imkoniyat (Phase'dan tashqari, keyin oson qo'shiladi):** xuddi shu `TelegramBotService` orqali kelajakda arizalar/chat bildirishnomalarini ham Telegramga yuborish mumkin — infratuzilma shu bosqichda tayyor bo'ladi.

- **Session** — standart Laravel `web` guard, cookie-based (Blade bilan bir domenda ishlagani uchun Sanctum SPA token shart emas).
- **Rollar** — `EnsureRole` middleware: `Route::middleware('role:institution')`. Bugungi `kind === 'institution'` JS tekshiruvi shu bilan almashtiriladi (server view ham, endpoint ham himoyalanadi).
- **Policy** — `InstitutionPolicy@update` (faqat owner/admin), `ApplicationPolicy@updateStatus` (faqat institution egasi), `ConversationPolicy@view` (faqat ishtirokchilar).
- **CSRF** — Blade formalar `@csrf` bilan; JS `fetch()` chaqiruvlari `X-CSRF-TOKEN` headerini `<meta name="csrf-token">`dan oladi. `routes/telegram.php` webhook'i esa CSRF'dan mustasno qilinadi (`bootstrap/app.php`da `except`), o'rniga Telegram'ning `secret_token` header tekshiruvi bilan himoyalanadi.

---

## 6. Marshrutlar (routes)

`web.php` — mavjud sahifa marshrutlari qoladi, lekin ichida `MaktabgidData::` chaqiruvlari **Eloquent so'rovlariga almashtiriladi** (masalan `Institution::with('media','specializations')->findOrFail($id)`).

`ajax.php` (yangi, `web` middleware guruhi ostida, session bilan ishlaydi):

```
POST   /ajax/auth/register/parent
POST   /ajax/auth/register/institution
POST   /ajax/auth/login
POST   /ajax/auth/logout
POST   /ajax/auth/otp/request          → { linked: bool, telegramDeepLink? }
POST   /ajax/auth/otp/verify

GET    /ajax/institutions              ?type=&q=&district=&spec=&priceMin=&priceMax=&sat=&sort=&page=
GET    /ajax/institutions/{id}

PUT    /ajax/institution/me                    (role:institution — Yandex geocoding shu yerda ishga tushadi)
POST   /ajax/institution/me/media
DELETE /ajax/institution/me/media/{id}
PATCH  /ajax/institution/me/accepting
GET    /ajax/institution/me/applications
PATCH  /ajax/institution/me/applications/{id}/status
GET    /ajax/institution/me/stats

GET    /ajax/favorites
POST   /ajax/favorites/{institution}
DELETE /ajax/favorites/{institution}

POST   /ajax/applications                      (guest ham yubora oladi)
GET    /ajax/applications/me                    (role:parent)

GET    /ajax/conversations                      (role:parent|institution)
POST   /ajax/conversations                       { institutionId }
GET    /ajax/conversations/{id}/messages
POST   /ajax/conversations/{id}/messages         → MessageSent event Reverb orqali broadcast qilinadi

POST   /ajax/forum/threads
POST   /ajax/forum/threads/{id}/replies
POST   /ajax/forum/threads/{id}/like
POST   /ajax/forum/replies/{id}/like

POST   /ajax/vacancies                           (auth)
POST   /ajax/resumes                             (auth)

POST   /ajax/ai/consult                          { messages: [...] }   — rate-limited
```

`telegram.php`:
```
POST   /telegram/webhook/{secret}       → Telegram\WebhookController (CSRF'siz, secret-token bilan himoyalangan)
```

`channels.php` — `private-conversation.{id}` (faqat shu suhbat ishtirokchilariga ruxsat, Reverb orqali).

---

## 7. Real vaqtli chat — Laravel Reverb

Chat boshidanoq **Reverb** bilan quriladi (polling yo'q):

- `composer require laravel/reverb` → `php artisan reverb:install` (broadcasting config, `.env` o'zgaruvchilarini qo'shadi — §11).
- `MessageSent` event `ShouldBroadcastNow` interfeysini implement qiladi, `broadcastOn()` → `new PrivateChannel('conversation.'.$this->message->conversation_id)`.
- `channels.php`da ruxsat: foydalanuvchi shu suhbatning `parent_user_id` yoki tegishli `institution.owner_user_id`iga tengmi — tekshiriladi.
- Frontend: Laravel Echo + `pusher-js` (Reverb Pusher protokoliga mos) `resources/js/app.js`ga qo'shiladi; `chat.blade.php`dagi hozirgi qo'lda yozilgan JS (`AUTO_REPLIES`, `setTimeout` bilan soxta javob) olib tashlanadi, o'rniga real `Echo.private('conversation.'+id).listen('MessageSent', ...)`.
- Production'da Reverb alohida process sifatida ishlaydi (`php artisan reverb:start`, supervisor/systemd bilan doimiy ushlab turiladi) — hosting shu portni (default `8080`) ochishi kerak.

---

## 8. Media saqlash — local → Cloudflare R2

- `config/filesystems.php`ga yangi disk qo'shiladi:
  ```php
  'r2' => [
      'driver' => 's3',
      'key' => env('R2_ACCESS_KEY_ID'),
      'secret' => env('R2_SECRET_ACCESS_KEY'),
      'region' => 'auto',
      'bucket' => env('R2_BUCKET'),
      'endpoint' => env('R2_ENDPOINT'),
      'url' => env('R2_URL'),
      'use_path_style_endpoint' => true,
  ],
  ```
- Composer paket: `composer require league/flysystem-aws-s3-v3` (Laravel S3 driver buni talab qiladi; R2 S3-compatible bo'lgani uchun shu driver ishlaydi).
- Yangi `.env` kaliti: **`MEDIA_DISK`** (default `public`). `MediaUploadService` va `InstitutionMedia` yaratishda doim `Storage::disk(config('filesystems.media_disk'))` ishlatiladi — kod hech qachon `'public'` yoki `'r2'`ni qattiq yozmaydi.
- `config/filesystems.php`da: `'media_disk' => env('MEDIA_DISK', 'public'),`
- Eslatma: Laravel'ning standart `'local'` diski (`storage/app/private`) brauzerdan ko'rinmaydi — shu sababli "hozircha mahalliy" holat uchun aynan `'public'` disk (`storage/app/public`, `php artisan storage:link` bilan ulanadi) ishlatiladi. R2 hisobi tayyor bo'lgach faqat `.env`da `MEDIA_DISK=r2` + R2 kalitlarini qo'yish kifoya — kodni qayta yozish shart emas.
- Validatsiya: rasm `image|max:5120` (5MB). Video — MVP'da fayl emas, YouTube/Vimeo link (og'ir video R2 xarajatini oshiradi).

---

## 9. Xarita — Yandex Maps

- `institutions.lat`/`lng` — profil saqlanganda `YandexGeocodingService` orqali `address` + `district`dan avtomatik hisoblanadi (Yandex Geocoder API, `YANDEX_MAPS_API_KEY` bilan server-side chaqiriladi). Muassasa xohlasa xaritada pinni qo'lda ham surishi mumkin (Phase 2, ixtiyoriy).
- Katalog sahifasidagi hozirgi placeholder canvas (`MapCanvas`/`_map.png`) **Yandex Maps JS API**ga almashtirildi — natija (`institutions` ro'yxati, `lat`/`lng` bilan) frontendga JSON sifatida beriladi, marker'lar shu koordinatalarga qo'yiladi. Haqiqiy `lat`/`lng` bo'lmagan yozuvlar uchun Toshkent markazi atrofida barqaror pseudo-koordinata ishlatiladi (`MaktabgidData::pseudoLatLng`).
- Yandex Maps JS API `apikey` parametrisiz (tokensiz, past hajmli/dev foydalanish uchun) ulanadi — `YANDEX_MAPS_API_KEY` bo'sh qoldirilishi mumkin. Kalit qo'shilsa, u faqat server-side geocoding (`YandexGeocodingService`) uchun ishlatiladi.

---

## 10. AI konsultant

Hozir `cabinet.blade.php`/`school.blade.php` ichida `aiAnswer()` — brauzerda ishlaydigan regex-based soxta AI (`MG_SCHOOLS` global JS massividan qidiradi). Bu butunlay serverga ko'chiriladi:

- `AiConsultantService` — `Institution::with('specializations')->get()` dan qisqa matn kontekst yasaydi ("100+ muassasa: nomi, tuman, narx, til, ixtisoslik…"), Anthropic Claude API'ga (`claude-sonnet` yoki `claude-haiku`, tez javob uchun) system prompt bilan yuboradi: **"faqat quyidagi ro'yxatdagi muassasalardan tavsiya ber, taxmin qilma"**.
- `POST /ajax/ai/consult` — `RateLimiter::for('ai-consult', 10/daqiqa/IP)`.
- `ANTHROPIC_API_KEY` faqat `.env`da, frontendga hech qachon chiqmaydi.
- Frontend JS (`js-ai-form` submit) `aiAnswer()` chaqirish o'rniga shu endpointga `fetch` qiladi — UI o'zgarmaydi, faqat manba almashadi.

---

## 11. `.env` o'zgaruvchilari — yangi qo'shiladiganlar

> **Qoida:** implementatsiya paytida `.env`ga yozilgan har bir yangi o'zgaruvchi shu zahoti `.env.example`ga ham (bo'sh/namuna qiymat bilan) qo'shiladi — ikkalasi doim sinxron yuriladi.

| Kalit | Default / namuna | Nima uchun |
|---|---|---|
| `ANTHROPIC_API_KEY` | *(bo'sh)* | AI konsultant |
| `TELEGRAM_BOT_TOKEN` | *(bo'sh)* | OTP va bot xabarlari |
| `TELEGRAM_BOT_USERNAME` | *(bo'sh)* | deep-link (`t.me/{username}`) yasash uchun |
| `TELEGRAM_WEBHOOK_SECRET` | *(random string)* | webhook'ni tekshirish |
| `BROADCAST_CONNECTION` | `reverb` | mavjud `.env.example`da hozir `log` — `reverb`ga o'zgartiriladi |
| `REVERB_APP_ID` | *(auto, `reverb:install` generatsiya qiladi)* | |
| `REVERB_APP_KEY` | *(auto)* | |
| `REVERB_APP_SECRET` | *(auto)* | |
| `REVERB_HOST` | `localhost` | |
| `REVERB_PORT` | `8080` | |
| `REVERB_SCHEME` | `http` (dev) / `https` (prod) | |
| `MEDIA_DISK` | `public` | `public` (mahalliy, brauzerdan ko'rinadi) yoki `r2` |
| `R2_ACCESS_KEY_ID` | *(bo'sh)* | faqat `MEDIA_DISK=r2` bo'lganda kerak |
| `R2_SECRET_ACCESS_KEY` | *(bo'sh)* | |
| `R2_BUCKET` | *(bo'sh)* | |
| `R2_ENDPOINT` | *(bo'sh)* | `https://<account-id>.r2.cloudflarestorage.com` |
| `R2_URL` | *(bo'sh)* | ommaviy fayl URL bazasi (custom domen yoki R2.dev) |
| `YANDEX_MAPS_API_KEY` | *(bo'sh)* | Maps widget (tokensiz ham ishlaydi) + Geocoding |

---

## 12. Xavfsizlik

- OTP so'rovlariga rate-limit (1 raqamga daqiqada N marta) — brute-force'dan himoya.
- Telegram webhook — `TELEGRAM_WEBHOOK_SECRET` header orqali tekshiriladi, boshqa manbadan kelgan so'rov rad etiladi.
- Parollar `bcrypt` (`BCRYPT_ROUNDS=12` allaqachon `.env`da).
- Fayl yuklashda MIME/hajm tekshiruvi (disk qaysi bo'lishidan qat'i nazar bir xil qoidalar).
- `Application`, `ForumThread` kabi foydalanuvchi matnlari Blade orqali avtomatik escape qilinadi (XSS'dan himoyalangan).
- Login urinishlariga throttle (`throttle:6,1` — Laravel default).

---

## 13. Test strategiyasi

`tests/Feature/` ichida: ro'yxatdan o'tish/login oqimi (Telegram OTP `TelegramBotService` fake/mock bilan), katalog filtri, favorite toggle, ariza yuborish + status o'zgartirish (faqat owner), chat xabar yuborish (Reverb broadcast fake bilan test qilinadi — `Event::fake()`), forum layk (ikki marta bosib bo'lmasligi), media upload (fayl saqlash `Storage::fake('local')`/`Storage::fake('r2')` bilan). SQLite in-memory baza bilan tez ishlaydi.

---

## 14. Amalga oshirish bosqichlari

1. **Sxema** — barcha migratsiyalar + modellar + seederlar (MaktabgidData → real DB, ko'rinish o'zgarmaydi)
2. **Auth + Telegram OTP** — bot yaratish/webhook, telefon+parol ro'yxatdan o'tish/login/logout, rol middleware, localStorage fake auth olib tashlanadi
3. **Muassasa kabineti** — profil CRUD, media upload (`MEDIA_DISK=public`), Yandex geocoding orqali `lat`/`lng`, qabul holati
4. **Ota-ona tomoni** — favorites, ariza yuborish (ekskursiya/joylashtirish), kabinet real statistikasi
5. **Arizalar inbox** — muassasa tomonidan tasdiqlash/rad etish + bildirishnoma
6. **Chat** — Reverb o'rnatiladi, conversations/messages real-time
7. **Forum** — mavzu/javob/layk
8. **Kontent** — yangiliklar/blog (o'qish), vakansiya/rezyume joylash (yozish)
9. **AI konsultant** — server endpoint + Claude API integratsiyasi
10. **Xarita** — Yandex Maps JS API bilan katalogdagi placeholder almashtiriladi
11. **Sayqal** — rate-limit, testlar, prod konfiguratsiya (MySQL, R2ga o'tish, Reverb prod sozlamalari)

> **Kelajakda (hozirgi rejaga kiritilmagan):** admin panel (yangilik/maqola/vakansiya kabi kontentni boshqarish uchun) — alohida so'rov bo'yicha keyinroq loyihalanadi.

---

Tayyor bo'lsangiz, **"boshla"** deng — 1-bosqichdan shu tartibda qurishni boshlayman.

# ADR-0003: Suhbat (parent/ustoz ↔ muassasa) — AJAX polling asosida to'liq ishlaydigan chat

**Status:** Accepted — Faza A-D kod darajasida amalga oshirildi (2026-07-23). Diqqat:
bu muhitda PHP ishga tushirilmagani sababli `php artisan migrate`, `vendor/bin/pint`
va `php artisan test` mahalliy muhitda ishga tushirilib tasdiqlanishi kerak — pastga
qarang, "Amalga oshirish natijasi" bo'limi.
**Sana:** 2026-07-23
**Qaror qabul qiluvchilar:** Jamshidbek (loyiha egasi)

> Ushbu ADR ADR-0002'dan keyingi bosqich. ADR-0002 suhbat (chat) sahifalarini "to'liq real" deb belgilagan edi — bu to'g'ri, lekin faqat ma'lumot darajasida: `Conversation`/`Message` real DB'da, xabar yuborish/o'qish holati real ishlaydi. Bu ADR ikki narsani hal qiladi: **(1)** xabarlarni reload'siz, deyarli-real-vaqtli yetkazish — **qo'shimcha paket/infratuzilmasiz, sof AJAX polling bilan**; **(2)** ustoz ↔ muassasa suhbatini ham shu tizimga qo'shish (ADR-0002'da kechiktirilgan bo'shliq).

## Context

**Ishlayotgan qismlar (audit tasdiqladi):**
- `conversations`/`messages` jadvallari, unique(`parent_user_id`,`institution_id`).
- Ota-ona "Suhbat boshlash" (`ParentMessageController@start`) va ikkala tomon xabar yozishi (`@store`) — real DB yozuvi.
- Unread hisoblagich va sahifa ochilganda `read_at` avtomatik belgilanishi — real ishlaydi.
- Xabar yuborish allaqachon AJAX orqali (`jsonFetch` → `POST .../messages`, `public/js/maktabgid.js:1587-1608`) — lekin muvaffaqiyatli bo'lsa `window.location.href = ...?c=convId` bilan **butun sahifa qayta yuklanadi**. Yangi kelgan xabarni ko'rish uchun ham reload shart — real vaqtli yangilanish yo'q.

**Yetishmayotgan/yarimta qismlar:**
1. **Xabar olish uchun AJAX yo'q** — faqat yuborish (`POST`) bor, o'qish (`GET`) yo'q. Shuning uchun polling ham, boshqa hech qanday reload'siz yangilanish ham hozircha mumkin emas.
2. **`ConversationPolicy` yo'q** — ruxsat tekshiruvi ikkala controllerda qo'lda `abort_unless(...)` shaklida takrorlangan.
3. **Rate-limit yo'q** — xabar yuborish endpointlarida spamga qarshi cheklov yo'q.
4. **Pagination yo'q** — suhbat tarixi har safar to'liq yuklanadi.
5. **Ustoz ↔ muassasa chat — hali yo'q, va UI ham soxta:**
   - `teacher.conversations` blade sahifasi **butunlay statik mock** (`$threads`/`$messages` — hardcoded massiv, forma `onsubmit="return false"`, sahifada ochiq izoh: *"Bu bo'lim demo ko'rinishda"*).
   - `institution/conversations.blade.php` esa **faqat `$c->parent`ga qattiq bog'langan** (`"Ota-onalar bilan yozishmalar"` sarlavha, avatar/ism/telefon hammasi `$active->parent`dan olinadi) — ustoz suhbati qo'shilsa bu sahifa uni ko'rsata olmaydi, chunki "kim yozgani"ni umuman generic tarzda hisoblamaydi.
   - Sabab — ADR-0002: `conversations.parent_user_id`ni nullable qilish (ustoz uchun) `doctrine/dbal` talab qiladi deb hisoblangan va loyihada bu paket yo'q edi.
   - **Muhim eslatma:** loyiha Laravel **13.8** ishlatadi — Laravel 11'dan boshlab ko'pchilik migratsiya `->change()` holatlari (jumladan SQLite'da) endi `doctrine/dbal`siz ishlaydi. Demak ADR-0002'dagi blocker eskirgan bo'lishi mumkin — Faza A'ning birinchi qadami aynan shuni amalda tekshirish (dev muhitda sinov migratsiyasi).

## Decision

1. **Real vaqtlilik — AJAX polling, hech qanday yangi paket/server-process yo'q.** Bitta `GET` endpoint xabarlarni beradi; frontend uni **har 3 sekunda** so'raydi (`setInterval`) va yangi xabar bo'lsa DOM'ga qo'shadi. Xabar yuborish ham AJAX orqali (hozirgidek), faqat endi **reload o'rniga** yuborilgan xabar darhol chatga qo'shiladi (optimistic UI), keyingi poll esa boshqa tomondan kelgan xabarlarni tortadi. Bu Reverb/WebSocket/`laravel-echo`/`pusher-js`ni **butunlay chetlab o'tadi** — `composer.json`/`package.json`ga yangi qator qo'shilmaydi, `.env`ga `BROADCAST_*` kerak emas.
2. **Ustoz ↔ muassasa chat qo'shiladi** — mavjud `conversations`/`messages` jadvallari kengaytiriladi (yangi jadval emas — infratuzilma, polling endpoint, JS bittasi qoladi). UI: `teacher/conversations.blade.php` mockdan realga o'tadi (parent sahifasi bilan bir andoza), `institution/conversations.blade.php` esa `$c->parent`ga qattiq bog'liqlikdan chiqib, **generic "suhbatdosh"** (parent yoki teacher, kim bo'lishidan qat'i nazar) ko'rsatadigan qilib tuzatiladi.

## Options Considered

### A) Real vaqtli yetkazish transporti

| Dimension | Reverb/WebSocket (ilgari ADR-0003 v1'da tanlangan) | **AJAX polling (3s) — endi tanlangan** |
|---|---|---|
| Yangi bog'liqlik | `laravel/reverb`, `laravel-echo`, `pusher-js` | **Yo'q — mavjud `fetch`/`jsonFetch` yetarli** |
| Xostingga bog'liqlik | Doimiy process + WS port ochiq bo'lishi shart | **Yo'q — oddiy PHP-FPM/HTTP kifoya, har qanday hostingda ishlaydi** |
| Tezlik | Millisekundlarda | ~0-3s kechikish (chat uchun amalda sezilarli emas) |
| Murakkablik | O'rta-yuqori (channel auth, event, frontend SDK) | **Past — bitta GET route + `setInterval`** |
| Server yuki | Past (push-based) | Har foydalanuvchi/ochiq chat oynasi uchun 20 so'rov/daqiqa — oz sonli parallel chat oynasi uchun arzon, lekin son ko'paysa monitoring qilinishi kerak |

**Qaror:** foydalanuvchi aniq talab qilgani sabab — **AJAX polling**, hech qanday paket qo'shilmaydi. Bu ADR-0003'ning birinchi versiyasidagi Reverb qarorini bekor qiladi.

### B) Ustoz chatini qo'shish — mavjud jadvalni kengaytirish vs alohida jadval

| Dimension | Mavjud `conversations`/`messages`ni kengaytirish | Alohida `teacher_conversations`/`teacher_messages` |
|---|---|---|
| Kod/infratuzilma takrori | Yo'q — bitta polling endpoint, bitta JS, bitta Policy | Ikki barobar — har bir yangi xususiyat ikki joyda yozilishi kerak |
| Migratsiya xavfi | `parent_user_id`ni nullable qilish + `teacher_user_id` nullable FK qo'shish kerak (`->change()` — Laravel 13'da dbal'siz ishlashi kutilmoqda, tekshirilishi kerak) | Past — faqat yangi jadvallar, eskisiga tegilmaydi |
| Unique constraint mantiqi | `(institution_id, COALESCE(parent_user_id, teacher_user_id))` — ilova darajasida tekshiriladi (SQLite'da DB darajasida emas) | Har bir jadval o'zining oddiy unique'iga ega |
| UI | `institution/conversations.blade.php`ni "generic suhbatdosh"ga o'tkazish baribir kerak (ikkala holatda ham — chunki bitta ro'yxatda ikkala turdagi suhbat ko'rinishi kerak) | Xuddi shunday, ustiga ikkita ro'yxatni birlashtirib saralash logikasi qo'shiladi |

**Qaror:** **mavjud jadvalni kengaytirish** — chunki polling infratuzilmasi (endi Reverb kabi og'ir emas) ikki marta yozilishi shart emas, va institution tomonida baribir ikkala turni bitta ro'yxatda ko'rsatish kerak bo'lgani uchun "alohida jadval" yechimi UI murakkabligini kamaytirmaydi, faqat backend takrorini oshiradi. Agar Faza A'dagi tekshiruv `->change()` haqiqatan `doctrine/dbal` talab qilishini ko'rsatsa, fallback: `composer require doctrine/dbal` (CLAUDE.md bo'yicha — yangi bog'liqlik, foydalanuvchidan alohida tasdiq so'raladi) YOKI shu jadvaldagi ustunni qayta yaratish migratsiyasi (drop+recreate, dev bosqichida ma'lumot yo'qotish xavfi past, chunki hali production'da real foydalanuvchi yozishmasi yo'q).

## Trade-off Analysis

- **Polling intervali (3s):** chat uchun UX jihatidan yetarli (WhatsApp Web darajasidagi "darhol" tuyg'usi shart emas), lekin har ochiq suhbat oynasi doimiy so'rov yuboradi. Ko'p foydalanuvchi bir vaqtda faol chatda bo'lsa server yukini kuzatish kerak (keyinchalik interval'ni sahifada moslashtirish — masalan oyna fon rejimida bo'lsa to'xtatish — arzon optimallashtirish bo'lib qoladi).
- **Polling endpointi "hamma xabarni qaytarish" emas, faqat yangilarini qaytarishi shart** — aks holda har 3 sekundda butun tarix qayta yuklanadi (keraksiz DB yuki + trafik). Shu sabab endpoint `after_id` (oxirgi ko'ringan xabar ID'si) parametrini qabul qiladi.
- **Ustoz chatini qo'shish** asosiy noaniqligi — `parent_user_id`ni nullable qilishning haqiqiy texnik xavfi (dbal kerakmi-yo'qmi) amalda tekshirilmaguncha noma'lum. Shu sabab bu ADR ikkita yo'lni ham (kengaytirish + fallback) oldindan hujjatlashtiradi.

## Consequences

- **Osonlashadi:** yangi bog'liqlik yo'q, deploy jarayoni o'zgarmaydi (yangi process/port yo'q) — har qanday hostingda ishlayveradi.
- **Osonlashadi:** parent, institution, ustoz — uchala tomon ham bitta chat infratuzilmasidan foydalanadi, kelajakda funksiya qo'shish (masalan fayl biriktirish) bir joyda qilinadi.
- **Qiyinlashadi:** doimiy polling ko'p ochiq oyna bo'lsa server so'rovlarini oshiradi — Reverb'dagi kabi "push" emas, shuning uchun juda katta miqyosda (minglab bir vaqtdagi foydalanuvchi) kelajakda qayta ko'rib chiqilishi kerak bo'lishi mumkin (bu ADR shuni ochiq muammo sifatida qayd etadi, hoziroq hal qilmaydi — miqyos hali kichik).
- **Qiyinlashadi:** `institution/conversations.blade.php` va institution tomonidagi barcha "faqat parent" taxminlar (avatar, telefon, `$active->parent`) generic qilib qayta yozilishi kerak — bu UI'ga tegadigan, biroz ko'proq o'zgarish talab qiladigan qism.

## Action Items

**Faza A — Sxema: ustoz chatini qo'shish**
1. [ ] Dev muhitda sinov migratsiyasi: `conversations.parent_user_id`ni `->nullable()->change()` qilib ko'rish — Laravel 13'da `doctrine/dbal`siz ishlaydimi tekshiriladi. Ishlasa 2-bandga o'tiladi; ishlamasa — foydalanuvchidan `doctrine/dbal` qo'shishga ruxsat so'raladi yoki jadval qayta yaratiladi (drop+recreate, dev bosqichida xavfsiz).
2. [ ] Migratsiya: `conversations`ga nullable `teacher_user_id` FK (`users`, `nullOnDelete`) qo'shiladi; `parent_user_id` nullable qilinadi; ilova darajasida validatsiya — aynan bittasi (`parent_user_id` YOKI `teacher_user_id`) to'ldirilgan bo'lishi shart.
3. [ ] `messages.sender_type` enum'iga `teacher` qo'shiladi (ustunning o'zi `string`, DB darajasida enum cheklovi yo'q — faqat validatsiya qatlamida `Rule::in([...])`ga qo'shiladi).
4. [ ] `Conversation` modeli: `teacher()` relation (`belongsTo(User::class, 'teacher_user_id')`), `scopeForUser($user)` — parent/teacher/institution kontekstiga qarab to'g'ri suhbatlarni qaytaradigan umumiy query helper.

**Faza B — Backend: polling endpointlari va policy**
5. [ ] `app/Policies/ConversationPolicy.php@view` — foydalanuvchi parent/teacher/institution egasimi tekshiradi, ikkala mavjud controllerdagi qo'lda `abort_unless`ni almashtiradi.
6. [ ] `GET /ajax/conversations/{conversation}/messages?after_id={id}` — **polling endpointi**: faqat `id > after_id` bo'lgan yangi xabarlarni qaytaradi (agar `after_id` berilmasa — oxirgi N ta, masalan 30, pagination bilan). Chaqirilganda qarshi tomon xabarlari `read_at`ga belgilanadi.
7. [ ] `GET /ajax/conversations` — suhbatlar ro'yxati JSON (unread badge'ni ham reload'siz yangilash uchun, keyingi bosqichda ixtiyoriy foydalaniladi).
8. [ ] Ustoz uchun yangi route guruhi: `Route::middleware(['auth','role:teacher'])->prefix('teacher')->group(...)` — `POST conversations` (start), `POST conversations/{c}/messages`, `GET conversations/{c}/messages` — parent/institution controllerlari bilan bir xil andoza (`TeacherMessageController`, `ConversationController@startForTeacher` yoki umumiy `ConversationController`ga refaktor).
9. [ ] Rate-limit: xabar yuborish endpointlariga `RateLimiter::for('chat-message', ...)` (masalan 20/daqiqa/foydalanuvchi).

**Faza C — Frontend: polling JS va UI tuzatish**
10. [ ] `public/js/maktabgid.js`dagi `.js-chat-send-form` submit handleri — muvaffaqiyatli yuborilgandan so'ng `window.location.href` reload o'rniga, yangi xabar bubble'ini to'g'ridan-to'g'ri `.chat-msgs`ga qo'shadi (optimistic append) va inputni tozalaydi.
11. [ ] Yangi polling bloki: `setInterval(..., 3000)` — `GET .../messages?after_id=` chaqiradi, natijadagi har bir yangi xabarni `.chat-msgs`ga qo'shadi (o'zi yuborgan xabarni takrorlamaslik uchun `sender_user_id === joriy foydalanuvchi` bo'lsa optimistic bubble bilan solishtiriladi/dedupe qilinadi); sahifa fon rejimida bo'lganda (`document.hidden`) polling to'xtatiladi (server yukini kamaytirish).
12. [ ] `resources/views/teacher/conversations.blade.php` — mock massivlar olib tashlanadi, `TeacherCabinetController@conversations`'dan real `$conversations`/`$active`/`$activeMessages` uzatiladi (parent sahifasi bilan bir xil andoza), forma `data-send-url`ga ulanadi, `"demo ko'rinishda"` izohi olib tashlanadi.
13. [ ] `resources/views/institution/conversations.blade.php` — `$c->parent` o'rniga generic `$c->participant` (accessor: parent yoki teacher, qaysi to'ldirilgan bo'lsa) ishlatiladi; sarlavha "Ota-onalar bilan yozishmalar" → "Ota-onalar va ustozlar bilan yozishmalar"; ro'yxatda suhbatdosh turini bildiruvchi kichik yorliq (badge: "Ota-ona"/"Ustoz") qo'shiladi.
14. [ ] `TeacherCabinetController@conversations` — mock `view('teacher.conversations', $this->context())` o'rniga real query (`$user->teacherConversations()->with(...)->withCount(...)->latest('last_message_at')->get()`, parent/institution controllerlari bilan bir xil andoza).

**Faza D — Ishonchlilik va sayqal**
15. [ ] Offline foydalanuvchiga bildirishnoma — yangi xabar yaratilganda (barcha `store()` metodlarida, event/listener shart emas — to'g'ridan-to'g'ri chaqiriladi) mavjud `TelegramBotService`/`TelegramLink` orqali qisqa xabar yuboriladi.
16. [ ] Feature testlar: parent/teacher/institution xabar yuborish, policy (begona foydalanuvchi ko'ra olmasligi), polling endpoint (`after_id` filtri to'g'ri ishlashi), rate-limit, ustoz suhbati unique constraint (bitta ustoz-muassasa juftligi uchun bitta suhbat).
17. [ ] `vendor/bin/pint --dirty --format agent`

## Doirasidan tashqari (out of scope)

- **Guruh chat / fayl biriktirish (attachment)** — so'rov faqat matnli xabar almashinuvining to'liq (parent+teacher+institution) va reload'siz ishlashi haqida; media/attachment keyingi bosqich.
- **Juda katta miqyosdagi polling optimizatsiyasi** (masalan minglab bir vaqtdagi foydalanuvchi uchun push-based arxitekturaga qaytish) — hozirgi miqyos uchun ortiqcha, kerak bo'lsa alohida ADR bilan qayta ko'riladi.
- **Institution tomonidan ustozga birinchi bo'lib yozish** (masalan "Nomzodlar" sahifasidan "Xabar yozish" tugmasi) — backend/model buni qo'llab-quvvatlaydi (`Conversation::firstOrCreate(['teacher_user_id' => ..., 'institution_id' => ...])` istalgan joydan chaqirilishi mumkin), lekin bu ADR doirasida institution-cabinet Vakansiyalar/Nomzodlar sahifasiga tugma ulanmadi — ustoz hozircha faqat maktab profilidagi "Suhbat boshlash" tugmasi orqali suhbat ochadi. Alohida, kichik follow-up.

## Amalga oshirish natijasi (2026-07-23)

Barcha fazalar (A-D) `backend/` papkasi ichida kod darajasida bajarildi:

- **Sxema:** `2026_07_23_100000_add_teacher_chat_to_conversations_table.php` — `parent_user_id` nullable, yangi nullable `teacher_user_id` FK + unique(`teacher_user_id`,`institution_id`). `Conversation`ga `teacher()`, `participant` va `participant_role` accessor'lari, `User`ga `teacherConversations()` qo'shildi.
- **Backend:** `ConversationPolicy`, `MessageResource`, yangi `ConversationController` (rol-agnostik `start` + polling `messages`), yangi `TeacherMessageController`, `ParentMessageController`/`Institution\MessageController` `MessageResource`ga o'tkazildi. `routes/ajax.php`da umumiy `auth` guruhi (`POST conversations`, `GET conversations/{c}/messages`) va `teacher/` prefiksli yangi guruh qo'shildi — **muhim**: teacher route'i alohida `teacher/` prefiksga olindi, aks holda parent'ning bir xil URI'siga (`POST conversations/{c}/messages`) to'qnashib, marshrutlashda birini "soya qilib qo'yardi". `AppServiceProvider`ga `chat-message` rate-limiter (20/daqiqa) qo'shildi.
- **UI:** `teacher/conversations.blade.php` mockdan realga o'tkazildi (parent bilan bir xil andoza), `institution/conversations.blade.php` `$c->parent`dan generic `$c->participant`ga o'tkazildi (+ "Ota-ona"/"Ustoz" belgisi), `TeacherCabinetController@conversations` va sidebar badge real so'rovga o'tkazildi.
- **Frontend:** `public/js/maktabgid.js` — xabar yuborish endi reload qilmasdan optimistic tarzda chatga qo'shiladi, yangi 3-sekundlik polling bloki qo'shildi (`document.hidden`da to'xtaydi), "Suhbat boshlash" tugmasi endi backend'dan kelgan `redirect` manzilidan foydalanadi (avval `/cabinet/conversations`ga qattiq kodlangan edi — bu ustoz uchun noto'g'ri sahifaga olib borar edi, endi tuzatildi).
- **Testlar:** `tests/Feature/ChatTest.php` — start (parent/teacher/institution rad etilishi), xabar almashish, begona foydalanuvchi ko'ra olmasligi (policy), polling `after_id` filtri, `read_at` belgilanishi, institution ro'yxatida ikkala tur ham chiqishi.

**Diqqat — tekshirilmagan qismlar:** bu ish shu muhitda PHP mavjud emasligi sababli **hech qanday Artisan/Composer buyrug'i ishga tushirilmadi**. Ishni yakunlash uchun mahalliy muhitda quyidagilarni ishga tushiring va natijani xabar bering (xatolik chiqsa tuzataman):

1. `php artisan migrate` — ayniqsa `parent_user_id`ni nullable qilish qadami (`->change()`) haqiqatan `doctrine/dbal`siz o'tishini tasdiqlaydi.
2. `php artisan test --compact tests/Feature/ChatTest.php`
3. `vendor/bin/pint --dirty --format agent`

@props(['school'])

<section class="card-block enroll-block" id="joylashtirish">
    <div class="enroll-head">
        <h3 style="margin:0"><x-maktabgid.icon name="edit" :width="19" :height="19" /> Oʻquvchini joylashtirish</h3>
        <p>Arizani toʻldiring — qabul boʻlimi siz bilan bogʻlanadi. Yoki avval bepul ekskursiyaga yoziling.</p>
    </div>

    <div class="js-inline-enroll" data-school="{{ $school['name'] }}">
        <form class="enroll-form js-application-form">
            <input type="hidden" name="institution_id" value="{{ $school['id'] }}" />
            <input type="hidden" name="type" value="enrollment" />

            <div class="form-section">Bola haqida</div>
            <x-maktabgid.field label="Bola ism familiyasi" icon="user"><input name="child_name" required placeholder="Asadbek Karimov" /></x-maktabgid.field>
            <div class="form-row2">
                <x-maktabgid.field label="Tugʻilgan sana" icon="cal"><input name="child_birth_date" required type="date" /></x-maktabgid.field>
                <x-maktabgid.field label="Hozirgi sinf / guruh" icon="school"><input name="current_grade" placeholder="Tayyorlov" /></x-maktabgid.field>
            </div>
            <div class="form-row2">
                <x-maktabgid.field label="Qaysi sinfga" icon="school"><input name="target_grade" required placeholder="1-sinf" /></x-maktabgid.field>
                <x-maktabgid.field label="Boshlanish vaqti" icon="cal">
                    <select name="preferred_start"><option>Hozir (joriy chorak)</option><option>Keyingi chorak</option><option selected>Keyingi oʻquv yili</option></select>
                </x-maktabgid.field>
            </div>
            <x-maktabgid.field label="Oldingi maktab / bogʻcha" hint="ixtiyoriy"><input name="previous_school" placeholder="Avval qatnagan muassasa" /></x-maktabgid.field>

            <div class="form-section">Ota-ona / vasiy</div>
            <div class="form-row2">
                <x-maktabgid.field label="Ism familiya" icon="user"><input name="parent_name" required placeholder="Dilnoza Murodova" /></x-maktabgid.field>
                <x-maktabgid.field label="Telefon raqami" icon="phone"><input name="parent_phone" required placeholder="+998 90 123 45 67" /></x-maktabgid.field>
            </div>
            <x-maktabgid.field label="Qoʻshimcha izoh" hint="ixtiyoriy"><textarea name="note" rows="2" placeholder="Allergiya, maxsus ehtiyoj yoki savollaringiz…"></textarea></x-maktabgid.field>

            <p class="form-note"><x-maktabgid.icon name="shield" :width="15" :height="15" /> Hujjatlar moderatsiyadan keyin faqat ushbu muassasaga yetkaziladi.</p>
            <div class="enroll-actions">
                <button class="btn btn-primary" type="submit"><x-maktabgid.icon name="send" :width="16" :height="16" /> Joylashtirish arizasini yuborish</button>
            </div>
        </form>
        <x-maktabgid.success-note title="Ariza qabul qilindi!" class="js-fake-success" style="display:none">
            <b>{{ $school['name'] }}</b> qabul boʻlimi hujjatlaringizni koʻrib chiqib, keyingi bosqich (suhbat va shartnoma) boʻyicha siz bilan bogʻlanadi. Holatini kabinetdan kuzating.
        </x-maktabgid.success-note>
    </div>
</section>

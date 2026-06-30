@props(['school'])

<x-maktabgid.modal-shell id="enroll-modal" :width="560">
    <div class="js-modal-body">
        <div class="modal-head js-fake-form-head">
            <h3>Oʻquvchini joylashtirish</h3>
            <p>{{ $school['name'] }}</p>
        </div>
        <form class="form js-fake-form">
            <div class="form-section">Bola haqida</div>
            <x-maktabgid.field label="Bola ism familiyasi" icon="user"><input required placeholder="Asadbek Karimov" /></x-maktabgid.field>
            <div class="form-row2">
                <x-maktabgid.field label="Tugʻilgan sana" icon="cal"><input required type="date" /></x-maktabgid.field>
                <x-maktabgid.field label="Hozirgi sinf / guruh" icon="school"><input placeholder="Tayyorlov" /></x-maktabgid.field>
            </div>
            <div class="form-row2">
                <x-maktabgid.field label="Qaysi sinfga" icon="school"><input required placeholder="1-sinf" /></x-maktabgid.field>
                <x-maktabgid.field label="Boshlanish vaqti" icon="cal">
                    <select><option>Hozir (joriy chorak)</option><option>Keyingi chorak</option><option selected>Keyingi oʻquv yili</option></select>
                </x-maktabgid.field>
            </div>
            <x-maktabgid.field label="Oldingi maktab / bogʻcha" hint="ixtiyoriy"><input placeholder="Avval qatnagan muassasa" /></x-maktabgid.field>

            <div class="form-section">Ota-ona / vasiy</div>
            <div class="form-row2">
                <x-maktabgid.field label="Ism familiya" icon="user"><input required placeholder="Dilnoza Murodova" /></x-maktabgid.field>
                <x-maktabgid.field label="Telefon" icon="phone"><input required placeholder="+998 90 123 45 67" /></x-maktabgid.field>
            </div>
            <x-maktabgid.field label="Qoʻshimcha izoh" hint="ixtiyoriy"><textarea rows="2" placeholder="Allergiya, maxsus ehtiyoj yoki savollaringiz…"></textarea></x-maktabgid.field>
            <p class="form-note"><x-maktabgid.icon name="shield" :width="15" :height="15" /> Hujjatlar moderatsiyadan keyin faqat ushbu muassasaga yetkaziladi.</p>
            <button class="btn btn-primary form-submit" type="submit"><x-maktabgid.icon name="send" :width="16" :height="16" /> Joylashtirish arizasini yuborish</button>
        </form>
        <x-maktabgid.success-note title="Ariza qabul qilindi!" :close-target="true" class="js-fake-success" style="display:none">
            <b>{{ $school['name'] }}</b> qabul boʻlimi hujjatlaringizni koʻrib chiqib, keyingi bosqich (suhbat va shartnoma) boʻyicha siz bilan bogʻlanadi. Holatini «Kabinet → Arizalarim» da kuzating.
        </x-maktabgid.success-note>
    </div>
</x-maktabgid.modal-shell>

@props(['school'])

<x-maktabgid.modal-shell id="excursion-modal" :width="460">
    <div class="js-modal-body">
        <div class="modal-head js-fake-form-head">
            <h3>Ekskursiyaga yozilish</h3>
            <p>{{ $school['name'] }}</p>
        </div>
        <form class="form js-application-form">
            <input type="hidden" name="institution_id" value="{{ $school['id'] }}" />
            <input type="hidden" name="type" value="excursion" />
            <x-maktabgid.field label="Ota-ona ism familiyasi" icon="user"><input name="parent_name" required placeholder="Dilnoza Murodova" /></x-maktabgid.field>
            <x-maktabgid.field label="Telefon raqami" icon="phone"><input name="parent_phone" required placeholder="+998 90 123 45 67" /></x-maktabgid.field>
            <div class="form-row2">
                <x-maktabgid.field label="Bola yoshi" icon="user"><input name="child_age" required type="number" min="1" max="18" placeholder="7" /></x-maktabgid.field>
                <x-maktabgid.field label="Sinf / guruh" icon="school"><input name="target_grade" required placeholder="1-sinf" /></x-maktabgid.field>
            </div>
            <div class="form-row2">
                <x-maktabgid.field label="Ekskursiya kuni" icon="cal"><input name="excursion_date" required type="date" min="{{ now()->toDateString() }}" /></x-maktabgid.field>
                <x-maktabgid.field label="Soati" icon="clock"><input name="excursion_time" required type="time" value="10:00" /></x-maktabgid.field>
            </div>
            <p class="form-note"><x-maktabgid.icon name="shield" :width="15" :height="15" /> Maʼlumotlaringiz faqat ushbu muassasaga yuboriladi.</p>
            <button class="btn btn-primary form-submit" type="submit"><x-maktabgid.icon name="send" :width="16" :height="16" /> Arizani yuborish</button>
        </form>
        <x-maktabgid.success-note title="Arizangiz yuborildi!" :close-target="true" class="js-fake-success" style="display:none">
            <b>{{ $school['name'] }}</b> belgilagan kun va soatingizni koʻrib chiqib, tasdiqlaydi yoki koʻrsatilgan raqamga qoʻngʻiroq qilib qayta kelishadi. Ariza «Kabinet → Arizalarim» boʻlimida saqlanadi.
        </x-maktabgid.success-note>
    </div>
</x-maktabgid.modal-shell>

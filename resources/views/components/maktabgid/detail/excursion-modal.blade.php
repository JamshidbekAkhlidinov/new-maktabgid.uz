@props(['school'])

<x-maktabgid.modal-shell id="excursion-modal" :width="460">
    <div class="js-modal-body">
        <div class="modal-head js-fake-form-head">
            <h3>Ekskursiyaga yozilish</h3>
            <p>{{ $school['name'] }}</p>
        </div>
        <form class="form js-fake-form">
            <x-maktabgid.field label="Ota-ona ism familiyasi" icon="user"><input required placeholder="Dilnoza Murodova" /></x-maktabgid.field>
            <x-maktabgid.field label="Telefon raqami" icon="phone"><input required placeholder="+998 90 123 45 67" /></x-maktabgid.field>
            <div class="form-row2">
                <x-maktabgid.field label="Bola yoshi" icon="user"><input required type="number" min="1" max="18" placeholder="7" /></x-maktabgid.field>
                <x-maktabgid.field label="Sinf / guruh" icon="school"><input required placeholder="1-sinf" /></x-maktabgid.field>
            </div>
            <p class="form-note"><x-maktabgid.icon name="shield" :width="15" :height="15" /> Maʼlumotlaringiz faqat ushbu muassasaga yuboriladi.</p>
            <button class="btn btn-primary form-submit" type="submit"><x-maktabgid.icon name="send" :width="16" :height="16" /> Arizani yuborish</button>
        </form>
        <x-maktabgid.success-note title="Arizangiz yuborildi!" :close-target="true" class="js-fake-success" style="display:none">
            <b>{{ $school['name'] }}</b> tez orada koʻrsatilgan raqamga qoʻngʻiroq qilib, ekskursiya vaqtini kelishadi. Ariza «Kabinet → Arizalarim» boʻlimida saqlanadi.
        </x-maktabgid.success-note>
    </div>
</x-maktabgid.modal-shell>

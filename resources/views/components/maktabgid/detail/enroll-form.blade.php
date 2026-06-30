@props(['school'])

<section class="card-block enroll-block" id="joylashtirish">
    <div class="enroll-head">
        <h3 style="margin:0"><x-maktabgid.icon name="edit" :width="19" :height="19" /> Oʻquvchini joylashtirish</h3>
        <p>Arizani toʻldiring — qabul boʻlimi siz bilan bogʻlanadi. Yoki avval bepul ekskursiyaga yoziling.</p>
    </div>

    <div class="js-inline-enroll" data-school="{{ $school['name'] }}">
        <form class="enroll-form js-fake-form">
            <div class="form-row2">
                <x-maktabgid.field label="Bola ism familiyasi" icon="user"><input required placeholder="Asadbek Karimov" /></x-maktabgid.field>
                <x-maktabgid.field label="Qaysi sinfga" icon="school"><input required placeholder="1-sinf" /></x-maktabgid.field>
            </div>
            <div class="form-row2">
                <x-maktabgid.field label="Ota-ona ism familiyasi" icon="user"><input required placeholder="Dilnoza Murodova" /></x-maktabgid.field>
                <x-maktabgid.field label="Telefon raqami" icon="phone"><input required placeholder="+998 90 123 45 67" /></x-maktabgid.field>
            </div>
            <div class="enroll-actions">
                <button class="btn btn-primary" type="submit"><x-maktabgid.icon name="send" :width="16" :height="16" /> Arizani yuborish</button>
                <button class="btn btn-ghost" type="button" data-modal-open="enroll-modal">Toʻliq forma <x-maktabgid.icon name="arrowR" :width="15" :height="15" /></button>
            </div>
        </form>
        <x-maktabgid.success-note title="Ariza yuborildi!" class="js-fake-success" style="display:none">
            Qabul boʻlimi tez orada koʻrsatilgan raqamga bogʻlanadi. Holatini kabinetdan kuzating.
        </x-maktabgid.success-note>
    </div>
</section>

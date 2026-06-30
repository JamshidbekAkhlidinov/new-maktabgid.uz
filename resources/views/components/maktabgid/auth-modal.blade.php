@php
    use App\Support\MaktabgidData;
    $districts = MaktabgidData::districts();
@endphp

<div class="modal-scrim js-modal" id="auth-modal" hidden>
    <div class="modal-card" style="max-width:480px;width:100%">
        <button class="modal-x js-modal-close" type="button" aria-label="Yopish">
            <x-maktabgid.icon name="close" :width="18" :height="18" />
        </button>

        {{-- ===== LOGIN PANEL ===== --}}
        <div class="auth-panel" data-panel="login">
            <div class="modal-head">
                <h3>Kabinetga kirish</h3>
                <p>Telefon raqamingiz orqali tizimga kiring</p>
            </div>
            <form class="form js-fake-auth" data-mode="login" autocomplete="off">
                <label class="field">
                    <span class="field-label"><x-maktabgid.icon name="phone" :width="14" :height="14" /> Telefon raqami</span>
                    <span class="field-control">
                        <x-maktabgid.icon name="phone" :width="17" :height="17" />
                        <input type="tel" name="phone" placeholder="+998 90 123 45 67" required />
                    </span>
                </label>
                <label class="field">
                    <span class="field-label"><x-maktabgid.icon name="lock" :width="14" :height="14" /> Parol</span>
                    <span class="field-control">
                        <x-maktabgid.icon name="lock" :width="17" :height="17" />
                        <input type="password" name="password" placeholder="••••••••" required />
                    </span>
                </label>
                <button class="btn btn-primary form-submit" type="submit">
                    Kirish <x-maktabgid.icon name="arrowR" :width="17" :height="17" />
                </button>
            </form>
            <div class="auth-switch">
                Hisobingiz yo'qmi?
                <button type="button" class="js-auth-switch" data-target="parent">Ota-ona bo'lib ro'yxatdan o'ting</button>
            </div>
        </div>

        {{-- ===== PARENT REGISTER PANEL ===== --}}
        <div class="auth-panel" data-panel="parent" style="display:none">
            <div class="modal-head">
                <h3>Ota-ona kabinetini yaratish</h3>
                <p>Bir daqiqada ro'yxatdan o'ting va arizalaringizni boshqaring</p>
            </div>
            <form class="form js-fake-auth" data-mode="parent" autocomplete="off">
                <label class="field">
                    <span class="field-label"><x-maktabgid.icon name="user" :width="14" :height="14" /> Ism Familiya</span>
                    <span class="field-control">
                        <x-maktabgid.icon name="user" :width="17" :height="17" />
                        <input type="text" name="name" placeholder="Masalan, Dilnoza Murodova" required />
                    </span>
                </label>
                <div class="form-row2">
                    <label class="field">
                        <span class="field-label"><x-maktabgid.icon name="phone" :width="14" :height="14" /> Telefon raqami</span>
                        <span class="field-control">
                            <x-maktabgid.icon name="phone" :width="17" :height="17" />
                            <input type="tel" name="phone" placeholder="+998 90 123 45 67" required />
                        </span>
                    </label>
                    <label class="field">
                        <span class="field-label"><x-maktabgid.icon name="user" :width="14" :height="14" /> Yoshingiz</span>
                        <span class="field-control">
                            <input type="number" name="age" min="18" max="90" placeholder="34" required />
                        </span>
                    </label>
                </div>
                <label class="field">
                    <span class="field-label"><x-maktabgid.icon name="pin" :width="14" :height="14" /> Yashash tumani</span>
                    <span class="field-control">
                        <x-maktabgid.icon name="pin" :width="17" :height="17" />
                        <select name="district" required>
                            <option value="">Tumanni tanlang</option>
                            @foreach ($districts as $d)
                                <option value="{{ $d }}">{{ $d }}</option>
                            @endforeach
                        </select>
                    </span>
                </label>
                <label class="field">
                    <span class="field-label"><x-maktabgid.icon name="lock" :width="14" :height="14" /> Parol</span>
                    <span class="field-control">
                        <x-maktabgid.icon name="lock" :width="17" :height="17" />
                        <input type="password" name="password" placeholder="••••••••" required />
                    </span>
                </label>
                <button class="btn btn-primary form-submit" type="submit">
                    Ro'yxatdan o'tish <x-maktabgid.icon name="arrowR" :width="17" :height="17" />
                </button>
            </form>
            <div class="auth-switch">
                Allaqachon hisobingiz bormi?
                <button type="button" class="js-auth-switch" data-target="login">Kirish</button>
            </div>
            <button class="auth-alt js-auth-switch" type="button" data-target="institution">
                <x-maktabgid.icon name="building" :width="16" :height="16" />
                Men muassasaman — muassasa qo'shish
            </button>
        </div>

        {{-- ===== INSTITUTION REGISTER PANEL ===== --}}
        <div class="auth-panel" data-panel="institution" style="display:none">
            <div class="modal-head">
                <h3>Muassasa kabinetini ochish</h3>
                <p>Maktab, bog'cha yoki o'quv markazingizni ro'yxatdan o'tkazing</p>
            </div>
            <form class="form js-fake-auth" data-mode="institution" autocomplete="off">
                <label class="field">
                    <span class="field-label"><x-maktabgid.icon name="building" :width="14" :height="14" /> Muassasa nomi</span>
                    <span class="field-control">
                        <x-maktabgid.icon name="building" :width="17" :height="17" />
                        <input type="text" name="org" placeholder="Masalan, Sodiq School" required />
                    </span>
                </label>
                <label class="field">
                    <span class="field-label"><x-maktabgid.icon name="school" :width="14" :height="14" /> Muassasa turi</span>
                    <span class="field-control">
                        <x-maktabgid.icon name="school" :width="17" :height="17" />
                        <select name="kind">
                            <option value="maktab">Xususiy maktab</option>
                            <option value="bogcha">Xususiy bog'cha</option>
                            <option value="markaz">O'quv markazi</option>
                        </select>
                    </span>
                </label>
                <div class="form-row2">
                    <label class="field">
                        <span class="field-label"><x-maktabgid.icon name="user" :width="14" :height="14" /> Mas'ul shaxs</span>
                        <span class="field-control">
                            <x-maktabgid.icon name="user" :width="17" :height="17" />
                            <input type="text" name="name" placeholder="F.I.Sh." required />
                        </span>
                    </label>
                    <label class="field">
                        <span class="field-label"><x-maktabgid.icon name="phone" :width="14" :height="14" /> Telefon</span>
                        <span class="field-control">
                            <x-maktabgid.icon name="phone" :width="17" :height="17" />
                            <input type="tel" name="phone" placeholder="+998 71 200 00 00" required />
                        </span>
                    </label>
                </div>
                <label class="field">
                    <span class="field-label"><x-maktabgid.icon name="lock" :width="14" :height="14" /> Parol</span>
                    <span class="field-control">
                        <x-maktabgid.icon name="lock" :width="17" :height="17" />
                        <input type="password" name="password" placeholder="••••••••" required />
                    </span>
                </label>
                <button class="btn btn-primary form-submit" type="submit">
                    Ro'yxatdan o'tish <x-maktabgid.icon name="arrowR" :width="17" :height="17" />
                </button>
            </form>
            <div class="auth-switch">
                Allaqachon hisobingiz bormi?
                <button type="button" class="js-auth-switch" data-target="login">Kirish</button>
            </div>
            <button class="auth-alt js-auth-switch" type="button" data-target="parent">
                <x-maktabgid.icon name="user" :width="16" :height="16" />
                Men ota-onaman — kabinet ochish
            </button>
        </div>
    </div>
</div>

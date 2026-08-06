@php
    use App\Support\MaktabgidData;
    $districts = MaktabgidData::districts();
@endphp

<div class="modal-scrim js-modal" id="auth-modal" hidden>
    <div class="modal-card" style="max-width:480px;width:100%">
        <button class="modal-x js-modal-close" type="button" aria-label="{{ __('auth.close') }}">
            <x-maktabgid.icon name="close" :width="18" :height="18" />
        </button>

        {{-- ===== LOGIN PANEL ===== --}}
        <div class="auth-panel" data-panel="login">
            <div class="modal-head">
                <h3>{{ __('auth.login_title') }}</h3>
                <p>{{ __('auth.login_subtitle') }}</p>
            </div>
            <form class="form js-fake-auth" data-mode="login" autocomplete="off">
                <label class="field">
                    <span class="field-label"><x-maktabgid.icon name="phone" :width="14" :height="14" /> {{ __('auth.phone') }}</span>
                    <span class="field-control">
                        <x-maktabgid.icon name="phone" :width="17" :height="17" />
                        <input type="tel" name="phone" placeholder="{{ __('auth.phone_placeholder') }}" required />
                    </span>
                </label>
                <label class="field">
                    <span class="field-label"><x-maktabgid.icon name="lock" :width="14" :height="14" /> {{ __('auth.password') }}</span>
                    <span class="field-control">
                        <x-maktabgid.icon name="lock" :width="17" :height="17" />
                        <input type="password" name="password" placeholder="{{ __('auth.password_placeholder') }}" required />
                    </span>
                </label>
                <button class="btn btn-primary form-submit" type="submit">
                    {{ __('auth.login_submit') }} <x-maktabgid.icon name="arrowR" :width="17" :height="17" />
                </button>
            </form>
            <div class="auth-switch">
                {{ __('auth.no_account') }}
                <button type="button" class="js-auth-switch" data-target="parent">{{ __('auth.register_as_parent') }}</button>
            </div>
            <button class="auth-alt js-auth-switch" type="button" data-target="institution">
                <x-maktabgid.icon name="building" :width="16" :height="16" />
                {{ __('auth.im_institution') }}
            </button>
            <button class="auth-alt js-auth-switch" type="button" data-target="teacher">
                <x-maktabgid.icon name="user" :width="16" :height="16" />
                {{ __('auth.im_teacher') }}
            </button>
        </div>

        {{-- ===== PARENT REGISTER PANEL ===== --}}
        <div class="auth-panel" data-panel="parent" style="display:none">
            <div class="modal-head">
                <h3>{{ __('auth.parent_title') }}</h3>
                <p>{{ __('auth.parent_subtitle') }}</p>
            </div>
            <form class="form js-fake-auth" data-mode="parent" autocomplete="off">
                <label class="field">
                    <span class="field-label"><x-maktabgid.icon name="user" :width="14" :height="14" /> {{ __('auth.full_name') }}</span>
                    <span class="field-control">
                        <x-maktabgid.icon name="user" :width="17" :height="17" />
                        <input type="text" name="name" placeholder="{{ __('auth.name_placeholder_parent') }}" required />
                    </span>
                </label>
                <div class="form-row2">
                    <label class="field">
                        <span class="field-label"><x-maktabgid.icon name="phone" :width="14" :height="14" /> {{ __('auth.phone') }}</span>
                        <span class="field-control">
                            <x-maktabgid.icon name="phone" :width="17" :height="17" />
                            <input type="tel" name="phone" placeholder="{{ __('auth.phone_placeholder') }}" required />
                        </span>
                    </label>
                    <label class="field">
                        <span class="field-label"><x-maktabgid.icon name="user" :width="14" :height="14" /> {{ __('auth.age') }}</span>
                        <span class="field-control">
                            <input type="number" name="age" min="18" max="90" placeholder="34" required />
                        </span>
                    </label>
                </div>
                <label class="field">
                    <span class="field-label"><x-maktabgid.icon name="pin" :width="14" :height="14" /> {{ __('auth.district') }}</span>
                    <span class="field-control">
                        <x-maktabgid.icon name="pin" :width="17" :height="17" />
                        <select name="district" required>
                            <option value="">{{ __('auth.select_district') }}</option>
                            @foreach ($districts as $d)
                                <option value="{{ $d }}">{{ $d }}</option>
                            @endforeach
                        </select>
                    </span>
                </label>
                <label class="field">
                    <span class="field-label"><x-maktabgid.icon name="lock" :width="14" :height="14" /> {{ __('auth.password') }}</span>
                    <span class="field-control">
                        <x-maktabgid.icon name="lock" :width="17" :height="17" />
                        <input type="password" name="password" placeholder="{{ __('auth.password_placeholder') }}" required />
                    </span>
                </label>
                <button class="btn btn-primary form-submit" type="submit">
                    {{ __('auth.register_submit') }} <x-maktabgid.icon name="arrowR" :width="17" :height="17" />
                </button>
            </form>
            <div class="auth-switch">
                {{ __('auth.have_account') }}
                <button type="button" class="js-auth-switch" data-target="login">{{ __('auth.login_word') }}</button>
            </div>
            <button class="auth-alt js-auth-switch" type="button" data-target="institution">
                <x-maktabgid.icon name="building" :width="16" :height="16" />
                {{ __('auth.im_institution') }}
            </button>
            <button class="auth-alt js-auth-switch" type="button" data-target="teacher">
                <x-maktabgid.icon name="user" :width="16" :height="16" />
                {{ __('auth.im_teacher') }}
            </button>
        </div>

        {{-- ===== INSTITUTION REGISTER PANEL ===== --}}
        <div class="auth-panel" data-panel="institution" style="display:none">
            <div class="modal-head">
                <h3>{{ __('auth.institution_title') }}</h3>
                <p>{{ __('auth.institution_subtitle') }}</p>
            </div>
            <form class="form js-fake-auth" data-mode="institution" autocomplete="off">
                <label class="field">
                    <span class="field-label"><x-maktabgid.icon name="building" :width="14" :height="14" /> {{ __('auth.institution_name') }}</span>
                    <span class="field-control">
                        <x-maktabgid.icon name="building" :width="17" :height="17" />
                        <input type="text" name="org" placeholder="{{ __('auth.institution_name_placeholder') }}" required />
                    </span>
                </label>
                <label class="field">
                    <span class="field-label"><x-maktabgid.icon name="school" :width="14" :height="14" /> {{ __('auth.institution_type') }}</span>
                    <span class="field-control">
                        <x-maktabgid.icon name="school" :width="17" :height="17" />
                        <select name="kind">
                            <option value="maktab">{{ __('auth.type_school') }}</option>
                            <option value="bogcha">{{ __('auth.type_kindergarten') }}</option>
                            <option value="markaz">{{ __('auth.type_center') }}</option>
                        </select>
                    </span>
                </label>
                <div class="form-row2">
                    <label class="field">
                        <span class="field-label"><x-maktabgid.icon name="user" :width="14" :height="14" /> {{ __('auth.responsible_person') }}</span>
                        <span class="field-control">
                            <x-maktabgid.icon name="user" :width="17" :height="17" />
                            <input type="text" name="name" placeholder="{{ __('auth.fio_placeholder') }}" required />
                        </span>
                    </label>
                    <label class="field">
                        <span class="field-label"><x-maktabgid.icon name="phone" :width="14" :height="14" /> {{ __('auth.phone_short') }}</span>
                        <span class="field-control">
                            <x-maktabgid.icon name="phone" :width="17" :height="17" />
                            <input type="tel" name="phone" placeholder="{{ __('auth.phone_placeholder_institution') }}" required />
                        </span>
                    </label>
                </div>
                <label class="field">
                    <span class="field-label"><x-maktabgid.icon name="lock" :width="14" :height="14" /> {{ __('auth.password') }}</span>
                    <span class="field-control">
                        <x-maktabgid.icon name="lock" :width="17" :height="17" />
                        <input type="password" name="password" placeholder="{{ __('auth.password_placeholder') }}" required />
                    </span>
                </label>
                <button class="btn btn-primary form-submit" type="submit">
                    {{ __('auth.register_submit') }} <x-maktabgid.icon name="arrowR" :width="17" :height="17" />
                </button>
            </form>
            <div class="auth-switch">
                {{ __('auth.have_account') }}
                <button type="button" class="js-auth-switch" data-target="login">{{ __('auth.login_word') }}</button>
            </div>
            <button class="auth-alt js-auth-switch" type="button" data-target="parent">
                <x-maktabgid.icon name="user" :width="16" :height="16" />
                {{ __('auth.im_parent') }}
            </button>
            <button class="auth-alt js-auth-switch" type="button" data-target="teacher">
                <x-maktabgid.icon name="user" :width="16" :height="16" />
                {{ __('auth.im_teacher') }}
            </button>
        </div>

        {{-- ===== TEACHER REGISTER PANEL ===== --}}
        <div class="auth-panel" data-panel="teacher" style="display:none">
            <div class="modal-head">
                <h3>{{ __('auth.teacher_title') }}</h3>
                <p>{{ __('auth.teacher_subtitle') }}</p>
            </div>
            <form class="form js-fake-auth" data-mode="teacher" autocomplete="off">
                <label class="field">
                    <span class="field-label"><x-maktabgid.icon name="user" :width="14" :height="14" /> {{ __('auth.full_name') }}</span>
                    <span class="field-control">
                        <x-maktabgid.icon name="user" :width="17" :height="17" />
                        <input type="text" name="name" placeholder="{{ __('auth.name_placeholder_teacher') }}" required />
                    </span>
                </label>
                <div class="form-row2">
                    <label class="field">
                        <span class="field-label"><x-maktabgid.icon name="phone" :width="14" :height="14" /> {{ __('auth.phone') }}</span>
                        <span class="field-control">
                            <x-maktabgid.icon name="phone" :width="17" :height="17" />
                            <input type="tel" name="phone" placeholder="{{ __('auth.phone_placeholder') }}" required />
                        </span>
                    </label>
                    <label class="field">
                        <span class="field-label"><x-maktabgid.icon name="user" :width="14" :height="14" /> {{ __('auth.age') }}</span>
                        <span class="field-control">
                            <input type="number" name="age" min="18" max="90" placeholder="34" required />
                        </span>
                    </label>
                </div>
                <label class="field">
                    <span class="field-label"><x-maktabgid.icon name="pin" :width="14" :height="14" /> {{ __('auth.district') }}</span>
                    <span class="field-control">
                        <x-maktabgid.icon name="pin" :width="17" :height="17" />
                        <select name="district" required>
                            <option value="">{{ __('auth.select_district') }}</option>
                            @foreach ($districts as $d)
                                <option value="{{ $d }}">{{ $d }}</option>
                            @endforeach
                        </select>
                    </span>
                </label>
                <label class="field">
                    <span class="field-label"><x-maktabgid.icon name="lock" :width="14" :height="14" /> {{ __('auth.password') }}</span>
                    <span class="field-control">
                        <x-maktabgid.icon name="lock" :width="17" :height="17" />
                        <input type="password" name="password" placeholder="{{ __('auth.password_placeholder') }}" required />
                    </span>
                </label>
                <button class="btn btn-primary form-submit" type="submit">
                    {{ __('auth.register_submit') }} <x-maktabgid.icon name="arrowR" :width="17" :height="17" />
                </button>
            </form>
            <div class="auth-switch">
                {{ __('auth.have_account') }}
                <button type="button" class="js-auth-switch" data-target="login">{{ __('auth.login_word') }}</button>
            </div>
            <button class="auth-alt js-auth-switch" type="button" data-target="parent">
                <x-maktabgid.icon name="user" :width="16" :height="16" />
                {{ __('auth.im_parent') }}
            </button>
            <button class="auth-alt js-auth-switch" type="button" data-target="institution">
                <x-maktabgid.icon name="building" :width="16" :height="16" />
                {{ __('auth.im_institution') }}
            </button>
        </div>
    </div>
</div>

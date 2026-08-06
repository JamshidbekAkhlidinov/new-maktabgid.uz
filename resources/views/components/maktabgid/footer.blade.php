<footer class="footer">
    <div class="wrap">
        <div class="foot-grid">
            <div>
                <div class="foot-logo"><span class="logo-mark"><x-maktabgid.icon name="school" :width="20" :height="20" /></span> MaktabGID</div>
                <p class="foot-about">{{ __('footer.about') }}</p>
                <div class="foot-social">
                    <a href="#"><x-maktabgid.icon name="send" :width="18" :height="18" /></a>
                    <a href="#"><x-maktabgid.icon name="globe" :width="18" :height="18" /></a>
                    <a href="#"><x-maktabgid.icon name="phone" :width="18" :height="18" /></a>
                </div>
            </div>
            <div class="foot-col">
                <h5>{{ __('footer.col_catalog') }}</h5>
                <a href="#">{{ __('footer.link_private_schools') }}</a><a href="#">{{ __('footer.link_private_kindergartens') }}</a>
                <a href="#">{{ __('footer.link_edu_centers') }}</a><a href="#">{{ __('footer.link_specialists') }}</a>
            </div>
            <div class="foot-col">
                <h5>{{ __('footer.col_platform') }}</h5>
                <a href="#">{{ __('footer.link_vacancies') }}</a><a href="#">{{ __('footer.link_blog') }}</a>
                <a href="#">{{ __('footer.link_add_institution') }}</a><a href="#">{{ __('footer.link_telegram_bot') }}</a>
            </div>
            <div class="foot-col">
                <h5>{{ __('footer.col_contact') }}</h5>
                <a href="#"><span style="display:inline-flex;gap:6px;align-items:center"><x-maktabgid.icon name="pin" :width="15" :height="15" /> {{ __('footer.address') }}</span></a>
                <a href="#">@maktabgid_bot</a>
                <a href="#">instagram.com/maktabgid</a>
            </div>
        </div>
        <div class="foot-bottom">
            <span>{{ __('footer.copyright') }}</span>
            <span>{{ __('footer.legal_links') }}</span>
        </div>
    </div>
</footer>

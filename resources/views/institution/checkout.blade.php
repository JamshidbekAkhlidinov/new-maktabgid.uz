<x-institution.shell
    active="plans"
    title="Tariflar va obuna"
    sub="E'loningizni yuqoriga chiqaring"
    :institution="$institution"
    :organizations="$organizations"
    :counts="$counts"
    :user="$user"
>
    @if ($institution)

    <a href="{{ route('institution.cabinet.plans') }}" class="idash-back-link">
        <x-maktabgid.icon name="arrowL" :width="16" :height="16" /> Tariflarga qaytish
    </a>

    <div class="js-checkout-wrap">
        <form class="js-fake-form">
            <div class="idash-checkout-row">
                <div class="panel">
                    <div class="panel-head js-fake-form-head" style="display:block">
                        <h3>To'lovni rasmiylashtirish</h3>
                        <p>To'lov usulini tanlang</p>
                    </div>

                    <div class="idash-pay-list">
                        @foreach ($paymentMethods as $m)
                            <label class="idash-pay-item{{ !empty($m['selected']) ? ' on' : '' }}">
                                <input type="radio" name="method" value="{{ $m['key'] }}" @checked(!empty($m['selected'])) />
                                <span class="idash-pay-badge" style="background:{{ $m['color'] }}">{{ $m['badge'] }}</span>
                                <span class="idash-pay-name">{{ $m['name'] }}</span>
                                <span class="idash-pay-radio"></span>
                            </label>
                        @endforeach
                    </div>

                    <button type="button" class="idash-pay-add">
                        <x-maktabgid.icon name="plus" :width="16" :height="16" /> Yangi karta qo'shish
                    </button>

                    <p class="form-note" style="margin-top:18px">
                        <x-maktabgid.icon name="shield" :width="15" :height="15" />
                        To'lov xavfsiz amalga oshiriladi. Istalgan vaqtda bekor qilishingiz mumkin.
                    </p>
                </div>

                <div class="idash-order-card">
                    <h3>Buyurtma</h3>
                    <div class="idash-order-row"><span>Muassasa</span><span>{{ $institution->name }}</span></div>
                    <div class="idash-order-row"><span>Tarif</span><span>{{ $plan['name'] }} · {{ $plan['dur'] }}</span></div>
                    <div class="idash-order-row"><span>Amal qilish</span><span>{{ $plan['days'] }} kun</span></div>
                    <div class="idash-order-row"><span>Lidlar</span><span>{{ $plan['leadsLabel'] }}</span></div>

                    <div class="idash-order-total">
                        <b>Jami</b>
                        <b>{{ $plan['price'] }} <span style="font-family:var(--font-sans);font-size:13px;font-weight:600;color:var(--ink-3)">so'm</span></b>
                    </div>

                    <button type="submit" class="btn btn-primary idash-order-cta">
                        <x-maktabgid.icon name="lock" :width="16" :height="16" /> {{ $plan['price'] }} so'm to'lash
                    </button>
                </div>
            </div>
        </form>

        <x-maktabgid.success-note title="To'lov muvaffaqiyatli amalga oshirildi!" class="js-fake-success" style="display:none">
            <b>{{ $plan['name'] }}</b> tarifi faollashtirildi (demo rejim). Real to'lov tizimi ulanganda bu yerda haqiqiy tranzaksiya amalga oshiriladi va e'loningiz darhol yuqoriga chiqadi.
            <br /><br />
            <a href="{{ route('institution.cabinet') }}" class="btn btn-primary">Boshqaruv paneliga qaytish</a>
        </x-maktabgid.success-note>
    </div>

    @endif
</x-institution.shell>

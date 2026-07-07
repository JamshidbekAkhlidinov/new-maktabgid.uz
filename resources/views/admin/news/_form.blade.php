@php($news = $news ?? null)

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <x-admin.input name="title" label="Sarlavha" :value="$news?->title" required />
    <x-admin.input name="tag" label="Teg" :value="$news?->tag" required />
    <x-admin.input name="source" label="Manba" :value="$news?->source" />
    <x-admin.input name="published_at" label="Chop etilgan sana" type="datetime-local"
        :value="$news?->published_at?->format('Y-m-d\TH:i')" required />
</div>

<div class="mt-5">
    <x-admin.textarea name="excerpt" label="Qisqacha matn" :value="$news?->excerpt" rows="3" required />
</div>

<div class="mt-5">
    <x-admin.textarea name="body" label="To'liq matn" :value="$news?->body" rows="8" />
</div>

<div class="mt-5">
    <x-admin.checkbox name="hot" label="Dolzarb (hot)" :checked="(bool) $news?->hot" />
</div>

@php($article = $article ?? null)

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <x-admin.input name="title" label="Sarlavha" :value="$article?->title" required />
    <x-admin.input name="tag" label="Teg" :value="$article?->tag" required />
    <x-admin.input name="author_name" label="Muallif" :value="$article?->author_name" required />
    <x-admin.input name="read_minutes" label="O'qish vaqti (daqiqa)" type="number" :value="$article?->read_minutes ?? 5" required />
    <x-admin.input name="published_at" label="Chop etilgan sana" type="datetime-local"
        :value="$article?->published_at?->format('Y-m-d\TH:i')" required />
</div>

<div class="mt-5">
    <x-admin.textarea name="excerpt" label="Qisqacha matn" :value="$article?->excerpt" rows="3" required />
</div>

<div class="mt-5">
    <x-admin.textarea name="body" label="To'liq matn" :value="$article?->body" rows="8" />
</div>

<div class="mt-5">
    <x-admin.checkbox name="featured" label="Tanlangan (featured)" :checked="(bool) $article?->featured" />
</div>

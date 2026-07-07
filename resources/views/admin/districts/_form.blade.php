@php($district = $district ?? null)

<x-admin.input name="name" label="Tuman nomi" :value="$district?->name" required />

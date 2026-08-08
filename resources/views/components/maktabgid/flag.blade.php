@props(['code'])

@switch($code)
    @case('uz')
        <svg width="16" height="11" viewBox="0 0 16 11"><rect width="16" height="11" rx="2" fill="#0099B5"/><rect y="4" width="16" height="3" fill="#fff"/><rect y="4.6" width="16" height="1.8" fill="#1EB53A"/><circle cx="3.2" cy="2.2" r="1.1" fill="#fff"/></svg>
        @break
    @case('ru')
        <svg width="16" height="11" viewBox="0 0 16 11"><rect width="16" height="11" rx="2" fill="#fff"/><rect y="3.67" width="16" height="3.67" fill="#003DA5"/><rect y="7.33" width="16" height="3.67" fill="#E4181C"/></svg>
        @break
    @case('en')
        <svg width="16" height="11" viewBox="0 0 16 11"><rect width="16" height="11" rx="2" fill="#012169"/><path d="M0 0l16 11M16 0L0 11" stroke="#fff" stroke-width="2.2"/><path d="M8 0v11M0 5.5h16" stroke="#fff" stroke-width="4"/><path d="M8 0v11M0 5.5h16" stroke="#C8102E" stroke-width="2.4"/></svg>
        @break
@endswitch

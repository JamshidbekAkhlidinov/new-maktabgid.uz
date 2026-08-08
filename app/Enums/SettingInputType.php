<?php

namespace App\Enums;

/** Admin sozlamalar formasi qaysi HTML input komponentini render qilishini belgilaydi (SettingKey::inputType()). */
enum SettingInputType: string
{
    case Text = 'text';
    case Textarea = 'textarea';
    case Image = 'image';
}

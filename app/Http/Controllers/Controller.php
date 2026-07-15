<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ResolvesActiveInstitution;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller
{
    use AuthorizesRequests;
    use ResolvesActiveInstitution;
}

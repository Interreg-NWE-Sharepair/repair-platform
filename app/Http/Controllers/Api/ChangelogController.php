<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Replog\ReplogController;
use Illuminate\Support\Str;

class ChangelogController extends ReplogController
{
    public function index()
    {
        $markdown = file_get_contents(resource_path('docs/CHANGELOG.md'));

        return response()->make(Str::markdown($markdown));
    }
}

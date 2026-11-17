<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Service;
use App\Models\Staff;

class HomeController extends Controller
{
    public function index()
    {
        return view('site.home', [
            'services' => Service::active()->with(['staff' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')->orderBy('name')])->get(),
            'staff' => Staff::active()->get(),
            'posts' => Post::published()->limit(3)->get(),
            'maxDate' => now()->addDays((int) setting('max_advance_days', 60))->toDateString(),
        ]);
    }
}

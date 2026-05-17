<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        return Inertia::render('Welcome', [
            'name' => 'Bronx',
            'quote' => [
                'message' => 'Walk as if you are kissing the Earth with your feet.',
                'author' => 'Thich Nhat Hanh',
            ],
            'auth' => [
                'user' => auth()->user(),
            ],
            'sidebarOpen' => true,
            'canLogin' => true,
            'canRegister' => true,
        ]);
    }
}

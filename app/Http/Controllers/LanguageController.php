<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\View\View;

class LanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('language.index');
    }
}

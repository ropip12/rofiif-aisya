<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = $request->user();

        return view('pages.dashboard', [
            'user' => $user,
            'title' => 'Dashboard',
        ]);
    }

    public function aset(Request $request)
    {
        return view('pages.management', [
            'title' => 'Manajemen Aset',
            'user' => $request->user(),
            'management' => 'aset',
        ]);
    }

    public function risiko(Request $request)
    {
        return view('pages.management', [
            'title' => 'Manajemen Risiko',
            'user' => $request->user(),
            'management' => 'risiko',
        ]);
    }

    public function layanan(Request $request)
    {
        return view('pages.management', [
            'title' => 'Manajemen Layanan',
            'user' => $request->user(),
            'management' => 'layanan',
        ]);
    }
}

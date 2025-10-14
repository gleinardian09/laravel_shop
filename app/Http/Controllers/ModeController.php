<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ModeController extends Controller
{
    public function switchToAdmin(Request $request) // ADD Request parameter
    {
        // Check if user is admin
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect('/')->with('error', 'Unauthorized access. Admin privileges required.');
        }

        session(['current_mode' => 'admin']);
        return redirect()->route('admin.dashboard');
    }

    public function switchToStore(Request $request) // ADD Request parameter
    {
        session(['current_mode' => 'store']);
        return redirect()->route('home');
    }

    public function getCurrentMode()
    {
        return session('current_mode', 'store');
    }

    public function isAdminMode()
    {
        return $this->getCurrentMode() === 'admin';
    }

    public function isStoreMode()
    {
        return $this->getCurrentMode() === 'store';
    }
}
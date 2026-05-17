<?php

namespace Ssh521\KoreanBbs\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (session('bbs_admin_authenticated')) {
            return redirect()->route('bbs.admin.dashboard');
        }

        return view('korean-bbs::admin.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'id'       => 'required|string',
            'password' => 'required|string',
        ]);

        $adminId       = config('korean-bbs.admin.id');
        $adminPassword = config('korean-bbs.admin.password');

        if ($request->id === $adminId && $request->password === $adminPassword) {
            session([
                'bbs_admin_authenticated' => true,
                'bbs_admin_name'          => config('korean-bbs.admin.name'),
            ]);

            return redirect()->route('bbs.admin.dashboard');
        }

        return back()->withErrors(['id' => '아이디 또는 비밀번호가 올바르지 않습니다.'])->withInput();
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget(['bbs_admin_authenticated', 'bbs_admin_name']);

        return redirect()->route('bbs.admin.login');
    }
}

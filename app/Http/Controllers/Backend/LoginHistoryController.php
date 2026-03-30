<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\LoginHistory;
use App\Models\UserRole;
use Illuminate\Http\Request;

class LoginHistoryController extends Controller
{
    public function index(Request $request)
    {
        $roles = UserRole::orderBy('role_title')->get();
        
        $query = LoginHistory::with('role')
            ->whereNotNull('last_login_at');

        if ($request->has('role_id') && $request->role_id != '') {
            $query->where('role_id', $request->role_id);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $loginHistory = $query->orderBy('last_login_at', 'desc')
            ->paginate(20)
            ->appends($request->all());

        return view('backend.login-history.index', compact('loginHistory', 'roles'));
    }
}

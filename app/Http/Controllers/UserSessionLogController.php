<?php

namespace App\Http\Controllers;

use App\Models\UserSessionLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserSessionLogController extends Controller
{
    public function index(Request $request): View
    {
        if (!$request->user()?->is_admin) {
            abort(403);
        }

        $logs = UserSessionLog::with(['user.organization'])
            ->latest('login_at')
            ->paginate(50);

        return view('user-session-logs.index', compact('logs'));
    }
}

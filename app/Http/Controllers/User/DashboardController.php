<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'myTools'     => 2,
            'usageHours'  => 38,
            'ticketsOpen' => 1,
        ];
        return Inertia::render('User/Dashboard', ['stats' => $stats]);
    }
}

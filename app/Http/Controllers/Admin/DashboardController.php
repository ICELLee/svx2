<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $registeredUsers = DB::table('users')->count();

        $onlineUsers = Schema::hasTable('sessions')
            ? DB::table('sessions')
                ->where('last_activity', '>=', now()->subMinutes(5)->timestamp)
                ->count()
            : 0;

        $activeTools = Schema::hasTable('tools')
            ? DB::table('tools')->where('active', 1)->count()
            : 0;

        $revenue = Schema::hasTable('orders')
            ? (float) DB::table('orders')->where('status', 'paid')->sum('amount')
            : 0.0;

        $serverErrors = Schema::hasTable('failed_jobs')
            ? DB::table('failed_jobs')->count()
            : 0;

        $root = base_path();
        $free = @disk_free_space($root);
        $total = @disk_total_space($root);
        $storageUsage = ($total && $free !== false)
            ? sprintf('%s / %s', $this->humanBytes($total - $free), $this->humanBytes($total))
            : 'n/a';

        $serverUp = true;

        $stats = [
            'onlineUsers'     => $onlineUsers,
            'activeTools'     => $activeTools,
            'revenue'         => $revenue,
            'storageUsage'    => $storageUsage,
            'registeredUsers' => $registeredUsers,
            'serverUp'        => $serverUp,
            'serverErrors'     => $serverErrors,
        ];

        return Inertia::render('Admin/Dashboard', ['stats' => $stats]);
    }

    private function humanBytes(int|float $bytes, int $decimals = 1): string
    {
        $units = ['B','KB','MB','GB','TB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return number_format($bytes, $decimals) . ' ' . $units[$i];
    }
}

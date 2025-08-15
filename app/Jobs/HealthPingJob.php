<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class HealthPingJob implements ShouldQueue
{
    use Dispatchable, Queueable;
    public function handle(): void { /* no-op */ }
}

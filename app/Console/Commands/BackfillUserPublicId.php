<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class BackfillUserPublicId extends Command
{
    protected $signature = 'users:backfill-public-id';
    protected $description = 'Generate SVX-Uxxxxxxx public_id for users without one';

    public function handle(): int
    {
        $count = 0;
        User::whereNull('public_id')->orWhere('public_id','')->chunkById(200, function ($chunk) use (&$count) {
            foreach ($chunk as $u) {
                $u->public_id = User::generatePublicId();
                $u->save();
                $count++;
            }
        });
        $this->info("Updated {$count} users.");
        return self::SUCCESS;
    }
}

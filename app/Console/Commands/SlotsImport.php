<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Slot;

class SlotsImport extends Command
{
    protected $signature = 'slots:import {url}';
    protected $description = 'Import slots from external JSON URL (upsert by key)';

    public function handle(): int
    {
        $url = $this->argument('url');
        $res = Http::timeout(30)->get($url);
        if (!$res->ok()) {
            $this->error('HTTP '.$res->status());
            return 1;
        }
        $data = $res->json();
        if (!is_array($data)) {
            $this->error('Invalid JSON');
            return 1;
        }
        $n=0;
        foreach ($data as $row) {
            if (empty($row['key']) || empty($row['name'])) continue;
            $payload = [
                'name'         => $row['name'] ?? null,
                'provider'     => $row['provider'] ?? null,
                'rtp'          => $row['rtp'] ?? null,
                'max_win'      => $row['max_win'] ?? null,
                'image_url'    => $row['image_url'] ?? null,
                'personal_win' => $row['personal_win'] ?? null,
                'personal_bet' => $row['personal_bet'] ?? null,
            ];
            $slot = Slot::firstOrNew(['key'=>$row['key']]);
            $slot->fill($payload)->save();
            $n++;
        }
        $this->info("Imported/updated: {$n}");
        return 0;
    }
}

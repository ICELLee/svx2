<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BonusHunt;
use App\Models\BonusHuntEntry;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class BonusHuntEntryAdminController extends Controller
{
    public function hunts()
    {
        return BonusHunt::query()
            ->select('id','name','is_active','public_token','updated_at')
            ->orderByDesc('id')
            ->get()
            ->each->append('public_url');
    }
    public function index(BonusHunt $hunt)
    {
        return $hunt->entries()->with('slot')->get();
    }

    public function store(Request $req, BonusHunt $hunt)
    {
        $data = $req->validate([
            'slot_id'      => ['required', Rule::exists('slots','id')],
            'position'     => ['nullable','integer','min:1'],
            'bet'          => ['nullable','numeric','min:0'],
            'win'          => ['nullable','numeric','min:0'],
            'x_value'      => ['nullable','numeric','min:0'],
            'bonus_bought' => ['nullable','boolean'],
            'bonus_cost'   => ['nullable','numeric','min:0'],
        ]);

        return DB::transaction(function () use ($hunt, $data) {
            if (!empty($data['position'])) {
                $this->makeSpace($hunt->id, (int)$data['position']);
            } else {
                $data['position'] = (int) BonusHuntEntry::where('bonus_hunt_id', $hunt->id)->max('position') + 1;
            }

            $entry = BonusHuntEntry::create([
                'bonus_hunt_id' => $hunt->id,
                'slot_id'       => $data['slot_id'],
                'position'      => $data['position'],
                'bet'           => $data['bet'] ?? 0,
                'win'           => $data['win'] ?? null,
                'x_value'       => $data['x_value'] ?? null,
                'bonus_bought'  => (bool)($data['bonus_bought'] ?? false),
                'bonus_cost'    => $data['bonus_cost'] ?? null,
            ]);

            return $entry->load('slot');
        });
    }

    public function bulkStore(Request $req, BonusHunt $hunt)
    {
        $data = $req->validate([
            'slot_ids'      => ['required','array','min:1'],
            'slot_ids.*'    => [Rule::exists('slots','id')],
            'bet'           => ['nullable','numeric','min:0'],
            'start_position'=> ['nullable','integer','min:1'],
        ]);

        return DB::transaction(function () use ($hunt, $data) {
            $pos = $data['start_position'] ?? (int) BonusHuntEntry::where('bonus_hunt_id', $hunt->id)->max('position') + 1;

            if (isset($data['start_position'])) {
                $count = count($data['slot_ids']);
                $this->makeSpace($hunt->id, $pos, $count);
            }

            $created = 0;
            foreach (array_unique($data['slot_ids']) as $slotId) {
                $exists = BonusHuntEntry::where('bonus_hunt_id',$hunt->id)->where('slot_id',$slotId)->exists();
                if ($exists) { $pos++; continue; }

                BonusHuntEntry::create([
                    'bonus_hunt_id' => $hunt->id,
                    'slot_id'       => $slotId,
                    'position'      => $pos++,
                    'bet'           => $data['bet'] ?? 0,
                ]);
                $created++;
            }

            return response()->json(['created' => $created]);
        });
    }

    public function update(Request $req, BonusHunt $hunt, BonusHuntEntry $entry)
    {
        abort_if($entry->bonus_hunt_id !== $hunt->id, 404);

        $data = $req->validate([
            'position'     => ['nullable','integer','min:1'],
            'bet'          => ['nullable','numeric','min:0'],
            'win'          => ['nullable','numeric','min:0'],
            'x_value'      => ['nullable','numeric','min:0'],
            'bonus_bought' => ['nullable','boolean'],
            'bonus_cost'   => ['nullable','numeric','min:0'],
        ]);

        return DB::transaction(function () use ($entry, $hunt, $data) {
            if (isset($data['position']) && $data['position'] !== $entry->position) {
                $this->moveToPosition($hunt->id, $entry, (int)$data['position']);
            }

            $entry->fill(collect($data)->except('position')->all())->save();
            return $entry->load('slot');
        });
    }

    public function destroy(BonusHunt $hunt, BonusHuntEntry $entry)
    {
        abort_if($entry->bonus_hunt_id !== $hunt->id, 404);

        return DB::transaction(function () use ($hunt, $entry) {
            $oldPos = $entry->position;
            $entry->delete();

            BonusHuntEntry::where('bonus_hunt_id',$hunt->id)
                ->where('position','>', $oldPos)
                ->decrement('position');

            $hunt->touch();
            return response()->noContent();
        });
    }

    public function clear(BonusHunt $hunt)
    {
        $hunt->entries()->delete();
        $hunt->touch();
        return response()->noContent();
    }

    private function makeSpace(int $huntId, int $fromPos, int $count = 1): void
    {
        BonusHuntEntry::where('bonus_hunt_id', $huntId)
            ->where('position', '>=', $fromPos)
            ->increment('position', $count);
    }

    private function moveToPosition(int $huntId, BonusHuntEntry $entry, int $newPos): void
    {
        $oldPos = $entry->position;
        if ($newPos === $oldPos) return;

        if ($newPos < $oldPos) {
            BonusHuntEntry::where('bonus_hunt_id',$huntId)
                ->whereBetween('position', [$newPos, $oldPos - 1])
                ->increment('position');
        } else {
            BonusHuntEntry::where('bonus_hunt_id',$huntId)
                ->whereBetween('position', [$oldPos + 1, $newPos])
                ->decrement('position');
        }
        $entry->position = $newPos;
        $entry->save();
    }
}

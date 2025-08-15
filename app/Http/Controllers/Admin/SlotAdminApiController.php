<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Client\ConnectionException;

class SlotAdminApiController extends Controller
{
    // KEIN __construct() – Absicherung passiert über die Routen (web.php / admin/api)

    // GET /admin/api/slots?s=&provider=&active=all|1|0&sort=name|updated_at&dir=asc|desc&page=1
    public function index(Request $req)
    {
        $q = Slot::query();

        $s = $req->string('s')->trim()->toString();
        if ($s !== '') {
            $q->where(function ($x) use ($s) {
                $x->where('name', 'like', "%{$s}%")
                    ->orWhere('key', 'like', "%{$s}%")
                    ->orWhere('provider', 'like', "%{$s}%");
            });
        }

        $provider = $req->string('provider')->trim()->toString();
        if ($provider !== '') {
            $q->where('provider', $provider);
        }

        $active = $req->string('active')->toString() ?: 'all';
        if ($active === '1') $q->where('is_active', true);
        if ($active === '0') $q->where('is_active', false);

        $sortParam = $req->string('sort')->toString();
        $sort = in_array($sortParam, ['name','updated_at'], true) ? $sortParam : 'updated_at';

        $dirParam = strtolower($req->string('dir')->toString());
        $dir = $dirParam === 'asc' ? 'asc' : 'desc';

        $q->orderBy($sort, $dir)->orderBy('id', 'desc');

        return $q->paginate(24);
    }

    public function providers(Request $req)
    {
        $q = \App\Models\Slot::query()
            ->whereNotNull('provider')
            ->whereRaw("TRIM(provider) <> ''");

        // optional nach Aktiv-Status zählen (wie im Grid)
        $active = $req->string('active')->toString() ?: 'all';
        if ($active === '1') $q->where('is_active', true);
        if ($active === '0') $q->where('is_active', false);

        return $q->selectRaw('TRIM(provider) as provider, COUNT(*) as count')
            ->groupBy(DB::raw('TRIM(provider)'))
            ->orderBy('provider')
            ->get();
    }

    public function store(Request $req)
    {
        $data = $this->validateData($req);
        $data['image_url'] = $this->normalizeImageUrl($data['image_url'] ?? null);
        $slot = Slot::create($data);
        return response()->json($slot, 201);
    }

    public function update(Request $req, Slot $slot)
    {
        $data = $this->validateData($req, $slot->id);
        $data['image_url'] = $this->normalizeImageUrl($data['image_url'] ?? null);
        $slot->update($data);
        return $slot;
    }

    public function destroy(Slot $slot)
    {
        $slot->delete();
        return response()->noContent();
    }

    public function toggle(Slot $slot)
    {
        $slot->is_active = ! $slot->is_active;
        $slot->save();
        return response()->json(['is_active' => $slot->is_active]);
    }

    // POST { url: "https://..." }
    // Unterstützt JSON wie dein Beispiel: id, name, img, RTP, Maxwin, provider
    public function importFromUrl(Request $req)
    {
        $req->validate(['url' => 'required|url']);
        $url = $req->string('url')->toString();

        try {
            $res = \Http::accept('application/json')->timeout(20)->retry(2, 300)->get($url);
        } catch (ConnectionException $e) {
            return response()->json(['error' => 'Fetch exception: '.$e->getMessage()], 422);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Server exception: '.$e->getMessage()], 422);
        }

        if (! $res->ok()) {
            return response()->json(['error' => 'Fetch failed (HTTP '.$res->status().')'], 422);
        }

        $items = $res->json();
        if (!is_array($items)) {
            return response()->json(['error' => 'Invalid JSON payload (expected array)'], 422);
        }

        $count = 0;
        DB::transaction(function () use ($items, &$count) {
            foreach ($items as $row) {
                $name = (string) data_get($row, 'name', '');
                if ($name === '') continue;

                // key aus JSON oder Name slugen; sonst Fallback mit id/random
                $key = (string) data_get($row, 'key', '');
                if ($key === '') $key = Str::slug($name) ?: ('slot-'.(data_get($row,'id') ?? Str::random(8)));

                $payload = [
                    'name'         => $name,
                    'key'          => $key,
                    'provider'     => data_get($row, 'provider'),
                    'rtp'          => $this->normalizeRtp(data_get($row, 'RTP') ?? data_get($row, 'rtp')),
                    'max_win'      => $this->normalizeMaxWin(data_get($row, 'Maxwin') ?? data_get($row, 'max_win')),
                    'image_url'    => $this->normalizeImageUrl(data_get($row, 'img') ?? data_get($row, 'image_url')),
                    'personal_win' => $this->nn(data_get($row, 'personal_win')),
                    'personal_bet' => $this->nn(data_get($row, 'personal_bet')),
                    // personal_x berechnet das Model beim saving()
                ];

                Slot::updateOrCreate(['key' => $key], $payload);
                $count++;
            }
        });

        return response()->json(['imported' => $count]);
    }

    private function validateData(Request $req, ?int $id = null): array
    {
        return $req->validate([
            'name'         => ['required','string','max:255'],
            'key'          => ['required','string','max:255', \Illuminate\Validation\Rule::unique('slots','key')->ignore($id)],
            'provider'     => ['nullable','string','max:100'],
            'rtp'          => ['nullable','string','max:20'],
            'max_win'      => ['nullable','string','max:50'],
            'image_url'    => ['nullable','string','max:500'],
            'personal_win' => ['nullable','numeric','min:0'],
            'personal_bet' => ['nullable','numeric','min:0'],
            'personal_x'   => ['nullable','numeric','min:0'],
            'is_active'    => ['sometimes','boolean'],
        ]);
    }

    private function normalizeImageUrl($url): ?string
    {
        $url = trim((string) $url);
        if ($url === '') return null;
        if (str_starts_with($url, '//')) $url = 'https:'.$url; // protokoll-relativ -> https
        if (!preg_match('~^https?://~i', $url)) {
            $prefix = rtrim(config('app.asset_cdn', config('app.url')), '/');
            $url = $prefix.'/'.ltrim($url, '/');
        }
        $url = preg_replace('~^http://~i', 'https://', $url);
        return filter_var($url, FILTER_VALIDATE_URL) ? $url : null;
    }

    private function normalizeRtp($v): ?string
    {
        if ($v === null) return null;
        $s = str_replace(['%', ' '], '', (string)$v);
        $s = str_replace(',', '.', $s); // 96,70 -> 96.70
        $s = trim($s);
        return $s === '' ? null : $s;
    }

    private function normalizeMaxWin($v): ?string
    {
        if ($v === null) return null;
        $s = trim((string)$v);
        if ($s === '' || preg_match('/unbek/i', $s)) return null;
        // z.B. "85.475x" -> "85475x"
        $digits = preg_replace('/\D+/', '', str_replace(['.', ' '], '', $s));
        if ($digits === '') return null;
        return $digits.'x';
    }

    private function nn($v): ?float
    {
        if ($v === null || $v === '') return null;
        return is_numeric($v) ? (float) $v : null;
    }
}

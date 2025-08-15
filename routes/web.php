<?php

use App\Http\Controllers\Admin\BonusHuntEntryAdminController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\PromoCodeAdminController;
use App\Http\Controllers\Admin\SlotAdminApiController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Media\SlotImageController;
use App\Http\Controllers\obs\BonusHuntOBSController as BH;
use App\Http\Controllers\Public\SlotLiveController;
use App\Http\Controllers\Tools\SlotTrackerController;
use App\Http\Controllers\User\BonusHuntApiController;
use App\Http\Controllers\User\DashboardController as UserDashboard;
use App\Http\Controllers\User\RedeemCodeController;
use App\Http\Controllers\User\SlotLookupController;
use App\Http\Controllers\User\SlotStatController;
use App\Models\Slot;
use App\Models\SlotUserStat;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Public OBS (Bonus Hunt)
|--------------------------------------------------------------------------
*/
Route::get('/o/bonushunt/{token}',                [BH::class, 'show'])->name('bonushunt.public');
Route::get('/o/bonushunt/{token}/partial',        [BH::class, 'partial'])->name('bonushunt.public.partial');
Route::get('/o/bonushunt/{token}/stats',          [BH::class, 'stats'])->name('bonushunt.public.stats');
Route::get('/api/bonus-hunt-updated-at/{token}',  [BH::class, 'updatedAt'])->name('bonushunt.public.updated');

Route::middleware('auth')->get('/tools/slottracker', [SlotTrackerController::class, 'index'])
    ->name('tools.slottracker');

Route::middleware('auth')->prefix('user/api')->group(function () {
    Route::get   ('/extension-tokens',      [SlotTrackerController::class, 'index']);
    Route::post  ('/extension-tokens',      [SlotTrackerController::class, 'store']);
    Route::delete('/extension-tokens/{id}', [SlotTrackerController::class, 'destroy']);
});

Route::middleware(['auth'])->get('/api/slot/current', function () {
    $userId = auth()->id();
    $key = Cache::get("slot:current:user:{$userId}");
    return response()->json(['key' => $key]);
});

/*
|--------------------------------------------------------------------------
| Slot Live + Partial
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->get('/slot/partial/{key}', function (string $key) {
    $norm = Str::of($key)->lower()->replace(['_', ' '], '-');

    $q = Slot::query()->where('key', $key);

    if (Schema::hasColumn('slots', 'slug')) {
        $q->orWhere('slug', $key);
    }

    $q->orWhereRaw('LOWER(REPLACE(REPLACE(name,"_","-")," ","-")) = ?', [$norm]);

    // Slot finden oder auf letzten als Fallback
    $slot = $q->first() ?: Slot::latest()->first();

    // 👇 persönliche Werte des eingeloggten Users einspeisen
    if ($slot) {
        $stat = SlotUserStat::where('user_id', auth()->id())
            ->where('slot_id', $slot->id)
            ->first();

        if ($stat) {
            $slot->personal_bet = $stat->personal_bet;
            $slot->personal_win = $stat->personal_win;
            $slot->personal_x   = $stat->personal_x;
        }
    }

    // Achtung: Pfad muss zu resources/views/extension/slot/_slotbox.blade.php passen
    return view('extension._slotbox', ['slot' => $slot]);
});

Route::get('/slot/live', function () {
    $slot = Slot::latest()->first();
    return view('extension.slot', compact('slot'));
})->name('slot.live')->middleware('auth');

// One-time Extension-Setup (signed)
Route::get('/o/extension/setup/{link:ulid}', [SlotTrackerController::class, 'setupBlade'])
    ->name('extension.setup')
    ->middleware('signed');

/*
|--------------------------------------------------------------------------
| Auth: Tools (Inertia)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/tools/bonushunt', fn () => Inertia::render('Tools/BonusHunt'))->name('tools.bonushunt');
    Route::get('/tools/slottracker', fn () => Inertia::render('Tools/SlotTracker'))->name('tools.slottracker');
});

Route::middleware(['auth','verified'])->group(function () {
    Route::get('/tools/slots', fn () => Inertia::render('Tools/Slots'))->name('tools.slots');
});

/*
|--------------------------------------------------------------------------
| Root -> Dashboard je Rolle
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (! auth()->check()) return redirect()->route('login');
    return auth()->user()->hasRole('admin')
        ? redirect()->route('admin.index')
        : redirect()->route('dashboard');
})->name('home');

/*
|--------------------------------------------------------------------------
| Authenticated User
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::middleware(['auth'])->get('/tools/redeem', fn () => Inertia::render('Tools/RedeemCode'))->name('tools.redeem');
    Route::get('/dashboard', [UserDashboard::class, 'index'])->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| User API (Bonus Hunts)
|--------------------------------------------------------------------------
*/
Route::prefix('user/api')->middleware(['web','auth'])->group(function () {
    Route::get('/slots/search', [SlotLookupController::class, 'search']);
    Route::get   ('/bonus-hunts',                        [BonusHuntApiController::class, 'index']);
    Route::post  ('/bonus-hunts',                        [BonusHuntApiController::class, 'store']);
    Route::put   ('/bonus-hunts/{hunt}',                 [BonusHuntApiController::class, 'update']);
    Route::delete('/bonus-hunts/{hunt}',                 [BonusHuntApiController::class, 'destroy']);
    Route::post('/codes/redeem', [RedeemCodeController::class,'redeem']);
    Route::get   ('/bonus-hunts/{hunt}/entries',         [BonusHuntApiController::class, 'entries']);
    Route::post  ('/bonus-hunts/{hunt}/entries',         [BonusHuntApiController::class, 'addEntry']);
    Route::put   ('/bonus-hunts/{hunt}/entries/{entry}', [BonusHuntApiController::class, 'updateEntry']);
    Route::delete('/bonus-hunts/{hunt}/entries/{entry}', [BonusHuntApiController::class, 'deleteEntry']);

    Route::get   ('/bonus-hunts/{hunt}/link',            [BonusHuntApiController::class, 'link']);
});

Route::controller(SlotLiveController::class)->group(function () {
    Route::get('/slot/live/{slug}', 'show')->name('slot.live');         // HTML-Seite mit Box
    Route::get('/slot/current/{slug}', 'current')->name('slot.current'); // JSON: { key: ... }
    Route::get('/slot/partial/{key}', 'partial')->name('slot.partial');  // HTML-Snippet
});
/*
|--------------------------------------------------------------------------
| Admin API (Slots + Bonus-Hunt-Entries)
|--------------------------------------------------------------------------
*/
Route::prefix('admin/api')->middleware(['web','auth','role:admin'])->group(function () {
    Route::get   ('/slots',               [SlotAdminApiController::class,'index']);
    Route::get   ('/slots/providers',     [SlotAdminApiController::class,'providers']);
    Route::post  ('/slots',               [SlotAdminApiController::class,'store']);
    Route::put   ('/slots/{slot}',        [SlotAdminApiController::class,'update']);
    Route::delete('/slots/{slot}',        [SlotAdminApiController::class,'destroy']);
    Route::patch ('/slots/{slot}/toggle', [SlotAdminApiController::class,'toggle']);
    Route::post  ('/slots/import-url',    [SlotAdminApiController::class,'importFromUrl']);
    Route::get   ('/codes',                 [PromoCodeAdminController::class,'index']);
    Route::post  ('/codes',                 [PromoCodeAdminController::class,'store']);
    Route::patch ('/codes/{code}/lock',     [PromoCodeAdminController::class,'lock']);
    Route::patch ('/codes/{code}/unlock',   [PromoCodeAdminController::class,'unlock']);
    Route::get   ('/bonus-hunts',                           [BonusHuntEntryAdminController::class, 'hunts']);
    Route::get   ('/bonus-hunts/{hunt}/entries',            [BonusHuntEntryAdminController::class, 'index']);
    Route::post  ('/bonus-hunts/{hunt}/entries',            [BonusHuntEntryAdminController::class, 'store']);
    Route::post  ('/bonus-hunts/{hunt}/entries/bulk',       [BonusHuntEntryAdminController::class, 'bulkStore']);
    Route::put   ('/bonus-hunts/{hunt}/entries/{entry}',    [BonusHuntEntryAdminController::class, 'update']);
    Route::delete('/bonus-hunts/{hunt}/entries/{entry}',    [BonusHuntEntryAdminController::class, 'destroy']);
    Route::delete('/bonus-hunts/{hunt}/entries',            [BonusHuntEntryAdminController::class, 'clear']);
});

/*
|--------------------------------------------------------------------------
| Admin Panel (Inertia screens)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware(['auth','role:admin'])->group(function () {
    Route::get('/',                     [AdminDashboard::class, 'index'])->name('admin.index');
    Route::get('/slots', fn () => Inertia::render('Admin/Slots'))->name('admin.slots');
    Route::get('/codes', fn () => Inertia::render('Admin/Codes'))->name('admin.codes');
    Route::get('/media/slot/{slot}',    [SlotImageController::class, 'show'])->name('media.slot');
    Route::get('/users',                [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{user}',         [UserController::class, 'show'])->name('admin.users.show');
    Route::put('/users/{user}',         [UserController::class, 'update'])->name('admin.users.update');
    Route::put('/users/{user}/roles',   [UserController::class, 'updateRoles'])->name('admin.users.roles');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

@php
    $startAmount   = $hunt->start_amount ?? 0;
    $totalWin      = optional($hunt)->entries?->sum('win') ?? 0;
    $missingAmount = max(0, $startAmount - $totalWin);
    $remaining     = optional($hunt)->entries?->filter(fn($e) => empty($e->win)) ?? collect();
    $remainingBets = $remaining->sum('bet');
    $xNeeded       = $remainingBets > 0 ? round($missingAmount / $remainingBets, 2) : null;
@endphp

<div class="bg-dark p-3 rounded-t-lg border-b-2 border-primary relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-r from-primary/10 to-transparent opacity-20 animate-shine"></div>
    <h1 class="text-xl md:text-3xl font-bold text-center text-primary relative z-10">
        {{ $hunt->name ?? 'Bonus Hunt' }}
    </h1>
    <p class="text-sm text-secondary text-center mt-1 font-mono relative z-10">
        <i class="fa-solid fa-play icon"></i>Startkapital: {{ number_format($startAmount, 2, ',', '.') }} €
    </p>
    <p class="text-sm text-secondary text-center mt-1 font-mono relative z-10">
        <i class="fa-solid fa-coins icon"></i>Gesamtgewinn: {{ number_format($totalWin, 2, ',', '.') }} €
    </p>
    @if($missingAmount > 0)
        <p class="text-sm text-secondary text-center mt-1 font-mono relative z-10">
            <i class="fa-solid fa-xmarks-lines icon"></i>
            Benötigt: {{ number_format($xNeeded, 2, ',', '.') }}x ({{ $remaining->count() }} Slots offen)
        </p>
    @endif
</div>

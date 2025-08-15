@foreach($hunt->entries as $entry)
    @php
        // Zahlen sicher casten
        $bet = is_numeric($entry->bet) ? (float)$entry->bet : 0.0;
        $win = is_numeric($entry->win) ? (float)$entry->win : null;

        // X: priorisiere gespeichertes x_value, sonst berechnen (nur wenn bet>0 und win gesetzt)
        $x_raw = is_numeric($entry->x_value) ? (float)$entry->x_value : ($bet > 0 && $win !== null ? $win / $bet : null);

        // Klassenlogik
        $textColor = 'text-white';
        $borderEffect = '';
        $specialEffect = '';
        $xClass = '';
        $xNum = $x_raw ?? 0.0;

        if ($xNum >= 500) { $borderEffect='border-2 border-primary';     $textColor='text-primary'; $xClass='animate-pulse'; }
        elseif ($xNum >= 250) { $borderEffect='border border-primary';   $textColor='text-primary'; }
        elseif ($xNum >= 100) { $borderEffect='border border-primary/50';$textColor='text-primary'; }
        elseif ($xNum >= 50)  { $textColor='text-secondary'; }

        $winClass = ($win !== null && $bet > 0 && $win >= $bet * 20) ? 'font-bold text-primary' : '';

        // Anzeige-Helper (deutsche Formatierung)
        $fmt = fn($n, $d=2) => number_format((float)$n, $d, ',', '.');
        $money = fn($n) => ($n === null ? '—' : $fmt($n).' €');
        $xDisp = $x_raw === null ? '—' : $fmt($x_raw).'x';

        $slotName = $entry->slot?->name ?? 'Unbekannt';
        $provider = $entry->slot?->provider ?? '—';
        $img      = $entry->slot?->image_url ?? asset('images/placeholder.jpg');
    @endphp

    <div class="slot-item relative border-b border-primary/20 last:border-b-0 overflow-hidden {{ $borderEffect }} {{ $specialEffect }}">
        <div class="p-3 flex items-center relative z-10 gap-3 h-full">
            <!-- Position -->
            <div class="w-10 h-10 flex items-center justify-center bg-dark border-2 border-primary rounded-full text-sm font-bold {{ $textColor }}">
                {{ (int)$entry->position }}
            </div>

            <!-- Bild -->
            <div class="slot-image flex-shrink-0">
                <img
                    src="{{ $img }}"
                    alt="{{ $slotName }}"
                    class="w-[100px] h-[100px] object-contain rounded-md border border-primary/30"
                    referrerpolicy="no-referrer"
                    loading="lazy"
                    onerror="this.onerror=null;this.src='{{ asset('images/placeholder.jpg') }}';"
                >
            </div>

            <!-- Details -->
            <div class="flex-grow min-w-0">
                <div class="flex justify-between items-baseline">
                    <p class="text-xs text-secondary/80 truncate" title="{{ $slotName }}">{{ $slotName }}</p>
                    <span class="text-sm font-mono {{ $textColor }} {{ $xClass }}">{{ $xDisp }}</span>
                </div>
                <p class="text-xs text-secondary/80 truncate" title="{{ $provider }}">{{ $provider }}</p>

                <div class="flex justify-between text-sm mt-2 font-medium">
                    <span class="text-secondary font-mono">Einsatz: {{ $fmt($bet) }} €</span>
                    <span class="font-mono {{ $winClass }}">{{ $money($win) }}</span>
                </div>

                @if ($entry->bonus_bought)
                    <div class="mt-2 pt-1 text-xs bg-secondary/10 text-secondary flex items-center justify-start border-t border-primary/20">
                        <span class="flex items-center px-2 py-1 rounded">
                            <span class="mr-1">✨</span> Bonus gekauft
                            @if (is_numeric($entry->bonus_cost))
                                <span class="ml-1 font-mono">({{ $fmt($entry->bonus_cost) }} €)</span>
                            @endif
                        </span>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endforeach

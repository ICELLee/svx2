<div id="slotContent" data-slot-key="{{ $slot?->key ?? 'none' }}"
     class="font-sans bg-[#0D0C0B]/90 text-[#EFE9BD] rounded-xl shadow-2xl border-2 border-[#E0BE56] p-6 w-[580px] transition-all duration-300 relative overflow-hidden"
     style="box-shadow: 0 0 25px rgba(224, 190, 86, 0.7); animation: border-glow 8s infinite ease-in-out;">

    @php
        // Helper für Zahl-Format mit Fallback
        $nf = function ($v, $dec = 2, $suffix = '') {
            return is_numeric($v) ? number_format((float)$v, $dec, ',', '.') . $suffix : 'n/a';
        };
        $img = $slot?->image_url ?: asset('images/placeholder-slot.png');
        $name = $slot?->name ?? 'Unbekannter Slot';
        $rtp = is_numeric($slot?->rtp ?? null) ? number_format((float)$slot->rtp, 2, ',', '.') . '%' : 'n/a';
        $maxwin = $slot?->max_win ?? 'n/a';
        $pwin = $nf($slot?->personal_win ?? null, 2, ' €');
        $pbet = $nf($slot?->personal_bet ?? null, 2, ' €');
        $px   = $nf($slot?->personal_x ?? null, 2, 'x');
    @endphp

    {{-- Background nur zeigen, wenn Slot existiert --}}
    @if($slot)
        <div class="absolute inset-0 -z-10 overflow-hidden rounded-xl">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-[1px]"></div>
            <img src="{{ $img }}" alt="{{ $name }}" class="w-full h-full object-cover object-center opacity-60 scale-110">
        </div>
    @endif

    <div class="particles-container absolute inset-0 overflow-hidden rounded-xl -z-0 pointer-events-none"></div>
    <div class="sparkles-container absolute inset-0 overflow-hidden rounded-xl -z-0 pointer-events-none"></div>
    <div class="absolute inset-0 rounded-xl bg-gradient-to-br from-[#E0BE56]/30 to-[#EFE9BD]/20 opacity-30 pointer-events-none"></div>

    @if($slot)
        <div class="relative z-10">
            <div class="mb-4 pb-2 border-b border-[#E0BE56]/50">
                <h2 class="text-sm font-semibold text-[#E0BE56] tracking-wider">SLOT INFORMATIONEN</h2>
            </div>

            <div class="flex items-center space-x-6">
                <div class="relative">
                    <div class="absolute inset-0 rounded-lg bg-gradient-to-br from-[#E0BE56] to-[#EFE9BD] opacity-30 blur-md -z-10"></div>
                    <img src="{{ $img }}" alt="{{ $name }}"
                         class="w-24 h-24 object-cover rounded-lg border-2 border-[#E0BE56] flex-shrink-0 shadow-lg shadow-[#E0BE56]/50">
                    <div class="absolute inset-0 rounded-lg border-2 border-transparent pointer-events-none animate-ping-slow"></div>
                </div>

                <div class="flex-1 min-w-0">
                    <h1 class="text-2xl font-bold mb-3 truncate" style="text-shadow: 0 0 8px rgba(224, 190, 86, 0.8);">
                        {{ $name }}
                    </h1>

                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div class="bg-[#0D0C0B]/80 rounded-lg p-3 flex items-center border border-[#E0BE56] animate-stat-pulse backdrop-blur-sm">
                            <span class="text-[#E0BE56] mr-2 text-xl">🎯</span>
                            <div>
                                <div class="text-xs text-[#C9C9C9]">RTP</div>
                                <span class="font-mono text-xl">{{ $rtp }}</span>
                            </div>
                        </div>
                        <div class="bg-[#0D0C0B]/80 rounded-lg p-3 flex items-center border border-[#E0BE56] animate-stat-pulse backdrop-blur-sm" style="animation-delay: 0.5s;">
                            <span class="text-[#E0BE56] mr-2 text-xl">🏆</span>
                            <div>
                                <div class="text-xs text-[#C9C9C9]">Max Win</div>
                                <span class="font-mono text-xl">{{ $maxwin }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-5 pt-5 border-t border-[#E0BE56]/50">
                <div class="flex justify-between items-center text-base mb-3">
                    <span class="text-[#EFE9BD]">Höchster Gewinn:</span>
                    <span class="font-mono text-2xl text-[#E0BE56]">{{ $pwin }}</span>
                </div>
                <div class="flex justify-between items-center text-base mb-3">
                    <span class="text-[#EFE9BD]">Einsatz:</span>
                    <span class="font-mono text-xl text-[#E0BE56]">{{ $pbet }}</span>
                </div>
                <div class="flex justify-between items-center text-base">
                    <span class="text-[#EFE9BD]">Multiplier:</span>
                    <span class="font-mono text-2xl text-[#E0BE56]">{{ $px }}</span>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-8 text-[#E0BE56]/70 relative z-10 animate-pulse text-xl">
            ❌ Slot nicht gefunden
        </div>
    @endif

    <style>
        @keyframes border-glow { 0%,100%{border-color:#E0BE56;box-shadow:0 0 25px rgba(224,190,86,.7)} 50%{border-color:#EFE9BD;box-shadow:0 0 40px rgba(239,233,189,1)} }
        @keyframes ping-slow { 0%{transform:scale(.95);opacity:.7} 100%{transform:scale(2);opacity:0} }
        @keyframes stat-pulse { 0%,100%{transform:scale(1);box-shadow:0 0 0 rgba(224,190,86,0)} 50%{transform:scale(1.02);box-shadow:0 0 10px rgba(224,190,86,.5)} }
        @keyframes rotate-glow { 0%{transform:rotate(0) translateX(-50%)} 100%{transform:rotate(360deg) translateX(-50%)} }
        .animate-ping-slow{animation:ping-slow 3s infinite ease-out}
        .animate-stat-pulse{animation:stat-pulse 4s infinite ease-in-out}
        .particle{position:absolute;border-radius:50%;background:radial-gradient(circle,rgba(224,190,86,1) 0%,rgba(201,201,201,.7) 70%,rgba(0,0,0,0) 100%);filter:drop-shadow(0 0 6px rgba(224,190,86,.9));animation:float-particle 25s infinite linear;opacity:0}
        .sparkle{position:absolute;width:8px;height:8px;background:#fff;border-radius:50%;filter:drop-shadow(0 0 6px #E0BE56);animation:sparkle 2.5s infinite ease-out;opacity:0}
        .glow-pointer{animation:rotate-glow 6s linear infinite;transform-origin:left center}
    </style>

    {{-- Hinweis: <script> in Partials wird beim innerHTML-Replacement nicht ausgeführt.
         Wenn du Partikel willst, initialisiere sie NACH dem Austausch im umgebenden JS. --}}
</div>

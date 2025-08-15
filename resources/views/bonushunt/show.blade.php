<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>{{ $hunt->name ?? 'Bonus Hunt' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind + Font Awesome -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/70b82cdab7.js" crossorigin="anonymous"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#FFCA00',
                        secondary: '#DAD0EB',
                        dark: '#262626',
                    },
                    animation: {
                        'glitch': 'glitch 2s linear infinite, noise-2 3s linear infinite, fudge 7s ease-in-out infinite',
                        'glitch-2': 'glitch-2 3s linear infinite',
                        'gold-sparkle': 'gold-sparkle 2s ease-in-out infinite',
                        'zoom-in-out': 'zoom-in-out 2s ease-in-out infinite',
                        'gradient-shift': 'gradient-shift 3s ease infinite',
                        'float': 'float 3s ease-in-out infinite',
                        'shine': 'shine 3s linear infinite',
                        'particle-float': 'particle-float 8s ease-in-out infinite',
                        'particle-rotate': 'particle-rotate 15s linear infinite',
                    },
                    keyframes: {
                        'glitch': { '1%': { transform: 'rotateX(10deg) skewX(90deg)' }, '2%': { transform: 'rotateX(0deg) skewX(0deg)' } },
                        'glitch-2': { '1%': { transform: 'rotateX(10deg) skewX(70deg)' }, '2%': { transform: 'rotateX(0deg) skewX(0deg)' } },
                        'gold-sparkle': {
                            '0%,100%': { textShadow: '0 0 5px #FFCA00, 0 0 10px #FFCA00', color: '#FFCA00' },
                            '50%': { textShadow: '0 0 10px #FFCA00, 0 0 20px #FFCA00, 0 0 30px #FFCA00', color: '#FFFFFF' },
                        },
                        'zoom-in-out': { '0%,100%': { transform: 'scale(1)' }, '50%': { transform: 'scale(1.1)' } },
                        'gradient-shift': { '0%,100%': { backgroundPosition: '0% 50%' }, '50%': { backgroundPosition: '100% 50%' } },
                        'float': { '0%,100%': { transform: 'translateY(0)' }, '50%': { transform: 'translateY(-8px)' } },
                        'shine': { '0%': { backgroundPosition: '-200% 0' }, '100%': { backgroundPosition: '200% 0' } },
                        'particle-float': {
                            '0%': { transform: 'translateY(0) translateX(0)' },
                            '25%': { transform: 'translateY(-20px) translateX(10px)' },
                            '50%': { transform: 'translateY(-40px) translateX(0)' },
                            '75%': { transform: 'translateY(-20px) translateX(-10px)' },
                            '100%': { transform: 'translateY(0) translateX(0)' },
                        },
                        'particle-rotate': { '0%': { transform: 'rotate(0deg)' }, '100%': { transform: 'rotate(360deg)' } },
                    }
                }
            }
        }
    </script>

    <style>
        html, body {
            background-color: transparent !important;
            margin: 0; padding: 0;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }
        @keyframes noise-2 {
            0% { clip-path: inset(76% 0 3% 0) } 5% { clip-path: inset(54% 0 40% 0) }
            10% { clip-path: inset(28% 0 33% 0) } 15% { clip-path: inset(89% 0 3% 0) }
            20% { clip-path: inset(96% 0 1% 0) } 25% { clip-path: inset(69% 0 8% 0) }
            30% { clip-path: inset(67% 0 6% 0) } 35% { clip-path: inset(8% 0 58% 0) }
            40% { clip-path: inset(100% 0 0% 0) } 45% { clip-path: inset(16% 0 70% 0) }
            50% { clip-path: inset(75% 0 19% 0) } 55% { clip-path: inset(6% 0 39% 0) }
            60% { clip-path: inset(79% 0 6% 0) } 65% { clip-path: inset(90% 0 3% 0) }
            70% { clip-path: inset(100% 0 0% 0) } 75% { clip-path: inset(23% 0 52% 0) }
            80% { clip-path: inset(15% 0 64% 0) } 85% { clip-path: inset(82% 0 5% 0) }
            90% { clip-path: inset(80% 0 1% 0) } 95% { clip-path: inset(57% 0 35% 0) }
            100% { clip-path: inset(100% 0 0% 0) }
        }
        @keyframes fudge { from { transform: translate(0,0) } to { transform: translate(0,2%) } }

        #slots-container { overflow: hidden; height: calc(3 * 150px); position: relative; }
        .transition-wrapper { transition: transform 2.5s cubic-bezier(0.25, 1, 0.5, 1); will-change: auto; }
        .slot-item { height: 150px; }
        .slot-image { width: 100px; height: 100px; }
        .bg-gradient-animated { background-size: 200% 200%; background-image: linear-gradient(45deg,#FFCA00,#DAD0EB,#FFCA00); }
        .bg-gold-sparkle { background: radial-gradient(circle, rgba(255,202,0,0.2) 0%, rgba(255,202,0,0) 70%); }
        .icon { color: #d4af37 !important; margin-right: .5em; font-size: 1.5em; }
    </style>
</head>
<body class="bg-dark text-white">
@php
    $startAmount   = $hunt->start_amount ?? 0;
    $totalWin      = optional($hunt)->entries?->sum('win') ?? 0;
    $missingAmount = max(0, $startAmount - $totalWin);
    $remaining     = optional($hunt)->entries?->filter(fn($e) => empty($e->win)) ?? collect();
    $remainingBets = $remaining->sum('bet');
    $xNeeded       = $remainingBets > 0 ? round($missingAmount / $remainingBets, 2) : null;
@endphp

    <!-- HEADER WRAPPER -->
<div class="max-w-2xl mx-auto px-20">
<div id="header-container">
    <div class="bg-dark p-3 rounded-t-lg border-b-2 border-primary relative overflow-hidden shadow-2xl">
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
</div>


<!-- LISTE -->
<div id="slots-container" class="relative bg-dark/95 rounded-b-lg border-2 border-primary/30 overflow-hidden" style="box-shadow: 0 0 15px rgba(255,202,0,0.2);">
    <div class="absolute inset-0 pointer-events-none overflow-hidden z-0">
        @for($i = 0; $i < 12; $i++)
            <div class="absolute text-secondary opacity-20 animate-particle-float"
                 style="top: {{ rand(0,100) }}%; left: {{ rand(0,100) }}%; font-size: {{ rand(12,24) }}px; animation-delay: {{ rand(0,8) }}s; animation-duration: {{ rand(8,16) }}s;">
                💰
            </div>
        @endfor
    </div>

    <div class="transition-wrapper flex flex-col relative z-10">
        @foreach(($hunt->entries ?? collect()) as $entry)
            @php
                $x = (float)($entry->x_value ?? 0);
                $borderEffect = $specialEffect = $textColor = $xClass = $winClass = '';
                $textColor = 'text-white';
                if     ($x >= 500) { $specialEffect='bg-gold-sparkle relative before:absolute before:inset-0 before:bg-primary/10 before:animate-glitch-2'; $borderEffect='border-2 border-primary'; $textColor='text-primary'; }
                elseif ($x >= 250) { $xClass='animate-gold-sparkle'; $specialEffect='bg-gold-sparkle'; $borderEffect='border border-primary'; $textColor='text-primary'; }
                elseif ($x >= 100) { $xClass='animate-zoom-in-out'; $specialEffect='animate-gradient-shift'; $borderEffect='border border-primary/50'; $textColor='text-primary'; }
                elseif ($x >= 50)  { $textColor='text-secondary'; }
                if (is_numeric($entry->win) && is_numeric($entry->bet) && $entry->win >= $entry->bet * 20) {
                    $winClass = 'font-bold text-primary';
                }
            @endphp
            <div class="slot-item relative border-b border-primary/20 last:border-b-0 overflow-hidden {{ $borderEffect }} {{ $specialEffect }}">
                <div class="p-3 flex items-center relative z-10 gap-3 h-full">
                    <div class="w-10 h-10 flex items-center justify-center bg-dark border-2 border-primary rounded-full text-sm font-bold {{ $textColor }} {{ $x >= 50 ? 'animate-pulse' : '' }}">
                        {{ $entry->position }}
                    </div>
                    <div class="slot-image flex-shrink-0 transform transition-all duration-300 hover:scale-110 relative">
                        <img src="{{ $entry->slot?->image_url ?? asset('images/placeholder.jpg') }}"
                             alt="{{ $entry->slot?->name ?? 'Unbekannter Slot' }}"
                             class="w-full h-full object-contain rounded-md border border-primary/30">
                        @if($x >= 100)
                            <div class="absolute inset-0 bg-gradient-to-br from-primary/10 to-secondary/10 animate-particle-rotate"></div>
                        @endif
                    </div>
                    <div class="flex-grow min-w-0">
                        <div class="flex justify-between items-baseline">
                            <p class="text-xs text-secondary/80">{{ $entry->slot?->name ?? 'Unbekannt' }}</p>
                            <span class="text-sm font-mono {{ $xClass }} {{ $textColor }}">
                                {{ is_numeric($entry->x_value) ? number_format($entry->x_value, 2, ',', '.') : '—' }}x
                            </span>
                        </div>
                        <p class="text-xs text-secondary/80">
                            {{ $entry->slot?->provider ?? 'Unbekannter Anbieter' }}
                        </p>
                        <div class="flex justify-between text-sm mt-2 font-medium">
                            <span class="text-secondary font-mono">Einsatz: {{ number_format($entry->bet ?? 0, 2, ',', '.') }} €</span>
                            <span class="font-mono {{ $winClass }}">
                                {{ is_numeric($entry->win) ? number_format($entry->win, 2, ',', '.') : '—' }} €
                            </span>
                        </div>
                        @if ($entry->bonus_bought)
                            <div class="mt-2 pt-1 text-xs bg-secondary/10 text-secondary flex items-center justify-start border-t border-primary/20">
                                <span class="flex items-center px-2 py-1 rounded">
                                    <span class="mr-1">✨</span> Bonus gekauft
                                    @if (is_numeric($entry->bonus_cost))
                                        <span class="ml-1 font-mono">({{ number_format($entry->bonus_cost, 2, ',', '.') }} €)</span>
                                    @endif
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="mt-2 h-1.5 bg-dark rounded-full overflow-hidden border border-primary/20">
    <div id="scroll-progress" class="h-full bg-gradient-to-r from-primary to-primary/70 rounded-full relative">
        <div class="absolute inset-0 bg-gradient-to-r from-white/30 to-transparent animate-shine"></div>
    </div>
</div>

<script>
        const token = @json($hunt->public_token);
        let lastVersion = null;

        async function fetchPartialAndSwap() {
        const p = await fetch(`/o/bonushunt/${token}/partial?t=${Date.now()}`, { cache: 'no-store' });
        const html = await p.text();
        const container = document.querySelector('#slots-container .transition-wrapper');
        if (container) {
        container.innerHTML = html;
        if (typeof initAutoScroll === 'function') initAutoScroll();
    }
    }

        async function fetchHeaderAndSwap() {
        const r = await fetch(`/o/bonushunt/${token}/stats?t=${Date.now()}`, { cache: 'no-store' });
        const html = await r.text();
        const header = document.getElementById('header-container');
        if (header) header.innerHTML = html;
    }

        async function tick() {
        try {
        const r = await fetch(`/api/bonus-hunt-updated-at/${token}?t=${Date.now()}`, { cache: 'no-store' });
        if (!r.ok) throw new Error('HTTP ' + r.status);
        const { version } = await r.json();
        if (version !== lastVersion) {
        lastVersion = version;
        await Promise.all([fetchHeaderAndSwap(), fetchPartialAndSwap()]);
    }
    } catch (e) {
        // optional: console.warn(e);
    } finally {
        setTimeout(tick, 2000);
    }
    }

        document.addEventListener('DOMContentLoaded', () => {
        if (typeof initAutoScroll === 'function') initAutoScroll();
        setTimeout(tick, 2000);
    });
</script>


</body>
</html>

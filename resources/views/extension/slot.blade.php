<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Live Slot Box</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Tailwind (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body { background-color: transparent !important; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px) scale(0.95); }
            to   { opacity: 1; transform: translateY(0)   scale(1); }
        }
    </style>
</head>
<body class="text-white bg-transparent">

<!-- 🔹 Live Slot-Box unten links -->
<div id="slotBox" class="fixed bottom-4 left-4 z-50">
    {{-- Wichtig: Das Partial muss ein #slotContent-Element liefern und data-slot-key setzen --}}
    @include('extension._slotbox', ['slot' => $slot])
</div>

<script>
    (() => {
        // Slug aus Blade reinreichen (die Route /slot/live/{slug} hat das hier verfügbar)
        const LIVE_SLUG = @json($slug ?? null);
        if (!LIVE_SLUG) {
            console.error("Kein live_slug vorhanden.");
            return;
        }

        const box = document.getElementById("slotBox");
        let currentKey = document.getElementById("slotContent")?.dataset.slotKey ?? '';
        let polling = null;

        async function getCurrentSlotKey() {
            // Eigene, slug-basierte Route verwenden – NICHT /api/slot/current ohne Kontext
            // Definiere serverseitig eine Route wie: GET /slot/current/{slug}
            const url = @json(route('slot.current', ['slug' => $slug ?? 'SLUG_PLACEHOLDER'])) ;
            const res = await fetch(url, { cache: 'no-store' });
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            const data = await res.json();
            return data?.key || '';
        }

        async function fetchPartial(newKey) {
            // Öffentliches Partial, das kein Login braucht
            // Definiere serverseitig Route: GET /slot/partial/{key}
            const url = @json(route('slot.partial', ['key' => 'KEY_PLACEHOLDER'])).replace('KEY_PLACEHOLDER', encodeURIComponent(newKey));
            const res = await fetch(url, { cache: 'no-store' });
            if (!res.ok) throw new Error(`Partial HTTP ${res.status}`);
            return await res.text();
        }

        async function tick() {
            try {
                const newKey = await getCurrentSlotKey();
                if (!newKey || newKey === currentKey) return;

                // Ausblenden
                box.classList.add("opacity-0","scale-90","transition-all","duration-300");
                await new Promise(r => setTimeout(r, 300));

                // Neues Partial laden
                const html = await fetchPartial(newKey);
                box.innerHTML = html;

                // Einblenden
                box.classList.remove("opacity-0","scale-90");
                box.classList.add("animate-[fadeIn_0.5s_ease-in-out]");

                // Key aktualisieren
                const newContent = document.getElementById("slotContent");
                currentKey = newContent?.dataset.slotKey ?? newKey;

                // Klasse nach Ende entfernen
                setTimeout(() => box.classList.remove("animate-[fadeIn_0.5s_ease-in-out]"), 500);
            } catch (e) {
                // nicht spammen
                console.warn("Fehler beim Slot-Wechsel:", e?.message || e);
            }
        }

        // Polling: 5s; pausiert im Hintergrund-Tab für Lastreduktion
        function startPolling() {
            if (polling) return;
            polling = setInterval(tick, 5000);
        }
        function stopPolling() {
            if (polling) { clearInterval(polling); polling = null; }
        }
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) stopPolling(); else startPolling();
        });

        startPolling();
    })();
</script>

</body>
</html>

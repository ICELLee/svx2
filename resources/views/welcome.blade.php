@verbatim
<!DOCTYPE html>
<html lang="de">
<head>
    <!-- ====== SEO-optimierter HEAD für StreamVaultX ====== -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>StreamVaultX – Streaming-Tools, Hosting & Services für Streamer</title>
    <meta name="description" content="StreamVaultX liefert performante Tools, Bots, Overlays und Hosting für Streamer – transparent bepreist, sauber integriert (OBS, Discord, Kick/Twitch APIs) und zuverlässig betreut." />

    <!-- Canonical & Language -->
    <link rel="canonical" href="https://streamvaultx.com/" />
    <link rel="alternate" hreflang="de-DE" href="https://streamvaultx.com/" />
    <meta name="language" content="de" />

    <!-- Robots (max. Vorschauen) -->
    <meta name="robots" content="index,follow,max-snippet:-1,max-image-preview:large,max-video-preview:-1" />
    <meta name="googlebot" content="index,follow" />
    <meta name="bingbot" content="index,follow" />

    <!-- Theming -->
    <meta name="theme-color" content="#0f0117" media="(prefers-color-scheme: dark)">
    <meta name="theme-color" content="#ffffff" media="(prefers-color-scheme: light)">
    <meta name="color-scheme" content="dark light" />

    <!-- Open Graph -->
    <meta property="og:type" content="website" />
    <meta property="og:locale" content="de_DE" />
    <meta property="og:site_name" content="StreamVaultX" />
    <meta property="og:title" content="StreamVaultX – Streaming-Tools, Hosting & Services für Streamer" />
    <meta property="og:description" content="Bundles, Bots, Overlays & Hosting – fair bepreist und technisch sauber umgesetzt." />
    <meta property="og:url" content="https://streamvaultx.com/" />
    <meta property="og:image" content="https://streamvaultx.com/assets/og/og-image.jpg" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <meta property="og:image:alt" content="StreamVaultX – Tools & Hosting für Streamer" />

    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="StreamVaultX – Streaming-Tools, Hosting & Services für Streamer" />
    <meta name="twitter:description" content="Bundles, Bots, Overlays & Hosting – fair bepreist und technisch sauber umgesetzt." />
    <meta name="twitter:image" content="https://streamvaultx.com/assets/og/og-image.jpg" />

    <!-- Favicons / Manifest (Pfad anpassen, falls nötig) -->
    <link rel="icon" href="/favicon.ico" sizes="any" />
    <link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32" />
    <link rel="apple-touch-icon" href="/apple-touch-icon.png" />
    <link rel="manifest" href="/site.webmanifest" />
    <meta name="application-name" content="StreamVaultX" />
    <meta name="apple-mobile-web-app-title" content="StreamVaultX" />

    <!-- Performance Hints -->
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin />
    <link rel="preconnect" href="https://cdn.dolo.codes" crossorigin />
    <!-- Optional: wichtigstes Hero-Bild preladen (LCP) -->
    <!-- <link rel="preload" as="image" href="/assets/hero/hero.webp" imagesrcset="/assets/hero/hero.webp 1x, /assets/hero/hero@2x.webp 2x" /> -->

    <!-- Font Awesome (falls noch nicht geladen) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- JSON-LD: Organization -->
    <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "Organization",
          "@id": "https://streamvaultx.com/#organization",
          "name": "StreamVaultX",
          "url": "https://streamvaultx.com/",
          "logo": "https://streamvaultx.com/assets/og/og-image.jpg"
        }
    </script>

    <!-- JSON-LD: WebSite -->
    <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "WebSite",
          "@id": "https://streamvaultx.com/#website",
          "url": "https://streamvaultx.com/",
          "name": "StreamVaultX",
          "publisher": { "@id": "https://streamvaultx.com/#organization" },
          "inLanguage": "de-DE"
        }
    </script>
    <!-- ====== /SEO-optimierter HEAD ====== -->

    <style>
        /* Animierbare Farb-Variablen registrieren (für echte Fades) */
        @property --bg      { syntax: '<color>'; inherits: true; initial-value: #0f0117; }
        @property --bgA1    { syntax: '<color>'; inherits: true; initial-value: rgba(188,19,254,.12); }
        @property --bgA2    { syntax: '<color>'; inherits: true; initial-value: rgba(255,94,0,.10); }
        @property --text    { syntax: '<color>'; inherits: true; initial-value: #ffffff; }
        @property --scrimC  { syntax: '<color>'; inherits: true; initial-value: rgba(0,0,0,.5); }
        /* ------------------------------
           Design Tokens (Dark/Light)
        ------------------------------ */
        :root {
            --neon-lila: #bc13fe;
            --neon-orange: #ff5e00;
            --dark-bg: #0f0117;
            --dark-card: #160223;
            --dark-card-2: #1d062d;
            --light-bg: #f7f1ff;
            --light-card: #ffffff;
            --text-dark: #ffffff;
            --text-light: #222222;
            --muted-dark: #b9b3c0;
            --muted-light: #4c4c4c;
            --ring: 0 0 0 3px rgba(188, 19, 254, .25);
            --glow: var(--neon-lila);
            --radius: 18px;
            --shadow-1: 0 10px 30px rgba(0,0,0,.35);
            --shadow-2: 0 20px 50px rgba(0,0,0,.45);
            --maxw: 1200px;
        }

        @media (prefers-color-scheme: light) {
            :root { --glow: var(--neon-orange); }
        }

        /* Global Reset + Base */
        * { box-sizing: border-box; }
        html, body { height: 100%; }
        body {
            margin: 0;
            font-family: system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, Noto Sans, Helvetica, Arial, "Apple Color Emoji", "Segoe UI Emoji";
            background: radial-gradient(1200px 800px at 10% 10%, rgba(188,19,254,.12), transparent 40%),
            radial-gradient(1000px 700px at 90% 20%, rgba(255,94,0,.10), transparent 45%),
            var(--dark-bg);
            color: var(--text-dark);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        /* Light-Mode Werte (faden weich hinein) */
        body.light{
            --bg:   #f7f1ff;
            --bgA1: rgba(255,94,0,.10);
            --bgA2: rgba(188,19,254,.10);
            --text: #222222;
            --scrimC: rgba(0,0,0,.25);
        }

        /* Globale Fades für Farben/Flächen */
        html, body, nav, .card, .features, .contact, .mobile-panel, .btn-ghost,
        input, textarea, .to-top, footer, .footer-bottom, .footer-links a, .services .card {
            transition:
                background-color .35s ease,
                color .35s ease,
                border-color .35s ease,
                box-shadow .35s ease,
                opacity .25s ease,
                transform .2s ease;
        }

        /* Body-Background: Gradient über Variablen → weicher Crossfade */
        body{
            color: var(--text);
            background:
                radial-gradient(1200px 800px at 10% 10%, var(--bgA1), transparent 40%),
                radial-gradient(1000px 700px at 90% 20%, var(--bgA2), transparent 45%),
                var(--bg);
        }

        /* Scrim-Hintergrund an Theme koppeln */
        .scrim{ background: var(--scrimC); }

        /* Kleine Touch-Feedback-Verbesserung für den Toggle */
        .theme-toggle{ transition: transform .15s ease; }
        .theme-toggle:active{ transform: scale(.92); }

        /* Barrierefreiheit: keine Animationen bei reduced motion */
        @media (prefers-reduced-motion: reduce){
            *, *::before, *::after{ transition: none !important; }
        }
        body.light {
            background: radial-gradient(1200px 800px at 10% 10%, rgba(255,94,0,.10), transparent 40%),
            radial-gradient(1000px 700px at 90% 20%, rgba(188,19,254,.10), transparent 45%),
            var(--light-bg);
            color: var(--text-light);
        }
        .logo-badge{
            /* --------- Konfig --------- */
            --svx-size: 30px;
            --from: #7c3aed; /* purple-600 */
            --to:   #fb923c; /* orange-400 */

            display:inline-grid;
            place-items:center;
            width:var(--svx-size);
            height:var(--svx-size);
            border-radius:12px;             /* .round => Kreis */
            background:
                radial-gradient(120% 120% at 0% 0%, rgba(124,58,237,.35), transparent 60%),
                radial-gradient(120% 120% at 100% 100%, rgba(251,146,60,.30), transparent 60%),
                linear-gradient(135deg, rgba(255,255,255,.06), rgba(255,255,255,.02));
            border:1px solid rgba(255,255,255,.12);
            box-shadow:
                0 1px 0 rgba(255,255,255,.08) inset,
                0 8px 20px rgba(0,0,0,.35);
            position:relative;
            overflow:hidden;
            vertical-align:middle;
        }

        /* Gradient-Border via Mask, sehr clean */
        .logo-badge::after{
            content:"";
            position:absolute; inset:-1px;
            border-radius:inherit;
            padding:1px;
            background:linear-gradient(135deg, var(--from), transparent 30% 70%, var(--to));
            -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
            -webkit-mask-composite: xor; mask-composite: exclude;
            pointer-events:none; opacity:.9;
        }

        /* „SVX“ Monogramm */
        .logo-badge::before{
            content:"SVX";
            font-weight:900;
            font-family: system-ui, ui-sans-serif, Segoe UI, Roboto, Inter, sans-serif;
            font-size: clamp(10px, calc(var(--svx-size)*.44), 24px);
            letter-spacing:.02em;
            line-height:1;
            background:linear-gradient(90deg, var(--from), var(--to), var(--from));
            background-size:200% 100%;
            -webkit-background-clip:text; background-clip:text;
            -webkit-text-fill-color:transparent; color:transparent;
            filter: drop-shadow(0 1px 2px rgba(0,0,0,.6));
            animation: svx-pan 6s ease-in-out infinite alternate;
        }

        @keyframes svx-pan{ from{background-position:0 50%} to{background-position:100% 50%} }

        /* Größen-Modifier */
        .logo-badge.sm{ --svx-size:28px }
        .logo-badge.md{ --svx-size:40px }
        .logo-badge.lg{ --svx-size:56px }

        /* Runde Variante */
        .logo-badge.round{ border-radius:999px }

        /* Motion-Respect + Fallbacks */
        @media (prefers-reduced-motion: reduce){
            .logo-badge::before{ animation:none }
        }
        @supports not (-webkit-background-clip: text){
            .logo-badge::before{
                background:none; -webkit-text-fill-color:#fff; color:#fff;
            }
        }
        a { color: inherit; text-decoration: none; }
        img { max-width: 100%; display: block; }
        :focus-visible { outline: none; box-shadow: var(--ring); border-radius: 10px; }

        /* Layout */
        .wrapper { width: min(100%, var(--maxw)); margin-inline: auto; padding-inline: clamp(16px, 3vw, 32px); }
        section { padding: clamp(64px, 8vw, 120px) 0; scroll-margin-top: 100px; }
        .svx-title{
            /* Farben anpassbar */
            --from: #7c3aed; /* purple-600 */
            --to:   #fb923c; /* orange-400 */

            font-weight: 800;
            line-height: 1.1;

            /* Gradient-Text */
            background: linear-gradient(90deg, var(--from), var(--to), var(--from));
            background-size: 200% 100%;
            background-position: 0% 50%;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            color: transparent;

            /* Dezente Animation */
            animation: svx-grad-pan 4s ease-in-out infinite alternate;

            /* Fallback, falls clip nicht greift */
            color: #9b59f6;
        }

        @keyframes svx-grad-pan{
            0%   { background-position: 0% 50%; }
            100% { background-position: 100% 50%; }
        }

        /* Motion-Respect */
        @media (prefers-reduced-motion: reduce){
            .svx-title{ animation: none; }
        }

        /* Optional: etwas lebendiger bei Hover (kürzerer Zyklus) */
        .svx-title:hover{ animation-duration: 3.5s; }
        /* Top Gradient Strip */
        .grad-strip { height: 3px; width: 100%; background: linear-gradient(90deg, var(--neon-lila), var(--neon-orange), var(--neon-lila)); background-size: 200% 100%; animation: strip 8s linear infinite; }
        @keyframes strip { to { background-position: 200% 0; } }

        /* --- Feature Grid & Icon Cards --- */
        .feature-grid {
            display: grid;
            gap: clamp(16px, 3vw, 24px);
            grid-template-columns: repeat(12, 1fr);
            margin-top: clamp(18px, 3vw, 26px);
        }
        .feature-grid .feature-card { grid-column: span 4; }
        @media (max-width: 1100px) { .feature-grid .feature-card { grid-column: span 6; } }
        @media (max-width: 700px)  { .feature-grid .feature-card { grid-column: 1 / -1; } }

        .feature-card .feature-icon {
            width: 56px; height: 56px; border-radius: 14px;
            display: grid; place-items: center; margin-bottom: 12px;
            background: linear-gradient(135deg, var(--neon-lila), var(--neon-orange));
            color: #fff;
            box-shadow: 0 10px 24px color-mix(in srgb, var(--glow) 32%, transparent),
            inset 0 0 0 1px rgba(255,255,255,.08);
            transform: translateZ(12px);
            transition: transform .25s ease, box-shadow .25s ease, filter .25s ease;
        }
        .feature-card .feature-icon i { font-size: 1.35rem; }

        .feature-card:hover .feature-icon,
        .feature-card:focus-within .feature-icon {
            transform: translateZ(16px) scale(1.05);
            box-shadow: 0 16px 34px color-mix(in srgb, var(--glow) 38%, transparent),
            inset 0 0 0 1px rgba(255,255,255,.10);
            filter: saturate(1.06) contrast(1.02);
        }

        .feature-card h3 { margin-top: 6px; }
        .feature-card p  { margin: 8px 0 0; opacity: .92; }
        /* Nav */
        nav {
            position: sticky; top: 0; z-index: 50; backdrop-filter: blur(10px);
            background: color-mix(in srgb, #000 65%, transparent);
            border-bottom: 1px solid color-mix(in srgb, var(--glow) 40%, transparent);
        }
        body.light nav {
            background: color-mix(in srgb, #fff 70%, transparent);
            border-bottom-color: color-mix(in srgb, var(--glow) 35%, transparent);
        }
        .nav-inner { display:flex; align-items:center; justify-content:space-between; padding: 14px clamp(16px, 3vw, 32px); }
        .logo { font-weight: 800; letter-spacing:.2px; font-size: clamp(1.1rem, 2vw, 1.35rem); display:flex; align-items:center; gap:10px; }
        .logo-badge {
            width: 34px; height: 34px; border-radius: 10px;
            background: linear-gradient(135deg, var(--neon-lila), var(--neon-orange));
            box-shadow: 0 0 22px color-mix(in srgb, var(--glow) 40%, transparent);
        }
        .nav-links { display:flex; gap: clamp(14px, 2.4vw, 26px); align-items:center; }
        .nav-links a { opacity: .9; font-weight: 600; position: relative; }
        .nav-links a::after { content:""; position:absolute; left:0; right:0; bottom:-8px; height:2px; background: linear-gradient(90deg, var(--neon-lila), var(--neon-orange)); transform: scaleX(0); transform-origin: left; transition: transform .25s ease; }
        /* Typo-Fix: kein Backslash! */
        .nav-links a:hover::after { transform: scaleX(1); }

        /* Nav Right cluster + hamburger visibility */
        .nav-right { display:flex; align-items:center; gap: clamp(14px, 2.4vw, 26px); }
        .hamburger { display:none; }

        /* Buttons / Mobile actions base */
        .mobile-actions { display: none; }
        .theme-toggle, .hamburger {
            appearance: none; border: 0; background: transparent;
            font-size: 1.2rem; line-height: 1; padding: 8px 10px; border-radius: 10px; cursor: pointer;
        }
        .theme-toggle:focus-visible, .hamburger:focus-visible { box-shadow: var(--ring); }
        body.menu-open { overflow: hidden; }

        /* Mobile panel base (JS setzt top dynamisch) */
        .mobile-panel { display: none; }

        @media (max-width: 980px) {
            .nav-links { display: none; }
            .nav-right { display: none; }
            .hamburger { display: block; }
            .mobile-actions { display: flex; gap: 10px; }

            .mobile-panel {
                display: block;
                position: fixed;
                left: 12px; right: 12px;
                /* top wird per JS dynamisch gesetzt */
                border-radius: 16px; padding: 12px;
                backdrop-filter: blur(10px);
                background: color-mix(in srgb, #000 65%, transparent);
                box-shadow: var(--shadow-1);
                transform: translateY(-8px);
                opacity: 0; pointer-events: none;
                transition: opacity .25s ease, transform .25s ease;
                max-height: calc(100dvh - 90px);
                overflow: auto;
                z-index: 60;
            }
            body.light .mobile-panel { background: color-mix(in srgb, #fff 70%, transparent); }
            .mobile-panel.open { opacity: 1; pointer-events: auto; transform: translateY(0); }
            .mobile-panel a { display:block; padding:12px 10px; border-radius:10px; font-weight:600; }
            .mobile-panel a:hover { background: color-mix(in srgb, var(--glow) 14%, transparent); }
        }

        /* Nav CTA: Dashboard */
        .btn-cta-nav {
            display:inline-flex; align-items:center; gap:10px; font-weight:800; letter-spacing:.2px;
            padding: 10px 14px; border-radius: 12px; color:#fff; text-shadow: 0 0 10px rgba(0,0,0,.2);
            background: linear-gradient(135deg, var(--neon-lila), var(--neon-orange));
            box-shadow: 0 10px 24px color-mix(in srgb, var(--glow) 32%, transparent), inset 0 0 0 1px rgba(255,255,255,.08);
            transition: transform .2s ease, box-shadow .2s ease, opacity .2s ease;
        }
        body.light .btn-cta-nav { color: #fff; }
        .btn-cta-nav:hover { transform: translateY(-1px); box-shadow: 0 16px 34px color-mix(in srgb, var(--glow) 38%, transparent); }
        .btn-cta-nav i { font-size: 1rem; }

        /* Mobile panel CTA */
        .mobile-panel a.mobile-cta {
            background: linear-gradient(135deg, var(--neon-lila), var(--neon-orange)); color:#fff; text-align:center;
            border-radius: 12px; margin-bottom: 8px; box-shadow: 0 8px 22px color-mix(in srgb, var(--glow) 32%, transparent);
        }

        /* Hero */
        .hero { position: relative; padding: clamp(80px, 12vw, 160px) 0 clamp(40px, 8vw, 80px); text-align: center; }
        .hero .eyebrow { font-size: .95rem; opacity:.8; letter-spacing:.6px; }
        .hero h1 { margin: 10px 0 12px; font-size: clamp(2.2rem, 6vw, 4rem); line-height: 1.05; text-wrap: balance; text-shadow: 0 0 18px var(--glow); }
        .hero p { margin: 0 auto; max-width: 760px; font-size: clamp(1.05rem, 2.3vw, 1.25rem); opacity:.92; }
        .hero-cta { margin-top: 22px; display:flex; justify-content:center; gap:12px; flex-wrap: wrap; }
        .btn { --pad-x: 22px; --pad-y: 12px; border: 0; border-radius: 999px; padding: var(--pad-y) var(--pad-x); font-weight: 700; cursor: pointer; transition: transform .2s ease, box-shadow .2s ease, opacity .2s ease; }
        .btn-primary { background: linear-gradient(135deg, var(--neon-lila), var(--neon-orange)); color: white; box-shadow: 0 0 0 0 rgba(0,0,0,0); }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 12px 30px color-mix(in srgb, var(--glow) 30%, transparent); }
        .btn-ghost { background: transparent; color: currentColor; border: 1px solid color-mix(in srgb, var(--glow) 35%, transparent); }
        .btn-ghost:hover { background: color-mix(in srgb, var(--glow) 12%, transparent); }

        /* Parallax-ish floating orbs (deaktiviert, Network-Background übernimmt) */
        .orb { display: none !important; }

        /* Section Headings */
        .section-title { text-align:center; font-size: clamp(1.6rem, 3.6vw, 2.4rem); margin-bottom: clamp(26px, 4vw, 40px); text-shadow: 0 0 12px var(--glow); }
        .section-kicker { text-align:center; opacity:.8; margin-bottom: clamp(28px, 4vw, 36px); }

        /* Feature banner */
        .features {
            border-radius: var(--radius);
            background: linear-gradient(180deg, color-mix(in srgb, var(--glow) 14%, transparent), transparent 40%) ,
            color-mix(in srgb, #000 75%, transparent);
            padding: clamp(22px, 4vw, 34px);
            box-shadow: var(--shadow-1);
            border: 1px solid color-mix(in srgb, var(--glow) 24%, transparent);
        }
        body.light .features { background: linear-gradient(180deg, color-mix(in srgb, var(--glow) 10%, transparent), transparent 46%) , #fff; }
        .features h3 { margin: 0 0 10px; font-size: clamp(1.2rem, 2.5vw, 1.6rem); color: var(--neon-orange); }

        /* Cards Grid */
        .grid { display: grid; gap: clamp(18px, 3vw, 26px); }
        .grid.products { grid-template-columns: repeat(12, 1fr); }
        .grid .span-4 { grid-column: span 4; }
        .grid .span-6 { grid-column: span 6; }
        .grid .span-12 { grid-column: 1 / -1; }
        @media (max-width: 1100px) { .grid.products .span-4 { grid-column: span 6; } }
        @media (max-width: 700px)  { .grid.products .span-4, .grid.products .span-6 { grid-column: 1 / -1; } }

        /* Produktbilder */
        .product-image { position: relative; border-radius: var(--radius); overflow: hidden; aspect-ratio: 16 / 9; margin-bottom: 12px; background: linear-gradient(135deg, color-mix(in srgb, var(--glow) 18%, transparent), transparent 60%),
        radial-gradient(60% 120% at 20% 0%, rgba(188,19,254,.25), transparent 60%),
        radial-gradient(60% 120% at 80% 100%, rgba(255,94,0,.22), transparent 60%);
            box-shadow: 0 10px 35px rgba(0,0,0,.35);
        }
        .product-image img { width: 100%; height: 100%; object-fit: cover; transform: scale(1.02); transition: transform .35s ease, filter .35s ease; display:block; }
        .card:hover .product-image img { transform: scale(1.06); filter: saturate(1.06) contrast(1.02); }
        .product-image::after { content:""; position:absolute; inset:0; pointer-events:none; background: linear-gradient(180deg, transparent 60%, rgba(0,0,0,.25)); }

        .card {
            position: relative; border-radius: var(--radius);
            background: linear-gradient(180deg, rgba(255,255,255,.02), rgba(255,255,255,0));
            border: 1px solid color-mix(in srgb, var(--glow) 18%, transparent);
            box-shadow: var(--shadow-1);
            padding: clamp(18px, 3vw, 26px);
            transition: transform .35s ease, box-shadow .35s ease;
            transform-style: preserve-3d;
            will-change: transform;
        }
        body.light .card { background: #fff; }

        .card h3 { margin: 0 0 8px; font-size: clamp(1.1rem, 2.3vw, 1.35rem); color: var(--neon-orange); }
        .price { font-size: clamp(1.2rem, 3vw, 1.6rem); font-weight: 800; color: var(--neon-lila); }
        .card .meta { margin-top: 8px; opacity:.85; }

        /* Tilt: default skew; JS will override per scroll */
        .tilt-scene { perspective: 1000px; transform-style: preserve-3d; }
        [data-tilt] { transform: rotateY(8deg) rotateX(6deg) translateZ(0); will-change: transform; }
        [data-tilt].pop { box-shadow: var(--shadow-2), 0 0 22px color-mix(in srgb, var(--glow) 35%, transparent); }
        .card:hover { box-shadow: var(--shadow-2); }

        /* Back to Top */
        .to-top {
            position: fixed; right: 22px; bottom: 22px; z-index: 60; border: 0; cursor: pointer;
            width: 48px; height: 48px; border-radius: 12px; display: grid; place-items: center;
            background: linear-gradient(135deg, var(--neon-lila), var(--neon-orange)); color: #fff;
            box-shadow: 0 10px 30px color-mix(in srgb, var(--glow) 35%, transparent);
            opacity: 0; transform: translateY(8px); transition: opacity .25s ease, transform .25s ease;
        }
        .to-top.show { opacity: 1; transform: translateY(0); }
        .to-top:focus-visible { box-shadow: var(--ring); }
        .to-top i { font-size: 1.1rem; }

        /* Services list as cards */
        .services { display:grid; gap: clamp(14px, 2.8vw, 22px); grid-template-columns: repeat(2, 1fr); }
        @media (max-width: 860px) { .services { grid-template-columns: 1fr; } }

        /* Contact */
        .contact {
            border-radius: var(--radius);
            background: linear-gradient(180deg, color-mix(in srgb, var(--glow) 14%, transparent), transparent 40%) ,
            color-mix(in srgb, #000 75%, transparent);
            padding: clamp(22px, 4vw, 34px);
            box-shadow: var(--shadow-1);
            border: 1px solid color-mix(in srgb, var(--glow) 24%, transparent);
        }
        body.light .contact { background: #fff; }
        .form-grid { display:grid; gap: 14px; grid-template-columns: 1fr 1fr; }
        .form-grid .full { grid-column: 1 / -1; }
        @media (max-width:760px){ .form-grid { grid-template-columns: 1fr; } }
        label { font-weight: 600; display:block; margin-bottom: 6px; }
        input, textarea {
            width: 100%; padding: 12px 14px; border-radius: 12px; background: transparent; color: currentColor;
            border: 1.5px solid color-mix(in srgb, var(--glow) 30%, transparent);
        }
        textarea { min-height: 140px; resize: vertical; }
        input:focus-visible, textarea:focus-visible { box-shadow: var(--ring); outline: none; }

        /* Footer */
        footer { margin-top: clamp(40px, 8vw, 80px); padding: 0; border-top: 1px solid color-mix(in srgb, var(--glow) 20%, transparent); background: color-mix(in srgb, #000 75%, transparent); }
        body.light footer { background: #fff; }

        .footer-top { padding: clamp(28px, 4vw, 48px) 0; display:grid; gap: 24px; grid-template-columns: 2fr 1fr 1fr 1fr 1fr; }
        @media (max-width: 1000px){ .footer-top { grid-template-columns: 1.5fr 1fr 1fr; } }
        @media (max-width: 680px){ .footer-top { grid-template-columns: 1fr; } }

        .footer-brand { display:flex; gap:14px; align-items:flex-start; }
        .footer-brand .logo-badge { width: 40px; height: 40px; }
        .footer-brand p { margin: 6px 0 0; opacity:.85; max-width: 46ch; }

        .footer-col h4 { margin: 4px 0 10px; font-size: 1rem; opacity:.9; text-transform: uppercase; letter-spacing:.6px; }
        .footer-links { display:grid; gap: 8px; }
        .footer-links a { opacity:.9; }
        .footer-links a:hover { opacity:1; text-decoration: underline; text-underline-offset: 3px; }

        .footer-bottom { border-top: 1px solid color-mix(in srgb, var(--glow) 14%, transparent); padding: 14px 0 20px; }
        .footer-bottom-row { display:flex; align-items:center; justify-content:space-between; gap: 12px; flex-wrap: wrap; }
        .legal a { margin-right: 12px; opacity:.9; }
        .legal a:hover { opacity:1; }

        /* Prefers Reduced Motion: kill fancy transforms */
        @media (prefers-reduced-motion: reduce) {
            .grad-strip { animation: none; }
            [data-tilt] { transform: none !important; }
            .orb { display:none; }
            .nav-links a::after { transition: none; }
            .btn, .card { transition: none; }
        }
        /* Overlay (Scrim) */
        .scrim{
            position:fixed; inset:0;
            background:rgba(0,0,0,.5);
            backdrop-filter: blur(2px);
            z-index:55; opacity:0; pointer-events:none;
            transition:opacity .25s ease;
        }
        .scrim.show{ opacity:1; pointer-events:auto; }
        body.light .scrim{ background:rgba(0,0,0,.25); }

        /* Konsistente Nav-Höhe */
        .nav-inner{ min-height:56px; }

        /* Mobile Panel (ohne .wrapper) */
        @media (max-width: 980px){
            #mobilePanel{
                position: fixed;
                left: 12px; right: 12px;
                top: calc(var(--nav-h,56px) + 8px);
                z-index: 60;
                border-radius: 16px; padding: 12px;
                backdrop-filter: blur(10px);
                background: color-mix(in srgb, #000 65%, transparent);
                box-shadow: var(--shadow-1);
                max-height: calc(100dvh - (var(--nav-h,56px) + 24px));
                overflow: auto; overscroll-behavior: contain;
                transform: translateY(-10px);
                opacity: 0; pointer-events: none;
                transition: opacity .25s ease, transform .25s ease;
            }
            body.light #mobilePanel{ background: color-mix(in srgb, #fff 70%, transparent); }
            #mobilePanel.open{ opacity:1; pointer-events:auto; transform: translateY(0); }

            #mobilePanel a{
                display:block; padding:12px 10px; border-radius:10px; font-weight:600;
            }
            #mobilePanel a:hover{ background: color-mix(in srgb, var(--glow) 14%, transparent); }

            #mobilePanel .mobile-cta{
                background: linear-gradient(135deg, var(--neon-lila), var(--neon-orange));
                color:#fff; text-align:center; margin-bottom: 8px;
                box-shadow: 0 8px 22px color-mix(in srgb, var(--glow) 32%, transparent);
            }
        }

        /* Body Scroll-Lock */
        body.menu-open{ overflow:hidden; touch-action:none; }

    </style>
</head>
<body>

<canvas id="bgNet" aria-hidden="true"></canvas>
<div class="grad-strip" aria-hidden="true"></div>

<nav>
    <div class="nav-inner wrapper">
        <a href="#home" class="logo" aria-label="StreamVaultX">
            <span class="logo-badge" aria-hidden="true"></span>
            StreamVaultX
        </a>

        <div class="nav-right">
            <div class="nav-links" role="navigation" aria-label="Hauptnavigation">
                <a href="#home">Home</a>
                <a href="#about">Über uns</a>
                <a href="#features">Features</a>
                <a href="#products">Produkte</a>
                <a href="#services">Services</a>
                <a href="#contact">Kontakt</a>
            </div>
            <!-- Desktop CTA -->
            <a href="/dashboard" class="btn-cta-nav" data-tilt>
                <i class="fa-solid fa-gauge-high"></i><span>Dashboard</span>
            </a>
            <button id="themeToggle" class="theme-toggle" aria-label="Theme wechseln (Hell/Dunkel)">🌙</button>

        </div>

        <div class="mobile-actions">
            <button id="themeToggle" class="theme-toggle" aria-label="Theme wechseln (Hell/Dunkel)">🌙</button>
            <button id="hamburger" class="hamburger" aria-label="Menü öffnen" aria-expanded="false" aria-controls="mobilePanel">☰</button>
        </div>
    </div>
</nav> <!-- WICHTIG: nav schließen -->

<!-- Mobile Panel inkl. CTA (Geschwister von <nav>) -->
<div id="mobilePanel" class="mobile-panel" role="menu" aria-hidden="true">
    <a href="#home" role="menuitem">Home</a>
    <a href="#about" role="menuitem">Über uns</a>
    <a href="#features" role="menuitem">Features</a>
    <a href="#products" role="menuitem">Produkte</a>
    <a href="#services" role="menuitem">Services</a>
    <a href="#contact" role="menuitem">Kontakt</a>
    <a href="/dashboard" class="mobile-cta" role="menuitem" data-tilt>
        <i class="fa-solid fa-gauge-high"></i> Dashboard
    </a>
</div>
<div id="navScrim" class="scrim" aria-hidden="true"></div>

<header id="home" class="hero wrapper tilt-scene">
    <span class="orb o1" aria-hidden="true"></span>
    <span class="orb o2" aria-hidden="true"></span>
    <span class="orb o3" aria-hidden="true"></span>
    <div class="eyebrow">Streaming-Tools • Hosting • Design</div>
    <h1 class="svx-title">Mit StreamVaultX zum MaxWin!</h1>
    <p>Professionelle Tools, Hosting und Services für Streamer, die sich abheben wollen – fair bepreist, technisch sauber, ohne leere Versprechen.</p>
    <div class="hero-cta">
        <a class="btn btn-primary" href="#products">Jetzt entdecken</a>
        <a class="btn btn-ghost" href="#contact">Kontakt aufnehmen</a>
    </div>
</header>


<main>
    <section id="about" class="wrapper tilt-scene">
        <h2 class="section-title" >Über uns</h2>
        <p class="section-kicker">Seit 2025 entwickeln wir performante Tools & Services, die Creator wirklich weiterbringen.</p>
        <div class="grid products">
            <article class="card span-6" data-tilt>
                <h3>Wer wir sind</h3>
                <p>Ein kleines, schnelles Team aus Entwicklern & Designern. Wir liefern praxisnahe Lösungen – vom Bot bis zum Overlay – und achten auf Zuverlässigkeit, UX und Skalierung.</p>
            </article>
            <article class="card span-6" data-tilt>
                <h3>Was uns unterscheidet</h3>
                <p>Keine Blackbox, kein Hype. Klare Preise, klare Roadmaps, saubere Integrationen (OBS, Discord, Twitch/Kick APIs).</p>
            </article>
        </div>
    </section>

    <section id="features" class="wrapper tilt-scene">
        <h2 class="section-title">Features</h2>

        <!-- Intro-Badge/Banner bleibt als Value-Prop -->
        <div class="features" data-tilt>
            <h3>Günstigster Anbieter – ohne Abstriche</h3>
            <p>Faire Preise für hochwertige Tools. Vergleiche uns – Feature-für-Feature – und du wirst sehen: Wir liefern.</p>
        </div>

        <!-- Icon Cards -->
        <div class="feature-grid">
            <article class="card feature-card" data-tilt>
                <div class="feature-icon" aria-hidden="true"><i class="fa-solid fa-gauge-high"></i></div>
                <h3>OBS-Ready Overlays</h3>
                <p>Plug-and-play Widgets & Layouts, optimiert für OBS & Streaming-Setups.</p>
            </article>

            <article class="card feature-card" data-tilt>
                <div class="feature-icon" aria-hidden="true"><i class="fa-solid fa-chart-line"></i></div>
                <h3>Live-Stats & Tracker</h3>
                <p>Echtzeit-Metriken (z. B. Slot Tracker) für maximale Transparenz im Stream.</p>
            </article>

            <article class="card feature-card" data-tilt>
                <div class="feature-icon" aria-hidden="true"><i class="fa-solid fa-trophy"></i></div>
                <h3>BonusHunt & Turniere</h3>
                <p>Interaktive Formate für mehr Spannung, Watchtime und Community-Engagement.</p>
            </article>

            <article class="card feature-card" data-tilt>
                <div class="feature-icon" aria-hidden="true"><i class="fa-brands fa-discord"></i></div>
                <h3>Discord/Kick/Twitch Bots</h3>
                <p>Automatisierte Moderation, Commands & Integrationen – stabil und erweiterbar.</p>
            </article>

            <article class="card feature-card" data-tilt>
                <div class="feature-icon" aria-hidden="true"><i class="fa-solid fa-server"></i></div>
                <h3>Hosting & Backups</h3>
                <p>Eigenes Hosting mit automatisierten Backups und klaren SLAs – ohne Blackbox.</p>
            </article>

            <article class="card feature-card" data-tilt>
                <div class="feature-icon" aria-hidden="true"><i class="fa-solid fa-screwdriver-wrench"></i></div>
                <h3>Custom Development</h3>
                <p>APIs, Integrationen, Tools nach Maß – sauber dokumentiert, skalierbar gebaut.</p>
            </article>
        </div>
    </section>


    <section id="products" class="wrapper tilt-scene">
        <h2 class="section-title">Produkte</h2>
        <div class="grid products">
            <article class="card span-4" data-tilt>
                <h3>Bundle Alles</h3>
                <p>Alle Tools in einem Paket</p>
                <div class="price">79,99 € / Monat</div>
            </article>
            <article class="card span-4" data-tilt>
                <h3>BonusHunt</h3>
                <p>Spannende Bonusjagden für deine Zuschauer</p>
                <div class="price">25 € / Monat</div>
            </article>
            <article class="card span-4" data-tilt>
                <h3>Slot Request</h3>
                <p>Zuschauer können Slots vorschlagen</p>
                <div class="price">15 € / Monat</div>
            </article>
            <article class="card span-4" data-tilt>
                <h3>Slot Tracker</h3>
                <p>Stats & Transparenz im Stream</p>
                <div class="price">30 € / Monat</div>
            </article>
            <article class="card span-4" data-tilt>
                <h3>Tournament</h3>
                <p>Turniere für deine Community</p>
                <div class="price">25 € / Monat</div>
            </article>
            <article class="card span-4" data-tilt>
                <h3>Streaming Bots</h3>
                <p>Automatisiere Interaktionen im Chat</p>
                <div class="price">20 € / Monat</div>
            </article>
            <article class="card span-4" data-tilt>
                <h3>OBS Layouts</h3>
                <p>Professionelle Designs für deinen Stream</p>
            </article>
            <article class="card span-4" data-tilt>
                <h3>Webserver</h3>
                <p>Leistungsstarkes Hosting</p>
            </article>
            <article class="card span-4" data-tilt>
                <h3>Domains</h3>
                <p>Die perfekte Adresse für deine Präsenz</p>
            </article>
            <article class="card span-4" data-tilt>
                <h3>vServer</h3>
                <p>Virtuelle Server für Projekte</p>
            </article>
            <article class="card span-4" data-tilt>
                <h3>Root Server</h3>
                <p>Maximale Kontrolle</p>
            </article>
            <article class="card span-4" data-tilt>
                <h3>Discord Bots</h3>
                <p>Erweitere deinen Discord-Server</p>
            </article>
            <article class="card span-12" data-tilt>
                <h3>BackUp Server</h3>
                <p>Sichere deine wichtigen Daten – automatisiert & überprüfbar.</p>
            </article>
        </div>
    </section>

    <section id="services" class="wrapper tilt-scene">
        <h2 class="section-title">Services</h2>
        <div class="services">
            <article class="card" data-tilt>
                <h3>Video-Schnitt</h3>
                <p>Professioneller Schnitt für Highlights & Shorts.</p>
                <p class="meta">Preis auf Anfrage</p>
            </article>
            <article class="card" data-tilt>
                <h3>Web-Entwicklung</h3>
                <p>Individuelle Websites & Web-Apps (Vue/Laravel etc.).</p>
                <p class="meta">Preis auf Anfrage</p>
            </article>
            <article class="card" data-tilt>
                <h3>Custom Programming</h3>
                <p>Maßgeschneiderte Tools, Integrationen, Bots, APIs.</p>
                <p class="meta">Preis auf Anfrage</p>
            </article>
            <article class="card" data-tilt>
                <h3>Design & Branding</h3>
                <p>Logo, Banner, Overlays – stimmiges Brand Kit.</p>
                <p class="meta">Preis auf Anfrage</p>
            </article>
        </div>
    </section>

    <section id="contact" class="wrapper tilt-scene">
        <h2 class="section-title">Kontakt</h2>
        <form class="contact" data-tilt id="contactForm" novalidate>
            <div class="form-grid">
                <div>
                    <label for="name">Name</label>
                    <input id="name" name="name" type="text" autocomplete="name" required />
                </div>
                <div>
                    <label for="email">E-Mail</label>
                    <input id="email" name="email" type="email" autocomplete="email" required />
                </div>
                <div class="full">
                    <label for="message">Nachricht</label>
                    <textarea id="message" name="message" required></textarea>
                </div>
            </div>
            <div style="margin-top:16px; display:flex; gap:10px; align-items:center;">
                <button class="btn btn-primary" type="submit">Nachricht senden</button>
                <span id="formMsg" role="status" aria-live="polite" style="font-weight:600;"></span>
            </div>
        </form>
    </section>
</main>

<footer>
    <div class="wrapper footer-top">
        <div class="footer-brand" data-tilt>
            <span class="logo-badge" aria-hidden="true"></span>
            <div>
                <div class="logo">StreamVaultX</div>
                <p>Tools, Hosting & Design – alles für deinen Stream. Fokus auf Performance, UX und transparente Preise.</p>
            </div>
        </div>

        <div class="footer-col" data-tilt>
            <h4>Produkte</h4>
            <nav class="footer-links">
                <a href="#products">Bundle Alles</a>
                <a href="#products">BonusHunt</a>
                <a href="#products">Slot Tracker</a>
                <a href="#products">Bots & Discord</a>
            </nav>
        </div>

        <div class="footer-col" data-tilt>
            <h4>Services</h4>
            <nav class="footer-links">
                <a href="#services">Web-Entwicklung</a>
                <a href="#services">Video-Schnitt</a>
                <a href="#services">Design & Branding</a>
            </nav>
        </div>

        <div class="footer-col" data-tilt>
            <h4>Support</h4>
            <nav class="footer-links">
                <a href="#contact">Kontakt</a>
                <a href="#" target="_blank" rel="noopener">Status</a>
                <a href="#" target="_blank" rel="noopener">Dokumentation</a>
            </nav>
        </div>

        <div class="footer-col" data-tilt>
            <h4>Rechtliches</h4>
            <nav class="footer-links">
                <a href="/impressum">Impressum</a>
                <a href="/agb">AGB</a>
                <a href="/datenschutz">Datenschutz</a>
                <a href="/widerruf">Widerruf</a>
            </nav>
        </div>
    </div>

    <div class="wrapper footer-bottom">
        <div class="footer-bottom-row">
            <div>&copy; <span id="year"></span> StreamVaultX. Alle Rechte vorbehalten.</div>
            <div class="legal">
                <a href="/impressum">Impressum</a>
                <a href="/agb">AGB</a>
                <a href="/datenschutz">Datenschutz</a>
                <a href="/widerruf">Widerruf</a>
            </div>
        </div>
    </div>
</footer>

<!-- Backdrop Network Canvas styles -->
<style>
    #bgNet { position: fixed; inset: 0; z-index: 0; pointer-events: none; }
    main, header, footer, .wrapper { position: relative; z-index: 1; }
</style>

<!-- Back to Top Button -->
<button id="toTop" class="to-top" title="Nach oben" aria-label="Nach oben" data-tilt>
    <i class="fa-solid fa-arrow-up"></i>
</button>

<script>
    (function(){
        'use strict';

        // ===== Utilities =====
        var $ = function(sel, root){ return (root||document).querySelector(sel); };
        var $$ = function(sel, root){ return Array.prototype.slice.call((root||document).querySelectorAll(sel)); };
        var clamp = function(v, min, max){ return Math.min(Math.max(v,min), max); };
        var reduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        // ===== Theme Persist =====
        var body = document.body;
        var themeToggle = $('#themeToggle');
        var savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'light') body.classList.add('light');
        if (themeToggle){
            var setIcon = function(){ themeToggle.textContent = body.classList.contains('light') ? '☀️' : '🌙'; };
            setIcon();
            themeToggle.addEventListener('click', function(){
                body.classList.toggle('light');
                localStorage.setItem('theme', body.classList.contains('light') ? 'light' : 'dark');
                setIcon();
            });
        }

        // ===== Mobile Nav =====
        var rootEl = document.documentElement;
        var navEl  = $('nav');
        var burger = $('#hamburger');
        var panel  = $('#mobilePanel');

        var scrim = $('#navScrim');
        if (!scrim){
            scrim = document.createElement('div');
            scrim.id = 'navScrim';
            scrim.className = 'scrim';
            scrim.setAttribute('aria-hidden','true');
            document.body.appendChild(scrim);
        }

        var setNavHeightVar = function(){
            var h = navEl ? navEl.getBoundingClientRect().height : 56;
            rootEl.style.setProperty('--nav-h', h + 'px');
        };
        if (window.ResizeObserver && navEl){
            var RO = window.ResizeObserver;
            new RO(setNavHeightVar).observe(navEl);
        } else {
            setInterval(setNavHeightVar, 500);
            window.addEventListener('resize', setNavHeightVar, { passive:true });
        }
        setNavHeightVar();

        var trapHandler = null;
        var trapFocus = function(container){
            var nodes = $$('#mobilePanel a, #mobilePanel button, #mobilePanel [tabindex]:not([tabindex="-1"])')
                .filter(function(n){ return !n.disabled && n.offsetParent !== null; });
            if (!nodes.length) return;
            var first = nodes[0], last = nodes[nodes.length-1];
            trapHandler = function(e){
                if (e.key !== 'Tab') return;
                if (e.shiftKey && document.activeElement === first){ e.preventDefault(); last.focus(); }
                else if (!e.shiftKey && document.activeElement === last){ e.preventDefault(); first.focus(); }
            };
            container.addEventListener('keydown', trapHandler);
            first.focus({ preventScroll:true });
        };
        var releaseFocus = function(){
            if (trapHandler && panel){
                panel.removeEventListener('keydown', trapHandler);
                trapHandler = null;
            }
            if (burger) burger.focus({ preventScroll:true });
        };

        var openMenu = function(){
            if (!panel) return;
            panel.classList.add('open');
            panel.setAttribute('aria-hidden','false');
            if (burger){
                burger.setAttribute('aria-expanded','true');
                burger.textContent = '✕';
            }
            body.classList.add('menu-open');
            scrim.classList.add('show');
            trapFocus(panel);
        };
        var closeMenu = function(){
            if (!panel) return;
            panel.classList.remove('open');
            panel.setAttribute('aria-hidden','true');
            if (burger){
                burger.setAttribute('aria-expanded','false');
                burger.textContent = '☰';
            }
            body.classList.remove('menu-open');
            scrim.classList.remove('show');
            releaseFocus();
        };
        var toggleMenu = function(){
            if (!panel) return;
            if (panel.classList.contains('open')) closeMenu(); else openMenu();
        };

        if (burger) burger.addEventListener('click', toggleMenu);
        if (scrim) scrim.addEventListener('click', closeMenu, { passive:true });
        if (panel) panel.addEventListener('click', function(e){ if (e.target && e.target.tagName === 'A') closeMenu(); });
        document.addEventListener('keydown', function(e){
            if (e.key === 'Escape' && panel && panel.classList.contains('open')) closeMenu();
        });

        var MQ = window.matchMedia ? window.matchMedia('(max-width: 980px)') : null;
        var onMQ = function(e){ if (e && !e.matches) closeMenu(); setNavHeightVar(); };
        if (MQ){
            if (typeof MQ.addEventListener === 'function'){ MQ.addEventListener('change', onMQ); }
            else if (typeof MQ.addListener === 'function'){ MQ.addListener(onMQ); }
        }

        // ===== Year =====
        var yearEl = $('#year');
        if (yearEl) yearEl.textContent = new Date().getFullYear();

        // ===== Smooth Anchor Scroll =====
        $$( 'a[href^="#"]' ).forEach(function(a){
            a.addEventListener('click', function(e){
                var id = a.getAttribute('href');
                if (!id || id === '#') return;
                var el = $(id);
                if (!el) return;
                e.preventDefault();
                var offset = (navEl ? navEl.getBoundingClientRect().height : 56) + 16;
                var top = el.getBoundingClientRect().top + window.scrollY - offset;
                window.scrollTo({ top: top, behavior: 'smooth' });
                if (panel && panel.classList.contains('open')) closeMenu();
            });
        });

        // ===== Parallax Orbs =====
        var orbs = $$('.orb');
        if (!reduced && orbs.length){
            var ticking = false;
            var onScroll = function(){
                if (ticking) return; ticking = true;
                requestAnimationFrame(function(){
                    var y = window.scrollY * 0.04;
                    orbs.forEach(function(o, i){
                        o.style.transform = 'translate3d(' + ((i%2?1:-1)*y*6) + 'px, ' + (y*8) + 'px, 0)';
                    });
                    ticking = false;
                });
            };
            document.addEventListener('scroll', onScroll, { passive:true });
        }

        // ===== 3D Tilt =====
        var ensureTiltNodes = function(){
            var selectors = [
                '.logo', '.logo-badge', '.nav-links a', '.btn-cta-nav', '#mobilePanel a',
                '.hero .eyebrow', '.hero h1', '.hero p', '.hero-cta .btn',
                '.section-title', '.section-kicker',
                '.features', '.card', '.services .card', '.contact',
                '.footer-brand', '.footer-col h4', '.footer-links a', '.footer-bottom .legal a', '#toTop'
            ];
            selectors.forEach(function(sel){
                $$(sel).forEach(function(el){ el.setAttribute('data-tilt',''); });
            });
        };
        ensureTiltNodes();

        var PRODUCT_IMG_URL = "https://i.pinimg.com/736x/dd/1c/5b/dd1c5b14fc8446a4741fdb979c4fe3cc.jpg";
        $$('#products .card').forEach(function(card){
            if (!card.querySelector('.product-image')){
                var fig = document.createElement('figure');
                fig.className = 'product-image';
                fig.setAttribute('data-tilt','');
                var img = new Image();
                var h3 = card.querySelector('h3');
                img.alt = (h3 && h3.textContent) || 'Produktbild';
                img.loading = 'lazy';
                img.decoding = 'async';
                img.src = PRODUCT_IMG_URL;
                img.onerror = function(){ img.remove(); fig.classList.add('placeholder'); };
                fig.appendChild(img);
                card.prepend(fig);
            }
        });

        var tiltNodes = $$('[data-tilt]');
        var state = new Map();
        var MAX_Y = 18, MAX_X = 12, MAX_Z = 26;
        var HOVER_Z = 38, HOVER_SCALE = 1.045, SMOOTHING = 0.14;
        var easeInOutCubic = function(t){ return t < 0.5 ? 4*t*t*t : 1 - Math.pow(-2*t + 2, 3)/2; };

        var computeTarget = function(el, idx){
            var vh = Math.max(1, window.innerHeight);
            var r = el.getBoundingClientRect();
            var center = r.top + r.height/2;
            var progress = clamp(center / vh, 0, 1);
            var edge = Math.abs(progress - 0.5) * 2;
            edge = easeInOutCubic(edge);
            var sign = (idx % 2 === 0) ? 1 : -1;
            var ry = sign * (edge * MAX_Y);
            var rx = -sign * (edge * MAX_X * 0.8);
            var tz = (1 - edge) * MAX_Z;
            return { rx: rx, ry: ry, tz: tz, sc: 1 };
        };

        if (!reduced && tiltNodes.length){
            tiltNodes.forEach(function(el, idx){
                var t = computeTarget(el, idx);
                state.set(el, { rx: t.rx, ry: t.ry, tz: t.tz, sc: t.sc });
                el.style.transform = 'translateZ(' + t.tz + 'px) rotateY(' + t.ry + 'deg) rotateX(' + t.rx + 'deg) scale(' + t.sc + ')';
            });
            var tick = function(){
                tiltNodes.forEach(function(el, idx){
                    var t = computeTarget(el, idx);
                    if (el.classList.contains('hovered')) t = { rx: 0, ry: 0, tz: HOVER_Z, sc: HOVER_SCALE };
                    var s = state.get(el) || t;
                    s.rx += (t.rx - s.rx) * SMOOTHING;
                    s.ry += (t.ry - s.ry) * SMOOTHING;
                    s.tz += (t.tz - s.tz) * SMOOTHING;
                    s.sc += (t.sc - s.sc) * SMOOTHING;
                    el.style.transform = 'translateZ(' + s.tz + 'px) rotateY(' + s.ry + 'deg) rotateX(' + s.rx + 'deg) scale(' + s.sc + ')';
                    state.set(el, s);
                });
                requestAnimationFrame(tick);
            };
            requestAnimationFrame(tick);

            tiltNodes.forEach(function(el){
                el.addEventListener('mouseenter', function(){ if (!reduced) el.classList.add('hovered','pop'); });
                el.addEventListener('mouseleave', function(){ el.classList.remove('hovered','pop'); });
                el.addEventListener('focusin',  function(){ if (!reduced) el.classList.add('hovered','pop'); });
                el.addEventListener('focusout', function(){ el.classList.remove('hovered','pop'); });
            });
        }

        // ===== Back to Top =====
        var toTop = $('#toTop');
        if (toTop){
            var updateToTop = function(){
                if (window.scrollY > 400) toTop.classList.add('show');
                else toTop.classList.remove('show');
            };
            window.addEventListener('scroll', updateToTop, { passive:true });
            updateToTop();
            toTop.addEventListener('click', function(){ window.scrollTo({ top: 0, behavior: 'smooth' }); });
        }

        // ===== Background Network Canvas =====
        if (!reduced){
            var canvas = document.getElementById('bgNet');
            if (canvas){
                var c2d = canvas.getContext('2d', { alpha: true });
                if (!c2d) return;

                var dpr = Math.max(1, window.devicePixelRatio || 1);
                var COLORS = ['#bc13fe', '#ff5e00'];
                var NODES = 120;
                var FOV = 520;
                var WORLD_DEPTH = 900;
                var BASE_SPEED = 0.08;
                var CONNECT_DIST = 180;
                var SPRING_DIST = 120;
                var SPRING_K = 0.0007;
                var REPULSE_RADIUS = 220;
                var REPULSE_FORCE = 1.6;
                var FRICTION = 0.975;

                var W = 0, H = 0, CX = 0, CY = 0;

                var resize = function(){
                    W = window.innerWidth;
                    H = window.innerHeight;

                    // optional: sichtbare Größe setzen (nicht zwingend nötig mit inset:0)
                    canvas.style.width  = W + 'px';
                    canvas.style.height = H + 'px';

                    // Zeichenpuffer an DPR anpassen
                    canvas.width  = Math.floor(W * dpr);
                    canvas.height = Math.floor(H * dpr);

                    // Transform für DPR
                    c2d.setTransform(dpr, 0, 0, dpr, 0, 0);
                    CX = W * 0.5; CY = H * 0.5;
                    canvas.width  = Math.floor(W * dpr);
                    canvas.height = Math.floor(H * dpr);
                    c2d.setTransform(dpr, 0, 0, dpr, 0, 0);
                };
                resize();
                window.addEventListener('resize', resize, { passive:true });

                var rand = function(a, b){ return Math.random() * (b - a) + a; };

                var nodes = Array.from({ length: NODES }, function(_, i){
                    return {
                        x: rand(-CX, CX), y: rand(-CY, CY), z: rand(-WORLD_DEPTH/2, WORLD_DEPTH/2),
                        vx: rand(-BASE_SPEED, BASE_SPEED),
                        vy: rand(-BASE_SPEED, BASE_SPEED),
                        vz: rand(-BASE_SPEED, BASE_SPEED) * 0.25,
                        r: rand(1.2, 2.2),
                        c: COLORS[i % COLORS.length],
                        sx: 0, sy: 0, s: 1
                    };
                });

                var mouse = { x: -9999, y: -9999, down: false };
                window.addEventListener('pointermove', function(e){ mouse.x = e.clientX; mouse.y = e.clientY; }, { passive:true });
                window.addEventListener('pointerleave', function(){ mouse.x = -9999; mouse.y = -9999; });
                window.addEventListener('pointerdown', function(){ mouse.down = true; });
                window.addEventListener('pointerup', function(){ mouse.down = false; });

                var project = function(n){
                    var s = FOV / (FOV + n.z);
                    n.sx = n.x * s + CX;
                    n.sy = n.y * s + CY;
                    n.s  = s;
                };

                var wrap = function(n){
                    var margin = 60;
                    if (n.x < -CX - margin) n.x =  CX + margin;
                    if (n.x >  CX + margin) n.x = -CX - margin;
                    if (n.y < -CY - margin) n.y =  CY + margin;
                    if (n.y >  CY + margin) n.y = -CY - margin;
                    var halfD = WORLD_DEPTH / 2;
                    if (n.z < -halfD) n.z =  halfD;
                    if (n.z >  halfD) n.z = -halfD;
                };

                var step = function(){
                    c2d.clearRect(0,0,W,H);

                    for (var i=0;i<nodes.length;i++){
                        var n = nodes[i];

                        n.vx += rand(-0.002, 0.002);
                        n.vy += rand(-0.002, 0.002);

                        project(n);

                        var dx = n.sx - mouse.x;
                        var dy = n.sy - mouse.y;
                        var dist = Math.hypot(dx, dy);
                        if (dist < REPULSE_RADIUS){
                            var f = (1 - dist / REPULSE_RADIUS) * REPULSE_FORCE;
                            var ux = (dx / (dist || 1)) * f;
                            var uy = (dy / (dist || 1)) * f;
                            n.vx += ux * 0.06;
                            n.vy += uy * 0.06;
                            n.vz += (Math.random() - 0.5) * 0.02;
                        }
                        if (mouse.down && dist < REPULSE_RADIUS * 1.2){
                            n.vx -= (dx / (dist || 1)) * 0.01;
                            n.vy -= (dy / (dist || 1)) * 0.01;
                        }

                        n.x += n.vx; n.y += n.vy; n.z += n.vz;
                        n.vx *= FRICTION; n.vy *= FRICTION; n.vz *= FRICTION;
                        wrap(n);
                        project(n);
                    }

                    for (var aI=0; aI<nodes.length; aI++){
                        var a = nodes[aI];
                        for (var bI=aI+1; bI<nodes.length; bI++){
                            var b = nodes[bI];
                            var dx2 = a.sx - b.sx;
                            var dy2 = a.sy - b.sy;
                            var d = Math.hypot(dx2, dy2);

                            var depthBoost = 0.5 + 0.5 * (a.s + b.s) * 0.5;
                            var linkDist = CONNECT_DIST * depthBoost;

                            if (d < SPRING_DIST){
                                var f2 = (1 - d / SPRING_DIST) * SPRING_K;
                                var ux2 = (dx2 / (d || 1)) * f2;
                                var uy2 = (dy2 / (d || 1)) * f2;
                                a.vx -= ux2; a.vy -= uy2;
                                b.vx += ux2; b.vy += uy2;
                            }

                            if (d < linkDist){
                                var near = 1 - d / linkDist;
                                var alpha = Math.min(1, (near * 0.9) * (0.35 + 0.65 * ((a.s + b.s) * 0.5)));
                                var lw = 0.6 + near * 1.2;

                                c2d.globalAlpha = alpha;
                                var grad = c2d.createLinearGradient(a.sx, a.sy, b.sx, b.sy);
                                grad.addColorStop(0, a.c);
                                grad.addColorStop(1, b.c);
                                c2d.strokeStyle = grad;
                                c2d.lineWidth = lw;
                                c2d.beginPath();
                                c2d.moveTo(a.sx, a.sy);
                                c2d.lineTo(b.sx, b.sy);
                                c2d.stroke();
                                c2d.globalAlpha = 1;
                            }
                        }
                    }

                    for (var j=0;j<nodes.length;j++){
                        var n2 = nodes[j];
                        c2d.beginPath();
                        c2d.fillStyle = n2.c;
                        c2d.globalAlpha = 0.6 + 0.4 * Math.min(1, n2.s);
                        c2d.arc(n2.sx, n2.sy, Math.max(0.6, n2.r * n2.s), 0, Math.PI*2);
                        c2d.fill();
                        c2d.globalAlpha = 1;
                    }

                    requestAnimationFrame(step);
                };
                requestAnimationFrame(step);
            }
        }

        // ===== Contact Form (Demo) =====
        var form = $('#contactForm');
        var formMsg = $('#formMsg');
        if (form){
            form.addEventListener('submit', function(e){
                e.preventDefault();
                var data = new FormData(form);
                var name = String(data.get('name') || '').trim();
                var email = String(data.get('email') || '').trim();
                var message = String(data.get('message') || '').trim();
                if (!name || !email || !message){
                    formMsg.textContent = 'Bitte alle Felder ausfüllen.';
                    formMsg.style.color = 'tomato';
                    return;
                }
                formMsg.textContent = 'Danke! Wir melden uns kurzfristig.';
                formMsg.style.color = 'limegreen';
                form.reset();
            });
        }

    })();
</script>


</body>
@endverbatim
</html>


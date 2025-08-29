<template>
    <div :class="themeClass" class="min-h-screen bg-[rgb(var(--color-bg))] text-[rgb(var(--color-text))] transition-colors selection:bg-[rgb(var(--color-primary)/.25)] selection:text-[rgb(var(--color-text))]">
        <!-- Animated gradient top strip -->
        <div class="h-1 w-full bg-[linear-gradient(90deg,rgb(var(--color-primary)),rgb(var(--color-accent))_50%,rgb(var(--color-primary)))] bg-[length:200%_100%] animate-[svx-grad_8s_linear_infinite]" />

        <!-- Flash Toaster -->
        <transition name="fade">
            <div v-if="flashSuccess" class="fixed top-3 right-3 z-[70] rounded-xl border border-[rgb(34_197_94/0.30)] bg-[rgb(34_197_94/0.10)] px-4 py-2 text-[rgb(187_247_208)] shadow-lg backdrop-blur">
                {{ flashSuccess }}
            </div>
        </transition>
        <transition name="fade">
            <div v-if="flashError" class="fixed top-3 right-3 z-[70] rounded-xl border border-[rgb(239_68_68/0.30)] bg-[rgb(239_68_68/0.10)] px-4 py-2 text-[rgb(254_202_202)] shadow-lg backdrop-blur">
                {{ flashError }}
            </div>
        </transition>

        <div class="relative flex min-h-[100dvh]">

            <!-- Sidebar -->
            <aside
                class="fixed md:sticky md:top-0 md:self-start z-50 md:z-20 h-[100dvh] w-[82vw] max-w-80 md:w-72 flex flex-col bg-[rgb(var(--color-card))] border-r border-[rgb(var(--color-border))] relative shadow-[0_10px_40px_-10px_rgba(124,58,237,.25)] transition-transform duration-300 ease-out will-change-transform"
                :class="mobileOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
            >
                <!-- Brand -->
                <div class="p-4">
                    <div class="font-extrabold text-2xl text-transparent bg-clip-text bg-[linear-gradient(90deg,rgb(var(--color-primary)),rgb(var(--color-accent))_50%,rgb(var(--color-primary)))] bg-[length:200%_100%] animate-[svx-grad_8s_linear_infinite] select-none">
                        StreamVaultX
                    </div>
                    <div class="mt-1 text-[11px] tracking-wide text-[rgb(var(--color-muted))]">Admin & Tools</div>
                </div>

                <!-- Profile (improved with Breeze route) -->
                <div class="px-4 pb-3">
                    <div
                        class="group relative flex items-center gap-3 rounded-2xl border border-[rgb(var(--color-border))] bg-[rgb(var(--color-card)/.82)] backdrop-blur p-3 transition-all duration-300 hover:translate-y-[-2px] hover:shadow-[0_10px_40px_-10px_rgba(124,58,237,.35)]"
                    >
                        <!-- Avatar (clickable -> Profile) -->
                        <Link
                            :href="route('profile')"
                            class="relative h-11 w-11 shrink-0 rounded-full grid place-items-center bg-[rgb(var(--color-card))] overflow-hidden profile-glow transition hover:scale-105"
                        >
                            <img v-if="avatarUrl" :src="avatarUrl" alt="Avatar" class="h-full w-full object-cover" />
                            <span v-else class="text-sm font-semibold">{{ initials }}</span>
                            <span
                                class="absolute -bottom-0.5 -right-0.5 h-3.5 w-3.5 rounded-full bg-[rgb(34_197_94)] ring-2 ring-[rgb(var(--color-card))] animate-[svx-pulse_2.2s_ease-in-out_infinite]"
                            />
                        </Link>

                        <!-- User info -->
                        <div class="min-w-0">
                            <div class="flex items-center gap-2">
                                <div class="font-medium truncate">{{ user?.name ?? '—' }}</div>
                                <span
                                    v-if="isAdmin"
                                    class="text-[10px] px-2 py-0.5 rounded-full bg-[rgb(var(--color-primary)/.12)] border border-[rgb(var(--color-border))]"
                                >
                    Admin
                </span>
                            </div>
                            <div class="text-xs text-[rgb(var(--color-muted))] truncate">{{ user?.email ?? '' }}</div>

                            <!-- Actions -->
                            <div class="mt-2 flex items-center gap-2">
                                <Link
                                    :href="route('profile.edit')"
                                    class="btn-neon text-xs px-2.5 py-1.5 rounded-lg"
                                >
                                    Profil
                                </Link>

                                <button
                                    @click="logout"
                                    class="text-xs px-2.5 py-1.5 rounded-lg border border-[rgb(var(--color-border))] bg-[rgb(var(--color-card))] hover:bg-[rgb(var(--color-primary)/0.10)] transition-all duration-200"
                                >
                                    Abmelden
                                </button>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Nav -->
                <nav class="relative p-2 overflow-y-auto scrollbar-custom grow">
                    <span aria-hidden class="absolute left-0 top-0 h-full w-[2px] bg-[linear-gradient(180deg,rgb(var(--color-primary)),rgb(var(--color-accent)))] opacity-60" />

                    <!-- User Section -->
                    <NavHeading v-if="!isAdmin" label="User" />
                    <ul v-if="!isAdmin" class="space-y-1">
                        <NavItem to="/dashboard"         :active="isActive('/dashboard')"         icon="dashboard" @close="closeMobile">User Dashboard</NavItem>
                        <NavItem to="/tools/bonushunt"   :active="isActive('/tools/bonushunt')"   icon="dice"      @close="closeMobile">BonusHunt</NavItem>
                        <NavItem to="/tools/slottracker" :active="isActive('/tools/slottracker')" icon="chart"     @close="closeMobile">Slot-Tracker</NavItem>
                        <NavItem to="/tools/redeem"      :active="isActive('/tools/redeem')"      icon="ticket"    @close="closeMobile">Code redeem</NavItem>
                        <NavItem to="/tickets"           :active="isActive('/tickets')"           icon="ticket"    @close="closeMobile">Tickets</NavItem>
                    </ul>

                    <!-- Admin Section -->
                    <template v-else>
                        <NavHeading label="Admin" />
                        <ul class="space-y-1">
                            <NavItem to="/admin"        :active="isActive('/admin')"        icon="dashboard" glow @close="closeMobile">Admin Dashboard</NavItem>
                            <NavItem to="/admin/users"  :active="isActive('/admin/users')"  icon="users"     glow @close="closeMobile">Accounts</NavItem>
                            <NavItem to="/admin/slots"  :active="isActive('/admin/slots')"  icon="db"        glow @close="closeMobile">Slots DB</NavItem>
                            <NavItem to="/admin/codes"  :active="isActive('/admin/codes')"  icon="key"       glow @close="closeMobile">Code Generator</NavItem>
                            <NavItem to="/admin/tickets":active="isActive('/admin/tickets')"icon="ticket"    glow @close="closeMobile">Tickets</NavItem>
                        </ul>

                        <NavHeading class="mt-4" label="Tools" />
                        <ul class="space-y-1">
                            <NavItem to="/tools/bonushunt"   :active="isActive('/tools/bonushunt')"   icon="dice"   @close="closeMobile">BonusHunt</NavItem>
                            <NavItem to="/tools/slottracker" :active="isActive('/tools/slottracker')" icon="chart"  @close="closeMobile">Slot-Tracker</NavItem>
                            <NavItem to="/tools/redeem"      :active="isActive('/tools/redeem')"      icon="ticket" @close="closeMobile">Code redeem</NavItem>
                        </ul>
                    </template>

                </nav>

                <!-- Sidebar bottom meta (sticks to bottom) -->
                <div class="mt-auto p-3 border-t border-[rgb(var(--color-border))] text-[10px] text-[rgb(var(--color-muted))]">
                    v1.0 · Laravel + Vue · © {{ new Date().getFullYear() }}
                </div>
            </aside>

            <!-- Mobile overlay -->
            <button
                v-if="mobileOpen"
                class="fixed inset-0 z-40 bg-black/40 backdrop-blur-[2px] md:hidden"
                @click="mobileOpen = false"
                aria-label="Overlay schließen"
            />

            <!-- Main -->
            <div class="relative z-10 flex-1 flex flex-col min-w-0">
                <header class="sticky top-0 z-30 px-4 py-3 flex items-center justify-between border-b border-[rgb(var(--color-border))] bg-[rgb(var(--color-card)/.82)] backdrop-blur shadow-[0_10px_30px_-15px_rgba(0,0,0,.35)]">
                    <div class="flex items-center gap-2">
                        <button
                            class="md:hidden px-3 py-2 rounded-xl border border-[rgb(var(--color-border))] bg-[rgb(var(--color-card))] hover:bg-[rgb(var(--color-primary)/0.10)] active:scale-[.98] transition-all duration-200"
                            @click="mobileOpen = true"
                            aria-label="Menü öffnen"
                        >
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </button>

                        <slot name="header">
                            <h1 class="font-semibold tracking-tight">Dashboard</h1>
                        </slot>
                    </div>

                    <div class="flex items-center gap-2">
                        <slot name="header-actions" />
                        <ThemeToggle @change="setTheme" :value="theme" />
                    </div>
                </header>

                <main class="relative p-4 animate-[svx-pageIn_.35s_ease_both]">
                    <slot />
                </main>

                <footer class="px-4 py-3 text-[11px] text-[rgb(var(--color-muted))] border-t border-[rgb(var(--color-border))]">
                    © {{ new Date().getFullYear() }} StreamVaultX — built with Laravel + Vue
                </footer>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, onMounted, ref, watch, h, defineComponent } from 'vue'
import { usePage, Link, router } from '@inertiajs/vue3'
import ThemeToggle from '@/components/ThemeToggle.vue'

/** ---------- Category Heading ---------- */
const NavHeading = defineComponent({
    name: 'NavHeading',
    props: { label: { type: String, required: true } },
    setup(props) {
        return () => h('div', { class: 'px-3 pt-3 pb-2' }, [
            h('div', { class: 'flex items-center gap-2 text-[10px] uppercase tracking-wider text-[rgb(var(--color-muted))]' }, [
                h('span', { class: 'h-[1px] w-4 bg-[rgb(var(--color-border))]' }),
                h('span', props.label),
                h('span', { class: 'h-[1px] grow bg-[rgb(var(--color-border))]' }),
            ])
        ])
    }
})

/** ---------- NavItem (render via h()) ---------- */
const NavItem = defineComponent({
    name: 'NavItem',
    props: {
        to:     { type: String, required: true },
        active: { type: Boolean, default: false },
        glow:   { type: Boolean, default: false },
        icon:   { type: String, default: 'dot' },
    },
    emits: ['close'],
    setup(props, { slots, emit }) {
        const iconPath = computed(() => {
            switch (props.icon) {
                case 'dashboard': return 'M3 10l9-7 9 7v7a2 2 0 01-2 2h-3a2 2 0 01-2-2V9H8v8a2 2 0 01-2 2H3a2 2 0 01-2-2v-7z'
                case 'users':     return 'M16 11c1.657 0 3-1.567 3-3.5S17.657 4 16 4s-3 1.567-3 3.5 1.343 3.5 3 3.5zM8 11c1.657 0 3-1.567 3-3.5S9.657 4 8 4 5 5.567 5 7.5 6.343 11 8 11zm0 2c-2.21 0-6 1.12-6 3.333V19h12v-2.667C14 14.12 10.21 13 8 13zm8 0c-.79 0-1.523.09-2.187.248C15.766 13.96 18 15.01 18 16.333V19h6v-2.667C24 14.12 18.21 13 16 13z'
                case 'db':        return 'M4 6c0-2.21 4.03-4 9-4s9 1.79 9 4-4.03 4-9 4-9-1.79-9-4zm0 6c0-2.21 4.03-4 9-4s9 1.79 9 4-4.03 4-9 4-9-1.79-9-4zm0 6c0-2.21 4.03-4 9-4s9 1.79 9 4-4.03 4-9 4-9-1.79-9-4z'
                case 'key':       return 'M21 10a5 5 0 10-9.9 1H2v4h4v4h4v-4h1.1A5 5 0 1021 10zm-5 2a2 2 0 110-4 2 2 0 010 4z'
                case 'ticket':    return 'M4 8h16a2 2 0 002-2V5a3 3 0 01-3 3 3 3 0 003 3v1a2 2 0 01-2 2H4a2 2 0 01-2-2v-1a3 3 0 013-3 3 3 0 01-3-3v1a2 2 0 002 2z'
                case 'chart':     return 'M3 3h2v18H3V3zm6 7h2v11H9V10zm6-6h2v17h-2V4zm6 9h2v8h-2v-8z'
                case 'dice':      return 'M4 9a3 3 0 013-3h4a3 3 0 013 3v4a3 3 0 01-3 3H7a3 3 0 01-3-3V9zm11-5h4a2 2 0 012 2v4h-6V4zM4 15h6v6H6a2 2 0 01-2-2v-4zM19 13a2 2 0 012 2v4a2 2 0 01-2 2h-4v-8h4z'
                default:          return 'M12 8a2 2 0 110 4 2 2 0 010-4z'
            }
        })
        const base = 'group flex items-center gap-3 px-3 py-2 rounded-xl relative transition-all duration-200 cursor-pointer'
        const activeCls = computed(() =>
            props.active
                ? 'bg-[rgb(var(--color-primary)/.12)] text-[rgb(var(--color-text))] shadow-[0_10px_30px_-12px_rgba(124,58,237,.35)]'
                : 'text-[rgb(var(--color-text))]/90 hover:bg-[rgb(var(--color-primary)/.10)]'
        )
        const ring = computed(() => (props.glow ? 'hover:shadow-[0_6px_24px_-6px_rgba(124,58,237,.35)]' : ''))

        return () =>
            h('li', null, [
                h(Link, {
                    href: props.to,
                    class: [base, activeCls.value, ring.value, 'highlight-effect'].join(' '),
                    onClick: () => emit('close'),
                }, {
                    default: () => [
                        h('svg', { class: 'h-4 w-4 opacity-90', viewBox: '0 0 24 24', fill: 'currentColor' }, [h('path', { d: iconPath.value })]),
                        h('span', { class: 'text-sm' }, slots.default ? slots.default() : ''),
                        props.active ? h('span', { class: 'absolute left-0 top-1/2 -translate-y-1/2 h-6 w-[3px] rounded-r bg-[linear-gradient(180deg,rgb(var(--color-primary)),rgb(var(--color-accent)))]' }) : null,
                        h('span', { 'aria-hidden': 'true', class: 'pointer-events-none absolute inset-0 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300', style: 'background: radial-gradient(180px 180px at var(--mx,-100px) var(--my,-100px), rgba(124,58,237,.12), rgba(251,146,60,.10) 35%, transparent 60%);' })
                    ]
                })
            ])
    }
})

const page = usePage()
const user = computed(() => page.props?.auth?.user ?? null)
const isAdmin = computed(() => {
    const roles = user.value?.roles ?? []
    return roles.some(r => (typeof r === 'string' ? r === 'admin' : r?.name === 'admin'))
})

/* Theme */
const theme = ref('dark')
const themeClass = computed(() => (theme.value === 'dark' ? 'dark' : ''))

onMounted(() => {
    const saved = localStorage.getItem('theme')
    theme.value = saved ?? 'dark'
    applyTheme()
    enableHighlightMouseTracking()
})
function setTheme(val){ theme.value = val; localStorage.setItem('theme', val); applyTheme() }
function applyTheme(){ const root = document.documentElement; theme.value === 'dark' ? root.classList.add('dark') : root.classList.remove('dark') }

/* Mobile sidebar */
const mobileOpen = ref(false)
function closeMobile(){ mobileOpen.value = false }

/* Active helper */
function isActive(prefix){ const path = window.location.pathname; return path === prefix || path.startsWith(prefix + '/') }

/* Avatar/Initials */
const initials = computed(() => { const n = user.value?.name ?? ''; const parts = n.trim().split(/\s+/).slice(0, 2); return parts.map(p => p[0]?.toUpperCase() ?? '').join('') })
const avatarUrl = computed(() => user.value?.avatar || null)

/* Logout */
function logout(){ router.post('/logout', {}, { preserveScroll: true }) }

/* Flash */
const flashSuccess = ref(null)
const flashError   = ref(null)
watch(() => page.props.flash?.success, v => { flashSuccess.value = v; if (v) setTimeout(() => (flashSuccess.value = null), 3000) })
watch(() => page.props.flash?.error,   v => { flashError.value   = v; if (v) setTimeout(() => (flashError.value   = null), 3000) })

/* Cursor highlight vars */
function enableHighlightMouseTracking(){
    document.addEventListener('mousemove', (e) => {
        const targetEls = document.querySelectorAll('.highlight-effect')
        targetEls.forEach(el => {
            const r = el.getBoundingClientRect()
            el.style.setProperty('--mx', `${e.clientX - r.left}px`)
            el.style.setProperty('--my', `${e.clientY - r.top}px`)
        })
    }, { passive: true })
}
</script>

<style scoped>
@reference "tailwindcss";

/* transitions */
.fade-enter-active, .fade-leave-active { transition: opacity .2s ease }
.fade-enter-from, .fade-leave-to { opacity: 0 }
/* === Neon Additions === */

/* Neon-Karten */
.neon-card {
    background: rgb(var(--color-card) / 0.08);
    border: 1px solid rgba(124,58,237,.4);
    border-radius: 1rem;
    box-shadow:
        0 0 6px rgba(124,58,237,.35),
        0 0 12px rgba(251,146,60,.25);
    transition: all .3s ease;
}
.neon-card:hover {
    box-shadow:
        0 0 10px rgba(124,58,237,.55),
        0 0 20px rgba(251,146,60,.35);
}

/* Neon für Sidebar-Nav */
.highlight-effect {
    position: relative;
    overflow: hidden;
}
.highlight-effect::before {
    content: '';
    position: absolute;
    inset: 0;
    opacity: 0;
    border-radius: inherit;
    background: radial-gradient(120px 120px at var(--mx,-100px) var(--my,-100px),
    rgba(124,58,237,.20),
    rgba(251,146,60,.15) 40%,
    transparent 70%);
    transition: opacity .3s;
}
.highlight-effect:hover::before { opacity: 1 }

/* Neon-Schrift Gradient */
.text-gradient {
    background: linear-gradient(90deg, rgb(var(--color-primary)), rgb(var(--color-accent)));
    background-size: 200% 100%;
    background-clip: text;
    -webkit-background-clip: text;
    color: transparent;
    animation: svx-grad 6s ease infinite;
}

/* Buttons Neon */
.btn-neon {
    @apply px-3 py-2 rounded-xl font-medium text-white;
    background: linear-gradient(90deg, rgba(124,58,237,.9), rgba(251,146,60,.9));
    box-shadow: 0 0 6px rgba(124,58,237,.6), 0 0 12px rgba(251,146,60,.4);
    transition: all .3s ease;
}
.btn-neon:hover { transform: translateY(-2px) scale(1.02); }

/* Profil Avatar Glow */
.profile-glow {
    box-shadow: 0 0 8px rgba(124,58,237,.6), 0 0 16px rgba(251,146,60,.4);
}

/* keyframes */
@keyframes svx-grad { 0% {background-position:0% 50%} 50% {background-position:100% 50%} 100% {background-position:0% 50%} }
@keyframes svx-float { 0%,100% {transform:translateY(0)} 50% {transform:translateY(-6px)} }
@keyframes svx-pageIn { from {opacity:0; transform:translateY(4px)} to {opacity:1; transform:translateY(0)} }
@keyframes svx-pulse { 0%,100% {transform:scale(1); box-shadow:0 0 0 0 rgba(124,58,237,0)} 50% {transform:scale(1.06); box-shadow:0 0 14px 2px rgba(124,58,237,.35)} }

.scrollbar-custom{ scrollbar-width: thin; scrollbar-color: rgba(124,58,237,.5) transparent; }
.scrollbar-custom::-webkit-scrollbar { width: 8px }
.scrollbar-custom::-webkit-scrollbar-thumb { background: linear-gradient(180deg, rgba(124,58,237,.6), rgba(251,146,60,.5)); border-radius: 999px }
.scrollbar-custom::-webkit-scrollbar-track { background: transparent }

@media (prefers-reduced-motion: reduce){ * { animation: none !important; transition-duration: .01ms !important } }
</style>

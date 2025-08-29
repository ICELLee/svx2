<template>
    <AuthenticatedLayout>
        <template #header>
            <h1 class="text-2xl font-bold text-gradient">Slot-Tracker & Extension</h1>
        </template>

        <div class="max-w-3xl space-y-6">

            <!-- 🔒 Entitlement-Status -->
            <div
                class="p-4 rounded-2xl neon-card"
                :class="ent.active
                  ? (ent.expires_at ? 'border-emerald-400/60' : 'border-emerald-400/60')
                  : 'border-red-400/60 bg-red-500/5'"
            >
                <div class="flex items-center justify-between gap-3">
                    <div class="text-sm">
                        <template v-if="ent.active">
                            <div class="font-medium text-emerald-400">
                                🔓 Freigeschaltet
                                <span v-if="ent.expires_at">
                                    – gültig bis {{ expiresHuman }}
                                    <span class="ml-2 text-xs px-2 py-0.5 rounded-full border"
                                          :class="isSoon ? 'border-amber-400 text-amber-400' : 'border-emerald-400 text-emerald-400'">
                                        {{ countdownHuman }}
                                    </span>
                                </span>
                                <span v-else class="ml-1 text-emerald-400">– dauerhaft</span>
                            </div>
                            <div v-if="isSoon" class="text-xs text-amber-400 mt-1">
                                Hinweis: Dein Zugang läuft bald ab.
                            </div>
                        </template>
                        <template v-else>
                            <div class="font-medium text-red-500">🔒 Abgelaufen</div>
                            <div class="text-xs text-muted">Bitte Code einlösen.</div>
                        </template>
                    </div>

                    <a href="/tools/redeem" class="btn-neon-sm">Code einlösen</a>
                </div>
            </div>

            <!-- Erklärung -->
            <div class="p-4 rounded-2xl neon-card text-sm">
                <p class="text-muted">
                    Erzeuge ein persönliches <strong>Extension-Token</strong> und füge es in der Browser-Extension ein.
                </p>
            </div>

            <!-- 🔗 Live-Slot -->
            <div class="p-4 rounded-2xl neon-card">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="font-semibold">Live-Slot Anzeige</h2>
                    <div class="flex items-center gap-2">
                        <a v-if="liveUrl"
                           :href="liveUrl"
                           target="_blank"
                           class="btn-neon-sm"
                           :class="!ent.active ? 'pointer-events-none opacity-50' : ''">
                            Live öffnen
                        </a>

                        <span v-else class="text-xs text-muted">Noch kein Live-Link verfügbar</span>

                        <button
                            @click="copy(liveUrl)"
                            class="btn-outline-sm"
                            :disabled="!ent.active || !liveUrl"
                        >
                            Link kopieren
                        </button>
                    </div>
                </div>
                <p class="mt-2 text-xs text-muted">Blade-Route: <code class="font-mono">{{ liveUrl }}</code></p>
            </div>

            <!-- 🔢 Mein Max Win -->
            <div class="p-4 rounded-2xl neon-card">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="font-semibold">Mein Max Win</h2>
                    <div class="flex items-center gap-2">
                        <button @click="loadCurrentKey" class="btn-outline-sm" :disabled="!ent.active">
                            Aktuellen Slot laden
                        </button>
                        <span class="text-xs text-muted">Slot-Key: <code class="font-mono">{{ currentKey || '—' }}</code></span>
                    </div>
                </div>

                <div class="mt-3 grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="text-xs text-muted">Einsatz</label>
                        <input v-model.number="form.bet" type="number" step="0.01" min="0"
                               class="neon-input mt-1" :disabled="!ent.active">
                    </div>
                    <div>
                        <label class="text-xs text-muted">Gewinn</label>
                        <input v-model.number="form.win" type="number" step="0.01" min="0"
                               class="neon-input mt-1" :disabled="!ent.active">
                    </div>
                    <div>
                        <label class="text-xs text-muted">x-Multi</label>
                        <input :value="xComputed" type="text" readonly
                               class="neon-input mt-1 text-muted">
                    </div>
                </div>

                <div class="mt-3 flex gap-2">
                    <button @click="saveStats"
                            class="btn-neon"
                            :disabled="!ent.active || !currentKey || Number(form.bet) <= 0">
                        Speichern
                    </button>
                    <button @click="loadStats"
                            class="btn-outline-sm"
                            :disabled="!ent.active || !currentKey">
                        Laden
                    </button>
                </div>
                <p v-if="flash" class="mt-2 text-xs" :class="flash.ok ? 'text-emerald-400' : 'text-red-500'">
                    {{ flash.msg }}
                </p>
            </div>

            <!-- Token erzeugen -->
            <<!-- Token erzeugen -->
            <div class="p-4 rounded-2xl neon-card">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="font-semibold">Extension-Token</h2>
                    <button @click="create" class="btn-neon" :disabled="!ent.active">
                        Neues Token erzeugen
                    </button>
                </div>

                <!-- ⚡️ Nur direkt nach Erstellung sichtbar -->
                <div v-if="createdToken" class="mt-4 p-3 rounded-xl border border-amber-400/50 bg-amber-500/10">
                    <p class="text-sm mb-2 text-amber-400 font-medium">Dein neuer Token (nur jetzt sichtbar):</p>
                    <code class="block text-xs break-all font-mono">{{ createdToken }}</code>
                    <button @click="copy(createdToken)" class="btn-outline-sm mt-2">Kopieren</button>
                </div>

                <!-- Tokens Liste -->
                <div class="p-4 rounded-2xl neon-card mt-4">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="font-semibold">Meine Tokens</h2>
                        <span class="text-xs text-muted">{{ tokens.length }} aktiv</span>
                    </div>

                    <div v-if="!tokens.length" class="text-sm text-muted">
                        Noch keine Tokens.
                    </div>

                    <ul v-else class="space-y-2">
                        <li v-for="t in formattedTokens" :key="t.id"
                            class="flex items-center justify-between gap-3 p-3 rounded-xl neon-card">
                            <div>
                                <span class="text-sm font-medium">ID: {{ t.id }}</span>
                                <div class="text-xs text-muted">erstellt: {{ t.created_human }}</div>
                            </div>
                            <button @click="revoke(t.id)" class="btn-danger-sm" :disabled="!ent.active">
                                Widerrufen
                            </button>
                        </li>
                    </ul>
                </div>
            </div>


            <!-- API Info -->
            <div class="p-4 rounded-2xl neon-card text-xs text-muted">
                API-Base-URL: <code class="font-mono">{{ apiBase }}</code>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/layouts/AuthLayout.vue'
import { ref, onMounted, computed, onBeforeUnmount } from 'vue'

const TOOL_KEY = 'slottracker'
const SOON_DAYS = 3

// --- Entitlement-Status ---
const ent = ref({ active:false, expires_at:null, remaining_seconds:null, soon:false })
const countdown = ref(0)
const hideSoonToast = ref(false)
const showExpiredModal = ref(false)

// --- Tokens ---
const tokens = ref([])
const createdToken = ref('')
const liveUrlFromApi = ref(null)

const apiBase = computed(() => `${window.location.origin}`)

// ✅ Live-URL mit Fallback
const liveUrl = ref(null)

// --- Mein Max Win ---
const currentKey = ref('')
const form = ref({ bet: 0, win: 0 })
const flash = ref(null)

// Derived
const xComputed = computed(() => {
    const b = Number(form.value.bet || 0)
    const w = Number(form.value.win || 0)
    if (b <= 0) return '0.00'
    return (w / b).toFixed(2)
})
const isSoon = computed(() => !!ent.value.active && !!ent.value.soon)
const expiresHuman = computed(() => ent.value.expires_at ? new Date(ent.value.expires_at).toLocaleString() : '—')
const countdownHuman = computed(() => formatSeconds(countdown.value))

let timer = null
function startTimer() {
    stopTimer()
    if (ent.value.active && ent.value.expires_at && ent.value.remaining_seconds != null) {
        countdown.value = ent.value.remaining_seconds
        timer = setInterval(() => {
            if (countdown.value > 0) countdown.value -= 1
        }, 1000)
    }
}
function stopTimer(){ if (timer) { clearInterval(timer); timer = null } }
onBeforeUnmount(stopTimer)

function formatSeconds(s) {
    if (s == null) return '—'
    const d = Math.floor(s / 86400)
    s -= d * 86400
    const h = Math.floor(s / 3600)
    s -= h * 3600
    const m = Math.floor(s / 60)
    const sec = s - m * 60
    const pad = (n)=> String(n).padStart(2,'0')
    if (d > 0) return `${d}d ${pad(h)}:${pad(m)}:${pad(sec)}`
    return `${pad(h)}:${pad(m)}:${pad(sec)}`
}

// --- API: Entitlement Status ---
async function loadEntitlement() {
    try {
        const { data } = await window.axios.get(`/user/api/entitlements/${encodeURIComponent(TOOL_KEY)}?soon_days=${SOON_DAYS}`)
        ent.value = {
            active: !!data?.active,
            expires_at: data?.expires_at || null,
            remaining_seconds: data?.remaining_seconds ?? null,
            soon: !!data?.soon,
        }
        if (!ent.value.active) {
            showExpiredModal.value = true
        }
        startTimer()
    } catch (e) {
        ent.value = { active:false, expires_at:null, remaining_seconds:null, soon:false }
        showExpiredModal.value = true
    }
}

// --- Tokens/API nur laden, wenn aktiv ---
async function loadTokens() {
    if (!ent.value.active) return
    try {
        const { data } = await window.axios.get('/user/api/extension-tokens')
        tokens.value = data?.tokens || []
        liveUrl.value = data?.live_url || null
    } catch (e) {
        flash.value = { ok:false, msg:'Zugriff fehlgeschlagen.' }
    }
}


async function create() {
    if (!ent.value.active) return
    const { data } = await window.axios.post('/user/api/extension-tokens')
    createdToken.value = data?.token || ''
    if (data?.live_url) liveUrlFromApi.value = data.live_url
    await loadTokens()
}

async function revoke(id) {
    if (!ent.value.active) return
    if (!confirm('Token wirklich widerrufen?')) return
    await window.axios.delete(`/user/api/extension-tokens/${id}`)
    await loadTokens()
}

// --- Slot-Key + Stats ---
async function loadCurrentKey() {
    if (!ent.value.active) { showExpiredModal.value = true; return }
    try {
        const { data } = await window.axios.get('/api/slot/current')
        currentKey.value = data?.key || ''
        flash.value = null
    } catch (e) {
        flash.value = { ok: false, msg: 'Konnte aktuellen Slot-Key nicht laden.' }
    }
}

async function loadStats() {
    if (!ent.value.active || !currentKey.value) return
    try {
        const { data } = await window.axios.get(`/user/api/slots/stats/${encodeURIComponent(currentKey.value)}`)
        form.value.bet = Number(data?.bet || 0)
        form.value.win = Number(data?.win || 0)
        flash.value = { ok: true, msg: 'Werte geladen.' }
    } catch {
        flash.value = { ok: false, msg: 'Keine gespeicherten Werte gefunden.' }
    }
}

async function saveStats() {
    if (!ent.value.active) { showExpiredModal.value = true; return }
    if (!currentKey.value) {
        flash.value = { ok: false, msg: 'Kein Slot-Key. „Aktuellen Slot laden“ zuerst.' }
        return
    }
    try {
        await window.axios.post('/user/api/slots/stats', {
            key: currentKey.value,
            bet: Number(form.value.bet),
            win: Number(form.value.win),
        })
        flash.value = { ok: true, msg: `Gespeichert (x=${xComputed.value}).` }
    } catch {
        flash.value = { ok: false, msg: 'Fehler beim Speichern.' }
    }
}

async function copy(text) {
    try { await navigator.clipboard.writeText(text) } catch {}
}

const formattedTokens = computed(() => {
    return (tokens.value || []).map(t => ({
        ...t,
        created_human: t.created_at ? new Date(t.created_at).toLocaleString() : '—',
    }))
})

onMounted(async () => {
    await loadEntitlement()
    await loadTokens()
    await loadCurrentKey()
})
</script>

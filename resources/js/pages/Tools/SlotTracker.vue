<template>
    <AuthenticatedLayout>
        <template #header>
            <h1 class="font-semibold text-xl">Slot-Tracker & Extension</h1>
        </template>

        <div class="max-w-3xl space-y-6">
            <!-- Erklärung -->
            <div class="p-4 rounded-2xl border border-border bg-card text-sm">
                <p class="text-muted">
                    Erzeuge ein persönliches <strong>Extension-Token</strong> und füge es in der Browser-Extension ein.
                    Die Extension sendet dann Slots <em>nutzerindividuell</em> an deine API.
                </p>
            </div>

            <!-- 🔗 Live-Slot (Blade) -->
            <div class="p-4 rounded-2xl border border-border bg-card">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="font-semibold">Live-Slot Anzeige</h2>
                    <div class="flex items-center gap-2">
                        <a :href="liveUrl" target="_blank"
                           class="px-3 py-2 rounded-xl bg-violet-600 text-white hover:opacity-90">
                            Live-Ansicht öffnen
                        </a>
                        <button @click="copy(liveUrl)"
                                class="px-3 py-2 rounded-xl border hover:bg-accent/10">
                            Link kopieren
                        </button>
                    </div>
                </div>
                <p class="mt-2 text-xs text-muted">Blade-Route: <code class="font-mono">{{ liveUrl }}</code></p>
            </div>

            <!-- 🔢 Mein Max Win (persönlich) -->
            <div class="p-4 rounded-2xl border border-border bg-card">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="font-semibold">Mein Max Win</h2>
                    <div class="flex items-center gap-2">
                        <button @click="loadCurrentKey"
                                class="px-3 py-2 rounded-xl border hover:bg-accent/10">
                            Aktuellen Slot laden
                        </button>
                        <span class="text-xs text-muted">Slot-Key: <code class="font-mono">{{ currentKey || '—' }}</code></span>
                    </div>
                </div>

                <div class="mt-3 grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="text-xs text-muted">Einsatz (Bet)</label>
                        <input v-model.number="form.bet" type="number" step="0.01" min="0"
                               class="mt-1 w-full px-3 py-2 rounded-xl border bg-background">
                    </div>
                    <div>
                        <label class="text-xs text-muted">Gewinn (Win)</label>
                        <input v-model.number="form.win" type="number" step="0.01" min="0"
                               class="mt-1 w-full px-3 py-2 rounded-xl border bg-background">
                    </div>
                    <div>
                        <label class="text-xs text-muted">x-Multi</label>
                        <input :value="xComputed" type="text" readonly
                               class="mt-1 w-full px-3 py-2 rounded-xl border bg-background text-muted-foreground">
                    </div>
                </div>

                <div class="mt-3 flex gap-2">
                    <button @click="saveStats"
                            :disabled="!currentKey || Number(form.bet) <= 0"
                            class="px-3 py-2 rounded-xl bg-violet-600 text-white hover:opacity-90 disabled:opacity-50">
                        Speichern
                    </button>
                    <button @click="loadStats"
                            :disabled="!currentKey"
                            class="px-3 py-2 rounded-xl border hover:bg-accent/10 disabled:opacity-50">
                        Laden
                    </button>
                </div>
                <p v-if="flash" class="mt-2 text-xs" :class="flash.ok ? 'text-emerald-500' : 'text-red-500'">
                    {{ flash.msg }}
                </p>
            </div>

            <!-- Token erzeugen -->
            <div class="p-4 rounded-2xl border border-border bg-card">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="font-semibold">Extension-Token</h2>
                    <button @click="create"
                            class="px-3 py-2 rounded-xl bg-violet-600 text-white hover:opacity-90">
                        Neues Token erzeugen
                    </button>
                </div>

                <!-- Gespeicherte Tokens -->
                <div class="p-4 rounded-2xl border border-border bg-card">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="font-semibold">Meine Tokens</h2>
                        <span class="text-xs text-muted">{{ tokens.length }} aktiv</span>
                    </div>

                    <div v-if="!tokens.length" class="text-sm text-muted">
                        Noch keine Tokens. Erzeuge eines oben.
                    </div>

                    <ul v-else class="space-y-2">
                        <li v-for="t in formattedTokens" :key="t.id"
                            class="flex items-center justify-between gap-3 p-3 rounded-xl border border-border/60 bg-background">
                            <div class="flex flex-col">
                                <span class="text-sm font-medium">…{{ t.last_four || '????' }}</span>
                                <span class="text-xs text-muted">erstellt: {{ t.created_human }}</span>
                            </div>
                            <button @click="revoke(t.id)"
                                    class="px-3 py-2 rounded-xl border border-red-500/40 text-red-400 hover:bg-red-500/10">
                                Widerrufen
                            </button>
                        </li>
                    </ul>
                </div>


            <!-- Optional: API Zielinfo für die Extension -->
            <div class="p-4 rounded-2xl border border-border bg-card text-xs text-muted">
                <div>API-Base-URL für die Extension: <code class="font-mono">{{ apiBase }}</code></div>
                <div class="mt-1">POST <code class="font-mono">{{ apiBase }}/api/slot/current</code> mit
                    <code class="font-mono">Authorization: Bearer &lt;TOKEN&gt;</code>
                </div>
            </div>
        </div>
        </div>

    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/layouts/AuthLayout.vue'
import { ref, onMounted, computed } from 'vue'

const tokens = ref([])
const createdToken = ref('')
const liveUrlFromApi = ref(null)

const apiBase = computed(() => `${window.location.origin}`)
const liveUrl = computed(() => liveUrlFromApi.value || '—')

// --- Mein Max Win ---
const currentKey = ref('')
const form = ref({ bet: 0, win: 0 })
const flash = ref(null)

const xComputed = computed(() => {
    const b = Number(form.value.bet || 0)
    const w = Number(form.value.win || 0)
    if (b <= 0) return '0.00'
    return (w / b).toFixed(2)
})

async function loadCurrentKey() {
    try {
        const { data } = await window.axios.get('/api/slot/current')
        currentKey.value = data?.key || ''
        flash.value = null
    } catch {
        flash.value = { ok: false, msg: 'Konnte aktuellen Slot-Key nicht laden.' }
    }
}
const formattedTokens = computed(() => {
    return (tokens.value || []).map(t => ({
        ...t,
        created_human: t.created_at ? new Date(t.created_at).toLocaleString() : '—',
    }));
});

async function loadStats() {
    if (!currentKey.value) return
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
    } catch (e) {
        flash.value = { ok: false, msg: 'Fehler beim Speichern.' }
    }
}

// --- Tokens ---
async function load() {
    const { data } = await window.axios.get('/user/api/extension-tokens')
    // erwartet: { tokens: [...], live_url: "..." }
    tokens.value = data?.tokens || []
    liveUrlFromApi.value = data?.live_url || null
}

async function create() {
    const { data } = await window.axios.post('/user/api/extension-tokens')
    // erwartet: { token: "PLAIN...", live_url: "..." }
    createdToken.value = data?.token || ''
    if (data?.live_url) liveUrlFromApi.value = data.live_url
    await load()
}

async function revoke(id) {
    if (!confirm('Token wirklich widerrufen?')) return
    await window.axios.delete(`/user/api/extension-tokens/${id}`)
    await load()
}

async function copy(text) {
    try { await navigator.clipboard.writeText(text) } catch {}
}

onMounted(async () => {
    await load()
    // optional: await loadCurrentKey()
})
</script>

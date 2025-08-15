<script setup>
import AuthenticatedLayout from '@/layouts/AuthLayout.vue'
import { ref, reactive, computed, onMounted } from 'vue'

const loading = ref(false)
const flash   = ref(null)

// Filter & Paging
const activeOnly = ref(true)
const page       = ref(1)

// Liste
const codes = ref([])
const meta  = ref(null)  // { current_page, last_page, ... }
const links = ref(null)  // { next, prev }

// Formular "Codes erzeugen"
const form = reactive({
    count: 1,
    duration_days: 30,
    tools: ['bonushunt'], // Default
    max_uses: 1,
    prefix: 'SVX',
    starts_at: '',
    expires_at: '',
})

function resetFlash(){ flash.value = null }
function setFlash(ok, msg){ flash.value = { ok, msg } }

async function load() {
    loading.value = true
    resetFlash()
    try {
        const { data } = await window.axios.get('/admin/api/codes', {
            params: { active_only: activeOnly.value ? 1 : 0, page: page.value }
        })
        codes.value = data.data ?? data
        meta.value  = data.meta ?? null
        links.value = data.links ?? null
    } catch (e) {
        setFlash(false, 'Konnte Codes nicht laden.')
    } finally {
        loading.value = false
    }
}

function toggleTool(tool) {
    const i = form.tools.indexOf(tool)
    if (i >= 0) form.tools.splice(i, 1)
    else form.tools.push(tool)
}

async function createCodes() {
    loading.value = true
    resetFlash()
    try {
        const payload = {
            count: Number(form.count),
            duration_days: Number(form.duration_days),
            tools: form.tools,
            max_uses: Number(form.max_uses),
            prefix: form.prefix || undefined,
            starts_at: form.starts_at || undefined,
            expires_at: form.expires_at || undefined,
        }
        const { data } = await window.axios.post('/admin/api/codes', payload)
        setFlash(true, `${data.codes?.length ?? 0} Codes erstellt.`)
        page.value = 1
        await load()
    } catch (e) {
        const m = e?.response?.data?.message || 'Fehler beim Erzeugen.'
        setFlash(false, m)
    } finally {
        loading.value = false
    }
}

async function lock(id) {
    try { await window.axios.patch(`/admin/api/codes/${id}/lock`); await load() } catch {}
}
async function unlock(id) {
    try { await window.axios.patch(`/admin/api/codes/${id}/unlock`); await load() } catch {}
}

async function copy(text) {
    try { await navigator.clipboard.writeText(text) } catch {}
}

const toolLabels = {
    bonushunt: 'BonusHunt',
    slottracker: 'Slot Tracker',
    tournament: 'Turnament',
    slotrequest: 'Slot Request',
}

const hasPrev = computed(() => meta.value && meta.value.current_page > 1)
const hasNext = computed(() => meta.value && meta.value.current_page < meta.value.last_page)

async function goPrev(){ if (hasPrev.value){ page.value = meta.value.current_page - 1; await load() } }
async function goNext(){ if (hasNext.value){ page.value = meta.value.current_page + 1; await load() } }

onMounted(load)
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between w-full">
                <h1 class="text-xl sm:text-2xl font-bold text-transparent bg-clip-text
                   bg-[linear-gradient(90deg,rgb(var(--color-primary)),rgb(var(--color-accent)))]">
                    Codes verwalten
                </h1>
                <div class="hidden sm:flex items-center gap-2 text-[11px] text-[rgb(var(--color-muted))]">
                    <span class="px-2 py-1 rounded-lg border border-[rgb(var(--color-border))] bg-[rgb(var(--color-card))]">Admin</span>
                </div>
            </div>
        </template>

        <div class="max-w-5xl mx-auto space-y-6">
            <!-- Filter -->
            <div class="flex items-center justify-between">
                <h2 class="sr-only">Filter</h2>
                <label class="text-sm inline-flex items-center gap-2 px-2 py-1 rounded-xl border border-[rgb(var(--color-border))]
                       bg-[rgb(var(--color-card))] hover:bg-[rgb(var(--color-primary)/.06)] transition">
                    <input type="checkbox" v-model="activeOnly" @change="page=1; load()" class="rounded border-[rgb(var(--color-border))] bg-[rgb(var(--color-card))] focus:ring-[rgb(var(--color-primary)/.35)]">
                    <span>Nur aktive Codes</span>
                </label>
            </div>

            <!-- Flash -->
            <transition name="fade">
                <p v-if="flash"
                   class="text-sm px-3 py-2 rounded-xl border backdrop-blur inline-flex items-center gap-2"
                   :class="flash.ok
             ? 'border-[rgb(34_197_94/0.35)] bg-[rgb(34_197_94/0.12)] text-[rgb(134_239_172)]'
             : 'border-[rgb(239_68_68/0.35)] bg-[rgb(239_68_68/0.12)] text-[rgb(254_202_202)]'">
                    <span v-if="flash.ok">✅</span><span v-else>⚠️</span>{{ flash.msg }}
                </p>
            </transition>

            <!-- Erzeugen -->
            <div class="p-4 rounded-2xl border border-[rgb(var(--color-border))] bg-[rgb(var(--color-card)/.8)] backdrop-blur space-y-4 shadow-[0_10px_40px_-12px_rgba(124,58,237,.2)]">
                <h2 class="font-semibold">Codes erzeugen</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label class="text-xs text-[rgb(var(--color-muted))]">Anzahl</label>
                        <input v-model.number="form.count" type="number" min="1" max="500"
                               class="mt-1 w-full px-3 py-2 rounded-xl border border-[rgb(var(--color-border))] bg-[rgb(var(--color-card))] focus:ring-2 focus:ring-[rgb(var(--color-primary)/.35)] outline-none transition">
                    </div>
                    <div>
                        <label class="text-xs text-[rgb(var(--color-muted))]">Laufzeit (Tage)</label>
                        <input v-model.number="form.duration_days" type="number" min="1" max="3650"
                               class="mt-1 w-full px-3 py-2 rounded-xl border border-[rgb(var(--color-border))] bg-[rgb(var(--color-card))] focus:ring-2 focus:ring-[rgb(var(--color-primary)/.35)] outline-none transition">
                    </div>
                    <div>
                        <label class="text-xs text-[rgb(var(--color-muted))]">Max. Nutzungen pro Code</label>
                        <input v-model.number="form.max_uses" type="number" min="1" max="100000"
                               class="mt-1 w-full px-3 py-2 rounded-xl border border-[rgb(var(--color-border))] bg-[rgb(var(--color-card))] focus:ring-2 focus:ring-[rgb(var(--color-primary)/.35)] outline-none transition">
                    </div>
                    <div>
                        <label class="text-xs text-[rgb(var(--color-muted))]">Prefix (optional)</label>
                        <input v-model="form.prefix" type="text" maxlength="16"
                               class="mt-1 w-full px-3 py-2 rounded-xl border border-[rgb(var(--color-border))] bg-[rgb(var(--color-card))] focus:ring-2 focus:ring-[rgb(var(--color-primary)/.35)] outline-none transition">
                    </div>
                    <div>
                        <label class="text-xs text-[rgb(var(--color-muted))]">Gültig ab (optional)</label>
                        <input v-model="form.starts_at" type="datetime-local"
                               class="mt-1 w-full px-3 py-2 rounded-xl border border-[rgb(var(--color-border))] bg-[rgb(var(--color-card))] focus:ring-2 focus:ring-[rgb(var(--color-primary)/.35)] outline-none transition">
                    </div>
                    <div>
                        <label class="text-xs text-[rgb(var(--color-muted))]">Gültig bis (optional)</label>
                        <input v-model="form.expires_at" type="datetime-local"
                               class="mt-1 w-full px-3 py-2 rounded-xl border border-[rgb(var(--color-border))] bg-[rgb(var(--color-card))] focus:ring-2 focus:ring-[rgb(var(--color-primary)/.35)] outline-none transition">
                    </div>
                </div>

                <div>
                    <div class="text-xs text-[rgb(var(--color-muted))] mb-1">Tools</div>
                    <div class="flex flex-wrap gap-2">
                        <button type="button"
                                @click="toggleTool('bonushunt')"
                                :class="[
                      'px-3 py-2 rounded-xl border transition',
                      form.tools.includes('bonushunt')
                        ? 'border-[rgb(var(--color-primary))] bg-[rgb(var(--color-primary)/.85)] text-white shadow-[0_8px_24px_-10px_rgba(124,58,237,.5)]'
                        : 'border-[rgb(var(--color-border))] bg-[rgb(var(--color-card))] hover:bg-[rgb(var(--color-primary)/.08)]'
                    ]">
                            BonusHunt
                        </button>

                        <button type="button"
                                @click="toggleTool('slottracker')"
                                :class="[
                      'px-3 py-2 rounded-xl border transition',
                      form.tools.includes('slottracker')
                        ? 'border-[rgb(var(--color-primary))] bg-[rgb(var(--color-primary)/.85)] text-white shadow-[0_8px_24px_-10px_rgba(124,58,237,.5)]'
                        : 'border-[rgb(var(--color-border))] bg-[rgb(var(--color-card))] hover:bg-[rgb(var(--color-primary)/.08)]'
                    ]">
                            Slot Tracker
                        </button>

                        <button type="button"
                                @click="toggleTool('tournament')"
                                :class="[
                      'px-3 py-2 rounded-xl border transition',
                      form.tools.includes('tournament')
                        ? 'border-[rgb(var(--color-primary))] bg-[rgb(var(--color-primary)/.85)] text-white shadow-[0_8px_24px_-10px_rgba(124,58,237,.5)]'
                        : 'border-[rgb(var(--color-border))] bg-[rgb(var(--color-card))] hover:bg-[rgb(var(--color-primary)/.08)]'
                    ]">
                            Turnament
                        </button>

                        <button type="button"
                                @click="toggleTool('slotrequest')"
                                :class="[
                      'px-3 py-2 rounded-xl border transition',
                      form.tools.includes('slotrequest')
                        ? 'border-[rgb(var(--color-primary))] bg-[rgb(var(--color-primary)/.85)] text-white shadow-[0_8px_24px_-10px_rgba(124,58,237,.5)]'
                        : 'border-[rgb(var(--color-border))] bg-[rgb(var(--color-card))] hover:bg-[rgb(var(--color-primary)/.08)]'
                    ]">
                            Slot Request
                        </button>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <button @click="createCodes"
                            :disabled="loading || !form.tools.length || form.count < 1"
                            class="px-4 py-2 rounded-xl text-white transition
                         bg-[rgb(var(--color-primary)/.90)] hover:bg-[rgb(var(--color-primary))] disabled:opacity-50">
                        {{ loading ? 'Erzeuge…' : 'Erzeugen' }}
                    </button>
                    <span v-if="loading" class="text-xs text-[rgb(var(--color-muted))] animate-pulse">Lade…</span>
                </div>
            </div>

            <!-- Liste -->
            <div class="p-4 rounded-2xl border border-[rgb(var(--color-border))] bg-[rgb(var(--color-card)/.8)] backdrop-blur">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="font-semibold">Codes</h2>
                    <div class="flex items-center gap-2">
                        <button @click="goPrev" :disabled="!hasPrev"
                                class="px-3 py-2 rounded-xl border border-[rgb(var(--color-border))] bg-[rgb(var(--color-card))]
                           hover:bg-[rgb(var(--color-primary)/.10)] disabled:opacity-50 transition">
                            Zurück
                        </button>
                        <button @click="goNext" :disabled="!hasNext"
                                class="px-3 py-2 rounded-xl border border-[rgb(var(--color-border))] bg-[rgb(var(--color-card))]
                           hover:bg-[rgb(var(--color-primary)/.10)] disabled:opacity-50 transition">
                            Weiter
                        </button>
                    </div>
                </div>

                <div v-if="!codes.length" class="text-sm text-[rgb(var(--color-muted))]">Keine Codes gefunden.</div>

                <ul v-else class="space-y-2">
                    <li v-for="c in codes" :key="c.id"
                        class="p-3 rounded-xl border border-[rgb(var(--color-border))] bg-[rgb(var(--color-card))] transition
                     hover:shadow-[0_10px_40px_-12px_rgba(124,58,237,.25)]">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <div class="flex items-center gap-2">
                                    <code class="font-mono text-sm px-2 py-1 rounded-lg bg-[rgb(var(--color-primary)/.08)] border border-[rgb(var(--color-border))]">
                                        {{ c.code }}
                                    </code>
                                    <button class="px-2 py-1 rounded-lg border border-[rgb(var(--color-border))]
                                 hover:bg-[rgb(var(--color-primary)/.10)] transition"
                                            @click="copy(c.code)">Kopieren</button>
                                </div>
                                <div class="text-xs text-[rgb(var(--color-muted))] mt-1">
                                    Laufzeit: {{ c.duration_days }} Tage ·
                                    Nutzung: {{ c.used_count ?? 0 }}/{{ c.max_uses }}
                                    <span v-if="c.starts_at"> · ab {{ new Date(c.starts_at).toLocaleString() }}</span>
                                    <span v-if="c.expires_at"> · bis {{ new Date(c.expires_at).toLocaleString() }}</span>
                                </div>
                                <div class="flex flex-wrap gap-1 mt-2">
                  <span v-for="t in (c.tools || [])" :key="t"
                        class="px-2 py-0.5 rounded-full text-xs border border-[rgb(var(--color-border))]
                               bg-[rgb(var(--color-card))] hover:bg-[rgb(var(--color-primary)/.08)] transition">
                    {{ toolLabels[t] ?? t }}
                  </span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                <span class="text-xs"
                      :class="c.is_locked
                        ? 'text-[rgb(254_202_202)]'
                        : 'text-[rgb(134_239_172)]'">
                  {{ c.is_locked ? 'Gesperrt' : 'Aktiv' }}
                </span>

                                <button v-if="!c.is_locked"
                                        @click="lock(c.id)"
                                        class="px-3 py-2 rounded-xl border border-[rgb(var(--color-border))]
                               hover:bg-[rgb(239_68_68/0.15)] transition">
                                    Sperren
                                </button>
                                <button v-else
                                        @click="unlock(c.id)"
                                        class="px-3 py-2 rounded-xl border border-[rgb(var(--color-border))]
                               hover:bg-[rgb(16_185_129/0.15)] transition">
                                    Entsperren
                                </button>
                            </div>
                        </div>
                    </li>
                </ul>

                <div v-if="meta" class="text-xs text-[rgb(var(--color-muted))] mt-3">
                    Seite {{ meta.current_page }} / {{ meta.last_page }}
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
@reference "tailwindcss";
.fade-enter-active, .fade-leave-active { transition: opacity .2s ease }
.fade-enter-from, .fade-leave-to { opacity: 0 }
</style>

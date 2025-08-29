<template>
    <AuthenticatedLayout>
        <template #header>
            <h1 class="text-2xl font-bold text-gradient">Bonus Hunt</h1>
        </template>

        <!-- 🔒 Entitlement-Status -->
        <div
            class="mb-6 p-4 rounded-xl neon-card"
            :class="ent.active
                ? 'border-emerald-400/60'
                : 'border-red-400/60 bg-red-500/5'">
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

                <a href="/tools/redeem"
                   class="btn-neon px-3 py-2 text-sm">
                    Code einlösen
                </a>
            </div>
        </div>

        <!-- Anlegen -->
        <div class="flex flex-wrap gap-3 mb-6 neon-card p-4 rounded-xl">
            <input v-model="form.name" placeholder="Name"
                   class="input neon-input"
                   :disabled="!ent.active" />

            <input v-model.number="form.start_amount" type="number" step="0.01" placeholder="Startkapital (€)"
                   class="input neon-input w-48"
                   :disabled="!ent.active" />

            <label class="flex items-center gap-2 px-3 py-2 rounded-lg border border-border cursor-pointer hover-glow">
                <input type="checkbox" v-model="form.is_active" class="h-4 w-4 text-primary rounded" :disabled="!ent.active" />
                <span class="text-sm">Aktiv</span>
            </label>

            <button @click="create"
                    class="btn-neon flex items-center gap-2 disabled:opacity-50"
                    :disabled="!ent.active">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Anlegen
            </button>
        </div>

        <!-- Liste -->
        <div class="overflow-hidden rounded-xl neon-card">
            <table class="w-full text-sm">
                <thead class="bg-card/50">
                <tr class="text-left border-b border-border">
                    <th class="p-4 font-medium text-muted">Name</th>
                    <th class="p-4 font-medium text-muted">Start</th>
                    <th class="p-4 font-medium text-muted">Einträge</th>
                    <th class="p-4 font-medium text-muted">Aktiv</th>
                    <th class="p-4 font-medium text-muted text-right">Aktion</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="h in rows?.data ?? []" :key="h.id"
                    class="hover-glow transition-smooth">
                    <td class="p-4 font-medium">{{ h.name }}</td>
                    <td class="p-4">{{ Number(h.start_amount).toFixed(2) }} €</td>
                    <td class="p-4">{{ h.entries_count }}</td>
                    <td class="p-4">
                        <input type="checkbox" :checked="h.is_active" @change="setActive(h, $event.target.checked)"
                               class="rounded text-primary" :disabled="!ent.active">
                    </td>
                    <td class="p-4 text-right flex gap-2 justify-end">
                        <button @click="openEdit(h)" class="btn-neon-sm">Bearbeiten</button>
                        <button @click="copyLink(h.id)" class="btn-neon-sm">Link</button>
                        <button @click="del(h)" class="btn-danger-sm">Löschen</button>
                    </td>
                </tr>
                <tr v-if="(rows?.data ?? []).length === 0">
                    <td colspan="5" class="p-8 text-center text-muted/70">
                        Keine Hunts vorhanden
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <!-- Edit-Dialog -->
        <div v-if="edit.open"
             class="fixed inset-0 bg-black/70 z-50 flex items-center justify-center">
            <div class="bg-card p-6 rounded-xl max-w-4xl w-[95%] max-h-[90vh] overflow-y-auto modal-content">

                <!-- Header -->
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold">Bearbeite Hunt: {{ edit.data.name }}</h2>
                    <button @click="closeEdit" class="btn-neon-sm">✕</button>
                </div>

                <!-- Basisdaten -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div>
                        <label class="block text-sm mb-1">Name</label>
                        <input v-model="edit.data.name" class="input neon-input w-full" />
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Startkapital (€)</label>
                        <input v-model.number="edit.data.start_amount" type="number" step="0.01" class="input neon-input w-full" />
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" v-model="edit.data.is_active" class="h-4 w-4 text-primary rounded" />
                        <span class="text-sm">Aktiv</span>
                    </div>
                </div>

                <div class="flex gap-2 mb-6">
                    <button @click="saveEdit" class="btn-neon">Änderungen speichern</button>
                </div>

                <!-- Slots hinzufügen -->
                <h3 class="text-lg font-semibold mb-2">Slots hinzufügen</h3>
                <div class="flex flex-wrap gap-2 mb-4">
                    <input v-model="slotSearch.q" placeholder="Slot suchen..."
                           class="input neon-input flex-1" />
                    <input v-model.number="slotSearch.bet" type="number" step="0.01"
                           placeholder="Einsatz (€)" class="input neon-input w-32" />
                    <button @click="searchSlots(1)" class="btn-neon-sm">Suchen</button>
                </div>

                <table class="w-full text-sm mb-4">
                    <thead>
                    <tr class="border-b border-border">
                        <th class="p-2"><input type="checkbox" @change="toggleAllSlots" /></th>
                        <th class="p-2">Name</th>
                        <th class="p-2">Provider</th>
                        <th class="p-2"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="s in slotRows" :key="s.id" class="hover-glow">
                        <td class="p-2"><input type="checkbox" v-model="selSlots" :value="s.id" /></td>
                        <td class="p-2">{{ s.name }}</td>
                        <td class="p-2">{{ s.provider }}</td>
                        <td class="p-2 text-right">
                            <button @click="addSlot(s.id)" class="btn-neon-sm">Hinzufügen</button>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <div class="flex gap-2 mb-8">
                    <button @click="bulkAddSlots" class="btn-neon-sm">Ausgewählte hinzufügen</button>
                </div>

                <!-- Einträge -->
                <h3 class="text-lg font-semibold mb-2">Vorhandene Einträge</h3>
                <table class="w-full text-sm">
                    <thead>
                    <tr class="border-b border-border">
                        <th class="p-2">#</th>
                        <th class="p-2">Slot</th>
                        <th class="p-2">Einsatz</th>
                        <th class="p-2">Win</th>
                        <th class="p-2">X</th>
                        <th class="p-2">Bonus</th>
                        <th class="p-2 text-right">Aktion</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="e in entries" :key="e.id" class="hover-glow">
                        <td class="p-2">{{ e.position }}</td>
                        <td class="p-2">{{ e.slot?.name }}</td>
                        <td class="p-2">
                            <input v-model.number="e.bet" type="number" step="0.01" class="input neon-input w-24"
                                   @change="saveEntry(e)" />
                        </td>
                        <td class="p-2">
                            <input v-model.number="e.win" type="number" step="0.01" class="input neon-input w-24"
                                   @change="saveEntry(e)" />
                        </td>
                        <td class="p-2">
                            <input v-model.number="e.x_value" type="number" step="0.01" class="input neon-input w-20"
                                   @change="saveEntry(e)" />
                        </td>
                        <td class="p-2 flex items-center gap-1">
                            <input type="checkbox" v-model="e.bonus_bought" @change="saveEntry(e)" />
                            <input v-if="e.bonus_bought" v-model.number="e.bonus_cost" type="number" step="0.01"
                                   class="input neon-input w-24" @change="saveEntry(e)" />
                        </td>
                        <td class="p-2 text-right">
                            <button @click="removeEntry(e)" class="btn-danger-sm">Entfernen</button>
                        </td>
                    </tr>
                    <tr v-if="entries.length === 0">
                        <td colspan="7" class="p-4 text-center text-muted/70">Noch keine Einträge</td>
                    </tr>
                    </tbody>
                </table>

            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-4 flex flex-wrap gap-2 items-center">
            <button v-if="rows?.prev_page_url" @click="load(rows.current_page - 1)" class="btn-neon-sm">
                Zurück
            </button>
            <button v-if="rows?.next_page_url" @click="load(rows.current_page + 1)" class="btn-neon-sm">
                Weiter
            </button>
            <div class="ml-auto text-sm text-muted">Seite {{ rows?.current_page || 1 }} von {{ rows?.last_page || 1 }}</div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/layouts/AuthLayout.vue'
import { ref, reactive, onMounted, computed, onBeforeUnmount } from 'vue'

const TOOL_KEY = 'bonushunt'
const SOON_DAYS = 3

// Entitlement
const ent = ref({ active:false, expires_at:null, remaining_seconds:null, soon:false })
const countdown = ref(0)
const isSoon = computed(() => !!ent.value.active && !!ent.value.soon)
const expiresHuman = computed(() => ent.value.expires_at ? new Date(ent.value.expires_at).toLocaleString() : '—')
const countdownHuman = computed(() => formatSeconds(countdown.value))
const showExpiredModal = ref(false)
let timer = null

function startTimer(){
    stopTimer()
    if (ent.value.active && ent.value.expires_at && ent.value.remaining_seconds != null) {
        countdown.value = ent.value.remaining_seconds
        timer = setInterval(()=>{ if (countdown.value > 0) countdown.value -= 1 }, 1000)
    }
}
function stopTimer(){ if (timer){ clearInterval(timer); timer=null } }
onBeforeUnmount(stopTimer)

function formatSeconds(s){
    if (s == null) return '—'
    const d = Math.floor(s/86400); s -= d*86400
    const h = Math.floor(s/3600);  s -= h*3600
    const m = Math.floor(s/60);    const sec = s - m*60
    const pad = n => String(n).padStart(2,'0')
    return d>0 ? `${d}d ${pad(h)}:${pad(m)}:${pad(sec)}` : `${pad(h)}:${pad(m)}:${pad(sec)}`
}

async function loadEntitlement(){
    try{
        const { data } = await window.axios.get(`/user/api/entitlements/${encodeURIComponent(TOOL_KEY)}?soon_days=${SOON_DAYS}`)
        ent.value = {
            active: !!data?.active,
            expires_at: data?.expires_at || null,
            remaining_seconds: data?.remaining_seconds ?? null,
            soon: !!data?.soon,
        }
        if (!ent.value.active) showExpiredModal.value = true
        startTimer()
    }catch{
        ent.value = { active:false, expires_at:null, remaining_seconds:null, soon:false }
        showExpiredModal.value = true
    }
}

// Daten
const rows = ref(null)
const form = ref({ name:'', start_amount:0, is_active:true })

// Edit / Slots
const edit = reactive({
    open: false,
    id: null,
    data: { name:'', start_amount:0, is_active:false }
})
const entries = ref([])
const slotSearch = ref({ q: '', bet: null })
const slotRows = ref([])
const selSlots = ref([])
const slotPages = ref({ page: 1, prev: false, next: false })

async function openEdit(h){
    if (!ent.value.active) { showExpiredModal.value = true; return }
    edit.open = true
    edit.id = h.id
    edit.data = { name:h.name, start_amount:h.start_amount, is_active:h.is_active }
    await awaitLoadEntries()
    await searchSlots(1)
}
function closeEdit(){ edit.open = false }

async function load(page=1){
    if (!ent.value.active) { showExpiredModal.value = true; return }
    try{
        const { data } = await window.axios.get('/user/api/bonus-hunts', { params: { page } })
        rows.value = data
    }catch(e){
        if (e?.response?.status === 403){ await loadEntitlement(); showExpiredModal.value = true }
    }
}

async function create(){
    if (!ent.value.active) { showExpiredModal.value = true; return }
    await window.axios.post('/user/api/bonus-hunts', form.value)
    form.value = { name:'', start_amount:0, is_active:true }
    await load(1)
}

async function setActive(h, val){
    if (!ent.value.active) { showExpiredModal.value = true; return }
    await window.axios.put(`/user/api/bonus-hunts/${h.id}`, { is_active: !!val })
    await load(rows.value?.current_page || 1)
}

async function copyLink(id) {
    if (!ent.value.active) { showExpiredModal.value = true; return }
    try {
        const { data } = await window.axios.get(`/user/api/bonus-hunts/${id}/link`)
        const text = data.public_url
        if (navigator.clipboard && window.isSecureContext) {
            await navigator.clipboard.writeText(text)
        } else {
            const ta = document.createElement('textarea')
            ta.value = text; ta.style.position = 'fixed'; ta.style.left = '-9999px'
            document.body.appendChild(ta); ta.select(); document.execCommand('copy'); document.body.removeChild(ta)
        }
        showToast('Link in die Zwischenablage kopiert!', 'success')
    } catch (e) {
        console.error(e)
        showToast('Kopieren fehlgeschlagen: ' + (e?.response?.data?.message ?? e.message), 'error')
    }
}

async function del(h){
    if (!ent.value.active) { showExpiredModal.value = true; return }
    if (!confirm('Wirklich löschen?')) return
    await window.axios.delete(`/user/api/bonus-hunts/${h.id}`)
    await load(rows.value?.current_page || 1)
    showToast('Bonus Hunt erfolgreich gelöscht', 'success')
}

async function saveEdit(){
    if (!ent.value.active) { showExpiredModal.value = true; return }
    await window.axios.put(`/user/api/bonus-hunts/${edit.id}`, edit.data)
    closeEdit()
    await load(rows.value?.current_page || 1)
    showToast('Änderungen gespeichert', 'success')
}

async function awaitLoadEntries(){
    if (!ent.value.active) { showExpiredModal.value = true; return }
    const { data } = await window.axios.get(`/user/api/bonus-hunts/${edit.id}/entries`)
    entries.value = data
}

async function searchSlots(page=1){
    if (!ent.value.active) { showExpiredModal.value = true; return }
    const { data } = await window.axios.get('/user/api/slots/search', {
        params: { s: slotSearch.value.q, page }
    })
    slotRows.value = data.data
    slotPages.value = { page: data.current_page, prev: !!data.prev_page_url, next: !!data.next_page_url }
}

function toggleAllSlots(ev){
    if (!ent.value.active) { ev.preventDefault(); showExpiredModal.value = true; return }
    if (ev.target.checked) selSlots.value = slotRows.value.map(s=>s.id)
    else selSlots.value = []
}

async function addSlot(slotId){
    if (!ent.value.active) { showExpiredModal.value = true; return }
    await window.axios.post(`/user/api/bonus-hunts/${edit.id}/entries`, {
        slot_id: slotId,
        bet: slotSearch.value.bet ?? null
    })
    await awaitLoadEntries()
    showToast('Slot hinzugefügt', 'success')
}

async function bulkAddSlots(){
    if (!ent.value.active) { showExpiredModal.value = true; return }
    if (selSlots.value.length === 0) return
    const bet = slotSearch.value.bet ?? null
    for (const id of selSlots.value) {
        try {
            await window.axios.post(`/user/api/bonus-hunts/${edit.id}/entries`, { slot_id: id, bet })
        } catch(e) { /* Duplikate ignorieren */ }
    }
    selSlots.value = []
    await awaitLoadEntries()
    showToast('Slots hinzugefügt', 'success')
}

async function saveEntry(e){
    if (!ent.value.active) { showExpiredModal.value = true; return }
    await window.axios.put(`/user/api/bonus-hunts/${edit.id}/entries/${e.id}`, {
        position: e.position,
        bet: e.bet ?? 0,
        win: e.win ?? null,
        x_value: e.x_value ?? null,
        bonus_bought: !!e.bonus_bought,
        bonus_cost: e.bonus_bought ? (e.bonus_cost ?? null) : null,
    })
}

async function removeEntry(e){
    if (!ent.value.active) { showExpiredModal.value = true; return }
    if (!confirm('Eintrag entfernen?')) return
    await window.axios.delete(`/user/api/bonus-hunts/${edit.id}/entries/${e.id}`)
    await awaitLoadEntries()
    showToast('Eintrag entfernt', 'success')
}

/* Toast */
function showToast(message, type = 'info') {
    console.log(`[${type}] ${message}`)
}

onMounted(async () => {
    await loadEntitlement()
    await load(1)
})
</script>

<style scoped>
@keyframes fade-in { from { opacity: 0; } to { opacity: 1; } }
.animate-fade-in { animation: fade-in 0.2s ease-out; }

.modal-content::-webkit-scrollbar { width: 8px; }
.modal-content::-webkit-scrollbar-track { background: rgba(var(--border)/0.2); border-radius: 4px; }
.modal-content::-webkit-scrollbar-thumb { background: rgba(var(--muted)/0.4); border-radius: 4px; }
.modal-content::-webkit-scrollbar-thumb:hover { background: rgba(var(--muted)/0.6); }
</style>

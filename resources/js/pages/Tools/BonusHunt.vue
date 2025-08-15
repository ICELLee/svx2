<template>
    <AuthenticatedLayout>
        <template #header>
            <h1 class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-orange-500 bg-clip-text text-transparent">
                Bonus Hunt
            </h1>
        </template>

        <!-- Anlegen -->
        <div class="flex flex-wrap gap-3 mb-6 p-4 bg-card rounded-xl border border-border/50 shadow-sm">
            <input v-model="form.name" placeholder="Name"
                   class="px-4 py-2 rounded-lg border border-border/50 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all" />

            <input v-model.number="form.start_amount" type="number" step="0.01" placeholder="Startkapital (€)"
                   class="px-4 py-2 rounded-lg border border-border/50 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all w-48" />

            <label class="flex items-center gap-2 px-3 py-2 bg-card rounded-lg border border-border/50 hover:bg-card/70 transition-colors cursor-pointer">
                <input type="checkbox" v-model="form.is_active" class="h-4 w-4 text-primary rounded border-border/50 focus:ring-primary/50" />
                <span class="text-sm">Aktiv</span>
            </label>

            <button @click="create"
                    class="px-4 py-2 rounded-lg bg-primary text-white hover:bg-primary/90 transition-colors shadow-md hover:shadow-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Anlegen
            </button>
        </div>

        <!-- Liste -->
        <div class="overflow-hidden rounded-xl border border-border/50 shadow-sm">
            <table class="w-full text-sm">
                <thead class="bg-card/80 backdrop-blur-sm">
                <tr class="text-left border-b border-border/50">
                    <th class="p-4 font-medium text-muted">Name</th>
                    <th class="p-4 font-medium text-muted">Start</th>
                    <th class="p-4 font-medium text-muted">Einträge</th>
                    <th class="p-4 font-medium text-muted">Aktiv</th>
                    <th class="p-4 font-medium text-muted text-right">Aktion</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="h in rows?.data ?? []" :key="h.id"
                    class="border-b border-border/20 hover:bg-card/50 transition-colors group">
                    <td class="p-4 font-medium">{{ h.name }}</td>
                    <td class="p-4">{{ Number(h.start_amount).toFixed(2) }} €</td>
                    <td class="p-4">{{ h.entries_count }}</td>
                    <td class="p-4">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" :checked="h.is_active" @change="setActive(h, $event.target.checked)"
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                        </label>
                    </td>
                    <td class="p-4 text-right">
                        <div class="flex gap-2 justify-end">
                            <button @click="openEdit(h)"
                                    class="px-3 py-1.5 rounded-lg border border-border/50 hover:bg-card/70 hover:border-primary/50 transition-colors flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Bearbeiten
                            </button>
                            <button @click="copyLink(h.id)"
                                    class="px-3 py-1.5 rounded-lg border border-border/50 hover:bg-card/70 hover:text-primary transition-colors flex items-center gap-1"
                                    title="Öffentlichen Link kopieren">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                                </svg>
                                Link
                            </button>
                            <button @click="del(h)"
                                    class="px-3 py-1.5 rounded-lg text-red-500 hover:bg-red-500/10 transition-colors flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Löschen
                            </button>
                        </div>
                    </td>
                </tr>
                <tr v-if="(rows?.data ?? []).length === 0">
                    <td colspan="5" class="p-8 text-center text-muted/70">
                        <div class="flex flex-col items-center justify-center gap-2">
                            <svg class="w-12 h-12 text-muted/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Keine Hunts vorhanden</span>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4 flex flex-wrap gap-2 items-center">
            <button v-if="rows?.prev_page_url" @click="load(rows.current_page - 1)"
                    class="px-4 py-2 rounded-lg border border-border/50 hover:bg-card/70 transition-colors flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Zurück
            </button>
            <button v-if="rows?.next_page_url" @click="load(rows.current_page + 1)"
                    class="px-4 py-2 rounded-lg border border-border/50 hover:bg-card/70 transition-colors flex items-center gap-1">
                Weiter
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="ml-auto text-sm text-muted">Seite {{ rows?.current_page || 1 }} von {{ rows?.last_page || 1 }}</div>
        </div>

        <!-- Edit-Modal -->
        <div v-if="edit.open" class="fixed inset-0 bg-black/50 backdrop-blur-sm grid place-items-center z-50 p-4 animate-fade-in">
            <div class="bg-card border border-border/50 rounded-2xl shadow-xl w-full max-w-5xl max-h-[90vh] overflow-hidden flex flex-col">
                <div class="p-6 border-b border-border/50">
                    <h3 class="text-xl font-semibold flex items-center gap-2">
                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Bonus Hunt bearbeiten
                    </h3>
                </div>

                <div class="overflow-y-auto flex-1 p-6">
                    <!-- Stammdaten -->
                    <div class="grid md:grid-cols-3 gap-4 mb-6">
                        <div>
                            <label class="text-sm font-medium text-muted block mb-2">Name</label>
                            <input v-model="edit.data.name"
                                   class="w-full px-4 py-2 rounded-lg border border-border/50 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-muted block mb-2">Startkapital (€)</label>
                            <input v-model.number="edit.data.start_amount" type="number" step="0.01"
                                   class="w-full px-4 py-2 rounded-lg border border-border/50 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                        </div>
                        <div class="flex items-end">
                            <label class="flex items-center gap-2 px-4 py-2 bg-card rounded-lg border border-border/50 hover:bg-card/70 transition-colors cursor-pointer">
                                <input type="checkbox" v-model="edit.data.is_active" class="h-4 w-4 text-primary rounded border-border/50 focus:ring-primary/50">
                                <span class="text-sm">Aktiv</span>
                            </label>
                        </div>
                    </div>

                    <div class="grid lg:grid-cols-2 gap-6">
                        <!-- Slots suchen & hinzufügen -->
                        <div class="p-4 border border-border/50 rounded-xl">
                            <h4 class="font-semibold mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Slots suchen & hinzufügen
                            </h4>

                            <div class="flex gap-2 mb-4">
                                <input v-model="slotSearch.q" @keyup.enter="searchSlots()" placeholder="Suche (Name/Key/Provider)"
                                       class="flex-1 px-4 py-2 rounded-lg border border-border/50 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                                <button @click="searchSlots()"
                                        class="px-4 py-2 rounded-lg bg-primary text-white hover:bg-primary/90 transition-colors">
                                    Suchen
                                </button>
                            </div>

                            <div class="flex items-center gap-2 mb-4">
                                <input v-model.number="slotSearch.bet" type="number" step="0.01"
                                       class="px-4 py-2 rounded-lg border border-border/50 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all w-32"
                                       placeholder="Bet €">
                                <button @click="bulkAddSlots"
                                        :disabled="selSlots.length===0"
                                        :class="{'opacity-50 cursor-not-allowed': selSlots.length===0, 'hover:bg-primary/90': selSlots.length>0}"
                                        class="px-4 py-2 rounded-lg bg-primary text-white transition-colors">
                                    Auswahl hinzufügen
                                </button>
                            </div>

                            <div class="border border-border/30 rounded-lg overflow-hidden">
                                <table class="w-full text-sm">
                                    <thead class="bg-card/80 border-b border-border/50">
                                    <tr class="text-left">
                                        <th class="p-3"><input type="checkbox" @change="toggleAllSlots($event)" class="rounded border-border/50 text-primary focus:ring-primary/50"></th>
                                        <th class="p-3">Slot</th>
                                        <th class="p-3 w-24 text-right">Aktion</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="s in slotRows" :key="s.id" class="border-b border-border/20 hover:bg-card/50 transition-colors">
                                        <td class="p-3"><input type="checkbox" v-model="selSlots" :value="s.id" class="rounded border-border/50 text-primary focus:ring-primary/50"></td>
                                        <td class="p-3">
                                            <div class="font-medium">{{ s.name }}</div>
                                            <div class="text-xs text-muted">{{ s.provider }} — {{ s.key }}</div>
                                        </td>
                                        <td class="p-3 text-right">
                                            <button @click="addSlot(s.id)"
                                                    class="p-1.5 rounded-lg border border-border/50 hover:bg-primary/10 hover:border-primary/50 text-primary transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-if="slotRows.length===0">
                                        <td colspan="3" class="p-6 text-center text-muted/70">
                                            <div class="flex flex-col items-center justify-center gap-2">
                                                <svg class="w-10 h-10 text-muted/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span>Keine Treffer</span>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4 flex flex-wrap gap-2 items-center">
                                <button v-if="slotPages.prev" @click="searchSlots(slotPages.page-1)"
                                        class="px-4 py-2 rounded-lg border border-border/50 hover:bg-card/70 transition-colors flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                    Zurück
                                </button>
                                <button v-if="slotPages.next" @click="searchSlots(slotPages.page+1)"
                                        class="px-4 py-2 rounded-lg border border-border/50 hover:bg-card/70 transition-colors flex items-center gap-1">
                                    Weiter
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                                <div class="ml-auto text-sm text-muted">Seite {{ slotPages.page }}</div>
                            </div>
                        </div>

                        <!-- Einträge im Hunt -->
                        <div class="p-4 border border-border/50 rounded-xl">
                            <h4 class="font-semibold mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                Einträge im Hunt
                            </h4>

                            <div class="border border-border/30 rounded-lg overflow-hidden">
                                <table class="w-full text-sm">
                                    <thead class="bg-card/80 border-b border-border/50">
                                    <tr class="text-left">
                                        <th class="p-3 w-16">Pos</th>
                                        <th class="p-3">Slot</th>
                                        <th class="p-3 w-20">Bet</th>
                                        <th class="p-3 w-24">Win</th>
                                        <th class="p-3 w-20">X</th>
                                        <th class="p-3 w-28">Buy</th>
                                        <th class="p-3 w-16 text-right"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="e in entries" :key="e.id" class="border-b border-border/20 hover:bg-card/50 transition-colors">
                                        <td class="p-3">
                                            <input type="number" min="1"
                                                   class="w-16 px-2 py-1.5 rounded border border-border/50 focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all"
                                                   v-model.number="e.position" @change="saveEntry(e)">
                                        </td>
                                        <td class="p-3">
                                            <div class="font-medium">{{ e.slot?.name }}</div>
                                            <div class="text-xs text-muted">{{ e.slot?.provider }}</div>
                                        </td>
                                        <td class="p-3">
                                            <input type="number" step="0.01"
                                                   class="w-20 px-2 py-1.5 rounded border border-border/50 focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all"
                                                   v-model.number="e.bet" @change="saveEntry(e)">
                                        </td>
                                        <td class="p-3">
                                            <input type="number" step="0.01"
                                                   class="w-24 px-2 py-1.5 rounded border border-border/50 focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all"
                                                   v-model.number="e.win" @change="saveEntry(e)">
                                        </td>
                                        <td class="p-3">
                                            <input type="number" step="0.01"
                                                   class="w-20 px-2 py-1.5 rounded border border-border/50 focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all"
                                                   v-model.number="e.x_value" @change="saveEntry(e)">
                                        </td>
                                        <td class="p-3">
                                            <label class="text-xs flex items-center gap-2 mb-1">
                                                <input type="checkbox" v-model="e.bonus_bought" @change="saveEntry(e)"
                                                       class="rounded border-border/50 text-primary focus:ring-primary/50">
                                                gekauft
                                            </label>
                                            <input v-if="e.bonus_bought" type="number" step="0.01" placeholder="Kosten €"
                                                   class="w-24 px-2 py-1.5 rounded border border-border/50 focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all"
                                                   v-model.number="e.bonus_cost" @change="saveEntry(e)">
                                        </td>
                                        <td class="p-3 text-right">
                                            <button @click="removeEntry(e)"
                                                    class="p-1.5 rounded-lg border border-border/50 hover:bg-red-500/10 hover:border-red-500/50 text-red-500 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-if="entries.length===0">
                                        <td colspan="7" class="p-6 text-center text-muted/70">
                                            <div class="flex flex-col items-center justify-center gap-2">
                                                <svg class="w-10 h-10 text-muted/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span>Noch keine Einträge</span>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-4 border-t border-border/50 flex justify-end gap-3">
                    <button @click="closeEdit"
                            class="px-4 py-2 rounded-lg border border-border/50 hover:bg-card/70 transition-colors">
                        Schließen
                    </button>
                    <button @click="saveEdit"
                            class="px-4 py-2 rounded-lg bg-primary text-white hover:bg-primary/90 transition-colors shadow-md hover:shadow-lg">
                        Stammdaten speichern
                    </button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/layouts/AuthLayout.vue'
import { ref, onMounted } from 'vue'

const rows = ref(null)
const form = ref({ name:'', start_amount:0, is_active:true })

async function load(page=1){
    const { data } = await window.axios.get('/user/api/bonus-hunts', { params: { page } })
    rows.value = data
}

async function create(){
    await window.axios.post('/user/api/bonus-hunts', form.value)
    form.value = { name:'', start_amount:0, is_active:true }
    await load(1)
}

async function setActive(h, val){
    await window.axios.put(`/user/api/bonus-hunts/${h.id}`, { is_active: !!val })
    await load(rows.value?.current_page || 1)
}

async function copyLink(id) {
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

        // Erfolgsmeldung mit Toast statt alert()
        showToast('Link in die Zwischenablage kopiert!', 'success')
    } catch (e) {
        console.error(e)
        showToast('Kopieren fehlgeschlagen: ' + (e?.response?.data?.message ?? e.message), 'error')
    }
}

async function del(h){
    if (!confirm('Wirklich löschen?')) return
    await window.axios.delete(`/user/api/bonus-hunts/${h.id}`)
    await load(rows.value?.current_page || 1)
    showToast('Bonus Hunt erfolgreich gelöscht', 'success')
}

/* -------- Bearbeiten + Slots -------- */
const edit = ref({ open:false, id:null, data:{ name:'', start_amount:0, is_active:false } })
const entries = ref([])

const slotSearch = ref({ q: '', bet: null })
const slotRows = ref([])
const selSlots = ref([])
const slotPages = ref({ page: 1, prev: false, next: false })

async function openEdit(h){
    edit.value = { open:true, id:h.id, data:{ name:h.name, start_amount:h.start_amount, is_active:h.is_active } }
    await awaitLoadEntries()
    await searchSlots(1)
}
function closeEdit(){ edit.value.open = false }

async function saveEdit(){
    await window.axios.put(`/user/api/bonus-hunts/${edit.value.id}`, edit.value.data)
    closeEdit()
    await load(rows.value?.current_page || 1)
    showToast('Änderungen gespeichert', 'success')
}

async function awaitLoadEntries(){
    const { data } = await window.axios.get(`/user/api/bonus-hunts/${edit.value.id}/entries`)
    entries.value = data
}

async function searchSlots(page=1){
    const { data } = await window.axios.get('/user/api/slots/search', {
        params: { s: slotSearch.value.q, page }
    })
    slotRows.value = data.data
    slotPages.value = { page: data.current_page, prev: !!data.prev_page_url, next: !!data.next_page_url }
}

function toggleAllSlots(ev){
    if (ev.target.checked) selSlots.value = slotRows.value.map(s=>s.id)
    else selSlots.value = []
}

async function addSlot(slotId){
    await window.axios.post(`/user/api/bonus-hunts/${edit.value.id}/entries`, {
        slot_id: slotId,
        bet: slotSearch.value.bet ?? null
    })
    await awaitLoadEntries()
    showToast('Slot hinzugefügt', 'success')
}

async function bulkAddSlots(){
    if (selSlots.value.length === 0) return
    const bet = slotSearch.value.bet ?? null
    for (const id of selSlots.value) {
        try {
            await window.axios.post(`/user/api/bonus-hunts/${edit.value.id}/entries`, { slot_id: id, bet })
        } catch(e) { /* Duplikate ignorieren */ }
    }
    selSlots.value = []
    await awaitLoadEntries()
    showToast(`${selSlots.value.length} Slots hinzugefügt`, 'success')
}

async function saveEntry(e){
    await window.axios.put(`/user/api/bonus-hunts/${edit.value.id}/entries/${e.id}`, {
        position: e.position,
        bet: e.bet ?? 0,
        win: e.win ?? null,
        x_value: e.x_value ?? null,
        bonus_bought: !!e.bonus_bought,
        bonus_cost: e.bonus_bought ? (e.bonus_cost ?? null) : null,
    })
}

async function removeEntry(e){
    if (!confirm('Eintrag entfernen?')) return
    await window.axios.delete(`/user/api/bonus-hunts/${edit.value.id}/entries/${e.id}`)
    await awaitLoadEntries()
    showToast('Eintrag entfernt', 'success')
}

/* Toast Notification */
function showToast(message, type = 'info') {
    // Hier würde eine echte Toast-Implementierung stehen
    console.log(`[${type}] ${message}`)
}

onMounted(() => load(1))
</script>

<style scoped>
/* Animationen */
@keyframes fade-in {
    from { opacity: 0; }
    to { opacity: 1; }
}

.animate-fade-in {
    animation: fade-in 0.2s ease-out;
}

/* Custom Scrollbar für Modal */
.modal-content::-webkit-scrollbar {
    width: 8px;
}

.modal-content::-webkit-scrollbar-track {
    background: rgba(var(--border)/0.2);
    border-radius: 4px;
}

.modal-content::-webkit-scrollbar-thumb {
    background: rgba(var(--muted)/0.4);
    border-radius: 4px;
}

.modal-content::-webkit-scrollbar-thumb:hover {
    background: rgba(var(--muted)/0.6);
}
</style>

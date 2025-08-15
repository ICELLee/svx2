<template>
    <AuthenticatedLayout :page="$page">
        <template #header>
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-transparent bg-clip-text bg-[linear-gradient(90deg,rgb(var(--color-primary)),rgb(var(--color-accent)))]">
                    Slots (Admin)
                </h1>
                <div class="flex gap-2">
                    <!-- Suche -->
                    <div class="relative">
                        <input
                            v-model="filters.s"
                            placeholder="Suche Name/Key/Provider"
                            class="input w-64 pl-9"
                            @keyup.enter="load(1)"
                        />
                        <svg class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 opacity-70" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M21 21l-4.3-4.3M10 18a8 8 0 110-16 8 8 0 010 16z"/>
                        </svg>
                    </div>

                    <!-- Filter -->
                    <select v-model="filters.provider" class="input" @change="load(1)">
                        <option value="">
                            Alle Provider ({{ providers.reduce((n,p)=>n+(p.count||0),0) }})
                        </option>
                        <option v-for="p in providers" :key="p.provider" :value="p.provider">
                            {{ p.provider }} ({{ p.count }})
                        </option>
                    </select>

                    <select v-model="filters.active" class="input" @change="load(1)">
                        <option value="all">Alle</option>
                        <option value="1">Aktiv</option>
                        <option value="0">Inaktiv</option>
                    </select>

                    <select v-model="filters.sort" class="input" @change="load(1)">
                        <option value="updated_at">Updated</option>
                        <option value="name">Name (A-Z/Z-A)</option>
                    </select>

                    <select v-model="filters.dir" class="input" @change="load(1)">
                        <option value="desc">↓ absteigend</option>
                        <option value="asc">↑ aufsteigend</option>
                    </select>

                    <!-- Actions -->
                    <button class="btn-secondary" @click="openCreate">
                        <span class="i">＋</span>
                        Neu
                    </button>
                    <button class="btn-primary" @click="openImport">
                        <span class="i">⇪</span>
                        Import URL
                    </button>
                </div>
            </div>
        </template>

        <!-- Table -->
        <div class="rounded-2xl border border-[rgb(var(--color-border))] bg-[rgb(var(--color-card)/.75)] backdrop-blur overflow-hidden shadow-[0_10px_40px_-12px_rgba(124,58,237,.25)]">
            <table class="w-full text-sm">
                <thead class="bg-[rgb(var(--color-card))] text-[rgb(var(--color-text))] border-b border-[rgb(var(--color-border))] sticky top-0 z-10">
                <tr>
                    <th class="th">Bild</th>
                    <th class="th">Name</th>
                    <th class="th">Key</th>
                    <th class="th">Provider</th>
                    <th class="th">RTP</th>
                    <th class="th">Max Win</th>
                    <th class="th">Aktiv</th>
                    <th class="th text-right">Aktionen</th>
                </tr>
                </thead>
                <tbody>
                <tr
                    v-for="s in rows?.data ?? []"
                    :key="s.id"
                    class="border-t border-[rgb(var(--color-border))] hover:bg-[rgb(var(--color-primary)/.05)] transition-colors"
                >
                    <td class="td">
                        <div class="relative h-10 w-16 overflow-hidden rounded-lg border border-[rgb(var(--color-border))] bg-[rgb(var(--color-card))]">
                            <img :src="s.image_proxy" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-[1.03]" />
                            <!-- subtle overlay on hover -->
                            <span class="pointer-events-none absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300" style="background: radial-gradient(120px 80px at 70% 30%, rgba(124,58,237,.18), transparent 60%);"></span>
                        </div>
                    </td>
                    <td class="td">
                        <div class="font-medium">{{ s.name }}</div>
                    </td>
                    <td class="td text-[rgb(var(--color-muted))] font-mono">
                        {{ s.key }}
                    </td>
                    <td class="td">
                        <span v-if="s.provider" class="badge">{{ s.provider }}</span>
                        <span v-else class="text-[rgb(var(--color-muted))]">—</span>
                    </td>
                    <td class="td">{{ s.rtp || '—' }}</td>
                    <td class="td">{{ s.max_win || '—' }}</td>
                    <td class="td">
              <span
                  :class="s.is_active ? 'status-ok' : 'status-bad'"
              >
                {{ s.is_active ? 'Aktiv' : 'Inaktiv' }}
              </span>
                    </td>
                    <td class="td text-right whitespace-nowrap">
                        <button class="btn-link" @click="openEdit(s)">Bearb.</button>
                        <button class="btn-link" @click="toggle(s)">Toggle</button>
                        <button class="btn-danger" @click="del(s)">Löschen</button>
                    </td>
                </tr>

                <tr v-if="(rows?.data ?? []).length === 0">
                    <td colspan="8" class="p-6 text-center text-[rgb(var(--color-muted))]">Keine Einträge.</td>
                </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex justify-between items-center mt-3">
            <div class="text-xs text-[rgb(var(--color-muted))]">
                Seite {{ rows?.current_page || 1 }} / {{ rows?.last_page || 1 }} – {{ rows?.total || 0 }} Einträge
            </div>
            <div class="flex gap-2">
                <button class="btn-secondary" :disabled="!rows?.prev_page_url" @click="load(rows.current_page - 1)">← Zurück</button>
                <button class="btn-secondary" :disabled="!rows?.next_page_url" @click="load(rows.current_page + 1)">Weiter →</button>
            </div>
        </div>

        <!-- Ambient Card (optional visual) -->
        <div class="mb-4 mt-4">
            <ThemeSmokeCard />
        </div>

        <!-- Modal: Create/Edit -->
        <div v-if="modal.open" class="fixed inset-0 z-50 bg-black/60 grid place-items-center p-4">
            <div class="w-full max-w-2xl rounded-2xl border border-[rgb(var(--color-border))] bg-[rgb(var(--color-card))] shadow-[0_30px_80px_-20px_rgba(124,58,237,.35)] p-4">
                <h3 class="font-semibold mb-3">
                    {{ modal.mode === 'create' ? 'Neuer Slot' : 'Slot bearbeiten' }}
                </h3>

                <form @submit.prevent="save">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="lbl">Name</label>
                            <input v-model="form.name" class="input" required />
                        </div>
                        <div>
                            <label class="lbl">Key</label>
                            <input v-model="form.key" class="input" required />
                        </div>
                        <div>
                            <label class="lbl">Provider</label>
                            <input v-model="form.provider" class="input" />
                        </div>
                        <div>
                            <label class="lbl">RTP</label>
                            <input v-model="form.rtp" class="input" placeholder="z.B. 96.38" />
                        </div>
                        <div>
                            <label class="lbl">Max Win</label>
                            <input v-model="form.max_win" class="input" placeholder="z.B. 12500x" />
                        </div>
                        <div class="col-span-2">
                            <label class="lbl">Image URL</label>
                            <input v-model="form.image_url" class="input" placeholder="https://..." />
                        </div>
                        <div>
                            <label class="lbl">Pers. Win (€)</label>
                            <input type="number" step="0.01" v-model.number="form.personal_win" class="input" />
                        </div>
                        <div>
                            <label class="lbl">Pers. Bet (€)</label>
                            <input type="number" step="0.01" v-model.number="form.personal_bet" class="input" />
                        </div>
                        <div>
                            <label class="lbl">Pers. X</label>
                            <input type="number" step="0.01" v-model.number="form.personal_x" class="input" />
                        </div>
                        <div>
                            <label class="lbl">Aktiv</label>
                            <select v-model="form.is_active" class="input">
                                <option :value="true">Aktiv</option>
                                <option :value="false">Inaktiv</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 mt-4">
                        <button type="button" class="btn-secondary" @click="closeModal">Abbrechen</button>
                        <button class="btn-primary" :disabled="busy">
                            {{ busy ? 'Speichern…' : 'Speichern' }}
                        </button>
                    </div>

                    <p v-if="err" class="text-[rgb(248_113_113)] text-sm mt-2">{{ err }}</p>
                </form>
            </div>
        </div>

        <!-- Modal: Import URL -->
        <div v-if="importModal" class="fixed inset-0 z-50 bg-black/60 grid place-items-center p-4">
            <div class="w-full max-w-xl rounded-2xl border border-[rgb(var(--color-border))] bg-[rgb(var(--color-card))] shadow-[0_30px_80px_-20px_rgba(124,58,237,.35)] p-4">
                <h3 class="font-semibold mb-3">Externer Import (JSON URL)</h3>
                <input v-model="importUrl" placeholder="https://cdn.example.com/slots.json" class="input w-full mb-3" />
                <div class="text-sm text-[rgb(var(--color-muted))] mb-3">
                    Erwartet wird ein JSON-Array mit Feldern: id(optional), name, key, provider, rtp, max_win, image_url, personal_win, personal_bet, personal_x.
                </div>
                <div class="flex justify-end gap-2">
                    <button class="btn-secondary" @click="importModal=false">Abbrechen</button>
                    <button class="btn-primary" @click="doImport" :disabled="busy">
                        {{ busy ? 'Importiere…' : 'Import starten' }}
                    </button>
                </div>
                <p v-if="msg" class="text-[rgb(134_239_172)] text-sm mt-2">{{ msg }}</p>
                <p v-if="err" class="text-[rgb(248_113_113)] text-sm mt-2">{{ err }}</p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/layouts/AuthLayout.vue'

export default {
    components: { AuthenticatedLayout },
    data() {
        return {
            providers: [],
            filters: { s:'', provider:'', active:'all', sort:'updated_at', dir:'desc', page:1 },
            rows: null,
            modal: { open:false, mode:'create', id:null },
            form: {
                name:'', key:'', provider:'', rtp:'', max_win:'', image_url:'',
                personal_win:null, personal_bet:null, personal_x:null, is_active:true,
            },
            busy:false, err:null, msg:null,
            importModal:false, importUrl:'',
        }
    },

    watch: {
        'filters.active': { handler() { this.fetchProviders() } },
    },

    async mounted() {
        await window.axios.get('/sanctum/csrf-cookie')
        await this.fetchProviders()
        await this.load(1)
    },

    methods: {
        async fetchProviders() {
            const { data } = await window.axios.get('/admin/api/slots/providers', {
                params: { active: this.filters.active }
            })
            this.providers = data
        },

        async load(page = 1) {
            this.filters.page = page
            const { data } = await window.axios.get('/admin/api/slots', { params: this.filters })
            this.rows = data
        },

        openCreate() {
            this.modal = { open:true, mode:'create', id:null }
            this.err=null; this.msg=null;
            this.form = { name:'', key:'', provider:'', rtp:'', max_win:'', image_url:'', personal_win:null, personal_bet:null, personal_x:null, is_active:true }
        },

        openEdit(s) {
            this.modal = { open:true, mode:'edit', id:s.id }
            this.err=null; this.msg=null;
            this.form = { ...s }
        },

        closeModal() { this.modal.open = false },

        async save() {
            this.busy = true; this.err=null; this.msg=null;
            try {
                if (this.modal.mode === 'create') {
                    await window.axios.post('/admin/api/slots', this.form)
                } else {
                    await window.axios.put(`/admin/api/slots/${this.modal.id}`, this.form)
                }
                this.msg = 'Gespeichert'
                await this.load(this.rows?.current_page ?? 1)
                await this.fetchProviders()
                this.closeModal()
            } catch (e) {
                this.err = e?.response?.data?.message ?? e.message
            } finally { this.busy = false }
        },

        async toggle(s) {
            await window.axios.patch(`/admin/api/slots/${s.id}/toggle`)
            await this.load(this.rows?.current_page ?? 1)
            await this.fetchProviders()
        },

        async del(s) {
            if (!confirm(`Slot löschen: ${s.name}?`)) return
            await window.axios.delete(`/admin/api/slots/${s.id}`)
            await this.load(this.rows?.current_page ?? 1)
            await this.fetchProviders()
        },

        openImport() { this.importModal = true; this.msg=null; this.err=null; this.importUrl='' },

        async doImport() {
            this.busy = true; this.err=null; this.msg=null;
            try {
                const { data } = await window.axios.post('/admin/api/slots/import-url', { url: this.importUrl })
                this.msg = `Importiert/aktualisiert: ${data.imported}`
                await this.load(1)
                await this.fetchProviders()
            } catch (e) {
                this.err = e?.response?.data?.error ?? e.message
            } finally { this.busy = false }
        },
    }
}
</script>

<style scoped>
@reference "tailwindcss";

/* cells */
.th { @apply p-3 text-left font-medium text-[rgb(var(--color-muted))]; }
.td { @apply p-2 align-middle; }

/* inputs & buttons – tokenbasiert */
.input {
    @apply px-3 py-2 rounded-xl border outline-none transition
    border-[rgb(var(--color-border))] bg-[rgb(var(--color-card))] text-[rgb(var(--color-text))]
    focus:ring-2 focus:ring-[rgb(var(--color-primary)/.35)];
}
.btn-primary {
    @apply inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-[rgb(255_255_255)]
    bg-[rgb(var(--color-primary)/.85)] hover:bg-[rgb(var(--color-primary))]
    transition shadow-[0_8px_24px_-10px_rgba(124,58,237,.5)];
}
.btn-secondary {
    @apply inline-flex items-center gap-1.5 px-3 py-2 rounded-xl
    border border-[rgb(var(--color-border))] text-[rgb(var(--color-text))]
    bg-[rgb(var(--color-card))] hover:bg-[rgb(var(--color-primary)/.10)] transition;
}
.btn-link {
    @apply px-2 py-1 underline decoration-dotted text-[rgb(var(--color-primary))]
    hover:text-[rgb(var(--color-accent))] transition;
}
.btn-danger {
    @apply px-2 py-1 underline text-[rgb(248_113_113)] hover:text-[rgb(254_202_202)] transition;
}

/* status badges */
.status-ok  { @apply inline-flex items-center px-2 py-0.5 rounded-full text-xs
bg-[rgb(34_197_94/0.15)] text-[rgb(134_239_172)] border border-[rgb(34_197_94/0.35)]; }
.status-bad { @apply inline-flex items-center px-2 py-0.5 rounded-full text-xs
bg-[rgb(239_68_68/0.15)] text-[rgb(254_202_202)] border border-[rgb(239_68_68/0.35)]; }

/* small badge */
.badge {
    @apply inline-flex items-center px-2 py-0.5 rounded-lg text-xs
    border border-[rgb(var(--color-border))] bg-[rgb(var(--color-card))]
    hover:bg-[rgb(var(--color-primary)/.08)] transition;
}

/* tiny icon bubble inside buttons */
.i {
    @apply inline-flex items-center justify-center h-5 w-5 rounded-md
    bg-[rgb(var(--color-primary)/.12)];
}

/* reduced motion */
@media (prefers-reduced-motion: reduce) {
    * { animation: none !important; transition-duration: .01ms !important }
}
</style>

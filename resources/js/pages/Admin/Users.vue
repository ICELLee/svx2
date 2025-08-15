<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between w-full">
                <h1 class="text-xl font-bold text-transparent bg-clip-text bg-[linear-gradient(90deg,rgb(var(--color-primary)),rgb(var(--color-accent)))]">
                    Kunden-Accounts
                </h1>
                <div class="hidden sm:flex items-center gap-2 text-[11px] text-[rgb(var(--color-muted))]">
                    <span class="px-2 py-1 rounded-lg border border-[rgb(var(--color-border))]">Verwaltung</span>
                </div>
            </div>
        </template>

        <!-- Suche / Aktionen -->
        <div class="mb-4 flex items-center gap-2">
            <div class="relative w-full sm:w-80">
                <input
                    v-model="q"
                    @keydown.enter="reload"
                    placeholder="Suche Name / E-Mail / ID"
                    class="w-full pl-9 pr-3 py-2 rounded-xl border border-[rgb(var(--color-border))] bg-[rgb(var(--color-card))] outline-none
                 focus:ring-2 focus:ring-[rgb(var(--color-primary)/.35)] transition"
                />
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 opacity-70" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M21 21l-4.3-4.3M10 18a8 8 0 110-16 8 8 0 010 16z"/>
                </svg>
            </div>
            <button
                @click="reload"
                class="px-3 py-2 rounded-xl border border-[rgb(var(--color-border))] bg-[rgb(var(--color-card))]
               hover:bg-[rgb(var(--color-primary)/.10)] transition"
            >
                Suchen
            </button>
        </div>

        <!-- Tabelle -->
        <div class="overflow-x-auto rounded-2xl border border-[rgb(var(--color-border))] bg-[rgb(var(--color-card)/.75)] backdrop-blur">
            <table class="min-w-full text-sm">
                <thead class="sticky top-0 bg-[rgb(var(--color-card))] border-b border-[rgb(var(--color-border))]">
                <tr class="text-left">
                    <th class="px-4 py-3 font-medium text-[rgb(var(--color-muted))]">ID</th>
                    <th class="px-4 py-3 font-medium text-[rgb(var(--color-muted))]">Nutzer</th>
                    <th class="px-4 py-3 font-medium text-[rgb(var(--color-muted))]">E-Mail</th>
                    <th class="px-4 py-3 font-medium text-[rgb(var(--color-muted))]">Rollen</th>
                    <th class="px-4 py-3 font-medium text-right text-[rgb(var(--color-muted))]">Aktion</th>
                </tr>
                </thead>
                <tbody>
                <tr
                    v-for="u in users.data"
                    :key="u.id"
                    class="border-t border-[rgb(var(--color-border))] hover:bg-[rgb(var(--color-primary)/.05)] transition-colors"
                >
                    <td class="px-4 py-3">{{ u.id }}</td>

                    <td class="px-4 py-3">
                        <Link :href="`/admin/users/${u.id}`" class="rounded px-1 underline decoration-dotted hover:bg-[rgb(var(--color-primary)/.10)]">
                            {{ u.name }}
                        </Link>
                        <div class="text-xs text-[rgb(var(--color-muted))]">{{ u.public_id }}</div>
                    </td>

                    <td class="px-4 py-3">{{ u.email }}</td>

                    <td class="px-4 py-3">
                        <div class="flex flex-wrap gap-3">
                            <label
                                v-for="r in allRoles"
                                :key="r"
                                class="inline-flex items-center gap-2 px-2 py-1 rounded-lg border border-[rgb(var(--color-border))] bg-[rgb(var(--color-card))]
                         hover:bg-[rgb(var(--color-primary)/.08)] transition"
                            >
                                <input
                                    type="checkbox"
                                    :value="r"
                                    v-model="form[u.id].roles"
                                    class="rounded border-[rgb(var(--color-border))] bg-[rgb(var(--color-card))] focus:ring-[rgb(var(--color-primary)/.35)]"
                                />
                                <span class="text-xs">{{ r }}</span>
                            </label>
                        </div>
                    </td>

                    <td class="px-4 py-3 text-right">
                        <button
                            @click="save(u.id)"
                            class="px-3 py-2 rounded-xl border border-[rgb(var(--color-border))] bg-[rgb(var(--color-card))]
                       hover:bg-[rgb(var(--color-primary)/.10)] transition"
                        >
                            Speichern
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4 flex gap-2 items-center">
            <button
                v-if="users.prev_page_url"
                @click="go(users.current_page - 1)"
                class="px-3 py-2 rounded-xl border border-[rgb(var(--color-border))] bg-[rgb(var(--color-card))]
               hover:bg-[rgb(var(--color-primary)/.10)] transition"
            >Zurück</button>

            <button
                v-if="users.next_page_url"
                @click="go(users.current_page + 1)"
                class="px-3 py-2 rounded-xl border border-[rgb(var(--color-border))] bg-[rgb(var(--color-card))]
               hover:bg-[rgb(var(--color-primary)/.10)] transition"
            >Weiter</button>

            <div class="text-xs text-[rgb(var(--color-muted))] ml-auto">
                Seite {{ users.current_page }} / {{ users.last_page }}
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/layouts/AuthLayout.vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import { reactive, ref } from 'vue'

const page = usePage()
const users    = page.props.users
const allRoles = page.props.allRoles
const q        = ref(page.props.filters?.q ?? '')

// lokaler Form-State absichern
const form = reactive({})
users.data.forEach(u => {
    form[u.id] = { roles: Array.isArray(u.roles) ? [...u.roles] : [] }
})

function save(id) {
    router.put(`/admin/users/${id}/roles`, { roles: form[id].roles }, {
        preserveScroll: true,
        onSuccess: () => router.reload({ only: ['users'] }),
        onError: (errors) => alert(Object.values(errors).join('\n')),
    })
}
function reload() {
    router.get('/admin/users', { q: q.value }, { preserveState: true, replace: true })
}
function go(p) {
    router.get('/admin/users', { q: q.value, page: p }, { preserveState: true, replace: true })
}
</script>

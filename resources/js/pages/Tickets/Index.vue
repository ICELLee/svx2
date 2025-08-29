<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h1 class="text-xl font-bold">Meine Tickets</h1>
                <Link href="/tickets/create" class="btn btn-primary">+ Neues Ticket</Link>
            </div>
        </template>

        <!-- Suche -->
        <div class="mb-4">
            <input v-model="filters.q" @keyup.enter="search" placeholder="Suchen…" class="input w-full max-w-sm" />
        </div>

        <!-- Liste -->
        <table class="w-full border text-sm">
            <thead>
            <tr class="bg-[rgb(var(--color-card))]">
                <th class="p-2 text-left">#</th>
                <th class="p-2">Titel</th>
                <th class="p-2">Status</th>
                <th class="p-2">Priorität</th>
                <th class="p-2">Erstellt</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="t in tickets.data" :key="t.id" class="hover:bg-[rgb(var(--color-card)/.5)]">
                <td class="p-2">#{{ t.id }}</td>
                <td class="p-2">
                    <Link :href="`/tickets/${t.id}`" class="text-primary underline">{{ t.title }}</Link>
                </td>
                <td class="p-2">{{ t.status }}</td>
                <td class="p-2 capitalize">{{ t.priority }}</td>
                <td class="p-2">{{ new Date(t.created_at).toLocaleString() }}</td>
            </tr>
            </tbody>
        </table>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/layouts/AuthLayout.vue'
import { Link, router } from '@inertiajs/vue3'

defineProps({ tickets:Object, filters:Object })

function search() {
    router.get('/tickets', { q: filters.q }, { preserveState:true })
}
</script>

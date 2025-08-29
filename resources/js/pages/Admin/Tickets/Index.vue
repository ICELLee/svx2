<template>
    <AuthenticatedLayout>
        <template #header>
            <h1 class="text-2xl font-bold text-gradient drop-shadow-neon">
                Admin Tickets
            </h1>
        </template>

        <!-- Haupt-Card -->
        <div class="card rounded-2xl shadow-neon p-6 space-y-6 card-hover">

            <!-- Suche -->
            <div class="flex flex-col sm:flex-row items-center gap-3">
                <input v-model="filters.q"
                       @keyup.enter="search"
                       placeholder="🔍 Ticket suchen…"
                       class="input w-full sm:max-w-sm neon-input"
                />
                <button @click="search" class="btn btn-primary neon-btn">
                    Suchen
                </button>
            </div>

            <!-- Desktop: Tabelle -->
            <div class="hidden md:block overflow-x-auto rounded-xl">
                <table class="min-w-full text-sm border-separate border-spacing-y-3">
                    <thead>
                    <tr class="bg-card text-muted">
                        <th class="p-3 text-left rounded-tl-lg">#</th>
                        <th class="p-3 text-left">Titel</th>
                        <th class="p-3 text-center">Status</th>
                        <th class="p-3 text-center">Priorität</th>
                        <th class="p-3 text-center rounded-tr-lg">Erstellt</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="t in tickets.data" :key="t.id"
                        class="card bg-card shadow-neon-sm rounded-xl hover:shadow-neon-lg transition-smooth cursor-pointer"
                    >
                        <td class="p-3 font-mono rounded-l-lg">#{{ t.id }}</td>
                        <td class="p-3">
                            <Link :href="`/admin/tickets/${t.id}`"
                                  class="text-gradient font-semibold underline hover-scale">
                                {{ t.title }}
                            </Link>
                            <div class="text-xs text-muted mt-1">
                                {{ preview(t.message) }}
                            </div>
                        </td>
                        <td class="p-3 text-center">
                            <span :class="statusColor(t.status)"
                                  class="px-2 py-1 rounded-full text-xs font-medium shadow-neon-sm">
                                {{ t.status }}
                            </span>
                        </td>
                        <td class="p-3 text-center capitalize font-semibold"
                            :class="priorityColor(t.priority)">
                            {{ t.priority }}
                        </td>
                        <td class="p-3 text-center text-muted rounded-r-lg">
                            {{ new Date(t.created_at).toLocaleString() }}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <!-- Mobile: Karten -->
            <div class="md:hidden space-y-4">
                <div v-for="t in tickets.data" :key="t.id"
                     class="card p-4 rounded-xl shadow-neon-sm hover:shadow-neon-lg transition-smooth border border-border/50">
                    <div class="flex justify-between items-center mb-2">
                        <Link :href="`/admin/tickets/${t.id}`"
                              class="font-semibold text-gradient underline">
                            #{{ t.id }} – {{ t.title }}
                        </Link>
                        <span :class="statusColor(t.status)"
                              class="px-2 py-1 text-xs rounded-full font-medium shadow-neon-sm">
                            {{ t.status }}
                        </span>
                    </div>
                    <div class="text-sm text-muted mb-2 line-clamp-2">
                        {{ preview(t.message) }}
                    </div>
                    <div class="flex justify-between items-center text-xs">
                        <span :class="priorityColor(t.priority)"
                              class="font-semibold capitalize">
                            {{ t.priority }}
                        </span>
                        <span>{{ new Date(t.created_at).toLocaleString() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/layouts/AuthLayout.vue'
import { Link, router } from '@inertiajs/vue3'

defineProps({ tickets:Object, filters:Object })

function preview(text) {
    if (!text) return ''
    return text.split(' ').slice(0,20).join(' ') + '...'
}
function search() {
    router.get('/admin/tickets', { q: filters.q }, { preserveState:true })
}

function statusColor(status) {
    const map = {
        offen: 'bg-purple-600/20 text-purple-300 border border-purple-500 shadow-neon-purple',
        geschlossen: 'bg-green-600/20 text-green-300 border border-green-500 shadow-neon-green',
        warte_info: 'bg-orange-600/20 text-orange-300 border border-orange-500 shadow-neon-orange'
    }
    return map[status] || 'bg-gray-700 text-gray-300 border border-gray-500'
}

function priorityColor(priority) {
    const map = {
        green: 'text-green-400 drop-shadow-neon-green',
        orange: 'text-orange-400 drop-shadow-neon-orange',
        red: 'text-red-400 drop-shadow-neon-red',
        gold: 'text-amber-400 drop-shadow-neon-gold'
    }
    return map[priority] || 'text-gray-400'
}
</script>

<style scoped>
/* Neon Effects */
.shadow-neon {
    box-shadow: 0 0 15px rgba(124,58,237,.6), 0 0 30px rgba(251,146,60,.4);
}
.shadow-neon-sm {
    box-shadow: 0 0 8px rgba(124,58,237,.4);
}
.shadow-neon-lg {
    box-shadow: 0 0 20px rgba(124,58,237,.8), 0 0 40px rgba(251,146,60,.6);
}
.drop-shadow-neon {
    text-shadow: 0 0 6px rgba(124,58,237,.8), 0 0 12px rgba(251,146,60,.6);
}

/* Neon Badges */
.shadow-neon-purple { box-shadow: 0 0 8px rgba(124,58,237,.8); }
.shadow-neon-orange { box-shadow: 0 0 8px rgba(251,146,60,.8); }
.shadow-neon-green { box-shadow: 0 0 8px rgba(34,197,94,.8); }
.shadow-neon-red { box-shadow: 0 0 8px rgba(239,68,68,.8); }
.shadow-neon-gold { box-shadow: 0 0 8px rgba(245,158,11,.8); }

/* Inputs / Buttons */
.neon-input {
    box-shadow: 0 0 6px rgba(124,58,237,.4);
}
.neon-btn {
    box-shadow: 0 0 12px rgba(124,58,237,.6), 0 0 25px rgba(251,146,60,.5);
}
</style>

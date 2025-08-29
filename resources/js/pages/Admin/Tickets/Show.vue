<template>
    <AuthenticatedLayout>
        <!-- Header -->
        <template #header>
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl font-bold text-gradient transition-smooth hover:translate-x-1">
                        Ticket #{{ ticket.id }}
                    </h1>
                    <span class="text-xs px-2 py-1 rounded-full neon-badge animate-pulse">
                        {{ ticket.title }}
                    </span>
                </div>
                <div class="text-sm text-muted">
                    Erstellt: {{ new Date(ticket.created_at).toLocaleString() }}
                </div>
            </div>
        </template>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Ticket Meta -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="neon-card p-4 rounded-xl">
                    <div class="mb-2">
                        <span class="text-muted">Status:</span>
                        <span :class="statusColor(ticket.status)" class="px-3 py-1 rounded-full text-sm font-medium">
                            {{ ticket.status }}
                        </span>
                    </div>
                    <div>
                        <span class="text-muted">Priorität:</span>
                        <span :class="priorityColor(ticket.priority)" class="px-3 py-1 rounded-full text-sm font-medium">
                            {{ ticket.priority }}
                        </span>
                    </div>
                </div>

                <div class="neon-card p-4 rounded-xl">
                    <div class="mb-2">
                        <span class="text-muted">Ersteller:</span>
                        <span class="font-medium">{{ ticket.user?.name || 'Unbekannt' }}</span>
                    </div>
                    <div>
                        <span class="text-muted">Zugewiesen an:</span>
                        <span class="font-medium">{{ ticket.assigned_to?.name || 'Niemand' }}</span>
                    </div>
                </div>
            </div>

            <!-- Aktionen -->
            <div class="neon-card p-4 rounded-xl">
                <h3 class="text-lg font-semibold mb-3 text-gradient">Ticket-Aktionen</h3>
                <div class="flex flex-col sm:flex-row gap-3">
                    <button @click="claim" class="btn btn-primary hover-scale">
                        Ticket claimen
                    </button>

                    <select v-model="updateForm.priority" class="input">
                        <option value="green">Niedrig (Grün)</option>
                        <option value="orange">Mittel (Orange)</option>
                        <option value="red">Hoch (Rot)</option>
                        <option value="gold">Kritisch (Gold)</option>
                    </select>

                    <select v-model="updateForm.status" class="input">
                        <option value="offen">Offen</option>
                        <option value="geschlossen">Geschlossen</option>
                        <option value="warte_info">Warte auf Info</option>
                    </select>

                    <button @click="update" class="btn btn-primary hover-scale">
                        Update
                    </button>
                </div>
            </div>

            <!-- Nachrichten Verlauf -->
            <div>
                <h3 class="text-lg font-semibold text-gradient mb-3">Konversationsverlauf</h3>

                <div v-for="m in ticket.messages" :key="m.id"
                     class="neon-card p-4 rounded-lg">
                    <div class="flex justify-between items-start mb-2">
                        <div class="font-medium">{{ m.user.name }}</div>
                        <div class="text-xs text-muted">{{ new Date(m.created_at).toLocaleString() }}</div>
                    </div>

                    <div class="text-text whitespace-pre-line">{{ m.message }}</div>

                    <div v-if="m.attachments && m.attachments.length" class="mt-2 text-xs space-y-1">
                        <div v-for="(a, i) in m.attachments" :key="i">
                            <a :href="`/storage/${a}`" target="_blank" class="underline text-primary hover:text-accent transition-smooth">
                                📎 {{ a.split('/').pop() }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reply Formular -->
            <form @submit.prevent="reply" class="neon-card p-4 rounded-xl">
                <h3 class="text-lg font-semibold mb-3 text-gradient">Antworten</h3>

                <textarea v-model="replyForm.message"
                          class="input w-full"
                          placeholder="Schreiben Sie Ihre Antwort..."
                          rows="4"></textarea>

                <div class="flex justify-between items-center mt-3">
                    <div class="text-sm text-muted">
                        Drücken Sie Enter zum Absenden
                    </div>
                    <button type="submit" class="btn btn-primary hover-scale" :disabled="replyForm.processing">
                        Absenden
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/layouts/AuthLayout.vue'
import { useForm, router } from '@inertiajs/vue3'
import { reactive } from 'vue'

const props = defineProps({ ticket: Object })
const ticket = reactive({ ...props.ticket })

const updateForm = useForm({
    priority: ticket.priority,
    status: ticket.status
})

const replyForm = useForm({ message: '' })

function statusColor(status) {
    const colors = {
        'offen': 'bg-yellow-500/20 text-yellow-400 border border-yellow-400/40',
        'geschlossen': 'bg-green-500/20 text-green-400 border border-green-400/40',
        'warte_info': 'bg-blue-500/20 text-blue-400 border border-blue-400/40'
    }
    return colors[status] || 'bg-gray-500/20 text-gray-300 border border-gray-400/40'
}

function priorityColor(priority) {
    const colors = {
        'green': 'text-green-400',
        'orange': 'text-orange-400',
        'red': 'text-red-400',
        'gold': 'text-amber-400'
    }
    return colors[priority] || 'text-gray-300'
}

function claim() {
    router.post(`/admin/tickets/${ticket.id}/claim`, {}, {
        onSuccess: () => router.reload({ only: ['ticket'] })
    })
}

function update() {
    updateForm.put(`/admin/tickets/${ticket.id}`)
}

function reply() {
    replyForm.post(`/admin/tickets/${ticket.id}/reply`, {
        preserveScroll: true,
        onSuccess: () => replyForm.reset()
    })
}
</script>

<style scoped>
.neon-card {
    background: rgb(var(--color-card) / 0.1);
    border: 1px solid rgba(124, 58, 237, 0.5); /* lila Rand */
    box-shadow:
        0 0 10px rgba(124,58,237,.4),
        0 0 20px rgba(251,146,60,.25);
    transition: transform .3s ease, box-shadow .3s ease;
}
.neon-card:hover {
    transform: translateY(-3px) scale(1.01);
    box-shadow:
        0 0 15px rgba(124,58,237,.6),
        0 0 30px rgba(251,146,60,.35);
}
.neon-badge {
    background: rgba(124,58,237,0.2);
    border: 1px solid rgba(251,146,60,0.6);
    box-shadow:
        0 0 5px rgba(124,58,237,.6),
        0 0 10px rgba(251,146,60,.4);
}
</style>

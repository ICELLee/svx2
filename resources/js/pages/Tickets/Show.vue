<template>
    <AuthenticatedLayout>
        <template #header>
            <h1 class="text-xl font-bold">Ticket #{{ ticket.id }} – {{ ticket.title }}</h1>
        </template>

        <div class="mb-4">
            <div>Status: <span class="font-semibold">{{ ticket.status }}</span></div>
            <div>Priorität: <span class="font-semibold">{{ ticket.priority }}</span></div>
            <div>Grund: {{ ticket.reason }}</div>
        </div>

        <!-- Nachrichtenverlauf -->
        <div class="space-y-4 mb-6">
            <div v-for="m in ticket.messages" :key="m.id"
                 :class="[m.user_id===ticket.user_id ? 'bg-blue-500/10' : 'bg-purple-500/10','p-3 rounded-lg']">
                <div class="text-xs text-[rgb(var(--color-muted))]">
                    {{ m.user.name }} · {{ new Date(m.created_at).toLocaleString() }}
                </div>
                <div>{{ m.message }}</div>
            </div>
        </div>
        <div v-if="m.attachments && m.attachments.length" class="mt-2 text-xs space-y-1">
            <div v-for="(a, i) in m.attachments" :key="i">
                <a :href="`/storage/${a}`" target="_blank" class="underline text-primary">
                    📎 {{ a.split('/').pop() }}
                </a>
            </div>
        </div>
        <!-- Antwort -->
        <form @submit.prevent="reply" class="space-y-3">
            <textarea v-model="form.message" rows="4" class="input w-full" placeholder="Antwort schreiben…"></textarea>
            <FileDropzone v-model="form.attachments" />
            <button class="btn btn-primary">Antwort senden</button>
        </form>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/layouts/AuthLayout.vue'
import { useForm } from '@inertiajs/vue3'
defineProps({ ticket:Object })

const form = useForm({ message:'', attachments:[] })

function handleFiles(e) {
    form.attachments = Array.from(e.target.files)
}

function reply() {
    form.post(`/tickets/${ticket.id}/reply`)
}
</script>

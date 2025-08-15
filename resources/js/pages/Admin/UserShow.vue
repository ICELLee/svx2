<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between w-full">
                <h1 class="text-xl font-bold text-transparent bg-clip-text bg-[linear-gradient(90deg,rgb(var(--color-primary)),rgb(var(--color-accent)))]">
                    User bearbeiten
                </h1>
                <Link
                    href="/admin/users"
                    class="px-3 py-2 rounded-xl border border-[rgb(var(--color-border))] bg-[rgb(var(--color-card))]
                 hover:bg-[rgb(var(--color-primary)/.10)] transition"
                >
                    Zurück
                </Link>
            </div>
        </template>

        <div class="grid gap-4 lg:grid-cols-3">
            <!-- Stammdaten -->
            <div class="lg:col-span-2 p-4 rounded-2xl border border-[rgb(var(--color-border))] bg-[rgb(var(--color-card)/.8)] backdrop-blur">
                <form @submit.prevent="submitProfile" class="space-y-4">
                    <div class="grid gap-3 sm:grid-cols-2">
                        <div>
                            <label class="text-xs text-[rgb(var(--color-muted))]">Öffentliche ID</label>
                            <input
                                v-model="form.public_id"
                                class="w-full px-3 py-2 rounded-xl border border-[rgb(var(--color-border))] bg-[rgb(var(--color-card))] outline-none
                       focus:ring-2 focus:ring-[rgb(var(--color-primary)/.35)] transition"
                            />
                            <p class="text-xs text-[rgb(var(--color-muted))] mt-1">Format: SVX-U0000000</p>
                            <p v-if="form.errors.public_id" class="text-xs text-[rgb(248_113_113)] mt-1">{{ form.errors.public_id }}</p>
                        </div>

                        <div>
                            <label class="text-xs text-[rgb(var(--color-muted))]">Name</label>
                            <input
                                v-model="form.name"
                                class="w-full px-3 py-2 rounded-xl border border-[rgb(var(--color-border))] bg-[rgb(var(--color-card))] outline-none
                       focus:ring-2 focus:ring-[rgb(var(--color-primary)/.35)] transition"
                            />
                            <p v-if="form.errors.name" class="text-xs text-[rgb(248_113_113)] mt-1">{{ form.errors.name }}</p>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="text-xs text-[rgb(var(--color-muted))]">E-Mail</label>
                            <input
                                v-model="form.email" type="email"
                                class="w-full px-3 py-2 rounded-xl border border-[rgb(var(--color-border))] bg-[rgb(var(--color-card))] outline-none
                       focus:ring-2 focus:ring-[rgb(var(--color-primary)/.35)] transition"
                            />
                            <p v-if="form.errors.email" class="text-xs text-[rgb(248_113_113)] mt-1">{{ form.errors.email }}</p>
                        </div>

                        <!-- Passwort (Textfeld + Generator + Copy) -->
                        <div class="sm:col-span-2">
                            <label class="text-xs text-[rgb(var(--color-muted))]">Neues Passwort (optional)</label>
                            <div class="flex gap-2">
                                <input
                                    v-model="form.password"
                                    type="text"
                                    class="flex-1 px-3 py-2 rounded-xl border border-[rgb(var(--color-border))] bg-[rgb(var(--color-card))] outline-none
                         focus:ring-2 focus:ring-[rgb(var(--color-primary)/.35)] transition font-mono"
                                />
                                <button type="button"
                                        @click="generatePassword"
                                        class="px-3 py-2 rounded-xl border border-[rgb(var(--color-border))] bg-[rgb(var(--color-card))]
                               hover:bg-[rgb(var(--color-primary)/.10)] transition"
                                        title="Passwort generieren">
                                    🔄
                                </button>
                                <button type="button"
                                        @click="copyPassword"
                                        class="px-3 py-2 rounded-xl border border-[rgb(var(--color-border))] bg-[rgb(var(--color-card))]
                               hover:bg-[rgb(var(--color-primary)/.10)] transition"
                                        title="Passwort kopieren">
                                    📋
                                </button>
                            </div>
                            <p v-if="form.errors.password" class="text-xs text-[rgb(248_113_113)] mt-1">{{ form.errors.password }}</p>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <button
                            :disabled="form.processing"
                            class="px-3 py-2 rounded-xl border border-[rgb(var(--color-border))] bg-[rgb(var(--color-card))]
                     hover:bg-[rgb(var(--color-primary)/.10)] disabled:opacity-60 transition"
                        >
                            {{ form.processing ? 'Speichere…' : 'Speichern' }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Rollen -->
            <div class="p-4 rounded-2xl border border-[rgb(var(--color-border))] bg-[rgb(var(--color-card)/.8)] backdrop-blur">
                <h3 class="font-medium mb-2">Rollen</h3>

                <div class="space-y-2">
                    <label
                        v-for="r in allRoles"
                        :key="r"
                        class="flex items-center gap-2 px-2 py-1 rounded-lg border border-[rgb(var(--color-border))] bg-[rgb(var(--color-card))]
                   hover:bg-[rgb(var(--color-primary)/.08)] transition"
                    >
                        <input type="checkbox" :value="r" v-model="roles"
                               class="rounded border-[rgb(var(--color-border))] bg-[rgb(var(--color-card))] focus:ring-[rgb(var(--color-primary)/.35)]" />
                        <span class="text-sm">{{ r }}</span>
                    </label>
                </div>

                <button
                    @click="submitRoles"
                    class="mt-3 px-3 py-2 rounded-xl border border-[rgb(var(--color-border))] bg-[rgb(var(--color-card))]
                 hover:bg-[rgb(var(--color-primary)/.10)] transition"
                >
                    Rollen speichern
                </button>

                <div class="mt-4 text-xs text-[rgb(var(--color-muted))] space-y-0.5">
                    <div>Erstellt: {{ user.created_at }}</div>
                    <div>Aktualisiert: {{ user.updated_at }}</div>
                    <div>User-ID intern: {{ user.id }}</div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/layouts/AuthLayout.vue'
import { router, useForm, Link } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
    user: Object,
    allRoles: Array
})

// Profil-Form
const form = useForm({
    public_id: props.user.public_id ?? '',
    name: props.user.name ?? '',
    email: props.user.email ?? '',
    password: ''
})
function submitProfile() {
    form.put(`/admin/users/${props.user.id}`, {
        preserveScroll: true,
        onSuccess: () => {},
        onError: (errors) => console.warn(errors),
    })
}

// Rollen-Form
const roles = ref(Array.isArray(props.user.roles) ? [...props.user.roles] : [])
function submitRoles() {
    router.put(`/admin/users/${props.user.id}/roles`, { roles: roles.value }, {
        preserveScroll: true,
        onSuccess: () => {},
        onError: (errors) => console.warn(errors),
    })
}

// Passwort
function generatePassword() {
    const charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+'
    let pwd = ''
    const values = new Uint32Array(16)
    crypto.getRandomValues(values)
    for (let i = 0; i < values.length; i++) {
        pwd += charset[values[i] % charset.length]
    }
    form.password = pwd
}
function copyPassword() {
    if (!form.password) return
    navigator.clipboard.writeText(form.password).then(() => {
        alert('Passwort kopiert!')
    })
}
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between w-full">
                <h1 class="text-xl sm:text-2xl font-bold text-transparent bg-clip-text
                   bg-[linear-gradient(90deg,rgb(var(--color-primary)),rgb(var(--color-accent)))]">
                    Admin Dashboard
                </h1>
                <div class="hidden sm:flex items-center gap-2 text-[11px] text-[rgb(var(--color-muted))]">
                    <span class="px-2 py-1 rounded-lg border border-[rgb(var(--color-border))] bg-[rgb(var(--color-card))]">Admin</span>
                </div>
            </div>
        </template>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <StatCard title="Online User" :value="stats.onlineUsers" />
            <StatCard title="Aktive Tools" :value="stats.activeTools" />
            <StatCard title="Einnahmen gesamt" :value="currency(stats.revenue)" />
            <StatCard title="Speicherverbrauch" :value="stats.storageUsage" />
            <StatCard title="Registrierte User" :value="stats.registeredUsers" />
            <StatCard title="Server aktiv" :value="stats.serverUp ? 'Ja' : 'Nein'" />
            <StatCard title="Server Fehler" :value="stats.serverErrors" />
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/layouts/AuthLayout.vue'
import StatCard from '@/components/StatCard.vue'
defineProps({ stats: Object })
const currency = (n) => new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'EUR' }).format(n ?? 0)
</script>

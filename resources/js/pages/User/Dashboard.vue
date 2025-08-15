<template>
    <AuthenticatedLayout>
        <template #header>
            <h1 class="text-xl font-bold text-primary">Dein Dashboard</h1>
        </template>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <StatCard title="Meine Tools" :value="stats.myTools" />
            <StatCard title="Nutzungsstunden" :value="stats.usageHours" />
            <StatCard title="Offene Tickets" :value="stats.ticketsOpen" />
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/layouts/AuthLayout.vue'
import StatCard from '@/components/StatCard.vue'
defineProps({ stats: Object })
</script>

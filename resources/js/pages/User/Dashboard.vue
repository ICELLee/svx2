<template>
    <AuthenticatedLayout>
        <template #header>
            <h1 class="text-xl font-bold text-primary">Dein Dashboard</h1>
        </template>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <StatCard title="Meine Tools" :value="stats.myTools" />
            <StatCard title="Nutzungsstunden" :value="stats.usageHours" />
            <StatCard title="Offene Tickets" :value="stats.ticketsOpen" />
        </div>

        <!-- Tools & Laufzeiten -->
        <div class="p-4 rounded-xl border border-border/50 bg-card">
            <div class="flex items-center justify-between mb-3">
                <h2 class="font-semibold">Tool-Status</h2>
                <a href="/tools/redeem" class="px-3 py-1.5 rounded-lg border hover:bg-accent/10 text-sm">Code einlösen</a>
            </div>

            <div v-if="(stats.tools || []).length === 0" class="text-sm text-muted">
                Keine aktiven Tools. Bitte Code einlösen.
            </div>

            <ul v-else class="space-y-2">
                <li v-for="t in stats.tools" :key="t.tool" class="flex items-center justify-between p-3 rounded-lg border bg-background">
                    <div class="font-medium">{{ t.tool }}</div>
                    <div class="text-sm">
            <span v-if="t.expires_at">
              gültig bis {{ new Date(t.expires_at).toLocaleString() }}
            </span>
                        <span v-else class="text-emerald-600">dauerhaft</span>
                    </div>
                </li>
            </ul>

            <!-- Hinweis: bald ablaufend -->
            <div v-if="(stats.toolsSoon || []).length" class="mt-3 text-sm text-amber-600">
                ⚠️ Folgende Zugänge laufen in {{ stats.soonDays }} Tagen oder früher ab:
                <span class="font-medium">
          {{ stats.toolsSoon.map(x => x.tool).join(', ') }}
        </span>
            </div>
        </div>

        <!-- Optional: weitere Kennzahlen -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-6">
            <StatCard title="Codes eingelöst" :value="stats.codesRedeemed" />
            <StatCard title="Bonus Hunts" :value="stats.bonusHunts" />
            <StatCard title="Extension-Tokens" :value="stats.extensionTokens" />
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/layouts/AuthLayout.vue'
import StatCard from '@/components/StatCard.vue'
defineProps({ stats: Object })
</script>

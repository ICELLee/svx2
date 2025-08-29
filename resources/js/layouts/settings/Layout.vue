<script setup lang="ts">
import Heading from '@/components/Heading.vue'
import { type NavItem } from '@/types'
import { Link, usePage } from '@inertiajs/vue3'

const sidebarNavItems: NavItem[] = [
    { title: 'Profile', href: '/settings/profile', icon: '👤' },
    { title: 'Password', href: '/settings/password', icon: '🔑' },
]

const page = usePage()
const currentPath = page.props.ziggy?.location
    ? new URL(page.props.ziggy.location).pathname
    : ''
</script>

<template>
    <div class="px-4 py-6">
        <Heading
            title="Settings"
            description="Manage your profile and account settings"
        />

        <div class="flex flex-col lg:flex-row lg:space-x-12 mt-6">
            <!-- Sidebar -->
            <aside
                class="w-full max-w-xl lg:w-56 rounded-2xl border border-[rgb(var(--color-border))] bg-[rgb(var(--color-card)/.7)] backdrop-blur shadow-[0_10px_30px_-12px_rgba(124,58,237,.25)] p-3"
            >
                <nav class="flex flex-col gap-1">
                    <Link
                        v-for="item in sidebarNavItems"
                        :key="item.href"
                        :href="item.href"
                        class="group flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium transition-all duration-200"
                        :class="
              currentPath === item.href
                ? 'bg-[rgb(var(--color-primary)/.15)] text-[rgb(var(--color-text))] shadow-[0_0_15px_rgba(124,58,237,.35)]'
                : 'text-[rgb(var(--color-muted))] hover:bg-[rgb(var(--color-primary)/.08)] hover:text-[rgb(var(--color-text))]'
            "
                    >
                        <span class="text-base">{{ item.icon }}</span>
                        <span>{{ item.title }}</span>
                        <span
                            v-if="currentPath === item.href"
                            class="ml-auto h-1.5 w-1.5 rounded-full bg-[rgb(var(--color-accent))] shadow-[0_0_6px_rgba(251,146,60,.8)]"
                        />
                    </Link>
                </nav>
            </aside>

            <!-- Content -->
            <div class="flex-1 md:max-w-2xl mt-8 lg:mt-0">
                <section class="max-w-xl space-y-12">
                    <slot />
                </section>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/layouts/AuthLayout.vue'
import SettingsLayout from '@/layouts/settings/Layout.vue'

import DeleteUser from '@/components/DeleteUser.vue'
import HeadingSmall from '@/components/HeadingSmall.vue'
import InputError from '@/components/InputError.vue'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { type User } from '@/types'

interface Props {
    mustVerifyEmail: boolean
    status?: string
}
defineProps<Props>()

const page = usePage()
const user = page.props.auth.user as User

const form = useForm({
    name: user.name,
    email: user.email,
    country: user.country ?? 'Germany',
    street: user.street ?? '',
    house_number: user.house_number ?? '',
    postal_code: user.postal_code ?? '',
    phone: user.phone ?? '',
    avatar: null as File | null,
})

const submit = () => {
    form.patch(route('profile.update'), {
        preserveScroll: true,
        forceFormData: true,
    })
}
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Profile settings" />

        <SettingsLayout>
            <div class="space-y-6 max-w-xl">
                <!-- Card: Profile Info -->
                <div
                    class="rounded-2xl border border-[rgb(var(--color-border))] bg-[rgb(var(--color-card)/.7)] backdrop-blur p-6 shadow-[0_10px_30px_-12px_rgba(124,58,237,.25)] hover:shadow-[0_12px_40px_-10px_rgba(124,58,237,.35)] transition-all"
                >
                    <HeadingSmall
                        title="Profile information"
                        description="Update your personal information and contact details"
                    />

                    <form @submit.prevent="submit" class="space-y-6 mt-4" enctype="multipart/form-data">
                        <!-- Name -->
                        <div class="grid gap-2">
                            <Label for="name">Name</Label>
                            <Input id="name" v-model="form.name" required autocomplete="name" />
                            <InputError :message="form.errors.name" />
                        </div>

                        <!-- Email -->
                        <div class="grid gap-2">
                            <Label for="email">Email</Label>
                            <Input id="email" type="email" v-model="form.email" required />
                            <InputError :message="form.errors.email" />
                        </div>

                        <!-- Country -->
                        <div class="grid gap-2">
                            <Label for="country">Country</Label>
                            <select
                                id="country"
                                v-model="form.country"
                                class="rounded-lg border border-[rgb(var(--color-border))] bg-[rgb(var(--color-card))] p-2"
                            >
                                <option value="Germany">Deutschland</option>
                                <option value="Austria">Österreich</option>
                                <option value="Switzerland">Schweiz</option>
                                <option value="Other">Anderes Land</option>
                            </select>
                            <InputError :message="form.errors.country" />
                        </div>

                        <!-- Street -->
                        <div class="grid gap-2">
                            <Label for="street">Street</Label>
                            <Input id="street" v-model="form.street" placeholder="Musterstraße" />
                            <InputError :message="form.errors.street" />
                        </div>

                        <!-- House number -->
                        <div class="grid gap-2">
                            <Label for="house_number">House number</Label>
                            <Input id="house_number" v-model="form.house_number" placeholder="12a" />
                            <InputError :message="form.errors.house_number" />
                        </div>

                        <!-- Postal Code -->
                        <div class="grid gap-2">
                            <Label for="postal_code">Postal Code</Label>
                            <Input id="postal_code" v-model="form.postal_code" type="text" placeholder="12345" />
                            <InputError :message="form.errors.postal_code" />
                        </div>

                        <!-- Phone -->
                        <div class="grid gap-2">
                            <Label for="phone">Phone number</Label>
                            <Input id="phone" v-model="form.phone" type="tel" placeholder="+49 123 456789" />
                            <InputError :message="form.errors.phone" />
                        </div>

                        <!-- Avatar -->
                        <div class="grid gap-2">
                            <Label for="avatar">Profile picture</Label>
                            <input
                                id="avatar"
                                type="file"
                                @change="e => form.avatar = e.target.files[0]"
                                class="block w-full text-sm text-muted-foreground file:mr-3 file:rounded-lg file:border file:border-[rgb(var(--color-border))] file:bg-[rgb(var(--color-card))] file:px-3 file:py-1.5 file:text-sm file:font-medium hover:file:bg-[rgb(var(--color-primary)/.1)]"
                            />
                            <InputError :message="form.errors.avatar" />
                        </div>

                        <!-- Actions -->
                        <!-- Actions -->
                        <div class="flex items-center gap-4">
                            <!-- Save Button mit Spinner -->
                            <Button :disabled="form.processing" class="relative">
                                <span v-if="!form.processing">Save</span>
                                <span v-else class="flex items-center gap-2">
      <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
        <circle class="opacity-25" cx="12" cy="12" r="10"
                stroke="currentColor" stroke-width="4" />
        <path class="opacity-75" fill="currentColor"
              d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
      </svg>
      Saving...
    </span>
                            </Button>

                            <!-- Erfolgsmeldung -->
                            <Transition
                                enter-active-class="transition ease-in-out"
                                enter-from-class="opacity-0 translate-y-1"
                                enter-to-class="opacity-100 translate-y-0"
                                leave-active-class="transition ease-in-out"
                                leave-from-class="opacity-100 translate-y-0"
                                leave-to-class="opacity-0 translate-y-1"
                            >
                                <p
                                    v-show="form.recentlySuccessful"
                                    class="text-sm text-emerald-600 font-medium"
                                >
                                    ✅ Saved successfully
                                </p>
                            </Transition>
                        </div>

                    </form>
                </div>

                <!-- Card: Delete User -->
                <div
                    class="rounded-2xl border border-red-500/30 bg-red-500/5 backdrop-blur p-6 shadow-[0_10px_30px_-12px_rgba(239,68,68,.3)] hover:shadow-[0_12px_40px_-10px_rgba(239,68,68,.5)] transition-all"
                >
                    <DeleteUser />
                </div>
            </div>
        </SettingsLayout>
    </AuthenticatedLayout>
</template>

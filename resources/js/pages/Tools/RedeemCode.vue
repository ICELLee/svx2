<script setup>
import AuthenticatedLayout from '@/layouts/AuthLayout.vue'
import { ref } from 'vue'

const code    = ref('')
const result  = ref(null)
const flash   = ref(null)
const loading = ref(false)

function reset() {
    result.value = null
    flash.value = null
}

async function redeem() {
    reset()
    if (!code.value) {
        flash.value = { ok:false, msg:'Bitte Code eingeben.' }
        return
    }
    loading.value = true
    try {
        const { data } = await window.axios.post('/user/api/codes/redeem', { code: code.value.trim() })
        result.value = data
        flash.value = { ok:true, msg:data.message || '✅ Code erfolgreich eingelöst.' }
        code.value = ''
    } catch (e) {
        const msg = e?.response?.data?.message || '❌ Einlösen fehlgeschlagen.'
        flash.value = { ok:false, msg }
    } finally {
        loading.value = false
    }
}
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h1 class="text-2xl font-bold text-gradient">Code einlösen</h1>
        </template>

        <div class="max-w-xl mx-auto space-y-6">
            <!-- Haupt-Card -->
            <div class="p-6 rounded-2xl neon-card space-y-4">
                <label class="text-xs text-muted">Gutschein-/Freischaltcode</label>

                <!-- Input -->
                <input
                    v-model="code"
                    type="text"
                    placeholder="z.B. SVX-ABCDE-12345"
                    class="neon-input font-mono"
                    @keydown.enter="redeem"
                />

                <!-- Button -->
                <button
                    @click="redeem"
                    :disabled="loading || !code"
                    class="btn-neon disabled:opacity-50"
                >
                    Einlösen
                </button>

                <!-- Flash-Message -->
                <p v-if="flash" class="text-sm" :class="flash.ok ? 'text-emerald-400' : 'text-red-400'">
                    {{ flash.msg }}
                </p>

                <!-- Ergebnis -->
                <div v-if="result" class="text-sm neon-card p-4 space-y-2">
                    <div class="font-semibold">Freigeschaltete Tools:</div>
                    <ul class="list-disc list-inside space-y-1">
                        <li v-for="t in (result.tools || [])" :key="t">
                            {{ t }}
                            <span v-if="result.until?.[t]" class="text-xs text-muted">
                                bis {{ new Date(result.until[t]).toLocaleString() }}
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

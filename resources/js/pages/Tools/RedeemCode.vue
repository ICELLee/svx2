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
        flash.value = { ok:true, msg:data.message || 'Code eingelöst.' }
        code.value = ''
    } catch (e) {
        const msg = e?.response?.data?.message || 'Einlösen fehlgeschlagen.'
        flash.value = { ok:false, msg }
    } finally {
        loading.value = false
    }
}
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h1 class="text-xl font-bold text-primary">Code einlösen</h1>
        </template>

        <div class="max-w-xl mx-auto space-y-4">
            <div class="p-4 rounded-2xl border bg-card space-y-3">
                <label class="text-xs text-muted">Gutschein-/Freischaltcode</label>
                <input
                    v-model="code"
                    type="text"
                    placeholder="z.B. SVX-ABCDE-12345"
                    class="w-full px-3 py-2 rounded-xl border bg-background font-mono"
                    @keydown.enter="redeem"
                />

                <button
                    @click="redeem"
                    :disabled="loading || !code"
                    class="px-4 py-2 rounded-xl bg-violet-600 text-white hover:opacity-90 disabled:opacity-50"
                >
                    Einlösen
                </button>

                <p v-if="flash" class="text-sm" :class="flash.ok ? 'text-emerald-500' : 'text-red-500'">
                    {{ flash.msg }}
                </p>

                <div v-if="result" class="text-sm border rounded-xl p-3 bg-background">
                    <div class="font-medium mb-1">Freigeschaltete Tools:</div>
                    <ul class="list-disc list-inside">
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

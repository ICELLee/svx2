<template>
    <AuthenticatedLayout>
        <template #header>
            <h1 class="text-xl font-bold">Neues Ticket</h1>
        </template>

        <form @submit.prevent="submit" class="space-y-4 max-w-2xl">
            <!-- Titel -->
            <div>
                <label class="block text-sm font-medium">Titel</label>
                <input v-model="form.title" type="text" class="input w-full" required />
            </div>

            <!-- Grund -->
            <div>
                <label class="block text-sm font-medium">Grund</label>
                <select v-model="form.reason" class="input w-full" required>
                    <option disabled value="">Bitte wählen…</option>
                    <option>Sonstiges</option>
                    <option>Partnerschaft</option>
                    <option>Test Code Erhalten</option>
                    <option>Allgemeine Frage</option>
                    <option>Frage zu Produkt</option>
                    <option>Produkt Kaufen</option>
                    <option>Bug Report</option>
                    <option>User Report</option>
                </select>
            </div>

            <!-- Extra Felder -->
            <div v-if="form.reason === 'Bug Report'">
                <label class="block text-sm font-medium">Error Code</label>
                <input v-model="form.extra_info" type="text" class="input w-full" />
            </div>
            <div v-if="form.reason === 'User Report'">
                <label class="block text-sm font-medium">User ID / Name / E-Mail</label>
                <input v-model="form.extra_info" type="text" class="input w-full" />
            </div>

            <!-- Produkte -->
            <div v-if="['Frage zu Produkt','Produkt Kaufen'].includes(form.reason)">
                <label class="block text-sm font-medium">Produkte</label>
                <div class="flex flex-wrap gap-3">
                    <label v-for="p in products" :key="p" class="flex items-center gap-2">
                        <input type="checkbox" :value="p" v-model="form.products" />
                        {{ p }}
                    </label>
                </div>
            </div>

            <!-- Priorität -->
            <div>
                <label class="block text-sm font-medium">Priorität</label>
                <div class="flex gap-4">
                    <button type="button" v-for="p in priorities" :key="p.value"
                            :class="['px-3 py-1 rounded-lg border', form.priority===p.value ? p.color+' text-white' : '']"
                            @click="form.priority = p.value">
                        {{ p.label }}
                    </button>
                </div>
            </div>

            <!-- Nachricht -->
            <div>
                <label class="block text-sm font-medium">Nachricht</label>
                <textarea v-model="form.message" rows="6" class="input w-full" required></textarea>
            </div>

            <!-- Anhänge -->
            <div>
                <label class="block text-sm font-medium">Anhänge</label>
                <FileDropzone v-model="form.attachments" />
            </div>

            <button type="submit" class="btn btn-primary">Absenden</button>
        </form>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/layouts/AuthLayout.vue'
import { useForm } from '@inertiajs/vue3'

const products = ['Slot-Tracker','Slot-Request','BonusHunt','Tournament','Bot','Sonstiges']
const priorities = [
    {value:'green', label:'Grün', color:'bg-green-500'},
    {value:'orange', label:'Orange', color:'bg-orange-500'},
    {value:'red', label:'Rot', color:'bg-red-500'},
    {value:'gold', label:'Gold (VIP)', color:'bg-yellow-500'}
]

const form = useForm({
    title:'',
    reason:'',
    extra_info:'',
    products:[],
    priority:'green',
    message:'',
    attachments:[]
})

function handleFiles(e) {
    form.attachments = Array.from(e.target.files)
}

function submit() {
    form.post('/tickets')
}
</script>

<style scoped>
:global(.rounded-lg) {}

</style>

<template>
    <div
        class="border-2 border-dashed rounded-lg p-6 text-center cursor-pointer"
        :class="dragging ? 'border-primary bg-primary/10' : 'border-[rgb(var(--color-border))]'"
        @dragover.prevent="dragging=true"
        @dragleave.prevent="dragging=false"
        @drop.prevent="handleDrop"
    >
        <p v-if="!files.length">📂 Dateien hierher ziehen oder klicken</p>
        <ul v-else class="text-sm text-left space-y-1">
            <li v-for="(f, i) in files" :key="i">- {{ f.name }}</li>
        </ul>
        <input type="file" multiple class="hidden" ref="input" @change="handleSelect" />
    </div>
</template>

<script setup>
import { ref } from 'vue'

const files = defineModel({ type:Array, default:[] })
const input = ref(null)
const dragging = ref(false)

function handleDrop(e) {
    dragging.value = false
    files.value = [...files.value, ...Array.from(e.dataTransfer.files)]
}
function handleSelect(e) {
    files.value = [...files.value, ...Array.from(e.target.files)]
}
</script>

<style scoped>
.border-primary { border-color: rgb(var(--color-primary)) }
.bg-primary\/10 { background-color: rgb(var(--color-primary) / 0.1) }
</style>

<template>
    <button @click="toggle" class="px-3 py-2 rounded-xl border border-border bg-card shadow hover:scale-[1.02] transition">
        {{ value === 'dark' ? 'Dark' : 'Light' }}
    </button>
</template>

<script setup>
const props = defineProps({ value: { type: String, default: 'dark' } })
const emit = defineEmits(['change'])
function toggle(){ emit('change', props.value === 'dark' ? 'light' : 'dark') }
</script>


<style scoped>

</style>

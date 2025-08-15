<template>
    <div
        class="stat-card relative p-4 rounded-2xl bg-card border border-border shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1 overflow-hidden group"
        :class="{'glow-effect': valueChanging}"
    >
        <!-- Hintergrund-Akzentanimation (konstant laufend) -->
        <div class="absolute inset-0 overflow-hidden opacity-10 group-hover:opacity-20 transition-opacity duration-500">
            <div class="absolute -left-10 -top-10 w-40 h-40 bg-purple-400 rounded-full filter blur-xl animate-pulse-slow"></div>
            <div class="absolute -right-5 -bottom-5 w-30 h-30 bg-orange-300 rounded-full filter blur-xl animate-pulse-slower"></div>
        </div>

        <!-- Glow Border (nur beim Hover) -->
        <div class="absolute inset-0 rounded-2xl pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            <div class="absolute inset-0 border-2 border-transparent group-hover:border-purple-500 rounded-2xl transition-all duration-500"></div>
        </div>

        <!-- Inhalt -->
        <div class="relative z-10">
            <div class="text-sm text-muted-foreground font-medium tracking-wider transition-all duration-200 group-hover:text-purple-300">
                {{ title }}
            </div>
            <div
                class="text-3xl font-bold mt-1 transition-all duration-300"
                :class="{
                    'text-orange-400': typeof value === 'number',
                    'text-purple-200': typeof value !== 'number',
                    'animate-pulse': valueChanging
                }"
            >
                {{ formattedValue }}
            </div>
            <div
                v-if="hint"
                class="text-xs mt-1 text-muted-foreground transition-all duration-200 group-hover:text-purple-200 flex items-center"
            >
                <span class="inline-block w-2 h-2 rounded-full bg-orange-400 mr-1 animate-pulse"></span>
                {{ hint }}
            </div>
        </div>

        <!-- Partikeleffekt beim Hover -->
        <div
            v-for="i in 5"
            :key="i"
            class="absolute w-1 h-1 rounded-full bg-purple-300 opacity-0 group-hover:opacity-70 transition-opacity duration-300"
            :class="`particle-${i}`"
            :style="particleStyle(i)"
        ></div>
    </div>
</template>

<script setup>
import { computed, ref, watch } from 'vue'

const props = defineProps({
    title: String,
    value: [String, Number, Boolean],
    hint: String
})

const valueChanging = ref(false)
const oldValue = ref(props.value)

const formattedValue = computed(() => {
    if (typeof props.value === 'number') {
        return new Intl.NumberFormat().format(props.value)
    }
    return props.value
})

watch(() => props.value, (newVal, oldVal) => {
    if (newVal !== oldVal) {
        valueChanging.value = true
        oldValue.value = oldVal
        setTimeout(() => valueChanging.value = false, 1000)
    }
})

const particleStyle = (i) => {
    const angle = (i * 72) * (Math.PI / 180) // 5 Partikel gleichmäßig verteilt
    const distance = 15
    return {
        top: '50%',
        left: '50%',
        transform: `translate(-50%, -50%) translate(${Math.cos(angle) * distance}px, ${Math.sin(angle) * distance}px)`,
        transition: `all ${0.5 + i*0.1}s ease-out`
    }
}
</script>

<style scoped>
@reference "tailwindcss";
@keyframes pulse-slow {
    0%, 100% { transform: scale(1); opacity: 0.7; }
    50% { transform: scale(1.1); opacity: 1; }
}

@keyframes pulse-slower {
    0%, 100% { transform: scale(1); opacity: 0.5; }
    50% { transform: scale(1.05); opacity: 0.8; }
}

@keyframes pulse {
    0%, 100% { opacity: 0.5; }
    50% { opacity: 1; }
}

.animate-pulse-slow {
    animation: pulse-slow 6s ease-in-out infinite;
}

.animate-pulse-slower {
    animation: pulse-slower 8s ease-in-out infinite;
}

.animate-pulse {
    animation: pulse 1s ease-in-out infinite;
}

.glow-effect {
    box-shadow: 0 0 15px rgba(168, 85, 247, 0.5);
}

.stat-card {
    --card-bg: theme('colors.gray.900');
    --card-border: theme('colors.gray.700');
    --text-muted: theme('colors.gray.400');
    background: var(--card-bg);
    border-color: var(--card-border);
}

.dark .stat-card {
    --card-bg: theme('colors.gray.800');
    --card-border: theme('colors.gray.600');
    --text-muted: theme('colors.gray.300');
}

.particle-1 { animation: float-up 3s ease-in-out infinite; }
.particle-2 { animation: float-down 3.5s ease-in-out infinite 0.5s; }
.particle-3 { animation: float-left 4s ease-in-out infinite 1s; }
.particle-4 { animation: float-right 3.8s ease-in-out infinite 1.5s; }
.particle-5 { animation: float-diagonal 4.2s ease-in-out infinite 2s; }

@keyframes float-up {
    0%, 100% { transform: translateY(0) translateX(0); opacity: 0; }
    50% { transform: translateY(-20px) translateX(5px); opacity: 0.7; }
}

@keyframes float-down {
    0%, 100% { transform: translateY(0) translateX(0); opacity: 0; }
    50% { transform: translateY(20px) translateX(-5px); opacity: 0.7; }
}

/* Weitere Partikel-Animationen hier einfügen */
</style>

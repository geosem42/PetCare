<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: String,
    active: Boolean,
});

const classes = computed(() => {
    return props.active
        ? 'bg-gray-800 text-white'
        : '';
});
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>

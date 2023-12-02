<script setup>
import { ref, watch } from 'vue';
import { MagnifyingGlassIcon } from '@heroicons/vue/24/solid'

const emit = defineEmits(['search', 'clear']);

let keywords = ref('');

const search = async (url) => {
    const response = await axios.post(url, { keywords: keywords.value });
    return response.data;
}

watch(keywords, (newVal) => {
    if (newVal === '') {
        emit('clear');
    }
});
</script>

<template>
	<input v-model="keywords" type="text" id="simple-search"
		class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500"
		placeholder="Search" required="">
	<button @click.prevent="$emit('search', search)"
		class="absolute inset-y-0 right-0 flex justify-center items-center w-[40px] text-gray-100 bg-indigo-700 hover:bg-indigo-600 rounded-md">
		<MagnifyingGlassIcon class="w-5 h-5" />
	</button>
</template>
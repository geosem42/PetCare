<script setup>
import { defineEmits, defineProps, computed } from 'vue'
import { ArrowSmallLeftIcon, ArrowSmallRightIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
	meta: Object,
})

const emit = defineEmits(['change-page'])

const changePage = (page) => {
	if (page >= 1 && page <= props.meta.lastPage) {
		emit('change-page', page)
	}
}

const pageNumbers = computed(() => {
  let start = Math.max(props.meta.currentPage - 2, 1)
  let end = Math.min(start + 4, props.meta.lastPage)
  start = Math.max(end - 4, 1)
  return Array.from({ length: end - start + 1 }, (_, i) => start + i)
})

const startItem = computed(() => {
  return (props.meta.currentPage - 1) * props.meta.perPage + 1;
});

const endItem = computed(() => {
  return Math.min(props.meta.currentPage * props.meta.perPage, props.meta.totalItems);
});
</script>

<template>
	<nav class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-3 md:space-y-0 p-4"
		aria-label="Table navigation">
		<span class="text-sm font-normal text-gray-500 dark:text-gray-400">
			Showing
			<span class="font-semibold text-gray-900 dark:text-white">{{ startItem }}-{{ endItem}}</span>
			of
			<span class="font-semibold text-gray-900 dark:text-white">{{ props.meta.totalItems }}</span>
		</span>
		<ul class="inline-flex items-stretch -space-x-px">
			<!-- Previous Page Link -->
			<li>
				<a href="#" @click="changePage(meta.currentPage - 1)" :disabled="meta && meta.currentPage === 1"
					class="flex items-center justify-center h-full py-1.5 px-3 ml-0 text-gray-500 bg-white rounded-l-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
					:class="{ 'cursor-not-allowed opacity-50': meta && meta.currentPage === 1 }">
					<span class="sr-only">Previous</span>
					<ArrowSmallLeftIcon class="w-5 h-5" />
				</a>
			</li>

			<!-- Page Numbers -->
			<li v-for="page in pageNumbers" :key="page">
				<a @click="changePage(page)" :class="page === meta.currentPage ? 'flex items-center justify-center text-sm z-10 py-2 px-3 leading-tight text-indigo-600 bg-indigo-50 border border-indigo-300 hover:bg-indigo-100 hover:text-indigo-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white cursor-not-allowed' : 'flex items-center justify-center text-sm py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white cursor-pointer'">
					{{ page }}
				</a>
			</li>

			<!-- Next Page Link -->
			<li>
				<a href="#" @click="changePage(meta.currentPage + 1)" :disabled="meta.currentPage === meta.lastPage"
					class="flex items-center justify-center h-full py-1.5 px-3 leading-tight text-gray-500 bg-white rounded-r-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
					:class="{ 'cursor-not-allowed opacity-50': meta && meta.currentPage === meta.lastPage }">
					<span class="sr-only">Next</span>
					<ArrowSmallRightIcon class="w-5 h-5" />
				</a>
			</li>
		</ul>
	</nav>
</template>
<script setup>
import { defineProps } from 'vue'

const props = defineProps({
	pet: {
		type: Object,
		required: true
	}
})


</script>

<template>
	<div class="px-4 py-5 sm:p-6">
		<template v-if="pet.images.length === 0">
			<p class="text-center text-gray-500 py-4">No images found.</p>
		</template>
		<template v-else>
			<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
				<div v-for="image in pet.images" :key="image.id" class="relative">
					<img :src="`/${image.path}`" alt="" class="w-full h-64 object-cover rounded-lg">
				</div>
			</div>
		</template>
	</div>
</template>
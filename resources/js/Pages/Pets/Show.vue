<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref, defineProps, onMounted } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3'
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue'
import { useToast } from "vue-toastification"
import Swal from "sweetalert2"
import {
	PencilSquareIcon, BoltIcon, ArrowSmallRightIcon, TrashIcon
} from "@heroicons/vue/24/outline/index.js"
import VaccinationsTable from '@/Pages/Pets/Partials/Tables/Vaccinations.vue'
import MedicalHistoryTable from '@/Pages/Pets/Partials/Tables/MedicalHistory.vue'
import MedicationsTable from '@/Pages/Pets/Partials/Tables/Medications.vue'
import SurgicalHistoryTable from '@/Pages/Pets/Partials/Tables/SurgicalHistory.vue'
import GalleryTable from '@/Pages/Pets/Partials/Tables/Gallery.vue'

const { pet } = usePage().props
const tabs = ref(['Vaccinations', 'Medical History', 'Medications', 'Surgical History', 'Gallery'])
const toast = useToast();
const data = ref([])

onMounted(async () => {
	await fetchVaccinations()
})

// Get the pet data from the prop
const props = defineProps({
	pet: {
		type: Object,
		required: true
	}
})

const fetchVaccinations = async () => {
	const response = await axios.get(`/pets/${pet.id}/vaccinations`);
	data.value = response.data;
}

const deletePet = (id) => {
	Swal.fire({
		title: 'Delete Pet?',
		text: 'Are you sure you want to delete this pet?',
		icon: 'warning',
		showCancelButton: true,
		confirmButtonText: 'Yes, delete it',
		cancelButtonText: 'No, keep it'
	}).then((result) => {
		if (result.isConfirmed) {
			axios.delete(`/pets/${id}`)
				.then(response => {
					Swal.fire('Deleted!', response.data.message, 'success')
						.then(() => {
							router.visit(route('pets'), { method: 'get' })
						});
				})
				.catch(error => {
					Swal.fire('Error!', error.response.data.message, 'error');
				});
		}
	});
}

</script>

<template>
	<AppLayout title="View Pet">
		<template #header>
			<div class="flex justify-between">
				<h2 class="font-semibold text-xl text-gray-800 leading-tight">
					Show Pet: {{ pet.name }}
				</h2>
				<div class="flex justify-evenly">
					<Link :href="route('pets.edit', { slug: pet.slug })" class="text-indigo-700 hover:text-indigo-500">
					<PencilSquareIcon class="w-8 h-8" />
					</Link>

					<button @click="deletePet(pet.id)"
						class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100"
						type="button">
						<TrashIcon class="w-8 h-8 text-red-500 hover:text-red-800" />
						<span class="sr-only">Delete</span>
					</button>
				</div>
			</div>
		</template>

		<div class="grid grid-cols-12 gap-4">
			<div class="col-span-12 lg:col-span-3">
				<div class="w-full max-w-sm overflow-hidden bg-white rounded-lg shadow-lg dark:bg-gray-800">
					<img v-if="pet.photo" class="object-cover object-center w-full h-56" :src="pet.photo" alt="avatar">
					<img v-else src="/storage/images/pets/no_photo.png" class="object-cover object-center w-full h-56">

					<div class="flex items-center px-6 py-3 bg-indigo-700">
						<BoltIcon class="w-6 h-6 text-white" />

						<h1 class="mx-3 text-lg font-semibold text-white">{{ pet.name }}</h1>
					</div>

					<div class="px-6 py-4">

						<div v-if="pet.species.name" class="flex items-center mt-4 text-gray-700 dark:text-gray-200">
							<ArrowSmallRightIcon class="w-6 h-6" />
							<h1 class="px-2 text-sm">{{ pet.species.name }}</h1>
						</div>

						<div v-if="pet.breed.name" class="flex items-center mt-4 text-gray-700 dark:text-gray-200">
							<ArrowSmallRightIcon class="w-6 h-6" />
							<h1 class="px-2 text-sm">{{ pet.breed.name }}</h1>
						</div>

						<div v-if="pet.gender" class="flex items-center mt-4 text-gray-700 dark:text-gray-200">
							<ArrowSmallRightIcon class="w-6 h-6" />
							<h1 class="px-2 text-sm">{{ pet.gender }}</h1>
						</div>

						<div v-if="pet.age" class="flex items-center mt-4 text-gray-700 dark:text-gray-200">
							<ArrowSmallRightIcon class="w-6 h-6" />
							<h1 class="px-2 text-sm">{{ pet.age }}</h1>
						</div>
					</div>
				</div>
			</div>
			<div class="col-span-12 lg:col-span-9">
				<TabGroup>
					<TabList class="flex flex-col sm:flex-row space-x-1 rounded-t-md bg-blue-900/20">
						<Tab as="template" v-slot="{ selected }" v-for="tab in tabs" :key="tab">
							<button :class="[
								'w-full rounded-t-md py-2.5 text-sm font-medium leading-5',
								'ring-white/60 ring-offset-2 focus:outline-none',
								selected
									? 'bg-white text-indigo-700'
									: 'text-blue-100 hover:bg-white/[0.12] hover:text-white',
							]">
								{{ tab }}
							</button>
						</Tab>
					</TabList>

					<TabPanels>
						<TabPanel
							:class="['rounded-b-md shadow-md bg-white p-3', 'ring-white/60 ring-offset-2 focus:outline-none']">
							<VaccinationsTable :pet="pet" />
						</TabPanel>
						<TabPanel
							:class="['rounded-b-md shadow-md bg-white p-3', 'ring-white/60 ring-offset-2 focus:outline-none']">
							<MedicalHistoryTable :pet="pet" />
						</TabPanel>
						<TabPanel
							:class="['rounded-b-md shadow-md bg-white p-3', 'ring-white/60 ring-offset-2 focus:outline-none']">
							<MedicationsTable :pet="pet" />
						</TabPanel>
						<TabPanel
							:class="['rounded-b-md shadow-md bg-white p-3', 'ring-white/60 ring-offset-2 focus:outline-none']">
							<SurgicalHistoryTable :pet="pet" />
						</TabPanel>
						<TabPanel
							:class="['rounded-b-md shadow-md bg-white p-3', 'ring-white/60 ring-offset-2 focus:outline-none']">
							<GalleryTable :pet="pet" />
						</TabPanel>
					</TabPanels>
				</TabGroup>
		</div>
	</div>
</AppLayout></template>
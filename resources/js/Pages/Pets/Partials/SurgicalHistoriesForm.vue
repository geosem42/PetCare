<script setup>
import { ref, defineProps, onMounted } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { useToast } from "vue-toastification"
import { PlusIcon, TrashIcon } from "@heroicons/vue/24/outline/index.js";
import moment from 'moment';
import { validateForm, watchFields, errors } from '@/Validation/Pets/SurgicalHistories/Index';

const isSubmitting = ref(false)
const toast = useToast();

const { pet } = usePage().props

const props = defineProps({
	pet: {
		type: Object,
		required: true
	}
})

onMounted(async () => {
	await fetchSurgeries()
	watchFields(surgeriesForm.value);
})

const surgeriesForm = ref([
	{ procedure_name: '', date: '', surgeon: '', notes: '' }
])

const addSurgery = () => {
	surgeriesForm.value.push({ pet_id: '', procedure_name: '', date: '', surgeon: '', notes: '' });
}

const deleteSurgery = async (index) => {
	if (surgeriesForm.value.length === 1) {
		const surgeryId = surgeriesForm.value[index].id;

		// Clear the form
		surgeriesForm.value[index] = { procedure_name: '', date: '', surgeon: '', notes: '' };

		// Send the id to the backend
		await axios.delete(`/pets/${pet.id}/surgeries/${surgeryId}`);

		toast.success('Surgical History successfully deleted!');
	} else {
		const surgery = surgeriesForm.value[index];
		await axios.delete(`/pets/${pet.id}/surgeries/${surgery.id}`);
		surgeriesForm.value.splice(index, 1);
		toast.success('Surgical History successfully deleted!');
	}
}

const storeSurgery = async () => {
	isSubmitting.value = true;

	validateForm(surgeriesForm.value);

	if (Object.keys(errors.value).length > 0) {
		toast.error("Please correct the errors in the form.");
		isSubmitting.value = false;
		return;
	}

	let submitData = { surgeries: surgeriesForm.value };

	submitData.surgeries.forEach((surgery) => {
		surgery.pet_id = pet.id;
		if (!surgery.id) {
			surgery.id = null;
		}

		if (surgery.date) {
			surgery.date = moment(surgery.date).format('YYYY-MM-DD');
		} else {
			delete surgery.date;
		}
	});

	const response = await axios.post(`/pets/${pet.id}/surgeries`, submitData, {
		headers: {
			'Content-Type': 'application/json',
		},
	});

	toast.success(response.data.message);

	errors.value = {};

	// Update the form with the returned surgeries
	if (response.data.surgeries && response.data.surgeries.length > 0) {
		response.data.surgeries.forEach((newSurgery) => {
			const index = surgeriesForm.value.findIndex((v) => v.id === null);
			if (index !== -1) {
				// Replace temporary surgery with real one
				surgeriesForm.value.splice(index, 1, newSurgery);
			}
		});
	}

	isSubmitting.value = false;
}

const fetchSurgeries = async () => {
	const response = await axios.get(`/pets/${pet.id}/surgeries`);
	surgeriesForm.value = response.data;

	if (surgeriesForm.value.length === 0) {
		surgeriesForm.value.push({ procedure_name: '', date: '', surgeon: '', notes: '' });
	}
}
</script>

<template>
	<div class="max-w-full bg-white rounded-md mt-2">

		<div class="text-lg font-semibold leading-6 text-gray-900 border-b p-6 flex justify-between items-center">
			Surgical History
			<button @click.stop.prevent="addSurgery" class="bg-indigo-500 hover:bg-indigo-700 text-white p-2 rounded-md">
				<PlusIcon class="h-6 w-6" />
			</button>
		</div>

		<form @submit.prevent="storeSurgery" class="mt-6">

			<div v-for="(surgery, index) in surgeriesForm" :key="index" class="grid grid-cols-12 gap-5 mb-5 p-5">
				<div class=" col-span-12 md:col-span-6 lg:col-span-2">
					<label for="procedure_name" class="mb-2 block text-sm font-medium text-gray-500">Procedure Name</label>
					<input v-model="surgery.procedure_name" name="procedure_name" id="procedure_name"
						placeholder="Procedure Name"
						class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm"
						:class="{ 'border-red-500': errors[`surgeries[${index}].procedure_name`] }">
					<span class="text-red-500 text-xs">{{ errors[`surgeries[${index}].procedure_name`] }}</span>
				</div>

				<div class="col-span-12 md:col-span-6 lg:col-span-2">
					<label for="date" class="mb-2 block text-sm font-medium text-gray-500">Date</label>
					<input type="date" v-model="surgery.date"
						class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm"
						:class="{ 'border-red-500': errors[`surgeries[${index}].date`] }">
					<span class="text-red-500 text-xs">{{ errors[`surgeries[${index}].date`] }}</span>
				</div>

				<div class="col-span-12 md:col-span-6 lg:col-span-2">
					<label for="treatment" class="mb-2 block text-sm font-medium text-gray-500">Surgeon</label>
					<input v-model="surgery.surgeon" name="surgeon" id="surgeon" placeholder="Surgeon"
						class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm"
						:class="{ 'border-red-500': errors[`surgeries[${index}].surgeon`] }">
					<span class="text-red-500 text-xs">{{ errors[`surgeries[${index}].surgeon`] }}</span>
				</div>

				<div class="col-span-12 md:col-span-6 lg:col-span-5">
					<label for="notes" class="mb-2 block text-sm font-medium text-gray-500">Notes</label>
					<textarea v-model="surgery.notes" name="notes" id="notes" placeholder="Notes" rows="5"
						class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm"
						:class="{ 'border-red-500': errors[`surgeries[${index}].notes`] }">
					</textarea>
					<span class="text-red-500 text-xs">{{ errors[`surgeries[${index}].notes`] }}</span>
				</div>

				<div class="col-span-12 sm:col-span-1 mt-7">
					<button v-if="surgery.id" @click.stop.prevent="deleteSurgery(index)"
						class="bg-red-500 hover:bg-red-700 text-white p-2 rounded-md">
						<TrashIcon class="h-6 w-6" />
					</button>
				</div>

			</div>

			<div class="col-span-12 m-5 pb-5">
				<button type="submit" :disabled="isSubmitting"
					class="w-full rounded-lg border border-indigo-700 bg-indigo-700 px-8 py-4 text-center text-lg font-medium text-white shadow-sm transition-all hover:border-indigo-800 hover:bg-indigo-800 disabled:cursor-not-allowed disabled:border-indigo-300 disabled:bg-indigo-300">
					Save
				</button>
			</div>
		</form>
	</div>
</template>

<style>
.dp__theme_light {
	--dp-button-height: 65px;
}
</style>
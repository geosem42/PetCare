<script setup>
import { ref, defineProps, onMounted } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { useToast } from "vue-toastification"
import { PlusIcon, TrashIcon } from "@heroicons/vue/24/outline/index.js";
import { validateForm, watchFields, errors } from '@/Validation/Pets/MedicalHistories/Index';

import moment from 'moment';

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
	await fetchHistories()
	watchFields(historiesForm.value);
})

const historiesForm = ref([
	{ condition: '', diagnosis_date: '', treatment: '', notes: '' }
]);

const addHistory = () => {
	historiesForm.value.push({ pet_id: '', condition: '', diagnosis_date: '', treatment: '', notes: '' });
};

const deleteHistory = async (index) => {
	if (historiesForm.value.length === 1) {
		const historyId = historiesForm.value[index].id;

		// Clear the form
		historiesForm.value[index] = { condition: '', diagnosis_date: '', treatment: '', notes: '' };

		// Send the id to the backend
		await axios.delete(`/pets/${pet.id}/histories/${historyId}`);

		toast.success('Medical History successfully deleted!');
	} else {
		const history = historiesForm.value[index];
		await axios.delete(`/pets/${pet.id}/histories/${history.id}`);
		historiesForm.value.splice(index, 1);
		toast.success('Medical History successfully deleted!');
	}
};

const storeHistory = async () => {
	isSubmitting.value = true;

	validateForm(historiesForm.value);

	if (Object.keys(errors.value).length > 0) {
		toast.error("Please correct the errors in the form.");
		isSubmitting.value = false;
		return;
	}

	let submitData = { histories: historiesForm.value };

	submitData.histories.forEach((history) => {
		history.pet_id = pet.id;
		if (!history.id) {
			history.id = null;
		}
		if (history.diagnosis_date) {
			history.diagnosis_date = moment(history.diagnosis_date).format('YYYY-MM-DD');
		} else {
			delete history.diagnosis_date;
		}
	});

	const response = await axios.post(`/pets/${pet.id}/histories`, submitData, {
		headers: {
			'Content-Type': 'application/json',
		},
	});

	toast.success(response.data.message);

	errors.value = {};

	// Update the form with the returned histories
	if (response.data.histories && response.data.histories.length > 0) {
		response.data.histories.forEach((newHistory) => {
			const index = historiesForm.value.findIndex((v) => v.id === null);
			if (index !== -1) {
				// Replace temporary history with real one
				historiesForm.value.splice(index, 1, newHistory);
			}
		});
	}

	isSubmitting.value = false;
};


const fetchHistories = async () => {
	const response = await axios.get(`/pets/${pet.id}/histories`);
	historiesForm.value = response.data;

	if (historiesForm.value.length === 0) {
		historiesForm.value.push({ condition: '', diagnosis_date: '', treatment: '', notes: '' });
	}
};
</script>

<template>
	<div class="max-w-full bg-white rounded-md mt-2">

		<div class="text-lg font-semibold leading-6 text-gray-900 border-b p-6 flex justify-between items-center">
			Medical History
			<button @click.stop.prevent="addHistory" class="bg-indigo-500 hover:bg-indigo-700 text-white p-2 rounded-md">
				<PlusIcon class="h-6 w-6" />
			</button>
		</div>

		<form @submit.prevent="storeHistory" class="mt-6">

			<div v-for="(history, index) in historiesForm" :key="index" class="grid grid-cols-12 gap-5 mb-5 p-5">
				<div class=" col-span-12 md:col-span-6 lg:col-span-2">
					<label for="vaccine_name" class="mb-2 block text-sm font-medium text-gray-500">Condition</label>
					<input v-model="history.condition" name="condition" id="condition" placeholder="Condition"
						class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm"
						:class="{ 'border-red-500': errors[`histories[${index}].condition`] }">
					<span class="text-red-500 text-xs">{{ errors[`histories[${index}].condition`] }}</span>
				</div>

				<div class="col-span-12 md:col-span-6 lg:col-span-2">
					<label for="diagnosis_date" class="mb-2 block text-sm font-medium text-gray-500">Diagnosis Date</label>
					<input type="date" v-model="history.diagnosis_date"
						class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm"
						:class="{ 'border-red-500': errors[`histories[${index}].diagnosis_date`] }">
					<span class="text-red-500 text-xs">{{ errors[`histories[${index}].diagnosis_date`] }}</span>
				</div>

				<div class="col-span-12 md:col-span-6 lg:col-span-2">
					<label for="treatment" class="mb-2 block text-sm font-medium text-gray-500">Treatment</label>
					<input v-model="history.treatment" name="treatment" id="treatment" placeholder="Treatment"
						class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm"
						:class="{ 'border-red-500': errors[`histories[${index}].treatment`] }">
					<span class="text-red-500 text-xs">{{ errors[`histories[${index}].treatment`] }}</span>
				</div>

				<div class="col-span-12 md:col-span-6 lg:col-span-5">
					<label for="notes" class="mb-2 block text-sm font-medium text-gray-500">Notes</label>
					<textarea v-model="history.notes" name="notes" id="notes" placeholder="Notes" rows="5"
						class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm"
						:class="{ 'border-red-500': errors[`histories[${index}].notes`] }"></textarea>
					<span class="text-red-500 text-xs">{{ errors[`histories[${index}].notes`] }}</span>
				</div>

				<div class="col-span-12 sm:col-span-1 mt-7">
					<button v-if="history.id" @click.stop.prevent="deleteHistory(index)"
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

<style scoped>
.multiselect>>>.multiselect__tags {
	border: 1px solid #D1D5DBFF;
}

.multiselect.error>>>.multiselect__tags {
	border: 1px solid #f05252;
}

.dp__theme_light {
	--dp-border-color: rgb(209 213 219);
}
</style>
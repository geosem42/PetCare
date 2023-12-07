<script setup>
import { ref, defineProps, onMounted } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { useToast } from "vue-toastification"
import { PlusIcon, TrashIcon } from "@heroicons/vue/24/outline/index.js";
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import moment from 'moment';

const isSubmitting = ref(false)
const errors = ref({});
const toast = useToast();

const { pet } = usePage().props

const props = defineProps({
	pet: {
		type: Object,
		required: true
	}
})

onMounted(async () => {
	await fetchVaccinations()
})

const vaccinationsForm = ref([
	{ vaccine_name: '', administered_at: '', batch_number: '', administering_veterinarian: '', notes: '' }
]);

const addVaccination = () => {
  vaccinationsForm.value.push({ pet_id: '', vaccine_name: '', administered_at: '', batch_number: '', administering_veterinarian: '', notes: '' });
};

const deleteVaccination = async (index) => {
  const vaccinationId = vaccinationsForm.value[index].id;

  if (vaccinationsForm.value.length === 1) {
    // Clear the form when there's only one vaccination entry
    vaccinationsForm.value[index] = { vaccine_name: '', administered_at: '', batch_number: '', administering_veterinarian: '', notes: '' };
  } else {
    // Remove the vaccination entry from the form array
    vaccinationsForm.value.splice(index, 1);
  }

  await axios.delete(`/pets/${pet.id}/vaccinations/${vaccinationId}`);
  toast.success('Vaccination successfully deleted!');
};

const storeVaccination = async () => {
  isSubmitting.value = true;

  let submitData = { vaccinations: vaccinationsForm.value };

  submitData.vaccinations.forEach((vaccination) => {
    vaccination.pet_id = pet.id;
    if (!vaccination.id) {
      vaccination.id = null;
    }

    if (vaccination.administered_at) {
      vaccination.administered_at = moment(vaccination.administered_at).format(
        'YYYY-MM-DD HH:mm:ss'
      );
    } else {
      delete vaccination.administered_at;
    }
  });

  const response = await axios.post(`/pets/${pet.id}/vaccinations`, submitData, {
    headers: {
      'Content-Type': 'application/json',
    },
  });

  toast.success(response.data.message);

  errors.value = {};

  // Update the form with the returned vaccinations
  if (response.data.vaccinations && response.data.vaccinations.length > 0) {
    response.data.vaccinations.forEach((newVaccination) => {
      const index = vaccinationsForm.value.findIndex((v) => v.id === null);
      if (index !== -1) {
        // Replace temporary vaccination with real one
        vaccinationsForm.value.splice(index, 1, newVaccination);
      }
    });
  }

  isSubmitting.value = false;
};

const fetchVaccinations = async () => {
  const response = await axios.get(`/pets/${pet.id}/vaccinations`);
  vaccinationsForm.value = response.data;

  if (vaccinationsForm.value.length === 0) {
    vaccinationsForm.value.push({ vaccine_name: '', administered_at: '', batch_number: '', administering_veterinarian: '', notes: '' });
  }
};
</script>

<template>
  <div class="max-w-full bg-white rounded-md mt-2">
			
        <div class="text-lg font-semibold leading-6 text-gray-900 border-b p-6 flex justify-between items-center">
          Vaccinations
          <button @click.stop.prevent="addVaccination" class="bg-indigo-500 hover:bg-indigo-700 text-white p-2 rounded-md">
            <PlusIcon class="h-6 w-6" />
          </button>
        </div>

			<form @submit.prevent="storeVaccination" class="mt-6">
				
				<div v-for="(vaccination, index) in vaccinationsForm" :key="index" class="grid grid-cols-12 gap-5 mb-5 p-5">
					<div class=" col-span-12 md:col-span-6 lg:col-span-2">
						<label for="vaccine_name" class="mb-2 block text-sm font-medium text-gray-500">Vaccine Name</label>
						<input v-model="vaccination.vaccine_name" name="vaccine_name" id="vaccine_name" placeholder="Vaccine Name"
							class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm">
					</div>

					<div class="col-span-12 md:col-span-6 lg:col-span-2">
						<label for="administered_at" class="mb-2 block text-sm font-medium text-gray-500">Date</label>
							<VueDatePicker v-model="vaccination.administered_at" input-class-name="dp-custom-input"></VueDatePicker>
					</div>

					<div class="col-span-12 md:col-span-6 lg:col-span-2">
						<label for="batch_number" class="mb-2 block text-sm font-medium text-gray-500">Batch Number</label>
						<input v-model="vaccination.batch_number" name="batch_number" id="batch_number" placeholder="Batch Number"
							class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm">
					</div>

					<div class="col-span-12 md:col-span-6 lg:col-span-2">
						<label for="administering_veterinarian" class="mb-2 block text-sm font-medium text-gray-500">Administering Veterinarian</label>
						<input v-model="vaccination.administering_veterinarian" name="administering_veterinarian" id="administering_veterinarian" placeholder="Administering Veterinarian"
							class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm">
					</div>

					<div class="col-span-12 md:col-span-6 lg:col-span-3">
						<label for="notes" class="mb-2 block text-sm font-medium text-gray-500">Notes</label>
						<textarea v-model="vaccination.notes" name="notes" id="notes" placeholder="Notes" rows="5"
							class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm"></textarea>
					</div>

					<div class="col-span-12 sm:col-span-1 mt-7">
						<button v-if="vaccination.id" @click.stop.prevent="deleteVaccination(index)" class="text-red-500 hover:text-red-700 p-2 rounded-md">
							<TrashIcon class="h-6 w-6" />
						</button>
					</div>

				</div>

				<div class="col-span-12 m-5 pb-5">
        <button type="submit" :disabled="isSubmitting"
          class="w-full rounded-lg border border-indigo-700 bg-indigo-700 px-8 py-4 text-center text-lg font-medium text-white shadow-sm transition-all hover:border-indigo-800 hover:bg-indigo-800 disabled:cursor-not-allowed disabled:border-indigo-300 disabled:bg-indigo-300">
          Save Vaccinations
        </button>
      </div>
			</form>
		</div>
</template>

<style lang="css">
.dp__input {
  height: 42px !important;
  border-color: rgb(209 213 219) !important;
}
</style>
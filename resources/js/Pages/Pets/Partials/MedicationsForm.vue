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
	await fetchMedications()
})

const medicationsForm = ref([
	{ medication_name: '', administered_at: '', dosage: '', frequency: '', administering_veterinarian: '', notes: '' }
]);

const addMedication = () => {
  medicationsForm.value.push({ pet_id: '', medication_name: '', administered_at: '', dosage: '', frequency: '', administering_veterinarian: '', notes: '' });
};

const deleteMedication = async (index) => {
  if (medicationsForm.value.length === 1) {
    const medicationId = medicationsForm.value[index].id;

    // Clear the form
    medicationsForm.value[index] = {
      medication_name: '',
      administered_at: null,
      dosage: '',
      frequency: '',
      administering_veterinarian: '',
      notes: '',
    };

    // Send the id to the backend
    await axios.delete(`/pets/${pet.id}/medications/${medicationId}`);

    toast.success('Medication successfully deleted!');
  } else {
    const medication = medicationsForm.value[index];
    await axios.delete(`/pets/${pet.id}/medications/${medication.id}`);
    medicationsForm.value.splice(index, 1);
    toast.success('Medication successfully deleted!');
  }
};

const storeMedication = async () => {
  isSubmitting.value = true;

  let submitData = { medications: medicationsForm.value };

  submitData.medications.forEach((medication) => {
    medication.pet_id = pet.id;
    if (!medication.id) {
      medication.id = null;
    }

    if (medication.administered_at) {
      medication.administered_at = moment(medication.administered_at).format(
        'YYYY-MM-DD HH:mm:ss'
      );
    } else {
      delete medication.administered_at;
    }
  });

  const response = await axios.post(`/pets/${pet.id}/medications`, submitData, {
    headers: {
      'Content-Type': 'application/json',
    },
  });

  toast.success(response.data.message);

  errors.value = {};

  // Update the form with the returned medications
  if (response.data.medications && response.data.medications.length > 0) {
    response.data.medications.forEach((newMedication) => {
      const index = medicationsForm.value.findIndex((v) => v.id === null);
      if (index !== -1) {
        // Replace temporary medication with real one
        medicationsForm.value.splice(index, 1, newMedication);
      }
    });
  }

  isSubmitting.value = false;
};

const fetchMedications = async () => {
  const response = await axios.get(`/pets/${pet.id}/medications`);
  medicationsForm.value = response.data;

  if (medicationsForm.value.length === 0) {
    medicationsForm.value.push({
      medication_name: '',
      administered_at: null,
      dosage: '',
      frequency: '',
      administering_veterinarian: '',
      notes: '',
    });
  }
};
</script>

<template>
  <div class="max-w-full bg-white rounded-md mt-2">
			
        <div class="text-lg font-semibold leading-6 text-gray-900 border-b p-6 flex justify-between items-center">
          Medications
          <button @click.stop.prevent="addMedication" class="bg-indigo-500 hover:bg-indigo-700 text-white p-2 rounded-md">
            <PlusIcon class="h-6 w-6" />
          </button>
        </div>

			<form @submit.prevent="storeMedication" class="mt-6">
				
				<div v-for="(medication, index) in medicationsForm" :key="index" class="grid grid-cols-12 gap-5 mb-5 p-5">
					<div class=" col-span-12 md:col-span-6 lg:col-span-2">
						<label for="vaccine_name" class="mb-2 block text-sm font-medium text-gray-500">Medication Name</label>
						<input v-model="medication.medication_name" name="medication_name" id="medication_name" placeholder="Medication Name"
							class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm">
					</div>

					<div class="col-span-12 md:col-span-6 lg:col-span-2">
						<label for="administered_at" class="mb-2 block text-sm font-medium text-gray-500">Date</label>
							<VueDatePicker v-model="medication.administered_at"></VueDatePicker>
					</div>

					<div class="col-span-12 md:col-span-6 lg:col-span-1">
						<label for="batch_number" class="mb-2 block text-sm font-medium text-gray-500">Dosage</label>
						<input v-model="medication.dosage" name="dosage" id="dosage" placeholder="Dosage"
							class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm">
					</div>

					<div class="col-span-12 md:col-span-6 lg:col-span-1">
						<label for="frequency" class="mb-2 block text-sm font-medium text-gray-500">Frequency</label>
						<input v-model="medication.frequency" name="frequency" id="frequency" placeholder="Frequency"
							class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm">
					</div>

          <div class="col-span-12 md:col-span-6 lg:col-span-2">
						<label for="administering_veterinarian" class="mb-2 block text-sm font-medium text-gray-500">Administering Veterinarian</label>
						<input v-model="medication.administering_veterinarian" name="administering_veterinarian" id="frequency" placeholder="Administering Veterinarian"
							class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm">
					</div>

					<div class="col-span-12 md:col-span-6 lg:col-span-3">
						<label for="notes" class="mb-2 block text-sm font-medium text-gray-500">Notes</label>
						<textarea v-model="medication.notes" name="notes" id="notes" placeholder="Notes" rows="5"
							class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm"></textarea>
					</div>

					<div class="col-span-12 sm:col-span-1 mt-7">
						<button v-if="medication.id" @click.stop.prevent="deleteMedication(index)" class="bg-red-500 hover:bg-red-700 text-white p-2 rounded-md">
							<TrashIcon class="h-6 w-6" />
						</button>
					</div>

				</div>

				<div class="col-span-12 m-5 pb-5">
        <button type="submit" :disabled="isSubmitting"
          class="w-full rounded-lg border border-indigo-700 bg-indigo-700 px-8 py-4 text-center text-lg font-medium text-white shadow-sm transition-all hover:border-indigo-800 hover:bg-indigo-800 disabled:cursor-not-allowed disabled:border-indigo-300 disabled:bg-indigo-300">
          Save Medications
        </button>
      </div>
			</form>
		</div>
</template>
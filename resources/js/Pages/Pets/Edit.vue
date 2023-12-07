<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref, watch, nextTick, defineProps, onMounted, reactive } from 'vue'
import { usePage } from "@inertiajs/vue3"
import VueMultiselect from 'vue-multiselect'
import 'vue-multiselect/dist/vue-multiselect.css'
import { useToast } from "vue-toastification"
import '@vuepic/vue-datepicker/dist/main.css';
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue'
import VaccinationsForm from '@/Pages/Pets/Partials/VaccinationsForm.vue'
import MedicationsForm from '@/Pages/Pets/Partials/MedicationsForm.vue'
import MedicalHistoriesForm from '@/Pages/Pets/Partials/MedicalHistoriesForm.vue'
import SurgicalHistoriesForm from '@/Pages/Pets/Partials/SurgicalHistoriesForm.vue'
import Gallery from '@/Pages/Pets/Partials/Gallery.vue'
import { validateForm, errors, watchFields } from '@/Validation/Pets/Index'

const isSubmitting = ref(false)
const selectedUser = ref(null)
const matchingUsers = ref([])
const selectedSpecies = ref(null)
const selectedBreed = ref(null)
const matchingSpecies = ref([])
const matchingBreeds = ref([])
const loadingBreeds = ref(false)
const selectedFile = ref(null)
const tabs = ref(['Vaccinations', 'Medical History', 'Medications', 'Surgical History', 'Gallery'])
const toast = useToast()
const newImage = ref(null)

const { pet } = usePage().props

// Get the pet data from the prop
const props = defineProps({
	pet: {
		type: Object,
		required: true
	}
})

// Initialize the form with the pet data
const editForm = reactive({
	name: props.pet.name,
	species_id: props.pet.species_id,
	breed_id: props.pet.breed_id,
	age: props.pet.age,
	gender: props.pet.gender,
	photo: {
		file: null,
		url: props.pet.photo
	},
	client_id: props.pet.client_id
})

// Set the initial values for the multiselect components
selectedUser.value = props.pet.client 
  ? { id: props.pet.client_id, name: props.pet.client.name } 
  : null;

selectedSpecies.value = props.pet.species 
  ? { id: props.pet.species_id, name: props.pet.species.name } 
  : null;

selectedBreed.value = props.pet.breed 
  ? { id: props.pet.breed_id, name: props.pet.breed.name } 
  : null;

// Set the initial value for the file input


const handleFileChange = (event) => {
	selectedFile.value = event.target.files[0];

	if (selectedFile.value) {
		editForm.photo = {
			file: selectedFile.value,
			url: URL.createObjectURL(selectedFile.value)
		};
	}
}

const handleFileDrop = (event) => {
	selectedFile.value = event.dataTransfer.files[0];

	// Update the createForm’s photo property with the URL for display purposes
	if (selectedFile.value) {
		newImage.value = URL.createObjectURL(selectedFile.value);
	}
}

const fetchAllClients = async () => {
  const response = await axios.get('/pets/fetchAllClients');
  matchingUsers.value = response.data.slice(0, 10);
}

const fetchUsers = async (query) => {
  const response = await axios.get(`/pets/users`, { params: { name: query } });
  matchingUsers.value = response.data.slice(0, 10);
}

const editPet = async () => {
  isSubmitting.value = true;

  validateForm(editForm);

	// If there are any errors, don't submit the form
	if (Object.keys(errors.value).length > 0) {
		toast.error("Please correct the errors in the form.");
		isSubmitting.value = false;
		return;
	}

  const formData = new FormData();

  let submitData = { ...editForm, ...editForm.value };
	submitData.species_id = selectedSpecies.value ? selectedSpecies.value.id : null;
  submitData.breed_id = selectedBreed.value ? selectedBreed.value.id : null;
  submitData.client_id = selectedUser.value ? selectedUser.value.id : null;

  // Append each property of submitData to formData
  for (let property in submitData) {
    if (submitData[property] !== null && submitData[property] !== '') {
      if (property === 'photo' && submitData.photo && submitData.photo.file instanceof File) {
        // Include the photo field only if a new photo file has been selected
        formData.append(property, submitData.photo.file);
      } else if (property !== 'photo') {
        // Append other properties to formData
        formData.append(property, submitData[property]);
      }
    }
  }

  const response = await axios.post(`/pets/${pet.id}`, formData, {
    headers: {
      'Content-Type': 'multipart/form-data',
    },
  })

  toast.success(response.data.message)

  isSubmitting.value = false;
}

const fetchSpecies = async (query) => {
  const response = await axios.get(`/pets/species`, { params: { name: query } });
  
  // Limit the initially fetched species to 10
  matchingSpecies.value = response.data.slice(0, 10);
  await nextTick();
}

const fetchAllSpecies = async () => {
  const response = await axios.get('/pets/fetchAllSpecies');
  matchingSpecies.value = response.data;
  await nextTick();
}

const setUserId = () => {
	if (selectedUser.value) {
		editForm.client_id = selectedUser.value.id;
	} else {
		editForm.client_id = ''
	}
}

const setSpeciesId = () => {
	if (selectedSpecies.value) {
		editForm.species_id = selectedSpecies.value.id;
	}
}

const fetchBreeds = async (speciesId) => {
  loadingBreeds.value = true;
  
  const response = await axios.get(`/pets/fetchAllBreeds`, {
    params: { species_id: speciesId }
  });
  
  matchingBreeds.value = response.data;
  await nextTick();

  loadingBreeds.value = false;
}

const fetchAllBreeds = async () => {
  loadingBreeds.value = true;
  
  const response = await axios.get(`/pets/fetchAllBreeds`, {
    params: { species_id: selectedSpecies.value.id }
  });
  
  matchingBreeds.value = response.data;
  await nextTick();

  loadingBreeds.value = false;
}

watch(selectedUser, () => {
  if (selectedUser.value) {
    setUserId();
  } else {
    editForm.client_id = '';
  }
})
watch(selectedSpecies, () => {
	if (selectedSpecies.value) {
		setSpeciesId();
		fetchBreeds(selectedSpecies.value.id);
	} else {
    editForm.species_id = '';
  }
})
watch(selectedBreed, () => {
	if (selectedBreed.value) {
		editForm.breed_id = selectedBreed.value.id;
	}
})

onMounted(async () => {
	errors.value = {}
	await fetchAllClients()
	await fetchAllSpecies()
	await fetchAllBreeds()
	watchFields(editForm);
})

</script>

<template>
	<AppLayout title="Edit Pet">
		<template #header>
			<h2 class="text-lg font-semibold leading-6 text-gray-900">
				Edit Pet: {{ pet.name }}
			</h2>
		</template>

		<div class="max-w-full bg-white p-5 rounded-md">
			<form @submit.prevent="editPet" enctype="multipart/form-data" class="space-y-5">
				<div class="grid grid-cols-12 gap-5">
					<div class="col-span-6">
						<label for="name" class="mb-2 block text-sm font-medium text-gray-500">Name</label>
						<input v-model="editForm.name" type="text" id="name"
							class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm"
							:class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': errors.name }"
							placeholder="Pet Name" />
						<div v-if="errors.name" class="text-sm text-red-500 mt-1">
							{{ errors.name }}
						</div>
					</div>
					<div class="col-span-6">
						<label for="client_id" class="mb-2 block text-sm font-medium text-gray-500">Client</label>
						<VueMultiselect v-model="selectedUser"
							:class="{ 'error': errors.client_id }"
							:options="matchingUsers" :multiple="false" :clear-on-select="true" placeholder="Type to search" label="name"
							track-by="id" @search-change="fetchUsers" @input="setUserId">
							<template #noResult>
								Oops! No users found. Try a different search query.
							</template>
						</VueMultiselect>
						<div v-if="errors.client_id" class="text-sm text-red-500 mt-1">
							{{ errors.client_id }}
						</div>
					</div>

					<div class="col-span-12 sm:col-span-6">
						<label for="species" class="mb-2 block text-sm font-medium text-gray-500">Species</label>
						<VueMultiselect v-model="selectedSpecies"
							:class="{ 'error': errors.species_id }"
							:options="matchingSpecies" :multiple="false" :clear-on-select="true" placeholder="Type to search"
							label="name" track-by="id" @search-change="fetchSpecies" @input="setSpeciesId">
							<template #noResult>
								Oops! No species found. Try a different search query.
							</template>
						</VueMultiselect>
						<div v-if="errors.species_id" class="text-sm text-red-500 mt-1">
							{{ errors.species_id }}
						</div>
					</div>
					<div class="col-span-12 sm:col-span-6">
						<label for="breed" class="mb-2 block text-sm font-medium text-gray-500">Breed</label>
						<VueMultiselect v-model="selectedBreed" :options="matchingBreeds" :multiple="false" :clear-on-select="true"
							placeholder="Type to search" label="name" track-by="id">
							<template #noResult1>
								Oops! No breeds found. Try a different search query.
							</template>
						</VueMultiselect>
					</div>

					<div class="col-span-8 sm:col-span-10">
						<label for="gender" class="mb-2 block text-sm font-medium text-gray-500">Gender</label>
						<select v-model="editForm.gender" id="gender"
							class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50">
							<option disabled selected>Select Gender</option>
							<option value="male">Male</option>
							<option value="female">Female</option>
							<option value="none">None</option>
						</select>
					</div>
					<div class="col-span-4 sm:col-span-2">
						<label for="age" class="mb-2 block text-sm font-medium text-gray-500">Age</label>
						<input v-model="editForm.age" type="number" id="age"
							class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500"
							placeholder="1" />
					</div>

					<div class="col-span-12">
						<div class="mx-auto max-w-full">
							<label for="photo" class="mb-2 block text-sm font-medium text-gray-500">Pet Photo</label>
							<label @dragover.prevent @drop.prevent="handleFileDrop"
								class="flex w-full cursor-pointer appearance-none items-center justify-center rounded-md border-2 border-dashed border-gray-200 p-6 transition-all hover:border-indigo-700"
								:class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': errors.photo }">
								<div class="space-y-1 text-center">
									<div class="mx-auto inline-flex h-20 w-20 items-center justify-center rounded-full bg-gray-100">
										<img v-if="editForm.photo && editForm.photo.url" :src="editForm.photo.url" alt="Pet Photo" class="h-20 w-20 rounded-full" />
										<div v-else>
											<!-- Your placeholder for no image -->
											<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
												stroke="currentColor" class="h-6 w-6 text-gray-500">
												<path stroke-linecap="round" stroke-linejoin="round"
													d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" />
											</svg>
										</div>
									</div>
									<div class="text-gray-600">
										<a href="#" class="font-medium text-indigo-500 hover:text-indigo-700">Click to upload</a> or drag and
										drop
									</div>
									<p class="text-sm text-gray-500">PNG or JPG (max. 1mb)</p>
								</div>
								<input @change="handleFileChange" id="photo" name="photo" type="file" class="sr-only" />
							</label>
							<div v-if="errors.photo" class="text-sm text-red-500 mt-1">
								{{ errors.photo }}
							</div>
						</div>
					</div>

					<div class="col-span-12">
						<button type="submit" :disabled="isSubmitting"
							class="w-full rounded-lg border border-indigo-700 bg-indigo-700 px-8 py-4 text-center text-lg font-medium text-white shadow-sm transition-all hover:border-indigo-800 hover:bg-indigo-800 disabled:cursor-not-allowed disabled:border-indigo-300 disabled:bg-indigo-300">
							Edit Pet
						</button>
					</div>

				</div>
			</form>
		</div>

		<div class="max-w-full px-2 py-10 sm:px-0">
			<TabGroup>
				<TabList class="flex flex-col sm:flex-row space-x-1 rounded-xl bg-blue-900/20 p-1">
					<Tab as="template" v-slot="{ selected }" v-for="tab in tabs" :key="tab">
						<button :class="[
							'w-full rounded-lg py-2.5 text-sm font-medium leading-5',
              'ring-white/60 ring-offset-2 focus:outline-none focus:ring-2',
              selected
                ? 'bg-white text-indigo-700 shadow'
                : 'text-blue-100 hover:bg-white/[0.12] hover:text-white',
						]">
							{{ tab }}
						</button>
					</Tab>
				</TabList>
				<TabPanels>
					<TabPanel class="mt-2">
						<VaccinationsForm :pet="pet" />
					</TabPanel>
					<TabPanel class="mt-2">
						<MedicalHistoriesForm :pet="pet" />
					</TabPanel>
					<TabPanel class="mt-2">
						<MedicationsForm :pet="pet" />
					</TabPanel>
					<TabPanel class="mt-2">
						<SurgicalHistoriesForm :pet="pet" />
					</TabPanel>
					<TabPanel class="mt-2">
						<Gallery :pet="pet" />
					</TabPanel>
				</TabPanels>
			</TabGroup>
		</div>


	</AppLayout>
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
}</style>

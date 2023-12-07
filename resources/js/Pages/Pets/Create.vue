<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref, watch, nextTick, onMounted } from 'vue'
import { useForm } from "@inertiajs/vue3"
import VueMultiselect from 'vue-multiselect'
import 'vue-multiselect/dist/vue-multiselect.css'
import { useToast } from "vue-toastification"
import { validateForm, errors, watchFields } from '@/Validation/Pets/create'

const isSubmitting = ref(false)
const selectedUser = ref(null);
const matchingUsers = ref([]);
const selectedSpecies = ref(null);
const selectedBreed = ref(null);
const matchingSpecies = ref([]);
const matchingBreeds = ref([]);
const loadingBreeds = ref(false);
const selectedFile = ref(null);

const toast = useToast();

const createForm = useForm({
	name: '',
	species_id: '',
	breed_id: '',
	age: '',
	gender: '',
	photo: null,
	client_id: ''
})
const resetForm = () => {
	createForm.name = '';
	createForm.species_id = '';
	createForm.breed_id = '';
	createForm.age = '';
	createForm.gender = '';
	createForm.photo = null;
	createForm.client_id = '';
	selectedUser.value = null;
	selectedSpecies.value = null;
	selectedBreed.value = null;
	selectedFile.value = null;
}

onMounted(async () => {
	await fetchUsers('');
	await fetchSpecies('');
	await fetchBreeds('');
	watchFields(createForm);
});

const handleFileChange = (event) => {
	selectedFile.value = event.target.files[0];

	// Update the createForm’s photo property with the File object
	if (selectedFile.value) {
		createForm.photo = {
			file: selectedFile.value,
			url: URL.createObjectURL(selectedFile.value)
		};
	}
}

const fetchUsers = async (query) => {
	const response = await axios.get(`/pets/users?name=${query}`);
	const data = response.data;

	// Limit the initially fetched users to 10
	matchingUsers.value = data.slice(0, 10);
};


const createPet = async () => {
	isSubmitting.value = true;

	validateForm(createForm);

	// If there are any errors, don't submit the form
	if (Object.keys(errors.value).length > 0) {
		toast.error("Please correct the errors in the form.");
		isSubmitting.value = false;
		return;
	}

	const formData = new FormData();
	formData.append('name', createForm.name);
	formData.append('species_id', createForm.species_id);
	formData.append('breed_id', createForm.breed_id);
	formData.append('age', createForm.age);
	formData.append('gender', createForm.gender);
	if (createForm.photo && createForm.photo.file instanceof File) {
		formData.append('photo', createForm.photo.file);
	}
	formData.append('client_id', createForm.client_id);

	const response = await axios.post('/pets/store', formData, {
		headers: {
			'Content-Type': 'multipart/form-data',
		},
	});

	resetForm();

	toast.success(response.data.message);

	isSubmitting.value = false;
};


const setUserId = () => {
	if (selectedUser.value) {
		errors.client_id = [];
		createForm.client_id = selectedUser.value.id;
	}
};

watch(selectedUser, () => {
	if (selectedUser.value) {
		setUserId();
	}
});

const fetchSpecies = async (query) => {
	const response = await axios.get(`/pets/species?name=${query}`);
	const data = response.data;
	matchingSpecies.value = data.slice(0, 10);
};


const setSpeciesId = () => {
	if (selectedSpecies.value) {
		errors.species_id = [];
		createForm.species_id = selectedSpecies.value.id;
	}
};

const fetchBreeds = async (speciesId) => {
	loadingBreeds.value = true;

	const response = await axios.get(`/pets/breeds?species_id=${speciesId}`);
	const data = response.data;

	matchingBreeds.value = data;
	await nextTick();

	loadingBreeds.value = false;
};

watch(selectedSpecies, () => {
	if (selectedSpecies.value) {
		setSpeciesId();
		fetchBreeds(selectedSpecies.value.id);
	}
});
watch(selectedBreed, () => {
	if (selectedBreed.value) {
		errors.breed_id = [];
		createForm.breed_id = selectedBreed.value.id;
	}
});

onMounted(async () => {
	errors.value = {}
})
</script>

<template>
	<AppLayout title="Add Pet">
		<template #header>
			<h2 class="text-lg font-semibold leading-6 text-gray-900">
				Add Pet
			</h2>
		</template>

		<div class="max-w-full bg-white p-5 rounded-md">
			<form @submit.prevent="createPet" enctype="multipart/form-data" class="space-y-5">
				<div class="grid grid-cols-12 gap-5">

					<div class="col-span-6">
						<label for="name" class="mb-2 block text-sm font-medium text-gray-700">Name</label>
						<input v-model="createForm.name" type="text" id="name"
							class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm"
							:class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': errors.name }" placeholder="Pet Name" />
						<div v-if="errors.name" class="text-sm text-red-500 mt-1">
							{{ errors.name }}
						</div>
					</div>
					<div class="col-span-6">
						<label for="client_id" class="mb-2 block text-sm font-medium text-gray-700">Client</label>
						<VueMultiselect v-model="selectedUser" :class="{ 'error': errors.client_id }" :options="matchingUsers"
							:multiple="false" :clear-on-select="true" placeholder="Type to search" label="name" track-by="id"
							@search-change="fetchUsers" @input="setUserId">
							<template #noUser>
								Oops! No users found. Try a different search query.
							</template>
						</VueMultiselect>
						<div v-if="errors.client_id" class="text-sm text-red-500 mt-1">
							{{ errors.client_id }}
						</div>
					</div>

					<div class="col-span-12 sm:col-span-6">
						<label for="species" class="mb-2 block text-sm font-medium text-gray-700">Species</label>
						<VueMultiselect v-model="selectedSpecies" :class="{ 'error': errors.species_id }" :options="matchingSpecies"
							:multiple="false" :clear-on-select="true" placeholder="Type to search" label="name" track-by="id"
							@search-change="fetchSpecies" @input="setSpeciesId">
							<template #noSpecies>
								Oops! No species found. Try a different search query.
							</template>
						</VueMultiselect>
						<div v-if="errors.species_id" class="text-sm text-red-500 mt-1">
							{{ errors.species_id }}
						</div>
					</div>
					<div class="col-span-12 sm:col-span-6">
						<label for="breed" class="mb-2 block text-sm font-medium text-gray-700">Breed</label>
						<VueMultiselect v-model="selectedBreed" :options="matchingBreeds" :multiple="false" :clear-on-select="true"
							placeholder="Type to search" label="name" track-by="id">
							<template #noResult1>
								Oops! No breeds found. Try a different search query.
							</template>
						</VueMultiselect>
					</div>

					<div class="col-span-8 sm:col-span-10">
						<label for="gender" class="mb-2 block text-sm font-medium text-gray-700">Gender</label>
						<select v-model="createForm.gender" id="gender"
							class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50">
							<option disabled selected>Select Gender</option>
							<option value="male">Male</option>
							<option value="female">Female</option>
							<option value="none">None</option>
						</select>
					</div>
					<div class="col-span-4 sm:col-span-2">
						<label for="age" class="mb-2 block text-sm font-medium text-gray-700">Age</label>
						<input v-model="createForm.age" type="number" id="age"
							class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500"
							placeholder="1" />
					</div>

					<div class="col-span-12">

						<div class="mx-auto max-w-full">
							<label for="photo" class="mb-2 block text-sm font-medium text-gray-700">Pet Photo</label>
							<label
								class="flex w-full cursor-pointer appearance-none items-center justify-center rounded-md border-2 border-dashed border-gray-200 p-6 transition-all hover:border-indigo-700"
								:class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': errors.photo }">
								<div class="space-y-1 text-center">
									<div class="mx-auto inline-flex h-20 w-20 items-center justify-center rounded-full bg-gray-100">
										<img v-if="createForm.photo" :src="createForm.photo.url" alt="Pet Photo"
											class="h-20 w-20 rounded-full" />
										<div v-else>
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
							Add Pet
						</button>
					</div>

				</div>
			</form>
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
</style>
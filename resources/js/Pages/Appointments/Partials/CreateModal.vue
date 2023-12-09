<script setup>
import { onMounted, ref, watch } from 'vue'
import { useForm } from "@inertiajs/vue3"
import VueMultiselect from 'vue-multiselect'
import 'vue-multiselect/dist/vue-multiselect.css'
import { useToast } from "vue-toastification"
import { TransitionRoot, TransitionChild, Dialog, DialogPanel, DialogTitle } from '@headlessui/vue'
import { validateForm, errors, watchFields } from '@/Validation/Appointments/Index'

const selectedCreateClient = ref([]);
const matchingClients = ref([]);
const toast = useToast();
const emit = defineEmits(['closeCreateModal'])

const props = defineProps({
	isCreateModalOpen: Boolean,
  calendarOptions: Object,
  selectedClient: Object,
  fetchAllAppointments: Function
})

const createForm = useForm({
	title: '',
	client_id: '',
	start_time: '',
	end_time: '',
	description: '',
})

const resetForm = () => {
	createForm.title = '';
	createForm.client_id = '';
	createForm.start_time = '';
	createForm.end_time = '';
	createForm.description = '';
	selectedCreateClient.value = null;
}

const closeCreateModal = () => {
	emit('closeCreateModal')
	errors.value = {}
	selectedCreateClient.value = null
};

const fetchAllClients = async () => {
	const response = await axios.get('/appointments/fetchAllClients');
	matchingClients.value = response.data;
};

const searchClients = async (query) => {
	const response = await axios.get('/appointments/searchClients', { params: { query } });
	matchingClients.value = response.data;
};

const submitAppointmentForm = async () => {
	validateForm(createForm);

	if (Object.keys(errors.value).length > 0) {
		toast.error("Please correct the errors in the form.");
		return;
	}

	const formData = {
		title: createForm.title,
		client_id: selectedCreateClient.value.id,
		start_time: createForm.start_time,
		end_time: createForm.end_time,
		description: createForm.description,
	};

	await axios.post("/appointments/create", formData);
	props.fetchAllAppointments();
	closeCreateModal()
	resetForm()
	toast.success("Appointment created successfully!");
};

const setClientId = () => {
	if (selectedCreateClient.value) {
		errors.value.client_id = [];
		createForm.client_id = selectedCreateClient.value.id;
	}
};

watch(selectedCreateClient, () => {
	if (selectedCreateClient.value) {
		setClientId();
	}
});

onMounted(async () => {
	await fetchAllClients();
	watchFields(createForm);
});
</script>
<template>
  <TransitionRoot appear :show="isCreateModalOpen" as="template">
    <Dialog as="div" @close="$emit('closeCreateModal')" class="relative z-10">
      <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0" enter-to="opacity-100"
        leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
        <div class="fixed inset-0 bg-black/25" />
      </TransitionChild>

      <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center">
          <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100" leave="duration-200 ease-in" leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95">
            <DialogPanel
              class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
              <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">
                Add New Event
              </DialogTitle>
              <form @submit.prevent="submitAppointmentForm" class="py-5">
                <div class="grid gap-4 mb-4 grid-cols-2">
                  <div class="col-span-2">
                    <label for="title" class="block mb-2 text-xs font-medium text-gray-500 dark:text-white">Title</label>
                    <input v-model="createForm.title" type="text" name="title" id="title"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500"
                      :class="errors.title ? 'border-red-500' : 'border-gray-300'" placeholder="Type event title">
                    <div v-if="errors.title" class="text-xs text-red-500 mt-1">
                      {{ errors.title }}
                    </div>
                  </div>
                  <div class="col-span-2">
                    <label for="client"
                      class="block mb-2 text-xs font-medium text-gray-500 dark:text-white">Client</label>
                    <VueMultiselect v-model="selectedCreateClient" :options="matchingClients" :multiple="false"
                      :clear-on-select="true" placeholder="Type to search" label="name" track-by="id"
                      @search-change="searchClients" @input="setClientId" :class="{ 'error': errors.client_id }">
                      <template #noUser>
                        Oops! No users found. Try a different search query.
                      </template>
                    </VueMultiselect>
                    <div v-if="errors.client_id" class="text-xs text-red-500 mt-1">
                      {{ errors.client_id }}
                    </div>
                  </div>
                  <div class="col-span-2 sm:col-span-1">
                    <label for="start_time" class="block mb-2 text-xs font-medium text-gray-500 dark:text-white">Start
                      Date</label>
                    <input v-model="createForm.start_time" type="datetime-local" name="start_time" id="start_time"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500"
                      :class="errors.start_time ? 'border-red-500' : 'border-gray-300'">
                    <div v-if="errors.start_time" class="text-xs text-red-500 mt-1">
                      {{ errors.start_time }}
                    </div>
                  </div>
                  <div class="col-span-2 sm:col-span-1">
                    <label for="end_time" class="block mb-2 text-xs font-medium text-gray-500 dark:text-white">End
                      Date</label>
                    <input v-model="createForm.end_time" type="datetime-local" name="end_time" id="end_time"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500"
                      :class="errors.end_time ? 'border-red-500' : 'border-gray-300'">
                    <div v-if="errors.end_time" class="text-xs text-red-500 mt-1">
                      {{ errors.end_time }}
                    </div>
                  </div>
                  <div class="col-span-2">
                    <label for="description"
                      class="block mb-2 text-xs font-medium text-gray-500 dark:text-white">Description</label>
                    <textarea v-model="createForm.description" id="description" rows="4"
                      class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500"
                      placeholder="Write event description here"></textarea>
                  </div>
                </div>
                <button type="submit"
                  class="text-white inline-flex items-center bg-indigo-700 hover:bg-indigo-800 focus:ring-4 focus:outline-none focus:ring-indigo-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800">
                  <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                      d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                      clip-rule="evenodd"></path>
                  </svg>
                  Add Event
                </button>
              </form>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<style scoped>
.multiselect>>>.multiselect__tags {
	border: 1px solid #D1D5DBFF;
}

.multiselect.error>>>.multiselect__tags {
	border: 1px solid #f05252;
}
</style>
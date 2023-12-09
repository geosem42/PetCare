<script setup>
import { onMounted, ref, watch } from 'vue'
import { useForm } from "@inertiajs/vue3"
import VueMultiselect from 'vue-multiselect'
import 'vue-multiselect/dist/vue-multiselect.css'
import { useToast } from "vue-toastification"
import { TransitionRoot, TransitionChild, Dialog, DialogPanel, DialogTitle } from '@headlessui/vue'
import { validateForm, errors, watchFields } from '@/Validation/Appointments/Index'
import { TrashIcon } from '@heroicons/vue/24/outline'

const selectedEditClient = ref([]);
const toast = useToast();
const emit = defineEmits(['closeEditModal'])

const props = defineProps({
	isEditModalOpen: Boolean,
  calendarOptions: Object,
  selectedClient: Object,
  fetchAllAppointments: Function,
  fetchAllClients: Function,
	searchClients: Function,
  matchingClients: Object,
  editData: Object
})

const editForm = useForm({
	title: '',
	client_id: '',
	start_time: '',
	end_time: '',
	description: '',
})

const resetForm = () => {
	editForm.title = '';
	editForm.client_id = '';
	editForm.start_time = '';
	editForm.end_time = '';
	editForm.description = '';
	selectedEditClient.value = null;
}

const closeEditModal = () => {
	emit('closeEditModal')
	errors.value = {}
	selectedEditClient.value = null
};

watch(() => props.editData, (newVal) => {
	if (newVal) {
		editForm.id = newVal.id;
		editForm.title = newVal.title;
		editForm.client_id = newVal.client_id;
		editForm.start_time = newVal.start_time;
		editForm.end_time = newVal.end_time;
		editForm.description = newVal.description;
		selectedEditClient.value = newVal.client;
	}
}, { immediate: true });

const submitEditForm = async () => {
	validateForm(editForm);

	if (Object.keys(errors.value).length > 0) {
		toast.error("Please correct the errors in the form.");
		return;
	}

	const formData = {
		title: editForm.title,
		client_id: selectedEditClient.value.id,
		start_time: editForm.start_time,
		end_time: editForm.end_time,
		description: editForm.description,
	};

	await axios.put(`/appointments/${editForm.id}`, formData);
	props.fetchAllAppointments();
	closeEditModal()
	resetForm()
	toast.success("Appointment created successfully!");
};

const deleteEvent = async (id) => {
    await axios.delete(`/appointments/${id}`);
    props.fetchAllAppointments();
    closeEditModal();
    toast.success("Appointment deleted successfully!");
};

const setClientId = () => {
	if (selectedEditClient.value) {
		errors.value.client_id = [];
		editForm.client_id = selectedEditClient.value.id;
	}
};

watch(selectedEditClient, () => {
	if (selectedEditClient.value) {
		setClientId();
	} else {
		editForm.client_id = null;
	}
	validateForm(editForm);
});

onMounted(async () => {
	await props.fetchAllClients();
	watchFields(editForm);
});
</script>

<template>
  <TransitionRoot appear :show="isEditModalOpen" as="template">
    <Dialog as="div" @close="closeEditModal" class="relative z-10">
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
              <DialogTitle as="h3" class="flex justify-between items-center text-lg font-medium leading-6 text-gray-900">
                <div>Edit Event</div>
                <button @click="deleteEvent(editForm.id)"
                  class="inline-flex items-center text-red-700 hover:text-red-900 font-medium rounded-lg text-sm py-2.5 text-center">
                  <TrashIcon class="w-6 h-6" />
                </button>
              </DialogTitle>
              <form @submit.prevent="submitEditForm" class="py-5">
                <div class="grid gap-4 mb-4 grid-cols-2">
                  <div class="col-span-2">
                    <label for="title" class="block mb-2 text-xs font-medium text-gray-500 dark:text-white">Title</label>
                    <input v-model="editForm.title" type="text" name="title" id="title"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500"
                      :class="errors.title ? 'border-red-500' : 'border-gray-300'" placeholder="Type event title">
                    <div v-if="errors.title" class="text-sm text-red-500 mt-1">
                      {{ errors.title }}
                    </div>
                  </div>
                  <div class="col-span-2">
                    <label for="client"
                      class="block mb-2 text-xs font-medium text-gray-500 dark:text-white">Client</label>
                    <VueMultiselect v-model="selectedEditClient" :options="props.matchingClients" :multiple="false"
                      :clear-on-select="true" placeholder="Type to search" label="name" track-by="id"
                      @search-change="props.searchClients" @input="setClientId" :class="{ 'error': errors.client_id }">
                      <template #noUser>
                        Oops! No users found. Try a different search query.
                      </template>
                    </VueMultiselect>
                    <div v-if="errors.client_id" class="text-sm text-red-500 mt-1">
                      {{ errors.client_id }}
                    </div>
                  </div>
                  <div class="col-span-2 sm:col-span-1">
                    <label for="start_time" class="block mb-2 text-xs font-medium text-gray-500 dark:text-white">Start
                      Date</label>
                    <input v-model="editForm.start_time" type="datetime-local" name="start_time" id="start_time"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500"
                      :class="errors.start_time ? 'border-red-500' : 'border-gray-300'">
                    <div v-if="errors.start_time" class="text-sm text-red-500 mt-1">
                      {{ errors.start_time }}
                    </div>
                  </div>
                  <div class="col-span-2 sm:col-span-1">
                    <label for="end_time" class="block mb-2 text-xs font-medium text-gray-500 dark:text-white">End
                      Date</label>
                    <input v-model="editForm.end_time" type="datetime-local" name="end_time" id="end_time"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500"
                      :class="errors.end_time ? 'border-red-500' : 'border-gray-300'">
                    <div v-if="errors.end_time" class="text-sm text-red-500 mt-1">
                      {{ errors.end_time }}
                    </div>
                  </div>
                  <div class="col-span-2">
                    <label for="description"
                      class="block mb-2 text-xs font-medium text-gray-500 dark:text-white">Description</label>
                    <textarea v-model="editForm.description" id="description" rows="4"
                      class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500"
                      placeholder="Write event description here"></textarea>
                  </div>
                </div>
                <div class="flex justify-between">
                  <button type="submit"
                    class="text-white inline-flex items-center bg-indigo-700 hover:bg-indigo-800 focus:ring-4 focus:outline-none focus:ring-indigo-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800">
                    <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                      xmlns="http://www.w3.org/2000/svg">
                      <path fill-rule="evenodd"
                        d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                        clip-rule="evenodd"></path>
                    </svg>
                    Update Event
                  </button>
                </div>
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
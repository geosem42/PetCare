<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { onMounted, ref, reactive } from 'vue'
import { useForm } from "@inertiajs/vue3"
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'
import VueMultiselect from 'vue-multiselect'
import 'vue-multiselect/dist/vue-multiselect.css'
import { useToast } from "vue-toastification"
import Swal from "sweetalert2";
import { TrashIcon } from '@heroicons/vue/24/outline'
import {
	TransitionRoot,
	TransitionChild,
	Dialog,
	DialogPanel,
	DialogTitle,
} from '@headlessui/vue'

const selectedClient = ref([]);
const matchingClients = ref([]);
const toast = useToast();

const calendarOptions = ref({
	plugins: [dayGridPlugin, interactionPlugin],
	initialView: 'dayGridMonth',
	headerToolbar: {
		left: 'prev,next today addEvent',
		center: 'title',
		right: 'dayGridMonth,dayGridWeek,dayGridDay'
	},
	eventTimeFormat: {
		hour: 'numeric', // Display the hour in 24-hour format
		minute: '2-digit', // Display the minute with two digits
		omitZeroMinute: false, // Omit the leading zero for minutes
		meridiem: true, // Add the am/pm designation
	},
	customButtons: {
		addEvent: {
			text: 'Add Event',
			click: () => {
				openModal();
			}
		}
	},
	eventClick: (info) => {
		openEditModal(info.event.id)
	},
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
	selectedClient.value = null;
}

const isModalOpen = ref(false)
const isEditModalOpen = ref(false)

const closeModal = () => {
	isModalOpen.value = false;
};

const openModal = () => {
	isModalOpen.value = true;
};

const closeEditModal = () => {
	isEditModalOpen.value = false;
};

const submitAppointmentForm = async () => {
	const formData = {
		title: createForm.title,
		client_id: selectedClient.value.id,
		start_time: createForm.start_time,
		end_time: createForm.end_time,
		description: createForm.description,
	};

	await axios.post("/appointments/create", formData);
	fetchAllAppointments()
	closeModal()
	resetForm()
	toast.success("Appointment created successfully!");
};

const setClientId = (client) => {
	selectedClient.value = client ? client.id : null;
};

const fetchAllAppointments = async () => {
	const response = await axios.get('/appointments/fetchAllAppointments');
	const events = response.data.map((appointment) => ({
		id: appointment.id,
		title: appointment.title,
		start: new Date(appointment.start_time),
		end: new Date(appointment.end_time),
		description: appointment.description
	}));
	calendarOptions.value.events = events;
};

const fetchAllClients = async () => {
	const response = await axios.get('/appointments/fetchAllClients');
	matchingClients.value = response.data;
};

const editForm = reactive({
	id: '',
	title: '',
	client_id: '',
	start_time: '',
	end_time: '',
	description: '',
});

const openEditModal = async (id) => {
	const response = await axios.get(`/appointments/${id}`);
	editForm.id = response.data.id;
	editForm.title = response.data.title;
	editForm.client_id = response.data.client_id;
	editForm.start_time = response.data.start_time;
	editForm.end_time = response.data.end_time;
	editForm.description = response.data.description;
	selectedClient.value = response.data.client;
	isEditModalOpen.value = true;
};

const submitEditForm = async () => {
	const formData = {
		title: editForm.title,
		client_id: selectedClient.value.id,
		start_time: editForm.start_time,
		end_time: editForm.end_time,
		description: editForm.description,
	};

	await axios.put(`/appointments/${editForm.id}`, formData);
	fetchAllAppointments();
	closeEditModal();
	resetForm();
	toast.success("Appointment updated successfully!");
};

const searchClients = async (query) => {
	const response = await axios.get('/appointments/searchClients', { params: { query } });
	matchingClients.value = response.data;
};

const deleteEvent = async (id) => {
	await axios.delete(`/appointments/${id}`);
	fetchAllAppointments();
	closeEditModal();
	toast.success("Appointment deleted successfully!");
};


onMounted(async () => {
	await fetchAllAppointments();
	await fetchAllClients();
});


</script>

<template>
	<AppLayout title="Appointments">
		<template #header>
			<h2 class="font-semibold text-xl text-gray-800 leading-tight">
				Appointments
			</h2>
		</template>

		<div class="rounded-xl">
			<FullCalendar :options="calendarOptions" />
		</div>

		<TransitionRoot appear :show="isModalOpen" as="template">
			<Dialog as="div" @close="closeModal" class="relative z-10">
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
											<label for="title"
												class="block mb-2 text-xs font-medium text-gray-500 dark:text-white">Title</label>
											<input v-model="createForm.title" type="text" name="title" id="title"
												class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500"
												placeholder="Type event title" required="">
										</div>
										<div class="col-span-2">
											<label for="client"
												class="block mb-2 text-xs font-medium text-gray-500 dark:text-white">Client</label>
											<VueMultiselect v-model="selectedClient" :options="matchingClients" :multiple="false"
												:clear-on-select="true" placeholder="Type to search" label="name" track-by="id"
												@search-change="searchClients" @input="setClientId">
												<template #noUser>
													Oops! No users found. Try a different search query.
												</template>
											</VueMultiselect>
										</div>
										<div class="col-span-2 sm:col-span-1">
											<label for="start_time" class="block mb-2 text-xs font-medium text-gray-500 dark:text-white">Start
												Date</label>
											<input v-model="createForm.start_time" type="datetime-local" name="start_time" id="start_time"
												class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500"
												required="">
										</div>
										<div class="col-span-2 sm:col-span-1">
											<label for="end_time" class="block mb-2 text-xs font-medium text-gray-500 dark:text-white">End
												Date</label>
											<input v-model="createForm.end_time" type="datetime-local" name="end_time" id="end_time"
												class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500"
												required="">
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
											class="inline-flex items-center text-red-700 focus:ring-4 focus:outline-none focus:ring-indigo-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800">
											<TrashIcon class="w-6 h-6" />
										</button>
								</DialogTitle>
								<form @submit.prevent="submitEditForm" class="py-5">
									<div class="grid gap-4 mb-4 grid-cols-2">
										<div class="col-span-2">
											<label for="title"
												class="block mb-2 text-xs font-medium text-gray-500 dark:text-white">Title</label>
											<input v-model="editForm.title" type="text" name="title" id="title"
												class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500"
												placeholder="Type event title" required="">
										</div>
										<div class="col-span-2">
											<label for="client"
												class="block mb-2 text-xs font-medium text-gray-500 dark:text-white">Client</label>
											<VueMultiselect v-model="selectedClient" :options="matchingClients" :multiple="false"
												:clear-on-select="true" placeholder="Type to search" label="name" track-by="id"
												@search-change="searchClients" @input="setClientId">
												<template #noUser>
													Oops! No users found. Try a different search query.
												</template>
											</VueMultiselect>
										</div>
										<div class="col-span-2 sm:col-span-1">
											<label for="start_time" class="block mb-2 text-xs font-medium text-gray-500 dark:text-white">Start
												Date</label>
											<input v-model="editForm.start_time" type="datetime-local" name="start_time" id="start_time"
												class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500"
												required="">
										</div>
										<div class="col-span-2 sm:col-span-1">
											<label for="end_time" class="block mb-2 text-xs font-medium text-gray-500 dark:text-white">End
												Date</label>
											<input v-model="editForm.end_time" type="datetime-local" name="end_time" id="end_time"
												class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500"
												required="">
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

	</AppLayout>
</template>

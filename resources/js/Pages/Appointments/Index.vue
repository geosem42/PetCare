<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { onMounted, ref, watch } from 'vue'
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'
import { validateForm, errors, watchFields } from '@/Validation/Appointments/Index'
import CreateModal from './Partials/CreateModal.vue'
import EditModal from './Partials/EditModal.vue'

const selectedCreateClient = ref([])
const selectedEditClient = ref([])
const isCreateModalOpen = ref(false)
const isEditModalOpen = ref(false)
const matchingClients = ref([]);
const editData = ref({})

const openCreateModal = () => {
	isCreateModalOpen.value = true;
}

const closeCreateModal = () => {
	isCreateModalOpen.value = false;
	errors.value = {}
	selectedCreateClient.value = null
};

const openEditModal = async (id) => {
	const response = await axios.get(`/appointments/${id}`);
	editData.value = response.data;
	isEditModalOpen.value = true;
};

const closeEditModal = () => {
	isEditModalOpen.value = false;
	errors.value = {}
	selectedEditClient.value = null
};

const calendarOptions = ref({
	plugins: [dayGridPlugin, interactionPlugin],
	initialView: 'dayGridMonth',
	headerToolbar: {
		left: 'prev,next today addEvent',
		center: 'title',
		right: 'dayGridMonth,dayGridWeek,dayGridDay'
	},
	eventTimeFormat: {
		hour: 'numeric',
		minute: '2-digit',
		omitZeroMinute: false,
		meridiem: true,
	},
	customButtons: {
		addEvent: {
			text: 'Add Event',
			click: () => {
				openCreateModal();
			}
		}
	},
	eventClick: (info) => {
		openEditModal(info.event.id)
	},
})

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

const searchClients = async (query) => {
	const response = await axios.get('/appointments/searchClients', { params: { query } });
	matchingClients.value = response.data;
};

onMounted(async () => {
	await fetchAllAppointments();
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

		<CreateModal :isCreateModalOpen="isCreateModalOpen"
								@closeCreateModal="closeCreateModal"
								:calendarOptions="calendarOptions"
								:selectedClient="selectedCreateClient"
								:fetchAllAppointments="fetchAllAppointments"
								:fetchAllClients="fetchAllClients"
								:searchClients="searchClients"
								:matchingClients="matchingClients" />
		
		<EditModal :isEditModalOpen="isEditModalOpen" 
							@closeEditModal="closeEditModal" 
							:calendarOptions="calendarOptions" 
							:selectedClient="selectedEditClient" 
							:fetchAllAppointments="fetchAllAppointments"
							:fetchAllClients="fetchAllClients"
							:searchClients="searchClients"
							:matchingClients="matchingClients"
							:editData="editData" />


	</AppLayout>
</template>

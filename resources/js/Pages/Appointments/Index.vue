<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue'
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'

const calendarOptions = ref({
  plugins: [ dayGridPlugin, interactionPlugin ],
  initialView: 'dayGridMonth',
  dateClick: handleDateClick,
  headerToolbar: {
    left: 'prev,next today addEvent',
    center: 'title',
    right: 'dayGridMonth,dayGridWeek,dayGridDay'
  },
  customButtons: {
    addEvent: {
      text: 'Add Event',
      click: function() {
        alert('clicked the custom button!');
      }
    }
  },
  events: [
    { title: 'event 1', date: '2023-12-07' },
    { title: 'event 2', date: '2023-12-07' }
  ]
})

function handleDateClick(arg) {
  alert('date click! ' + arg.dateStr)
}

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
    </AppLayout>
</template>

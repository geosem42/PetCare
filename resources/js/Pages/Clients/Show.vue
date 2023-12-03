<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { defineProps } from 'vue'
import { EnvelopeIcon, MapPinIcon, PhoneIcon, UserIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
  client: {
    type: Object,
    required: true
  }
})
</script>

<template>
  <AppLayout title="Show Client">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Show Client: {{ client.name }}
      </h2>
    </template>

    <div class="grid grid-cols-12 gap-4">
      <div class="col-span-12 lg:col-span-3">
        <div class="w-full max-w-sm overflow-hidden bg-white rounded-lg shadow-lg dark:bg-gray-800">
          <img class="object-cover object-center w-full h-56"
            src="https://images.unsplash.com/photo-1517841905240-472988babdf9?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=334&q=80"
            alt="avatar">

          <div class="flex items-center px-6 py-3 bg-gray-900">
            <UserIcon class="w-6 h-6 text-white" />

            <h1 class="mx-3 text-lg font-semibold text-white">{{ client.name }}</h1>
          </div>

          <div class="px-6 py-4">

            <div v-if="client.email" class="flex items-center mt-4 text-gray-700 dark:text-gray-200">
              <EnvelopeIcon class="w-6 h-6" />
              <h1 class="px-2 text-sm">{{ client.email }}</h1>
            </div>

            <div v-if="client.phone_number" class="flex items-center mt-4 text-gray-700 dark:text-gray-200">
              <PhoneIcon class="w-6 h-6" />
              <h1 class="px-2 text-sm">{{ client.phone_number }}</h1>
            </div>

            <div v-if="client.address" class="flex items-center mt-4 text-gray-700 dark:text-gray-200">
              <MapPinIcon class="w-6 h-6" />
              <h1 class="px-2 text-sm">{{ client.address }}</h1>
            </div>
          </div>
          <hr />
          <div class="px-6 py-4">
            <span class="text-sm font-semibold text-gray-400">Notes</span>
            <p class="text-sm">{{ client.notes }}</p>
          </div>
        </div>
      </div>
      <div class="col-span-12 lg:col-span-9 bg-gray-200">Column 2</div>
    </div>
  </AppLayout>
</template>
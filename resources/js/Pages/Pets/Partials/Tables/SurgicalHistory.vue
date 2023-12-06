<script setup>
import { defineProps } from 'vue';

// Define the props
const props = defineProps({
  pet: {
    type: Object,
    required: true
  }
});
</script>

<template>
  <div class="px-4 py-5 sm:p-6">
    <template v-if="pet.surgical_history.length === 0">
      <p class="py-4 text-center text-gray-500">
        No surgical history found.
      </p>
    </template>
    <template v-else>
      <table class="min-w-full divide-y divide-gray-300">
        <thead>
          <tr>
            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">Procedure Name
            </th>
            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Date</th>
            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Surgeon</th>
            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Notes</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <tr v-for="surgical_history in pet.surgical_history" :key="surgical_history.id">
            <td class="whitespace-normal py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">{{
              surgical_history.procedure_name }}</td>
            <td class="whitespace-normal px-3 py-4 text-sm text-gray-500">{{ surgical_history.date }}</td>
            <td class="whitespace-normal px-3 py-4 text-sm text-gray-500">{{ surgical_history.surgeon }}</td>
            <td class="relative whitespace-normal py-4 pl-3 pr-4 text-right text-sm sm:pr-0 text-gray-500">{{
              surgical_history.notes }}</td>
          </tr>
        </tbody>
      </table>
    </template>
  </div>
</template>

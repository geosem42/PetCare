<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref, defineProps, onMounted } from 'vue'
import { usePage } from "@inertiajs/vue3"
import { useToast } from "vue-toastification"
import { createValidationForm } from './Validation/create.js'
import Spinner from '@/Components/Spinner.vue'

const toast = useToast();
const { handleSubmit, meta, errors, name, nameAttrs, email, emailAttrs, phone_number, phoneNumberAttrs, address, addressAttrs, notes, notesAttrs } = createValidationForm();
const { client } = usePage().props
const isSubmitting = ref(false)

const props = defineProps({
	client: {
		type: Object,
		required: true
	}
})

onMounted(() => {
  name.value = client.name;
  email.value = client.email;
  phone_number.value = client.phone_number;
  address.value = client.address;
  notes.value = client.notes;
})

const updateForm = ref({
  name: props.client.name,
  email: props.client.email,
  phone_number: props.client.phone_number,
  address: props.client.address,
  notes: props.client.notes
})

const resetForm = () => {
  updateForm.value = {
    name: '',
    email: '',
    phone_number: '',
    address: '',
    notes: ''
  }
}

const submitForm = handleSubmit(async values => {
  isSubmitting.value = true
  const response = await axios.put(`/clients/${props.client.id}`, values)
  resetForm()
  isSubmitting.value = false
  toast.success(response.data.message)
});

</script>

<template>
  <AppLayout title="Add Client">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Edit Client
      </h2>
    </template>

    <section class="bg-white dark:bg-gray-900 rounded-xl">
      <div class="px-4 mx-auto py-4">
        <form @submit="submitForm">
          <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
            <div class="sm:col-span-2">
              <label for="name" class="block mb-2 text-xs font-medium text-gray-400 dark:text-white">Client Name</label>
              <input v-model="name" v-bind="nameAttrs" type="text" name="name" id="name"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500"
                :class="errors.name ? 'border-red-500' : ''"
                placeholder="John Doe">
              <span class="text-red-500 text-xs">{{ errors.name }}</span>
            </div>
            <div class="w-full">
              <label for="email" class="block mb-2 text-xs font-medium text-gray-400 dark:text-white">Email</label>
              <input v-model="email" v-bind="emailAttrs" type="text" name="email" id="email"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500"
                :class="errors.email ? 'border-red-500' : ''"
                placeholder="john@email.com">
              <span class="text-red-500 text-xs">{{ errors.email }}</span>
            </div>
            <div class="w-full">
              <label for="phone_number" class="block mb-2 text-xs font-medium text-gray-400 dark:text-white">Phone Number</label>
              <input v-model="phone_number" v-bind="phoneNumberAttrs" type="text" name="phone_number" id="phone_number"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500"
                :class="errors.phone_number ? 'border-red-500' : ''"
                placeholder="71 153 343">
              <span class="text-red-500 text-xs">{{ errors.phone_number }}</span>
            </div>
            <div class="sm:col-span-2">
              <label for="address" class="block mb-2 text-xs font-medium text-gray-400 dark:text-white">Address</label>
              <input v-model="address" v-bind="addressAttrs" type="text" name="address" id="address"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500"
                :class="errors.address ? 'border-red-500' : ''"
                placeholder="Downtown, Beirut">
              <span class="text-red-500 text-xs">{{ errors.address }}</span>
            </div>
            
            <div class="sm:col-span-2">
              <label for="notes"
                class="block mb-2 text-xs font-medium text-gray-400 dark:text-white">Notes</label>
              <textarea v-model="notes" v-bind="notesAttrs" name="notes" id="notes" rows="8"
                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500"
                :class="errors.notes ? 'border-red-500' : ''"
                placeholder="Your notes here..."></textarea>
              <span class="text-red-500 text-xs">{{ errors.notes }}</span>
            </div>
          </div>

          <button v-if="isSubmitting" disabled type="button" class="inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-white bg-indigo-700 hover:bg-indigo-800 focus:ring-4 focus:ring-indigo-300 rounded-lg text-center me-2 dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800 disabled:bg-indigo-300 disabled:cursor-not-allowed">
            <Spinner />
            Loading...
          </button>

          <button v-else type="submit" :disabled="!meta.valid"
            class="inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-indigo-700 rounded-lg focus:ring-4 focus:ring-primary-200 dark:focus:ring-indigo-900 hover:bg-indigo-800 disabled:bg-indigo-300 disabled:cursor-not-allowed">
            Update Client
          </button>
          
        </form>
      </div>
    </section>
  </AppLayout>
</template>
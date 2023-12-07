<script setup>
import { ref, defineProps, onMounted } from 'vue'
import { useToast } from "vue-toastification"
import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogPanel,
  DialogTitle,
} from '@headlessui/vue'
import {
  XMarkIcon, TrashIcon, ArrowUpOnSquareIcon
} from "@heroicons/vue/24/outline/index.js";

const toast = useToast();

const props = defineProps({
  pet: {
    type: Object,
    required: true
  }
})

const files = ref([])
const images = ref([])
const handleFiles = event => {
  files.value = [...files.value, ...Array.from(event.target.files)]
}

const isOpen = ref(false)

function closeModal() {
  files.value = []
  isOpen.value = false
}
function openModal() {
  isOpen.value = true
}

const uploadFiles = async () => {
  const formData = new FormData();

  files.value.forEach((file, index) => {
    formData.append('files[]', file);
  });

  const response = await axios.post(route('pets.gallery.store', { pet: props.pet.id }), formData);

  await fetchAllImages();
  closeModal();
  toast.success(response.data.message);

  // Handle invalid responses with 422 status code
  if (response.status === 422 && response.data.errors) {
    const errors = response.data.errors;
    for (const field in errors) {
      const fieldErrors = errors[field];
      if (Array.isArray(fieldErrors)) {
        fieldErrors.forEach((error) => {
          toast.error(error);
        });
      } else if (typeof fieldErrors === 'string') {
        toast.error(fieldErrors);
      }
    }
  }
};

const fetchAllImages = async () => {
  const response = await axios.get(`/pets/${props.pet.id}/gallery`);
  images.value = response.data;
};

const deleteImage = async (id) => {
  const response = await axios.delete(`/pets/${props.pet.id}/gallery/${id}`);
  images.value = images.value.filter((image) => image.id !== id);
  toast.success(response.data.message);
};

onMounted(async () => {
  await fetchAllImages();
});
</script>

<template>
  <div class="bg-white shadow rounded-lg overflow-hidden">
    <div class="px-4 py-5 sm:px-6">
      <button type="button" @click="openModal"
        class="w-[100px] flex items-end justify-center rounded-md bg-indigo-700 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75">
        <ArrowUpOnSquareIcon class="w-6 h-6" /> Upload
      </button>
    </div>
    <div class="px-4 py-5 sm:p-6">
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <div v-for="image in images" :key="image.id" class="relative">
          <img :src="image.path" alt="" class="w-full h-64 object-cover rounded-lg">
          <button @click="deleteImage(image.id)"
            class="absolute bottom-0 right-0 bg-red-500 hover:bg-red-700 text-white rounded px-2 py-1 m-2">
            <TrashIcon class="h-6 w-6" />
          </button>
        </div>
      </div>
    </div>
  </div>

  <TransitionRoot appear :show="isOpen" as="template">
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
              <div class="flex justify-between align-items-center mb-5">

                <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-500">
                  Upload Photos
                </DialogTitle>

                <button @click="closeModal" class="bg-gray-100 hover:bg-indigo-100 rounded-md p-1">
                  <XMarkIcon class="w-6 h-6 text-gray-500 hover:text-indigo-700" />
                </button>

              </div>

              <div class="flex flex-col items-center justify-center py-2 sm:py-0">

                <input
                  class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                  aria-describedby="file_input_help" id="file_input" type="file" multiple @change="handleFiles">
                <p class="w-100 mt-1 text-left text-sm text-gray-500 dark:text-gray-300" id="file_input_help">SVG, PNG,
                  JPG or GIF (MAX. 800x400px).</p>


                <div class="w-full py-2 align-middle inline-block min-w-full">
                  <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full table-auto divide-y divide-gray-200">
                      <thead class="bg-gray-50">
                        <tr>
                          <th scope="col"
                            class="w-75 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Name
                          </th>
                          <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Size
                          </th>
                          <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Type
                          </th>
                        </tr>
                      </thead>
                      <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="file in files" :key="file.name">
                          <td class="px-6 py-4 whitespace-normal text-sm text-gray-500">{{ file.name }}</td>
                          <td class="px-6 py-4 whitespace-normal text-sm text-gray-500">{{ file.size }}</td>
                          <td class="px-6 py-4 whitespace-normal text-sm text-gray-500">{{ file.type }}</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>

              </div>

              <div class="mt-4">
                <button @click="uploadFiles"
                  class="inline-flex justify-center rounded-md border border-transparent bg-indigo-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-indigo-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2">Upload</button>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

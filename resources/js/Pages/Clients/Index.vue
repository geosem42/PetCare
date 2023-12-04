<script setup>
import { ref, onMounted, watch, nextTick } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { PlusSmallIcon, EyeIcon, PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/outline'
import Pagination from '@/Components/Pagination.vue'
import SearchTable from '@/Components/SearchTable.vue'
import Swal from "sweetalert2";
import { Link } from '@inertiajs/vue3';
import { initFlowbite } from 'flowbite'
import moment from 'moment';
import { useToast } from "vue-toastification"

onMounted(() => {
    initFlowbite();
    fetchClients();
})

const toast = useToast();
const meta = ref({})
const clients = ref([])
const isLoading = ref(false)
const selectedClientIds = ref([])
const selectAll = ref(false)
const anyCheckboxSelected = ref(false)

const fetchClients = async (page = 1) => {
    isLoading.value = true
    const response = await axios.get('/clients/fetchAllClients', { params: { page } })
    clients.value = response.data.clients.data
    meta.value = response.data.meta
    isLoading.value = false
}

const handleSearch = async ({ search, keywords }) => {
    if (!keywords) {
        // Show a toast error
        toast.warning('Enter a search term')
    } else {
        // If not empty, perform the search
        clients.value = await search('/clients/search');
    }
}
const handleClear = () => {
    fetchClients();
}
watch(selectAll, (newVal) => {
    clients.value.forEach(client => {
        if (!client.hasOwnProperty('selected')) {
            client.selected = false;
        }

        if (client.selected !== newVal) {
            client.selected = newVal;
            togglePetSelection(client.id);
        }
    });
});
const togglePetSelection = (ClientId) => {
    if (selectedClientIds.value.includes(ClientId)) {
        selectedClientIds.value = selectedClientIds.value.filter(id => id !== ClientId);
    } else {
        selectedClientIds.value.push(ClientId);
    }

    anyCheckboxSelected.value = selectedClientIds.value.length > 0;
};

const deleteClient = (id) => {
    Swal.fire({
        title: 'Delete Client?',
        text: 'Are you sure you want to delete this client?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it',
        cancelButtonText: 'No, keep it'
    }).then((result) => {
        if (result.isConfirmed) {
            axios.delete(`/clients/${id}`)
                .then(response => {
                    Swal.fire('Deleted!', response.data.message, 'success');
                    fetchClients();
                })
                .catch(error => {
                    Swal.fire('Error!', error.response.data.message, 'error');
                });
        }
    });
}

const handleBulkDelete = () => {
    if (selectedClientIds.value.length > 0) {
        Swal.fire({
            title: 'Delete Selected Clients?',
            text: `You have selected ${selectedClientIds.value.length} client(s). Do you want to continue?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete them',
            cancelButtonText: 'No, keep them'
        }).then((result) => {
            if (result.isConfirmed) {
                axios.delete('/clients/bulk-delete/selected', { data: { selectedIds: selectedClientIds.value } })
                    .then((response) => {
                        Swal.fire('Deleted!', response.data.message, 'success')
                        selectedClientIds.value = []
                        anyCheckboxSelected.value = false
                        fetchClients()
                        nextTick(() => {
                            selectAll.value = false;
                        });
                    })
                    .catch((error) => {
                        Swal.fire('Error!', error.response.data.message, 'error')
                        console.error('Error:', error);
                    });
            }
        });
    }
};
</script>

<template>
    <AppLayout title="Clients">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Clients
            </h2>
        </template>

        <section class="bg-gray-50 dark:bg-gray-900">
            <div class="mx-auto max-w-full max-h-max">
                <div class="bg-white dark:bg-gray-800 relative shadow-md rounded-xl overflow-hidden">
                    <div class="overflow-x-auto h-[700px]">
                        <div
                            class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                            <div class="w-full md:w-1/2">
                                <form class="flex items-center">
                                    <label for="simple-search" class="sr-only">Search</label>
                                    <div class="relative w-full">
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400"
                                                fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd"
                                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <SearchTable @search="handleSearch" @clear="handleClear" />
                                    </div>
                                </form>
                            </div>
                            <div
                                class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                                <button @click="handleBulkDelete" v-show="anyCheckboxSelected" type="button"
                                    id="deleteSelected"
                                    class="flex items-center justify-center text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-3 py-2 text-center dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900">
                                    <TrashIcon class="w-5 h-5" />
                                </button>

                                <Link :href="route('clients.create')"
                                    class="flex items-center justify-center text-white bg-indigo-700 hover:bg-indigo-800 focus:ring-4 focus:ring-indigo-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-indigo-600 dark:hover:bg-indigo-700 focus:outline-none dark:focus:ring-indigo-800">
                                <PlusSmallIcon class="w-5 h-5 -ml-1 mr-2" /> New Client
                                </Link>
                            </div>
                        </div>
                        <div v-if="isLoading" role="status" class="flex items-center justify-center h-[400px] md:h-[500px]">
                            <svg aria-hidden="true"
                                class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-indigo-600"
                                viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                    fill="currentColor" />
                                <path
                                    d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                    fill="currentFill" />

                            </svg>
                            <span class="sr-only">Loading...</span>
                        </div>
                        <table v-else class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="hidden md:table-header-group text-xs text-gray-400 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-4 py-3 w-[5%]">
                                        <div class="flex items-center">
                                            <input v-model="selectAll" id="checkbox-all" type="checkbox"
                                                class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="checkbox-all" class="sr-only">checkbox</label>
                                        </div>
                                    </th>
                                    <th scope="col" class="px-4 py-3 w-[20%]">Name</th>
                                    <th scope="col" class="px-4 py-3 w-[20%]">Email</th>
                                    <th scope="col" class="px-4 py-3 w-[20%]">Phone Number</th>
                                    <th scope="col" class="px-4 py-3 w-[40%]">Added</th>
                                    <th scope="col" class="px-4 py-3">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr v-if="clients.length === 0">
                                    <td colspan="6" class="pt-8">
                                        <div class="flex items-center justify-center h-full">
                                            <div class="flex items-center mt-6 text-center rounded-lg h-96">
                                                <div class="flex flex-col w-full max-w-sm px-4 mx-auto">
                                                    <div class="p-3 mx-auto text-indigo-500 bg-indigo-100 rounded-full dark:bg-gray-800">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                                        </svg>
                                                    </div>
                                                    <h1 class="mt-3 text-lg text-gray-400 dark:text-white">No clients found</h1>
                                                    <div class="flex flex-col sm:flex-row items-center mt-4 sm:mx-auto gap-y-3 sm:gap-x-3">
                                                        <button @click="handleClear" class="px-5 py-2 text-sm text-gray-700 transition-colors duration-200 bg-white border rounded-lg sm:w-auto dark:hover:bg-gray-800 dark:bg-gray-900 hover:bg-gray-100 dark:text-gray-200 dark:border-gray-700">
                                                            Clear Search
                                                        </button>

                                                        <button class="flex items-center justify-center px-5 py-2 text-sm tracking-wide text-white transition-colors duration-200 bg-indigo-700 rounded-lg shrink-0 sm:w-auto gap-x-2 hover:bg-indigo-800 dark:hover:bg-indigo-800 dark:bg-indigo-700">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>

                                                            <span>Add Client</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-for="client in clients" :key="client.id" class="lg:table-row flex flex-col lg:flex-row border-b dark:border-gray-700">
                                    <th scope="row" class="px-4 py-3">
                                        <div class="flex items-center">
                                            <input v-model="client.selected" :id="'checkbox-' + client.id"
                                                @click="togglePetSelection(client.id)" type="checkbox"
                                                class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="checkbox-table-1" class="sr-only">checkbox</label>
                                        </div>
                                    </th>
                                    <td class="px-4 py-1 lg:py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ client.name }}
                                    </td>
                                    <td class="px-4 py-1 lg:py-">{{ client.email }}</td>
                                    <td class="px-4 py-1 lg:py-3">{{ client.phone_number }}</td>
                                    <td class="px-4 py-1 lg:py-3">{{ moment(client.created_at).format('MMMM Do, YYYY') }}</td>
                                    <td class="px-4 py-4 lg:py-3 flex items-center justify-start lg:justify-end">
                                        <Link :href="route('clients.show', { slug: client.slug })"
                                            class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100">
                                        <EyeIcon class="w-5 h-5 mr-1" />
                                        <span class="sr-only">View</span>
                                        </Link>
                                        <Link :href="route('clients.edit', { slug: client.slug })"
                                            class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100">
                                        <PencilSquareIcon class="w-5 h-5 text-indigo-500 hover:text-indigo-800 mr-1" />
                                        <span class="sr-only">Edit</span>
                                        </Link>
                                        <button @click="deleteClient(client.id)"
                                            class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100"
                                            type="button">
                                            <TrashIcon class="w-5 h-5 text-red-500 hover:text-red-800" />
                                            <span class="sr-only">Delete</span>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>

                        </table>
                    </div>
                    <Pagination v-if="clients.length > 0" :meta="meta" @change-page="fetchClients" />
                </div>
            </div>
        </section>
    </AppLayout>
</template>
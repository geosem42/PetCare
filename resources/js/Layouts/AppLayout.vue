<script setup>
import { onMounted } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import Banner from '@/Components/Banner.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import { UsersIcon, Squares2X2Icon, BoltIcon, CalendarDaysIcon, BeakerIcon } from '@heroicons/vue/24/outline'
import { initFlowbite } from 'flowbite'

onMounted(() => {
	initFlowbite();
})

defineProps({
	title: String,
});

const logout = () => {
	router.post(route('logout'));
};
</script>

<template>
	<div>

		<Head :title="title" />

		<Banner />

		<div class="antialiased bg-gray-50 dark:bg-gray-900">
			<nav
				class="bg-white border-b border-gray-200 px-4 py-2.5 dark:bg-gray-800 dark:border-gray-700 fixed left-0 right-0 top-0 z-50">
				<div class="flex flex-wrap justify-between items-center">
					<div class="flex justify-start items-center">
						<button data-drawer-target="drawer-navigation" data-drawer-toggle="drawer-navigation"
							aria-controls="drawer-navigation"
							class="p-2 mr-2 text-gray-600 rounded-lg cursor-pointer md:hidden hover:text-gray-900 hover:bg-gray-100 focus:bg-gray-100 dark:focus:bg-gray-700 focus:ring-2 focus:ring-gray-100 dark:focus:ring-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
							<svg aria-hidden="true" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
								xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd"
									d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
									clip-rule="evenodd"></path>
							</svg>
							<svg aria-hidden="true" class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
								xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd"
									d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
									clip-rule="evenodd"></path>
							</svg>
							<span class="sr-only">Toggle sidebar</span>
						</button>
						<a href="/dashboard" class="flex items-center justify-between mr-4">
							<img src="/img/logo.png" class="mr-3" alt="Flowbite Logo" />
							<span
								class="self-center text-2xl text-indigo-900 font-semibold whitespace-nowrap dark:text-white">Pet Care</span>
						</a>
					</div>
					<div class="flex items-center lg:order-2">
						
						<button type="button"
							class="flex mx-3 p-3 text-gray-100 bg-indigo-800 rounded-full md:mr-0 focus:ring-2 focus:ring-indigo-700 dark:focus:ring-gray-600 w-[40px] h-[40px] items-center justify-evenly"
							id="user-menu-button" aria-expanded="false" data-dropdown-toggle="dropdown">
							<span class="sr-only">Open user menu</span>
							{{ $page.props.auth.user.name.charAt(0) }}
						</button>
						<!-- Dropdown menu -->
						<div class="hidden z-50 my-4 w-56 text-base list-none bg-white divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600 rounded-xl"
							id="dropdown">
							<div class="py-3 px-4">
								<span class="block text-sm font-semibold text-gray-900 dark:text-white">{{ $page.props.auth.user.name }}</span>
								<span class="block text-sm text-gray-900 truncate dark:text-white">{{ $page.props.auth.user.email }}</span>
							</div>
							<ul class="py-1 text-gray-700 dark:text-gray-300" aria-labelledby="dropdown">
								<li>
										<Link :href="route('profile.show')" class="block py-2 px-4 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-400 dark:hover:text-white">Profile</Link>
								</li>
							</ul>
							<ul class="py-1 text-gray-700 dark:text-gray-300" aria-labelledby="dropdown">
								<li>
									<form @submit.prevent="logout">
										<DropdownLink as="button">
											Log Out
										</DropdownLink>
									</form>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</nav>

			<!-- Sidebar -->

			<aside
				class="fixed top-0 left-0 z-40 w-64 h-screen pt-14 transition-transform -translate-x-full bg-white border-r border-gray-200 md:translate-x-0 dark:bg-gray-800 dark:border-gray-700"
				aria-label="Sidenav" id="drawer-navigation">
				<div class="overflow-y-auto py-5 px-3 h-full bg-gray-900 dark:bg-gray-800">
					<form action="#" method="GET" class="md:hidden mb-2">
						<label for="sidebar-search" class="sr-only">Search</label>
						<div class="relative">
							<div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
								<svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor"
									viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
									<path fill-rule="evenodd" clip-rule="evenodd"
										d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z">
									</path>
								</svg>
							</div>
							<input type="text" name="search" id="sidebar-search"
								class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
								placeholder="Search" />
						</div>
					</form>
					<ul class="space-y-2">
						<li>
							<NavLink :href="route('dashboard')" :active="route().current('dashboard')"
								class="flex items-center p-2 text-base font-medium text-gray-500 hover:text-gray-100 rounded-lg dark:text-white hover:bg-gray-800 dark:hover:bg-gray-700 group">
								<Squares2X2Icon
									class="w-6 h-6 transition duration-75 dark:text-gray-400 group-hover:text-gray-100 dark:group-hover:text-white" />
								<span class="ml-3">Dashboard</span>
							</NavLink>
						</li>
						<li>
							<NavLink :href="route('clients')" :active="route().current('clients')"
								class="flex items-center p-2 text-base font-medium text-gray-500 hover:text-gray-100 rounded-lg dark:text-white hover:bg-gray-800 dark:hover:bg-gray-700 group">
								<UsersIcon
									class="w-6 h-6  transition duration-75 dark:text-gray-400 group-hover:text-gray-100 dark:group-hover:text-white" />
								<span class="ml-3">Clients</span>
							</NavLink>
						</li>
						<li>
							<NavLink :href="route('pets')" :active="route().current('pets')"
								class="flex items-center p-2 text-base font-medium text-gray-500 hover:text-gray-100 rounded-lg dark:text-white hover:bg-gray-800 dark:hover:bg-gray-700 group">
								<BoltIcon
									class="w-6 h-6transition duration-75 dark:text-gray-400 group-hover:text-gray-100 dark:group-hover:text-white" />
								<span class="ml-3">Pets</span>
							</NavLink>
						</li>
						<li>
							<NavLink :href="route('appointments')" :active="route().current('appointments')"
								class="flex items-center p-2 text-base font-medium text-gray-500 hover:text-gray-100 rounded-lg dark:text-white hover:bg-gray-800 dark:hover:bg-gray-700 group">
								<CalendarDaysIcon
									class="w-6 h-6 transition duration-75 dark:text-gray-400 group-hover:text-gray-100 dark:group-hover:text-white" />
								<span class="ml-3">Appointments</span>
							</NavLink>
						</li>
						<li>
							<NavLink :href="route('items')" :active="route().current('items')"
								class="flex items-center p-2 text-base font-medium text-gray-500 hover:text-gray-100 rounded-lg dark:text-white hover:bg-gray-800 dark:hover:bg-gray-700 group">
								<BeakerIcon
									class="w-6 h-6 transition duration-75 dark:text-gray-400 group-hover:text-gray-100 dark:group-hover:text-white" />
								<span class="ml-3">Inventory</span>
							</NavLink>
						</li>
					</ul>
				</div>

				<!-- Bottom Sidebar -->
				<!-- <div class="hidden absolute bottom-0 left-0 justify-center p-4 space-x-4 w-full lg:flex bg-slate-950 dark:bg-gray-800 z-20">
					
				</div> -->
			</aside>

			<main class="p-4 md:ml-64 h-full pt-20">
				<div class="max-w-full mx-auto sm:px-6 lg:px-8">
					<header v-if="$slots.header" class="bg-white rounded-xl shadow mb-6">
						<div class="py-6 px-4 sm:px-6 lg:px-8">
							<slot name="header" />
						</div>
					</header>
					<slot />
				</div>
			</main>
		</div>

	</div>
</template>
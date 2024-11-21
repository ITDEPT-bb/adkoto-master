<template>
	<div
		v-if="isOpen"
		class="modal-overlay max-w-full mx-auto"
		@click.self="closeModal">
		<div class="modal-container">
			<div class="modal-header">
				<h2 class="text-xl font-semibold text-gray-800">Search Followings</h2>
				<button
					@click="closeModal"
					class="close-btn text-gray-600 hover:text-gray-800">
					X
				</button>
			</div>
			<div class="modal-body pt-2">
				<!-- Search input field for real-time search -->
				<input
					v-model="searchQuery"
					type="text"
					placeholder="Search Followings"
					class="w-full p-3 border border-gray-300 rounded-lg mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500"
					@input="debouncedSearch(searchQuery)" />

				<!-- Loading indicator -->
				<div
					v-if="isLoading"
					class="text-center my-4 text-blue-600">
					Loading...
				</div>

				<!-- Message if no results found -->
				<div
					v-if="!isLoading && filteredFollowings.length === 0 && searchQuery.trim() !== ''"
					class="text-center text-gray-500">
					No followings found for "{{ searchQuery }}".
				</div>

				<!-- List of followings that match the search -->
				<ul v-if="!isLoading && filteredFollowings.length > 0">
					<li
						v-for="following in filteredFollowings"
						:key="following.id"
						class="following-item">
						<Link :href="`/chat/conversation/adktu/${following.id}`">
							<div
								class="flex items-center space-x-4 transition-transform duration-300 ease-in-out transform hover:scale-105 hover:bg-gray-100 p-2 rounded-lg">
								<img
									:src="following.avatar_url"
									alt="Avatar"
									class="w-10 h-10 rounded-full object-cover" />
								<div>
									<h3 class="text-lg font-medium text-gray-800">
										{{ following.name }} {{ following.surname }}
									</h3>
									<p class="text-sm text-gray-600">@{{ following.username }}</p>
								</div>
							</div>
						</Link>
					</li>
				</ul>
			</div>
		</div>
	</div>
</template>

<script setup>
import { ref, computed, defineProps, defineEmits } from "vue";
import { Link } from "@inertiajs/vue3";
import axios from "axios";

const props = defineProps({
	isOpen: {
		type: Boolean,
		required: true,
	},
});

const searchQuery = ref("");
const followings = ref([]);
const isLoading = ref(false);

const emit = defineEmits(["close"]);

const closeModal = () => {
	emit("close");
};

const searchFollowings = async (query) => {
	if (query.trim() === "") {
		followings.value = [];
		isLoading.value = false;
		return;
	}

	isLoading.value = true;
	try {
		const response = await axios.get("/chat/search-followings", {
			params: { query },
		});
		followings.value = response.data.followings;
	} catch (error) {
		console.error("Error fetching followings:", error);
	} finally {
		isLoading.value = false;
	}
};

// Use debounce to limit the API calls
const debouncedSearch = debounce((query) => {
	searchFollowings(query);
}, 500);

// Debounce function to delay function execution
function debounce(func, delay) {
	let timer;
	return function (...args) {
		clearTimeout(timer);
		timer = setTimeout(() => {
			func.apply(this, args);
		}, delay);
	};
}

// Computed property to filter followings based on the search query
const filteredFollowings = computed(() => {
	return followings.value.filter((following) => {
		return (
			following.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
			following.surname.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
			following.username.toLowerCase().includes(searchQuery.value.toLowerCase())
		);
	});
});
</script>

<style scoped>
.modal-overlay {
	position: fixed;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	background: rgba(0, 0, 0, 0.5);
	display: flex;
	justify-content: center;
	align-items: center;
	z-index: 1000;
}

.modal-container {
	background: white;
	padding: 20px;
	border-radius: 12px;
	width: 90%;
	max-width: 500px;
	box-shadow: 0px 10px 15px rgba(0, 0, 0, 0.1);
}

.modal-header {
	display: flex;
	justify-content: space-between;
	align-items: center;
}

.close-btn {
	background: none;
	border: none;
	font-size: 18px;
	cursor: pointer;
}

.following-item {
	padding: 10px;
	border-bottom: 1px solid #eaeaea;
}

.following-item:hover {
	background-color: #f9f9f9;
}

.following-item p {
	margin: 0;
}
</style>

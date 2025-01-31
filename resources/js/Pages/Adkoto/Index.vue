<template>
	<Head title="All Advertisements" />

	<AuthenticatedLayout>
		<div class="max-w-7xl mx-auto h-full overflow-y-auto p-4 scrollbar-thin">
			<div class="grid grid-cols-1 md:grid-cols-12 gap-4">
				<CategoryDrawer
					:categories="categories"
					class="col-span-12" />

				<FeaturedAds
					:featuredAds="featuredAds"
					class="col-span-12" />

				<div class="hidden lg:block lg:col-span-4">
					<ManageCard />
					<SponsoredAds :sponsoredAds="sponsoredAds" />
				</div>

				<Listings
					:advertisements="advertisements"
					class="col-span-12 lg:col-span-8" />
			</div>
		</div>
	</AuthenticatedLayout>

	<UpdateProfileReminder />
</template>

<script setup>
import { ref } from "vue";
import { usePage, Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import UpdateProfileReminder from "@/Components/UpdateProfileReminder.vue";
import CategoryList from "@/Components/Adkoto/CategoryList.vue";
import CategoryDrawer from "@/Components/Adkoto/CategoryDrawer.vue";
import Listings from "@/Components/Adkoto/Listings.vue";
import SponsoredAds from "@/Components/Adkoto/SponsoredAds.vue";
import ManageCard from "@/Components/Adkoto/ManageCard.vue";
import FeaturedAds from "@/Components/Adkoto/FeaturedAds.vue";

const { props } = usePage();
const advertisements = ref(props.advertisements);
const categories = ref(props.categories);
const featuredAds = ref(props.featuredAds);
const sponsoredAds = ref(props.sponsoredAds);
</script>

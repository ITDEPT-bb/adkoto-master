<script setup>
import PostItem from "@/Components/Tribekoto/PostItem.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { ref } from "vue";
import PostModal from "@/Components/Tribekoto/PostModal.vue";
import AttachmentPreviewModal from "@/Components/Tribekoto/AttachmentPreviewModal.vue";
import { usePage, Head } from "@inertiajs/vue3";
defineProps({
	post: Object,
});
const authUser = usePage().props.auth.user;
const showEditModal = ref(false);
const editPost = ref({});
const previewAttachmentsPost = ref({});
const showAttachmentsModal = ref(false);

function openEditModal(post) {
	editPost.value = post;
	showEditModal.value = true;
}
function openAttachmentPreviewModal(post, index) {
	previewAttachmentsPost.value = {
		post,
		index,
	};
	showAttachmentsModal.value = true;
}
function onModalHide() {
	editPost.value = {
		id: null,
		body: "",
		user: authUser,
	};
}
</script>

<template>
	<Head title="Social Media Website" />

	<AuthenticatedLayout>
		<div class="p-8 sm:w-[600px] mx-auto h-full overflow-auto">
			<PostItem
				:post="post"
				@editClick="openEditModal"
				@attachmentClick="openAttachmentPreviewModal" />
		</div>
		<PostModal
			:post="editPost"
			v-model="showEditModal"
			@hide="onModalHide" />
		<AttachmentPreviewModal
			:attachments="previewAttachmentsPost.post?.attachments || []"
			v-model:index="previewAttachmentsPost.index"
			v-model="showAttachmentsModal" />
	</AuthenticatedLayout>
</template>
<style scoped></style>

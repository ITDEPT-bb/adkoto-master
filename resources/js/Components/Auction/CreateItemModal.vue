<template>
    <div>
        <!-- Trigger button -->
        <PrimaryButton type="button" @click="openModal">
            Add Items
        </PrimaryButton>

        <TransitionRoot appear :show="isOpen" as="template">
            <Dialog as="div" @close="closeModal" class="relative z-50">
                <!-- Overlay -->
                <TransitionChild
                    as="template"
                    enter="duration-300 ease-out"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="duration-200 ease-in"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                >
                    <div class="fixed inset-0 bg-black/40" />
                </TransitionChild>

                <!-- Modal content -->
                <div class="fixed inset-0 overflow-y-auto">
                    <div
                        class="flex min-h-full items-center justify-center p-4 sm:p-6"
                    >
                        <TransitionChild
                            as="template"
                            enter="duration-300 ease-out"
                            enter-from="opacity-0 scale-95"
                            enter-to="opacity-100 scale-100"
                            leave="duration-200 ease-in"
                            leave-from="opacity-100 scale-100"
                            leave-to="opacity-0 scale-95"
                        >
                            <DialogPanel
                                class="w-full max-w-6xl transform overflow-hidden rounded-xl bg-white shadow-xl transition-all"
                            >
                                <!-- Header -->
                                <div
                                    class="flex items-center justify-between border-b px-6 py-4"
                                >
                                    <DialogTitle
                                        class="text-xl font-semibold text-gray-900"
                                    >
                                        Add you Item
                                    </DialogTitle>
                                    <button
                                        type="button"
                                        class="text-gray-400 hover:text-gray-600"
                                        @click="closeModal"
                                    >
                                        <span class="sr-only">Close</span>
                                        ✕
                                    </button>
                                </div>

                                <div class="px-6 py-4">
                                    <h1 class="text-3xl font-bold mb-6">
                                        Create New Listing
                                    </h1>
                                    <form
                                        @submit.prevent="submitForm"
                                        class="space-y-6"
                                    >
                                        <!-- Title -->
                                        <div>
                                            <label
                                                for="name"
                                                class="block text-sm font-medium text-gray-700 dark:text-white"
                                                >Name of the Product</label
                                            >
                                            <input
                                                type="text"
                                                v-model="form.name"
                                                required
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm dark:bg-slate-950 dark:text-white dark:border-gray-700"
                                                placeholder="Enter the name of the product"
                                            />
                                        </div>

                                        <!-- Category -->
                                        <div>
                                            <label
                                                for="category_id"
                                                class="block text-sm font-medium text-gray-700 dark:text-white"
                                                >Category</label
                                            >
                                            <select
                                                v-model="form.category_id"
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm dark:bg-slate-950 dark:text-white dark:border-gray-700"
                                                placeholder="Enter the name of the product"
                                            >
                                                <option value="" disabled>
                                                    Select a category
                                                </option>
                                                <option
                                                    v-for="category in categories"
                                                    :key="category.id"
                                                    :value="category.id"
                                                >
                                                    {{ category.name }}
                                                </option>
                                            </select>
                                        </div>

                                        <!-- Description -->
                                        <div>
                                            <label
                                                for="description"
                                                class="block text-sm font-medium text-gray-700 dark:text-white"
                                                >Description</label
                                            >
                                            <textarea
                                                v-model="form.description"
                                                required
                                                rows="4"
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm dark:bg-slate-950 dark:text-white dark:border-gray-700"
                                                placeholder="Enter the description of the product"
                                            ></textarea>
                                        </div>

                                        <!-- Price -->
                                        <div>
                                            <label
                                                for="price"
                                                class="block text-sm font-medium text-gray-700 dark:text-white"
                                                >Price</label
                                            >
                                            <input
                                                type="number"
                                                v-model="form.price"
                                                required
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm dark:bg-slate-950 dark:text-white dark:border-gray-700"
                                                placeholder="Enter the price of the product"
                                            />
                                        </div>

                                        <!-- Location -->
                                        <div>
                                            <label
                                                for="location"
                                                class="block text-sm font-medium text-gray-700 dark:text-white"
                                                >Location</label
                                            >
                                            <input
                                                type="text"
                                                v-model="form.location"
                                                required
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm dark:bg-slate-950 dark:text-white dark:border-gray-700"
                                                placeholder="Enter the location of the seller"
                                            />
                                        </div>

                                        <!-- Images -->
                                        <div>
                                            <label
                                                for="images"
                                                class="block text-sm font-medium text-gray-700 dark:text-white"
                                                >Images</label
                                            >
                                            <input
                                                type="file"
                                                @change="handleFileUpload"
                                                multiple
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm dark:bg-slate-950 dark:text-white dark:border-gray-700"
                                                placeholder="Enter the images of the product"
                                            />
                                        </div>

                                        <!-- Image Previews -->
                                        <div
                                            v-if="imagePreviews.length"
                                            class="mt-4"
                                        >
                                            <h3
                                                class="text-lg font-medium text-gray-700 dark:text-white"
                                            >
                                                Image Previews
                                            </h3>
                                            <div
                                                class="mt-2 flex flex-wrap space-x-2"
                                            >
                                                <div
                                                    v-for="(
                                                        image, index
                                                    ) in imagePreviews"
                                                    :key="index"
                                                    class="relative"
                                                >
                                                    <img
                                                        :src="image.url"
                                                        alt="Preview"
                                                        class="w-24 h-24 object-cover rounded-md shadow-sm dark:bg-slate-950 dark:text-white dark:border-gray-700"
                                                        placeholder="Enter the name of the product"
                                                    />
                                                    <button
                                                        @click="
                                                            removeImage(index)
                                                        "
                                                        class="absolute top-0 right-0 bg-red-600 text-white rounded-full p-1 shadow-md"
                                                    >
                                                        &times;
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Submit Button -->
                                        <button
                                            type="submit"
                                            :disabled="isLoading"
                                            :class="{
                                                'w-full py-2 px-4 bg-blue-300 text-white font-semibold rounded-md shadow-md hover:bg-blue-300 transition duration-300 ease-in-out cursor-not-allowed':
                                                    isLoading,
                                                'w-full py-2 px-4 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 transition duration-300 ease-in-out':
                                                    !isLoading,
                                            }"
                                        >
                                            Submit
                                        </button>
                                    </form>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>
    </div>
</template>

<script setup>
import PrimaryButton from "../PrimaryButton.vue";
import { ref, watchEffect } from "vue";
import axios from "axios";
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
} from "@headlessui/vue";
import { useToast } from "vue-toastification";
import { useForm, usePage } from "@inertiajs/vue3";

const { props } = usePage();
const toast = useToast();
const isOpen = ref(false);
const walletBalance = ref(Number(props.walletBalance) || 0);

const imagePreviews = ref([]);

function openModal() {
    isOpen.value = true;
    fetchCategories();
}

function closeModal() {
    isOpen.value = false;
}

const form = useForm({
    category_id: "",
    name: "",
    description: "",
    price: "",
    location: "",
    images: [],
});

const categories = ref([]);

async function fetchCategories() {
    try {
        const response = await axios.get("/auction/host/categories");
        categories.value = response.data.data;
    } catch (error) {
        console.error("Failed to load categories:", error);
    }
}

const handleFileUpload = (event) => {
    const files = Array.from(event.target.files);
    form.images.push(...files);

    imagePreviews.value.push(
        ...files.map((file) => ({
            file,
            url: URL.createObjectURL(file),
        }))
    );
};

const removeImage = (index) => {
    form.images.splice(index, 1);
    URL.revokeObjectURL(imagePreviews.value[index].url);
    imagePreviews.value.splice(index, 1);
};

const isLoading = ref(false);
const emit = defineEmits(["created"]);

const formatPrice = (price) => {
    return new Intl.NumberFormat("en-PH", {
        style: "currency",
        currency: "PHP",
        minimumFractionDigits: 2,
    }).format(price || 0);
};

const submitForm = () => {
    isLoading.value = true;

    const price = Number(form.price) || 0;
    const requiredBalance = price * 0.02;

    if (walletBalance.value < requiredBalance) {
        toast.error(
            `You need at least ${formatPrice(
                requiredBalance
            )} in your wallet (2% of the price).`
        );
        isLoading.value = false;
        return;
    }

    const formData = new FormData();
    formData.append("name", form.name);
    formData.append("description", form.description);
    formData.append("price", form.price);
    formData.append("location", form.location);
    formData.append("category_id", form.category_id);

    form.images.forEach((image) => {
        formData.append("attachments[]", image);
    });

    axios
        .post("/auction/host/item/live", formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        })
        .then(() => {
            toast.success("Listing created successfully!");
            resetForm();
            isLoading.value = false;
            closeModal();

            emit("created");
        })
        .catch(() => {
            toast.error("There was an error creating the listing.");
            isLoading.value = false;
        });
};

const resetForm = () => {
    form.reset();
    imagePreviews.value = [];
};

watchEffect(() => form.category_id);
</script>

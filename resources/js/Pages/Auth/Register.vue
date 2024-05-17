<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

// import { ref } from "vue";
// import flatPickr from "vue-flatpickr-component";
// import "flatpickr/dist/flatpickr.css";

// const date = ref(null);

// const config = ref({
//     altFormat: "M j, Y",
//     altInput: true,
//     dateFormat: "Y-m-d",
//     maxDate: "today",
// });

const form = useForm({
    name: "",
    surname: "",
    email: "",
    phone: "",
    birthday: "",
    gender: "",
    password: "",
    password_confirmation: "",
});

const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Name" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="mt-4">
                <InputLabel for="surname" value="Surname" />
                <TextInput
                    id="surname"
                    v-model="form.surname"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autocomplete="surname"
                />
                <InputError class="mt-2" :message="form.errors.surname" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="phone" value="Mobile No." />
                <TextInput
                    id="phone"
                    v-model="form.phone"
                    type="tel"
                    class="mt-1 block w-full"
                    required
                    autocomplete="phone"
                />
                <InputError class="mt-2" :message="form.errors.phone" />
            </div>

            <div class="mt-4">
                <InputLabel for="birthday" value="Birthday" />
                <TextInput
                    id="birthday"
                    v-model="form.birthday"
                    type="date"
                    class="mt-1 block w-full"
                    required
                    autocomplete="bday"
                />
                <InputError class="mt-2" :message="form.errors.birthday" />
            </div>

            <!-- <div class="mt-4">
                <InputLabel for="birthday" value="Birthday" />
                <flat-pickr
                    id="birthday"
                    v-model="form.birthday"
                    type="date"
                    class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    required
                    autocomplete="birthday"
                    placeholder="Select date"
                    :config="config"
                />
                <InputError class="mt-2" :message="form.errors.birthday" />
            </div> -->

            <div class="mt-4">
                <InputLabel for="gender" value="Gender" />
                <select
                    v-model="form.gender"
                    id="gender"
                    class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    required
                >
                    <option disabled value="">Select One</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
                <InputError class="mt-2" :message="form.errors.gender" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel
                    for="password_confirmation"
                    value="Confirm Password"
                />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />

                <InputError
                    class="mt-2"
                    :message="form.errors.password_confirmation"
                />
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link
                    :href="route('login')"
                    class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                >
                    Already registered?
                </Link>

                <PrimaryButton
                    class="ms-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Register
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>

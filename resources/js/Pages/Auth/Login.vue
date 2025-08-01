<script setup>
import Checkbox from "@/Components/Checkbox.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref } from "vue";

import { EyeIcon, EyeSlashIcon } from "@heroicons/vue/24/solid";

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    login: "",
    password: "",
    remember: false,
});

const showPassword = ref(false);

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />
        <div class="h-full overflow-y-auto">
            <!-- <div class="text-center dark:text-gray-100">
			<h2 class="text-2xl text-center">Login</h2>
			<span class="text-gray-400 text-sm">or</span>
			<div class="flex justify-center">
				<Link
					:href="route('register')"
					class="inline-block hover:underline">
					Create new account
				</Link>
			</div>
		</div> -->

            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>

            <div className="flex flex-col gap-6">
                <div
                    class="overflow-hidden rounded-xl bg-card text-card-foreground"
                >
                    <!-- <div className="sm:hidden flex flex-col items-center justify-center w-full h-full gap-2">
					<img
						src="img/Auth/adkoto_logo.png"
						alt="Adkoto Logo"
						className="h-auto max-w-[100%] object-contain px-6 pb-4 md:px-8" />
				</div> -->
                    <div
                        class="grid md:grid-cols-2 bg-white dark:bg-slate-600 border dark:border-none shadow-lg sm:rounded-none rounded-lg sm:py-0 py-2 sm:px-0 px-1"
                    >
                        <form
                            @submit.prevent="submit"
                            className="p-2 md:p-6 md:py-16"
                        >
                            <div className="flex flex-col gap-6">
                                <!-- <div
                                    class="flex flex-col items-center text-center"
                                >
                                    <img
                                        src="img/Auth/adkoto_logo.png"
                                        alt="Adkoto Logo"
                                        className="h-auto max-w-[70%] object-contain px-6 md:px-8"
                                    />
                                </div> -->
                                <div
                                    className="flex flex-col items-center text-center"
                                >
                                    <!-- <h1 className="text-2xl font-bold text-blue-primary">Welcome back</h1> -->
                                    <p
                                        className="text-balance text-muted-foreground dark:text-white"
                                    >
                                        Login to your Adkoto account
                                    </p>
                                </div>
                                <div class="grid gap-2">
                                    <InputLabel
                                        for="login"
                                        value="Email or Phone Number"
                                        class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                    />

                                    <TextInput
                                        id="login"
                                        type="text"
                                        class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                                        v-model="form.login"
                                        required
                                        autofocus
                                        autocomplete="username"
                                    />

                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.login"
                                    />
                                </div>

                                <div class="grid gap-2">
                                    <InputLabel
                                        for="password"
                                        value="Password"
                                        class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                    />

                                    <div class="relative">
                                        <TextInput
                                            :type="
                                                showPassword
                                                    ? 'text'
                                                    : 'password'
                                            "
                                            id="password"
                                            class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                                            v-model="form.password"
                                            required
                                            autocomplete="current-password"
                                        />

                                        <span
                                            class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer text-gray-500 dark:text-gray-400"
                                            @click="
                                                showPassword = !showPassword
                                            "
                                        >
                                            <EyeIcon
                                                class="h-6 w-6"
                                                v-if="!showPassword"
                                            />
                                            <EyeSlashIcon
                                                class="h-6 w-6"
                                                v-else
                                            />
                                        </span>
                                    </div>

                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.password"
                                    />
                                </div>

                                <div className="flex items-center pt-2 px-0.5">
                                    <div
                                        className="flex items-center space-x-2"
                                    >
                                        <Checkbox
                                            name="remember"
                                            v-model:checked="form.remember"
                                        />
                                        <span
                                            class="ms-1 text-sm text-gray-600 dark:text-gray-400"
                                            >Remember me</span
                                        >
                                    </div>

                                    <Link
                                        v-if="canResetPassword"
                                        :href="route('password.request')"
                                        class="ml-auto text-sm underline-offset-4 text-blue-primary dark:text-gray-200 hover:text-blue-hover hover:underline"
                                    >
                                        Forgot your password?
                                    </Link>
                                </div>

                                <!-- <div class="flex items-center justify-end mt-4">
								<Link
									v-if="canResetPassword"
									:href="route('password.request')"
									class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
									Forgot your password?
								</Link>

								<PrimaryButton
									class="ms-4"
									:class="{ 'opacity-25': form.processing }"
									:disabled="form.processing">
									Log in
								</PrimaryButton>

								<div className="text-center text-sm">
									Don&apos;t have an account?
									<Link
										:href="route('register')"
										className="underline underline-offset-4 text-blue-primary hover:text-blue-hover">
										Sign Up
									</Link>
								</div>
							</div> -->

                                <PrimaryButton
                                    class="w-full bg-blue-primary hover:bg-blue-hover inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0"
                                    :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing"
                                >
                                    Log in
                                </PrimaryButton>

                                <div
                                    className="text-center text-sm dark:text-white"
                                >
                                    Don&apos;t have an account?
                                    <Link
                                        :href="route('register')"
                                        className="underline underline-offset-4 text-blue-primary dark:text-gray-200 dark:hover:text-gray-400 hover:text-blue-hover"
                                    >
                                        Sign Up
                                    </Link>
                                </div>
                            </div>
                        </form>
                        <div
                            className="relative hidden md:flex bg-blue-primary p-4"
                        >
                            <div
                                className="flex flex-col items-center justify-center w-full h-full gap-12"
                            >
                                <img
								src="img/Auth/adkoto_logo.png"
								alt="Adkoto Logo"
								className="h-auto max-w-[60%] object-contain px-6 md:px-8" />

                                <img
                                    src="img/Auth/log_in.png"
                                    alt="Adkoto Vector"
                                    className="h-auto max-w-[115%] object-contain px-6 md:px-8"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>

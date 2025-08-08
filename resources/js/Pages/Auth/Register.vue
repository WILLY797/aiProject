<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const activateForm = useForm({
    business_name: '',
    contact_name: '',
    email: '',
    phone: '',
    account_number: '',
});

const registerForm = useForm({
    business_type: '',
    business_name: '',
    address: '',
    email: '',
    phone: '',
    password: '',
    password_confirmation: '',
    apply_credit: false,
});

const submitActivation = () => {
    activateForm.post(route('account.activate'), {
        onFinish: () => activateForm.reset(),
    });
};

const submitRegistration = () => {
    registerForm.post(route('register'), {
        onFinish: () => registerForm.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>

        <Head title="Register" />

        <!-- Activate Existing Account -->
        <div
            class="relative card-accent bg-white/95 backdrop-blur p-6 md:p-8 rounded-xl shadow-card ring-1 ring-brand-100 mb-8">
            <h2 class="text-xl font-bold text-gray-900 l-4 border-brand-500 pl">Activate Existing Equinox Account</h2>

            <form @submit.prevent="submitActivation" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <InputLabel for="business_name" value="Business Name" />
                        <TextInput id="business_name" v-model="activateForm.business_name"
                            class="mt-1 block w-full border-gray-300 focus:border-brand-500 focus:ring-brand-500 rounded-lg"
                            required />
                        <InputError :message="activateForm.errors.business_name" class="mt-2" />
                    </div>

                    <div>
                        <InputLabel for="contact_name" value="Your Name" />
                        <TextInput id="contact_name" v-model="activateForm.contact_name"
                            class="mt-1 block w-full border-gray-300 focus:border-brand-500 focus:ring-brand-500 rounded-lg"
                            required />
                        <InputError :message="activateForm.errors.contact_name" class="mt-2" />
                    </div>

                    <div>
                        <InputLabel for="email" value="Email" />
                        <TextInput id="email" type="email" v-model="activateForm.email"
                            class="mt-1 block w-full border-gray-300 focus:border-brand-500 focus:ring-brand-500 rounded-lg"
                            required />
                        <InputError :message="activateForm.errors.email" class="mt-2" />
                    </div>

                    <div>
                        <InputLabel for="phone" value="Phone Number" />
                        <TextInput id="phone" v-model="activateForm.phone"
                            class="mt-1 block w-full border-gray-300 focus:border-brand-500 focus:ring-brand-500 rounded-lg"
                            required />
                        <InputError :message="activateForm.errors.phone" class="mt-2" />
                    </div>

                    <div class="md:col-span-2">
                        <InputLabel for="account_number" value="Account Number (if known)" />
                        <TextInput id="account_number" v-model="activateForm.account_number"
                            class="mt-1 block w-full border-gray-300 focus:border-brand-500 focus:ring-brand-500 rounded-lg" />
                        <InputError :message="activateForm.errors.account_number" class="mt-2" />
                    </div>
                </div>

                <PrimaryButton class="!bg-brand-600 hover:!bg-brand-700 focus-visible:!ring-brand-500 !text-white">
                    Activate Account
                </PrimaryButton>
            </form>
        </div>

        <!-- Register New Business -->
        <div
            class="relative card-accent bg-white/95 backdrop-blur p-6 md:p-8 rounded-xl shadow-card ring-1 ring-brand-100">
            <h2 class="text-xl font-semibold text-brand-800 mb-6">Register New Business</h2>

            <form @submit.prevent="submitRegistration" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <InputLabel for="business_type" value="Type of Business" />
                        <TextInput id="business_type" v-model="registerForm.business_type"
                            class="mt-1 block w-full border-gray-300 focus:border-brand-500 focus:ring-brand-500 rounded-lg"
                            required />
                        <InputError :message="registerForm.errors.business_type" class="mt-2" />
                    </div>

                    <div>
                        <InputLabel for="business_name" value="Business Name" />
                        <TextInput id="business_name" v-model="registerForm.business_name"
                            class="mt-1 block w-full border-gray-300 focus:border-brand-500 focus:ring-brand-500 rounded-lg"
                            required />
                        <InputError :message="registerForm.errors.business_name" class="mt-2" />
                    </div>

                    <div class="md:col-span-2">
                        <InputLabel for="address" value="Address" />
                        <TextInput id="address" v-model="registerForm.address"
                            class="mt-1 block w-full border-gray-300 focus:border-brand-500 focus:ring-brand-500 rounded-lg"
                            required />
                        <InputError :message="registerForm.errors.address" class="mt-2" />
                    </div>

                    <div>
                        <InputLabel for="email2" value="Email" />
                        <TextInput id="email2" type="email" v-model="registerForm.email"
                            class="mt-1 block w-full border-gray-300 focus:border-brand-500 focus:ring-brand-500 rounded-lg"
                            required />
                        <InputError :message="registerForm.errors.email" class="mt-2" />
                    </div>

                    <div>
                        <InputLabel for="phone2" value="Phone Number" />
                        <TextInput id="phone2" v-model="registerForm.phone"
                            class="mt-1 block w-full border-gray-300 focus:border-brand-500 focus:ring-brand-500 rounded-lg"
                            required />
                        <InputError :message="registerForm.errors.phone" class="mt-2" />
                    </div>

                    <div>
                        <InputLabel for="password" value="Password" />
                        <TextInput id="password" type="password" v-model="registerForm.password"
                            class="mt-1 block w-full border-gray-300 focus:border-brand-500 focus:ring-brand-500 rounded-lg"
                            required />
                        <InputError :message="registerForm.errors.password" class="mt-2" />
                    </div>

                    <div>
                        <InputLabel for="password_confirmation" value="Confirm Password" />
                        <TextInput id="password_confirmation" type="password"
                            v-model="registerForm.password_confirmation"
                            class="mt-1 block w-full border-gray-300 focus:border-brand-500 focus:ring-brand-500 rounded-lg"
                            required />
                        <InputError :message="registerForm.errors.password_confirmation" class="mt-2" />
                    </div>

                    <div class="md:col-span-2 flex items-center mt-2">
                        <Checkbox v-model:checked="registerForm.apply_credit" id="apply_credit"
                            class="text-brand-600 focus:ring-brand-500" />
                        <InputLabel for="apply_credit" class="ml-2 text-gray-700" value="Apply for Credit Account" />
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-between">
                    <Link :href="route('login')" class="text-sm text-brand-700 hover:text-brand-900 underline">
                    Already registered?
                    </Link>

                    <PrimaryButton class="!bg-brand-500 hover:!bg-brand-600 !text-black focus-visible:!ring-brand-400">
                        Register
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </GuestLayout>
</template>

<template>
    <div class="container mt-5">
        <div class="card p-4 mx-auto" style="max-width: 400px;">
            <h3 class="mb-3 text-center">Register</h3>
            <form @submit.prevent="register">
                <div class="mb-3">
                    <input
                        v-model="name"
                        type="text"
                        placeholder="Name"
                        class="form-control"
                        :class="{ 'is-invalid': errors.name }"
                    />
                    <div v-if="errors.name" class="invalid-feedback">{{ errors.name }}</div>
                </div>

                <div class="mb-3">
                    <input
                        v-model="email"
                        type="email"
                        placeholder="Email"
                        class="form-control"
                        :class="{ 'is-invalid': errors.email }"
                    />
                    <div v-if="errors.email" class="invalid-feedback">{{ errors.email }}</div>
                </div>

                <div class="mb-3">
                    <input
                        v-model="password"
                        type="password"
                        placeholder="Password"
                        class="form-control"
                        :class="{ 'is-invalid': errors.password }"
                    />
                    <div v-if="errors.password" class="invalid-feedback">{{ errors.password }}</div>
                </div>

                <div class="mb-3">
                    <input
                        v-model="password_confirmation"
                        type="password"
                        placeholder="Confirm Password"
                        class="form-control"
                        :class="{ 'is-invalid': errors.password_confirmation }"
                    />
                    <div v-if="errors.password_confirmation" class="invalid-feedback">
                        {{ errors.password_confirmation }}
                    </div>
                </div>

                <button type="submit" class="btn btn-success w-100">Register</button>

                <div v-if="generalError" class="text-danger mt-2 text-center">{{ generalError }}</div>
            </form>

            <div class="mt-3 text-center">
                <span>Do you have an account? </span>
                <router-link to="/login" class="text-decoration-underline">Login here</router-link>
            </div>
        </div>
    </div>
</template>

<script setup>
import {ref} from 'vue';
import axios from '../axios';
import {useRouter} from 'vue-router';

const name = ref('');
const email = ref('');
const password = ref('');
const password_confirmation = ref('');
const errors = ref({});
const generalError = ref(null);
const router = useRouter();

const register = async () => {
    errors.value = {};
    generalError.value = null;

    try {
        await axios.get('/sanctum/csrf-cookie');

        await axios.post(
            '/api/register',
            {
                name: name.value,
                email: email.value,
                password: password.value,
                password_confirmation: password_confirmation.value,
            },
            {
                suppressToast: true,
            }
        );

        router.push('/');
    } catch (err) {
        if (err.response?.status === 422) {
            const resErrors = err.response.data.errors;
            for (const key in resErrors) {
                errors.value[key] = resErrors[key][0];
            }
        } else {
            generalError.value = err.response?.data?.message || 'Registration failed.';
        }
    }
};
</script>

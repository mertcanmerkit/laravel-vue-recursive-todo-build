<template>
    <div class="container mt-5">
        <div class="card p-4 mx-auto" style="max-width: 400px;">
            <h3 class="mb-3 text-center">Login</h3>
            <form @submit.prevent="login">
                <div class="mb-3">
                    <input
                        v-model="email"
                        type="email"
                        placeholder="Email"
                        class="form-control"
                        :class="{ 'is-invalid': errors.email }"
                    />
                    <div v-if="errors.email" class="invalid-feedback">
                        {{ errors.email }}
                    </div>
                </div>

                <div class="mb-3">
                    <input
                        v-model="password"
                        type="password"
                        placeholder="Password"
                        class="form-control"
                        :class="{ 'is-invalid': errors.password }"
                    />
                    <div v-if="errors.password" class="invalid-feedback">
                        {{ errors.password }}
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">Login</button>

                <div v-if="generalError" class="text-danger mt-2 text-center">{{ generalError }}</div>
            </form>

            <div class="mt-3 text-center">
                <span>Don't have an account? </span>
                <router-link to="/register" class="text-decoration-underline">Register here</router-link>
            </div>

            <br>

            <pre>User1: test@test.com<br>Pass: pass<br><br>User2: test2@test.com<br>Pass: pass2</pre>
        </div>
    </div>
</template>

<script setup>
import {ref} from 'vue';
import axios from '../axios';
import {useRouter} from 'vue-router';

const email = ref('');
const password = ref('');
const errors = ref({});
const generalError = ref(null);
const router = useRouter();

const login = async () => {
    errors.value = {};
    generalError.value = null;

    try {
        await axios.get('/sanctum/csrf-cookie');
        await axios.post('/api/login', {
            email: email.value,
            password: password.value,
        }, {
            suppressToast: true
        });

        router.push('/');
    } catch (err) {
        if (err.response?.status === 422) {
            const resErrors = err.response.data.errors;
            for (const key in resErrors) {
                errors.value[key] = resErrors[key][0];
            }
        } else {
            generalError.value = err.response?.data?.message || 'Login failed.';
        }
    }
};
</script>

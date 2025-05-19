<template>
    <div>
        <nav class="navbar navbar-expand navbar-light bg-light px-4">
            <router-link to="/" class="navbar-brand">ToDo App</router-link>
            <div class="ms-auto">
                <button v-if="currentUser" class="btn btn-outline-danger btn-sm" @click="handleLogout">Logout</button>
            </div>
        </nav>
        <router-view></router-view>
    </div>
</template>

<script setup>
import { currentUser, logout } from './auth';
import { useRouter } from 'vue-router';

const router = useRouter();

const handleLogout = async () => {
    await logout();
    router.push('/login');
};
</script>



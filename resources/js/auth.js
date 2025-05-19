import axios from './axios';
import { ref } from 'vue';

export const currentUser = ref(null);

export const getUser = async () => {
    try {
        const res = await axios.get('/api/user', {
            suppressToast: true,
        });
        currentUser.value = res.data;
        return res.data;
    } catch {
        currentUser.value = null;
        return null;
    }
};

export const logout = async () => {
    await axios.post('/api/logout', {}, {
        suppressToast: true,
    });
    currentUser.value = null;
};

import axios from 'axios';
import {useToast} from 'vue-toast-notification';

const instance = axios.create({
    baseURL: 'http://localhost:8000',
    withCredentials: true,
    withXSRFToken: true,
});

const toast = useToast({position: 'top'});

instance.interceptors.response.use(
    response => response,
    error => {
        const suppress = error.config?.suppressToast;

        console.log('error.config:', error.config);

        if (!suppress) {
            if (error.response) {
                const status = error.response.status;

                if (status === 401) {
                    toast.error('Unauthorized.');
                } else if (status === 403) {
                    toast.error('You do not have permission to perform this action.');
                } else if (status === 422) {
                    console.log('error.response.data:', error.response.data);
                    const messages = Object.values(error.response.data.errors || {})
                        .flat()
                        .join('\n');
                    toast.warning(messages);
                } else if (status >= 500) {
                    toast.error('Server error. Please try again later.');
                } else {
                    toast.error('Unexpected error occurred.');
                }
            } else {
                toast.error('Network error. Please check your connection.');
            }
        }

        return Promise.reject(error);
    }
);

export default instance;

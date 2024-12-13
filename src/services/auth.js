import axios from 'axios';
import router from '../router';

const API_URL = 'http://localhost:8000';

// Create axios instance for auth requests
const authAxios = axios.create({
    baseURL: `${API_URL}/api`,
    withCredentials: true,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
    }
});

export const authService = {
    async login(email, password) {
        try {
            // Get CSRF cookie
            await axios.get(`${API_URL}/sanctum/csrf-cookie`, {
                withCredentials: true,
                headers: {
                    'Accept': 'application/json'
                }
            });

            // Then make the login request
            const response = await authAxios.post('/auth/login', {
                email,
                password
            });

            if (response.data.token) {
                localStorage.setItem('user', JSON.stringify(response.data.user));
                localStorage.setItem('token', response.data.token);
                authAxios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`;
            }
            return response.data;
        } catch (error) {
            console.error('Auth error:', error.response || error);
            throw error.response?.data || { message: error.message };
        }
    },

    async register(name, email, password, password_confirmation) {
        try {
            console.log('Starting registration process...');
            
            // Get CSRF cookie first
            console.log('Fetching CSRF cookie...');
            await axios.get(`${API_URL}/sanctum/csrf-cookie`, {
                withCredentials: true,
                headers: {
                    'Accept': 'application/json'
                }
            });
            console.log('CSRF cookie obtained');

            // Prepare registration data
            const registrationData = {
                name,
                email,
                password,
                password_confirmation
            };
            console.log('Registration data prepared:', { ...registrationData, password: '[REDACTED]' });

            // Make the registration request
            console.log('Sending registration request...');
            const response = await authAxios.post('/auth/register', registrationData);
            console.log('Registration response received:', response.data);

            if (response.data.token) {
                localStorage.setItem('user', JSON.stringify(response.data.user));
                localStorage.setItem('token', response.data.token);
                authAxios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`;
            }

            return response.data;
        } catch (error) {
            console.error('Registration error:', {
                response: error.response?.data,
                status: error.response?.status,
                headers: error.response?.headers,
                config: {
                    url: error.config?.url,
                    method: error.config?.method,
                    headers: error.config?.headers
                }
            });
            throw error.response?.data || { message: error.message };
        }
    },

    logout() {
        localStorage.removeItem('user');
        localStorage.removeItem('token');
        delete authAxios.defaults.headers.common['Authorization'];
        router.push('/login');
    },

    getCurrentUser() {
        const userStr = localStorage.getItem('user');
        return userStr ? JSON.parse(userStr) : null;
    },

    getToken() {
        return localStorage.getItem('token');
    },

    isAuthenticated() {
        return !!this.getToken();
    }
};

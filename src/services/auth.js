import axios from 'axios';
import api from './api';
import { useRouter } from 'vue-router';

const API_URL = 'http://localhost:8000/api';
const router = useRouter();

export const authService = {
    async login(email, password) {
        try {
            const response = await api.post('/login', {
                email,
                password
            });
            if (response.data.token) {
                localStorage.setItem('user', JSON.stringify(response.data.user));
                localStorage.setItem('token', response.data.token);
                // Redirect to home after successful login
                router.push('/home');
            }
            return response.data;
        } catch (error) {
            throw error.response ? error.response.data : error.message;
        }
    },

    async register(name, email, password) {
        try {
            const response = await api.post('/register', {
                name,
                email,
                password
            });
            if (response.data.token) {
                localStorage.setItem('user', JSON.stringify(response.data.user));
                localStorage.setItem('token', response.data.token);
            }
            return response.data;
        } catch (error) {
            throw error.response ? error.response.data : error.message;
        }
    },

    logout() {
        localStorage.removeItem('user');
        localStorage.removeItem('token');
    },

    getCurrentUser() {
        return JSON.parse(localStorage.getItem('user'));
    },

    getToken() {
        return localStorage.getItem('token');
    },

    isAuthenticated() {
        return !!this.getToken();
    }
};

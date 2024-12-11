import api from './api';

export const userService = {
    async getUserProfile() {
        try {
            const response = await api.get('/user/profile');
            return response.data;
        } catch (error) {
            throw error.response ? error.response.data : error.message;
        }
    },

    async updateProfile(userData) {
        try {
            const response = await api.put('/user/profile', userData);
            return response.data;
        } catch (error) {
            throw error.response ? error.response.data : error.message;
        }
    },

    async getListeningHistory() {
        try {
            const response = await api.get('/user/history');
            return response.data;
        } catch (error) {
            throw error.response ? error.response.data : error.message;
        }
    },

    async addToHistory(songId) {
        try {
            const response = await api.post('/user/history', { songId });
            return response.data;
        } catch (error) {
            throw error.response ? error.response.data : error.message;
        }
    }
};

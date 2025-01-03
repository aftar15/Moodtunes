import api from './api';

export const userService = {
    // Get user profile
    async getProfile() {
        try {
            const response = await api.get('/user/profile');
            return response.data;
        } catch (error) {
            throw error.response ? error.response.data : error.message;
        }
    },

    // Update user profile
    async updateProfile(userData) {
        try {
            const response = await api.put('/user/profile', userData);
            return response.data;
        } catch (error) {
            throw error.response ? error.response.data : error.message;
        }
    },

    // Get user favorites
    async getFavorites() {
        try {
            const response = await api.get('/user/favorites');
            return response.data;
        } catch (error) {
            throw error.response ? error.response.data : error.message;
        }
    },

    // Add to favorites
    async addToFavorites(songId) {
        try {
            const response = await api.post('/user/favorites', { songId });
            return response.data;
        } catch (error) {
            throw error.response ? error.response.data : error.message;
        }
    },

    // Remove from favorites
    async removeFromFavorites(songId) {
        try {
            const response = await api.delete(`/user/favorites/${songId}`);
            return response.data;
        } catch (error) {
            throw error.response ? error.response.data : error.message;
        }
    },

    // Get user activity logs (admin)
    async getUserLogs() {
        try {
            const response = await api.get('/admin/user-logs');
            return response.data;
        } catch (error) {
            throw error.response ? error.response.data : error.message;
        }
    }
};

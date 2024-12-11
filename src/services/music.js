import api from './api';

export const musicService = {
    async getAllSongs() {
        try {
            const response = await api.get('/songs');
            return response.data;
        } catch (error) {
            throw error.response ? error.response.data : error.message;
        }
    },

    async getSongsByMood(mood) {
        try {
            const response = await api.get(`/songs/mood/${mood}`);
            return response.data;
        } catch (error) {
            throw error.response ? error.response.data : error.message;
        }
    },

    async getFavorites() {
        try {
            const response = await api.get('/favorites');
            return response.data;
        } catch (error) {
            throw error.response ? error.response.data : error.message;
        }
    },

    async addToFavorites(songId) {
        try {
            const response = await api.post('/favorites', { songId });
            return response.data;
        } catch (error) {
            throw error.response ? error.response.data : error.message;
        }
    },

    async removeFromFavorites(songId) {
        try {
            const response = await api.delete(`/favorites/${songId}`);
            return response.data;
        } catch (error) {
            throw error.response ? error.response.data : error.message;
        }
    },

    async getPlaylists() {
        try {
            const response = await api.get('/playlists');
            return response.data;
        } catch (error) {
            throw error.response ? error.response.data : error.message;
        }
    },

    async createPlaylist(name, description) {
        try {
            const response = await api.post('/playlists', { name, description });
            return response.data;
        } catch (error) {
            throw error.response ? error.response.data : error.message;
        }
    },

    async addToPlaylist(playlistId, songId) {
        try {
            const response = await api.post(`/playlists/${playlistId}/songs`, { songId });
            return response.data;
        } catch (error) {
            throw error.response ? error.response.data : error.message;
        }
    }
};

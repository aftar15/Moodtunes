import api from './api';

export const musicService = {
    // Fetch songs by mood
    async getSongsByMood(mood) {
        try {
            const response = await api.get(`/songs/mood/${mood}`);
            return response.data;
        } catch (error) {
            throw error.response ? error.response.data : error.message;
        }
    },

    // Get all songs (admin)
    async getAllSongs() {
        try {
            const response = await api.get('/songs');
            return response.data;
        } catch (error) {
            throw error.response ? error.response.data : error.message;
        }
    },

    // Add new song (admin)
    async addSong(songData) {
        try {
            const formData = new FormData();
            formData.append('title', songData.title);
            formData.append('artist', songData.artist);
            formData.append('mood', songData.mood);
            formData.append('audio', songData.audioFile);
            formData.append('cover', songData.coverFile);

            const response = await api.post('/songs', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            });
            return response.data;
        } catch (error) {
            throw error.response ? error.response.data : error.message;
        }
    },

    // Delete song (admin)
    async deleteSong(songId) {
        try {
            const response = await api.delete(`/songs/${songId}`);
            return response.data;
        } catch (error) {
            throw error.response ? error.response.data : error.message;
        }
    },

    // Update song (admin)
    async updateSong(songId, songData) {
        try {
            const response = await api.put(`/songs/${songId}`, songData);
            return response.data;
        } catch (error) {
            throw error.response ? error.response.data : error.message;
        }
    }
};

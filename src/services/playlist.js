import api from './api';

export const playlistService = {
    // Get user's playlists
    async getUserPlaylists() {
        try {
            const response = await api.get('/playlists');
            return response.data;
        } catch (error) {
            throw error.response ? error.response.data : error.message;
        }
    },

    // Create new playlist
    async createPlaylist(playlistData) {
        try {
            const response = await api.post('/playlists', playlistData);
            return response.data;
        } catch (error) {
            throw error.response ? error.response.data : error.message;
        }
    },

    // Add song to playlist
    async addSongToPlaylist(playlistId, songId) {
        try {
            const response = await api.post(`/playlists/${playlistId}/songs`, { songId });
            return response.data;
        } catch (error) {
            throw error.response ? error.response.data : error.message;
        }
    },

    // Remove song from playlist
    async removeSongFromPlaylist(playlistId, songId) {
        try {
            const response = await api.delete(`/playlists/${playlistId}/songs/${songId}`);
            return response.data;
        } catch (error) {
            throw error.response ? error.response.data : error.message;
        }
    },

    // Delete playlist
    async deletePlaylist(playlistId) {
        try {
            const response = await api.delete(`/playlists/${playlistId}`);
            return response.data;
        } catch (error) {
            throw error.response ? error.response.data : error.message;
        }
    },

    // Get playlist details
    async getPlaylistDetails(playlistId) {
        try {
            const response = await api.get(`/playlists/${playlistId}`);
            return response.data;
        } catch (error) {
            throw error.response ? error.response.data : error.message;
        }
    }
};

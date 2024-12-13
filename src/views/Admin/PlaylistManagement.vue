<template>
  <div class="playlist-management">
    <div class="user-playlists">
      <h2>User Playlists</h2>
      <table class="playlist-table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Created By</th>
            <th>Songs Count</th>
            <th>Created At</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="playlist in playlists" :key="playlist.id">
            <td>{{ playlist.name }}</td>
            <td>{{ playlist.user.name }}</td>
            <td>{{ playlist.songs_count }}</td>
            <td>{{ formatDate(playlist.created_at) }}</td>
            <td>
              <button @click="viewPlaylist(playlist)" class="btn-view">View</button>
              <button @click="deletePlaylist(playlist.id)" class="btn-delete">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div class="pagination">
        <button :disabled="currentPage === 1" @click="prevPage" class="btn-nav">Previous</button>
        <span>Page {{ currentPage }} of {{ totalPages }}</span>
        <button :disabled="currentPage === totalPages" @click="nextPage" class="btn-nav">Next</button>
      </div>
    </div>

    <!-- View Playlist Modal -->
    <div v-if="selectedPlaylist" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <h2>{{ selectedPlaylist.name }}</h2>
          <button @click="closeModal" class="btn-close">&times;</button>
        </div>
        <div class="modal-body">
          <h3>Songs in Playlist</h3>
          <table class="songs-table">
            <thead>
              <tr>
                <th>Title</th>
                <th>Artist</th>
                <th>Mood</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="song in selectedPlaylist.songs" :key="song.id">
                <td>{{ song.title }}</td>
                <td>{{ song.artist }}</td>
                <td>{{ song.mood }}</td>
                <td>
                  <button @click="removeSong(song.id)" class="btn-delete">Remove</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'PlaylistManagement',
  data() {
    return {
      playlists: [],
      selectedPlaylist: null,
      currentPage: 1,
      totalPages: 1
    };
  },
  methods: {
    async fetchPlaylists() {
      try {
        const response = await axios.get(`/api/admin/playlists?page=${this.currentPage}`);
        this.playlists = response.data.data;
        this.totalPages = response.data.last_page;
      } catch (error) {
        console.error('Error fetching playlists:', error);
      }
    },
    async deletePlaylist(id) {
      if (confirm('Are you sure you want to delete this playlist?')) {
        try {
          await axios.delete(`/api/admin/playlists/${id}`);
          await this.fetchPlaylists();
        } catch (error) {
          console.error('Error deleting playlist:', error);
        }
      }
    },
    async viewPlaylist(playlist) {
      try {
        const response = await axios.get(`/api/admin/playlists/${playlist.id}`);
        this.selectedPlaylist = response.data;
      } catch (error) {
        console.error('Error fetching playlist details:', error);
      }
    },
    async removeSong(songId) {
      if (!this.selectedPlaylist) return;
      
      try {
        await axios.delete(`/api/admin/playlists/${this.selectedPlaylist.id}/songs/${songId}`);
        await this.viewPlaylist(this.selectedPlaylist);
      } catch (error) {
        console.error('Error removing song from playlist:', error);
      }
    },
    closeModal() {
      this.selectedPlaylist = null;
    },
    formatDate(date) {
      return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
      });
    },
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
        this.fetchPlaylists();
      }
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
        this.fetchPlaylists();
      }
    }
  },
  mounted() {
    this.fetchPlaylists();
  }
};
</script>

<style scoped>
.playlist-management {
  padding: 20px;
}

h1 {
  margin-bottom: 30px;
  color: #333;
}

h2 {
  color: #444;
  margin-bottom: 20px;
}

.playlist-library {
  background: white;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.playlist-table,
.songs-table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 20px;
}

.playlist-table th,
.playlist-table td,
.songs-table th,
.songs-table td {
  padding: 12px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

.playlist-table th,
.songs-table th {
  background-color: #f5f5f5;
  font-weight: bold;
}

.btn-view,
.btn-delete {
  padding: 6px 12px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  margin-right: 5px;
  font-size: 12px;
  color: white;
}

.btn-view {
  background-color: #2196F3;
}

.btn-delete {
  background-color: #f44336;
}

.btn-view:hover {
  background-color: #1976D2;
}

.btn-delete:hover {
  background-color: #D32F2F;
}

.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 15px;
  margin-top: 20px;
}

.btn-nav {
  padding: 8px 15px;
  border: none;
  border-radius: 4px;
  background-color: #2196F3;
  color: white;
  cursor: pointer;
  transition: background-color 0.3s;
}

.btn-nav:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}

.btn-nav:hover:not(:disabled) {
  background-color: #1976D2;
}

/* Modal Styles */
.modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  background: white;
  border-radius: 8px;
  width: 80%;
  max-width: 800px;
  max-height: 80vh;
  overflow-y: auto;
}

.modal-header {
  padding: 15px 20px;
  border-bottom: 1px solid #ddd;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.modal-body {
  padding: 20px;
}

.btn-close {
  background: none;
  border: none;
  font-size: 24px;
  cursor: pointer;
  color: #666;
}

.btn-close:hover {
  color: #333;
}
</style>
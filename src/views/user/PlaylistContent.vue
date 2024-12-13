<template>
  <div class="playlist-container">
    <div class="playlist-header">
      <h2>My Playlists</h2>
      <button @click="showCreateModal = true" class="create-btn">
        <i class="fas fa-plus"></i> Create Playlist
      </button>
    </div>

    <!-- Playlists Grid -->
    <div class="playlists-grid">
      <div v-for="playlist in playlists" :key="playlist.id" class="playlist-card">
        <div class="playlist-cover">
          <img :src="playlist.coverUrl || defaultCover" :alt="playlist.name">
          <div class="playlist-actions">
            <button @click="editPlaylist(playlist)" class="action-btn">
              <i class="fas fa-edit"></i>
            </button>
            <button @click="deletePlaylist(playlist.id)" class="action-btn">
              <i class="fas fa-trash"></i>
            </button>
          </div>
        </div>
        <div class="playlist-info">
          <h3>{{ playlist.name }}</h3>
          <p>{{ playlist.songCount }} songs</p>
        </div>
      </div>
    </div>

    <!-- Create/Edit Modal -->
    <div v-if="showCreateModal || showEditModal" class="modal">
      <div class="modal-content">
        <h3>{{ showEditModal ? 'Edit' : 'Create' }} Playlist</h3>
        <form @submit.prevent="handleSubmit">
          <div class="form-group">
            <label>Name</label>
            <input v-model="playlistForm.name" required>
          </div>
          <div class="form-group">
            <label>Description</label>
            <textarea v-model="playlistForm.description"></textarea>
          </div>
          <div class="form-group">
            <label>Cover Image URL</label>
            <input v-model="playlistForm.coverUrl">
          </div>
          <div class="modal-actions">
            <button type="button" @click="closeModal" class="cancel-btn">Cancel</button>
            <button type="submit" class="submit-btn">
              {{ showEditModal ? 'Update' : 'Create' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import api from '@/services/api';

export default {
  name: 'PlaylistContent',
  data() {
    return {
      playlists: [],
      showCreateModal: false,
      showEditModal: false,
      defaultCover: '/path/to/default-cover.jpg',
      playlistForm: {
        name: '',
        description: '',
        coverUrl: '',
        id: null
      },
      isLoading: false,
      error: null
    }
  },
  methods: {
    async fetchPlaylists() {
      this.isLoading = true;
      this.error = null;
      try {
        const response = await api.get('/playlists');
        this.playlists = response.data;
      } catch (error) {
        this.error = error.message;
        console.error('Error fetching playlists:', error);
      } finally {
        this.isLoading = false;
      }
    },
    editPlaylist(playlist) {
      this.playlistForm = { ...playlist };
      this.showEditModal = true;
    },
    async handleSubmit() {
      this.isLoading = true;
      this.error = null;
      try {
        const endpoint = this.showEditModal ? `/playlists/${this.playlistForm.id}` : '/playlists';
        const method = this.showEditModal ? 'PUT' : 'POST';
        
        const response = await api({
          method,
          url: endpoint,
          data: this.playlistForm
        });

        if (!response.data.success) throw new Error('Failed to save playlist');
        await this.fetchPlaylists();
        this.closeModal();
      } catch (error) {
        this.error = error.message;
        console.error('Error saving playlist:', error);
      } finally {
        this.isLoading = false;
      }
    },
    async deletePlaylist(id) {
      if (confirm('Are you sure you want to delete this playlist?')) {
        this.isLoading = true;
        this.error = null;
        try {
          const response = await api.delete(`/playlists/${id}`);
          if (!response.data.success) throw new Error('Failed to delete playlist');
          await this.fetchPlaylists();
        } catch (error) {
          this.error = error.message;
          console.error('Error deleting playlist:', error);
        } finally {
          this.isLoading = false;
        }
      }
    },
    closeModal() {
      this.showCreateModal = false;
      this.showEditModal = false;
      this.playlistForm = {
        name: '',
        description: '',
        coverUrl: '',
        id: null
      };
    }
  },
  mounted() {
    this.fetchPlaylists();
  }
}
</script>

<style scoped>
.playlist-container {
  padding: 20px;
}

.playlist-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.create-btn {
  padding: 8px 16px;
  background: #1db954;
  color: white;
  border: none;
  border-radius: 20px;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 8px;
}

.playlists-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 20px;
}

.playlist-card {
  background: white;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.playlist-cover {
  position: relative;
  aspect-ratio: 1;
}

.playlist-cover img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.playlist-actions {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0,0,0,0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 10px;
  opacity: 0;
  transition: opacity 0.2s;
}

.playlist-card:hover .playlist-actions {
  opacity: 1;
}

.action-btn {
  background: white;
  border: none;
  border-radius: 50%;
  width: 36px;
  height: 36px;
  cursor: pointer;
}

.playlist-info {
  padding: 15px;
}

.playlist-info h3 {
  margin: 0;
  font-size: 16px;
}

.playlist-info p {
  margin: 5px 0 0;
  color: #666;
  font-size: 14px;
}

.modal {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0,0,0,0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-content {
  background: white;
  padding: 20px;
  border-radius: 8px;
  width: 100%;
  max-width: 400px;
}

.form-group {
  margin-bottom: 15px;
}

.form-group label {
  display: block;
  margin-bottom: 5px;
}

.form-group input,
.form-group textarea {
  width: 100%;
  padding: 8px;
  border: 1px solid #ddd;
  border-radius: 4px;
}

.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  margin-top: 20px;
}

.cancel-btn {
  padding: 8px 16px;
  background: #ddd;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.submit-btn {
  padding: 8px 16px;
  background: #1db954;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}
</style>

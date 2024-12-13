<template>
  <div class="music-management">
    <h1>Music Management</h1>

    <!-- Add New Music Form -->
    <div class="add-music-form">
      <h2>Add New Music</h2>
      <form @submit.prevent="addMusic" class="form-grid">
        <div class="form-group">
          <label for="title">Title</label>
          <input type="text" id="title" v-model="newMusic.title" required>
        </div>
        
        <div class="form-group">
          <label for="artist">Artist</label>
          <input type="text" id="artist" v-model="newMusic.artist" required>
        </div>
        
        <div class="form-group">
          <label for="mood">Mood</label>
          <select id="mood" v-model="newMusic.mood" required>
            <option value="">Select Mood</option>
            <option v-for="mood in moods" :key="mood" :value="mood">
              {{ mood }}
            </option>
          </select>
        </div>

        <div class="form-group">
          <label for="audio">Audio File</label>
          <select id="audio" v-model="newMusic.audioPath" required>
            <option value="">Select Audio File</option>
            <option v-for="file in availableAudioFiles" :key="file.path" :value="file.path">
              {{ file.name }}
            </option>
          </select>
        </div>

        <div class="form-group">
          <label for="coverImage">Cover Image</label>
          <input 
            type="file" 
            id="coverImage" 
            ref="coverInput"
            @change="e => handleFileUpload(e, 'cover')" 
            accept="image/*"
          >
        </div>

        <button type="submit" :disabled="loading" class="add-button">
          {{ loading ? 'Adding...' : 'Add Music' }}
        </button>
      </form>
    </div>

    <!-- Music Library -->
    <div class="music-library">
      <h2>Music Library</h2>
      
      <!-- Search and Filter -->
      <div class="filters">
        <div class="filter-group">
          <label>Filter by Mood</label>
          <select v-model="selectedMood" @change="fetchMusic">
            <option>All Moods</option>
            <option>Happy</option>
            <option>Sad</option>
            <option>Energetic</option>
            <option>Calm</option>
            <option>Romantic</option>
            <option>Unspecified</option>
          </select>
        </div>
        
        <div class="search-group">
          <label>Search</label>
          <input 
            type="text" 
            v-model="searchQuery" 
            @input="debounceSearch"
            placeholder="Search by title or artist"
          >
        </div>
      </div>

      <!-- Music Table -->
      <div class="table-container">
        <table v-if="musicList.length > 0">
          <thead>
            <tr>
              <th>Cover</th>
              <th>Title</th>
              <th>Artist</th>
              <th>Mood</th>
              <th>Audio</th>
              <th>Added Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="song in musicList" :key="song.id">
              <td class="cover-cell">
                <img 
                  v-if="song.cover_url" 
                  :src="song.cover_url" 
                  :alt="song.title + ' cover'"
                  class="cover-image"
                >
                <div v-else class="no-cover">No Cover</div>
              </td>
              <td>{{ song.title }}</td>
              <td>{{ song.artist }}</td>
              <td>{{ song.mood }}</td>
              <td>
                <audio controls>
                  <source :src="song.audio_url" type="audio/mpeg">
                  Your browser does not support the audio element.
                </audio>
              </td>
              <td>{{ new Date(song.created_at).toLocaleDateString() }}</td>
              <td>
                <button @click="editSong(song)" class="edit-btn">Edit</button>
                <button @click="deleteSong(song.id)" class="delete-btn">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>
        <div v-else class="no-songs">
          No songs found. Add some music or try a different search.
        </div>
      </div>

      <!-- Pagination -->
      <div class="pagination" v-if="totalPages > 1">
        <button 
          :disabled="currentPage === 1" 
          @click="changePage(currentPage - 1)"
        >
          Previous
        </button>
        <span>Page {{ currentPage }} of {{ totalPages }}</span>
        <button 
          :disabled="currentPage === totalPages" 
          @click="changePage(currentPage + 1)"
        >
          Next
        </button>
      </div>
    </div>

    <!-- Edit Modal -->
    <div v-if="showEditModal" class="modal">
      <div class="modal-content">
        <h2>Edit Music</h2>
        <form @submit.prevent="updateMusic" class="form-grid">
          <div class="form-group">
            <label for="editTitle">Title</label>
            <input type="text" id="editTitle" v-model="editingMusic.title" required>
          </div>
          
          <div class="form-group">
            <label for="editArtist">Artist</label>
            <input type="text" id="editArtist" v-model="editingMusic.artist" required>
          </div>
          
          <div class="form-group">
            <label for="editMood">Mood</label>
            <select id="editMood" v-model="editingMusic.mood" required>
              <option v-for="mood in moods" :key="mood" :value="mood">
                {{ mood }}
              </option>
            </select>
          </div>

          <div class="form-group">
            <label for="editAudioFile">New Audio File (optional)</label>
            <select id="editAudioFile" v-model="editingMusic.audioPath" required>
              <option value="">Select Audio File</option>
              <option v-for="file in availableAudioFiles" :key="file.path" :value="file.path">
                {{ file.name }}
              </option>
            </select>
          </div>

          <div class="form-group">
            <label for="editCoverImage">New Cover Image (optional)</label>
            <input type="file" id="editCoverImage" @change="handleEditImageUpload" accept="image/*">
          </div>

          <div class="modal-actions">
            <button type="submit" class="btn-primary">Save Changes</button>
            <button type="button" @click="closeEditModal" class="btn-secondary">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import axios from '@/plugins/axios';
import { ref } from 'vue';

export default {
  name: 'MusicManagement',
  setup() {
    const searchTimeout = ref(null);
    return { searchTimeout };
  },
  data() {
    return {
      musicList: [],
      loading: false,
      error: null,
      currentPage: 1,
      totalPages: 1,
      searchQuery: '',
      selectedMood: 'All Moods',
      availableAudioFiles: [],
      moods: ['All Moods', 'Happy', 'Sad', 'Energetic', 'Calm', 'Focused'],
      newMusic: {
        title: '',
        artist: '',
        mood: '',
        audioPath: '',
        coverImage: null
      },
      showEditModal: false,
      editingMusic: null,
      editingFiles: {
        audio: null,
        cover: null
      }
    };
  },
  mounted() {
    this.fetchMusic();
  },
  methods: {
    debounceSearch() {
      if (this.searchTimeout) {
        clearTimeout(this.searchTimeout);
      }
      this.searchTimeout = setTimeout(() => {
        this.fetchMusic();
      }, 300);
    },

    async fetchMusic() {
      try {
        this.loading = true;
        const response = await axios.get('/api/admin/music', {
          params: {
            page: this.currentPage,
            mood: this.selectedMood !== 'All Moods' ? this.selectedMood : null,
            search: this.searchQuery || null
          }
        });
        
        if (response.data && Array.isArray(response.data.data)) {
          this.musicList = response.data.data;
          this.totalPages = response.data.last_page || 1;
        } else {
          console.error('Unexpected response format:', response.data);
          this.error = 'Invalid response format from server';
        }
      } catch (error) {
        console.error('Error fetching music:', error);
        this.error = 'Failed to load music library';
      } finally {
        this.loading = false;
      }
    },
    
    async fetchAvailableAudioFiles() {
      try {
        const response = await axios.get('/api/admin/music/available-audio');
        if (response.data && response.data.success) {
          this.availableAudioFiles = response.data.data;
        }
      } catch (error) {
        console.error('Error fetching available audio files:', error);
      }
    },

    changePage(page) {
      this.currentPage = page;
      this.fetchMusic();
    },

    handleFileUpload(event, type) {
      if (type === 'cover') {
        this.newMusic.coverImage = event.target.files[0];
      }
    },
    
    async addMusic() {
      try {
        if (!this.newMusic.title || !this.newMusic.artist || !this.newMusic.mood || !this.newMusic.audioPath) {
          alert('Please fill in all fields and select files');
          return;
        }

        this.loading = true;
        const formData = new FormData();
        formData.append('title', this.newMusic.title);
        formData.append('artist', this.newMusic.artist);
        formData.append('mood', this.newMusic.mood);
        formData.append('audioPath', this.newMusic.audioPath);
        if (this.newMusic.coverImage) {
          formData.append('cover', this.newMusic.coverImage);
        }

        const response = await axios.post('/api/admin/music', formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        });

        if (response.data.success) {
          // Reset form
          this.newMusic = {
            title: '',
            artist: '',
            mood: '',
            audioPath: '',
            coverImage: null
          };
          
          // Clear file input
          if (this.$refs.coverInput) this.$refs.coverInput.value = '';
          
          // Refresh music library
          await this.fetchMusic();
          
          // Show success message
          alert('Music added successfully!');
        }
      } catch (error) {
        console.error('Error adding music:', error);
        alert(error.response?.data?.message || 'Error adding music. Please try again.');
      } finally {
        this.loading = false;
      }
    },
    
    editMusic(music) {
      this.editingMusic = { ...music };
      this.showEditModal = true;
    },
    
    handleEditImageUpload(event) {
      this.editingFiles.cover = event.target.files[0];
    },
    
    async updateMusic() {
      try {
        const formData = new FormData();
        formData.append('title', this.editingMusic.title);
        formData.append('artist', this.editingMusic.artist);
        formData.append('mood', this.editingMusic.mood);
        formData.append('audioPath', this.editingMusic.audioPath);
        
        if (this.editingFiles.cover) {
          formData.append('cover', this.editingFiles.cover);
        }

        await axios.put(`/api/admin/music/${this.editingMusic.id}`, formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        });

        this.closeEditModal();
        this.fetchMusic();
      } catch (error) {
        console.error('Error updating music:', error);
        alert(error.response?.data?.message || 'Error updating music. Please try again.');
      }
    },
    
    async deleteMusic(id) {
      if (confirm('Are you sure you want to delete this music?')) {
        try {
          await axios.delete(`/api/admin/music/${id}`);
          this.fetchMusic();
        } catch (error) {
          console.error('Error deleting music:', error);
          alert(error.response?.data?.message || 'Error deleting music. Please try again.');
        }
      }
    },
    
    closeEditModal() {
      this.showEditModal = false;
      this.editingMusic = null;
      this.editingFiles = {
        audio: null,
        cover: null
      };
    },
    
    formatDate(date) {
      return new Date(date).toLocaleString();
    },
    
    async handleSearch() {
      this.currentPage = 1;
      await this.fetchMusic();
    },
    
    async handleMoodFilter() {
      this.currentPage = 1;
      await this.fetchMusic();
    },
    
    async changePage(page) {
      this.currentPage = page;
      await this.fetchMusic();
    }
  }
};
</script>

<style scoped>
.music-management {
  padding: 20px;
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
  margin-bottom: 30px;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.form-group label {
  font-weight: bold;
  color: #444;
}

.form-group input,
.form-group select {
  padding: 8px 12px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 14px;
}

.filters {
  display: flex;
  gap: 20px;
  margin-bottom: 20px;
}

.filter-group {
  flex: 1;
}

.music-library {
  background: white;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  margin-bottom: 30px;
}

.table-container {
  margin-top: 20px;
  overflow-x: auto;
}

table {
  width: 100%;
  border-collapse: collapse;
  background: white;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

th, td {
  padding: 12px;
  text-align: left;
  border-bottom: 1px solid #eee;
}

th {
  background-color: #f8f9fa;
  font-weight: 600;
}

.no-songs {
  text-align: center;
  padding: 20px;
  color: #666;
  background: white;
  border-radius: 4px;
  margin-top: 20px;
}

audio {
  max-width: 200px;
}

.cover-cell {
  width: 60px;
}

.no-cover {
  width: 50px;
  height: 50px;
  background-color: #eee;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 12px;
  color: #666;
  border-radius: 4px;
}

.cover-image {
  width: 50px;
  height: 50px;
  object-fit: cover;
  border-radius: 4px;
}

.mood-badge {
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 12px;
  font-weight: bold;
  color: white;
}

.mood-badge.happy { background-color: #FFD700; }
.mood-badge.sad { background-color: #6495ED; }
.mood-badge.energetic { background-color: #FF4500; }
.mood-badge.relaxed { background-color: #98FB98; }
.mood-badge.romantic { background-color: #FF69B4; }
.mood-badge.focus { background-color: #4682B4; }
.mood-badge.party { background-color: #FF1493; }
.mood-badge.chill { background-color: #87CEEB; }
.mood-badge.workout { background-color: #FF6347; }
.mood-badge.sleep { background-color: #9370DB; }

.actions {
  display: flex;
  gap: 8px;
}

.btn-edit,
.btn-delete {
  padding: 6px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.btn-edit {
  background-color: #2196F3;
  color: white;
}

.btn-delete {
  background-color: #f44336;
  color: white;
}

.btn-edit:hover {
  background-color: #1976D2;
}

.btn-delete:hover {
  background-color: #D32F2F;
}

.modal {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  background: white;
  padding: 30px;
  border-radius: 8px;
  width: 90%;
  max-width: 600px;
  max-height: 90vh;
  overflow-y: auto;
}

.modal-actions {
  display: flex;
  gap: 10px;
  justify-content: flex-end;
  margin-top: 20px;
}

.btn-primary {
  background-color: #4CAF50;
  color: white;
  padding: 8px 16px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.btn-secondary {
  background-color: #9e9e9e;
  color: white;
  padding: 8px 16px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.btn-primary:hover {
  background-color: #45a049;
}

.btn-secondary:hover {
  background-color: #757575;
}

.add-music-form,
.music-library {
  background: white;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  margin-bottom: 30px;
}

.loading {
  text-align: center;
  padding: 20px;
}

.error {
  color: red;
  text-align: center;
  padding: 20px;
}

.pagination {
  display: flex;
  justify-content: center;
  gap: 20px;
  margin-top: 20px;
}

.pagination button {
  padding: 8px 16px;
  border: 1px solid #ddd;
  border-radius: 4px;
  cursor: pointer;
}

.pagination button:disabled {
  background-color: #f5f5f5;
  cursor: not-allowed;
}

button.delete {
  background-color: #ff4444;
  color: white;
  margin-left: 8px;
}

.form-group select {
  width: 100%;
  padding: 8px;
  border: 1px solid #ddd;
  border-radius: 4px;
  background-color: white;
}
</style>

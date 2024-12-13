<template>
  <div class="favorites-container">
    <div class="favorites-header">
      <h2>My Favorites</h2>
      <div class="view-options">
        <button 
          @click="viewMode = 'grid'"
          :class="{ active: viewMode === 'grid' }"
          class="view-btn"
        >
          <i class="fas fa-th"></i>
        </button>
        <button 
          @click="viewMode = 'list'"
          :class="{ active: viewMode === 'list' }"
          class="view-btn"
        >
          <i class="fas fa-list"></i>
        </button>
      </div>
    </div>

    <!-- Grid View -->
    <div v-if="viewMode === 'grid'" class="favorites-grid">
      <div v-for="song in favorites" :key="song.id" class="song-card">
        <div class="song-cover">
          <img :src="song.coverUrl || defaultCover" :alt="song.title">
          <div class="song-actions">
            <button @click="playSong(song)" class="action-btn play">
              <i class="fas fa-play"></i>
            </button>
            <button @click="removeFavorite(song.id)" class="action-btn">
              <i class="fas fa-heart-broken"></i>
            </button>
          </div>
        </div>
        <div class="song-info">
          <h3>{{ song.title }}</h3>
          <p>{{ song.artist }}</p>
        </div>
      </div>
    </div>

    <!-- List View -->
    <div v-else class="favorites-list">
      <div v-for="song in favorites" :key="song.id" class="song-item">
        <img :src="song.coverUrl || defaultCover" :alt="song.title" class="song-thumbnail">
        <div class="song-details">
          <h3>{{ song.title }}</h3>
          <p>{{ song.artist }}</p>
        </div>
        <div class="song-duration">{{ formatDuration(song.duration) }}</div>
        <div class="item-actions">
          <button @click="playSong(song)" class="action-btn play">
            <i class="fas fa-play"></i>
          </button>
          <button @click="removeFavorite(song.id)" class="action-btn">
            <i class="fas fa-heart-broken"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'FavoritesContent',
  data() {
    return {
      favorites: [],
      viewMode: 'grid',
      defaultCover: '/path/to/default-cover.jpg',
      isLoading: false,
      error: null
    }
  },
  methods: {
    async fetchFavorites() {
      this.isLoading = true;
      this.error = null;
      try {
        const response = await fetch('/api/favorites', {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          }
        });
        if (!response.ok) throw new Error('Failed to fetch favorites');
        this.favorites = await response.json();
      } catch (error) {
        this.error = error.message;
        console.error('Error fetching favorites:', error);
      } finally {
        this.isLoading = false;
      }
    },
    async removeFavorite(id) {
      if (confirm('Remove this song from favorites?')) {
        this.isLoading = true;
        this.error = null;
        try {
          const response = await fetch(`/api/favorites/${id}`, {
            method: 'DELETE',
            headers: {
              'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
          });
          if (!response.ok) throw new Error('Failed to remove from favorites');
          await this.fetchFavorites();
        } catch (error) {
          this.error = error.message;
          console.error('Error removing favorite:', error);
        } finally {
          this.isLoading = false;
        }
      }
    },
    async addToFavorites(songId) {
      this.isLoading = true;
      this.error = null;
      try {
        const response = await fetch('/api/favorites', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          },
          body: JSON.stringify({ songId })
        });
        if (!response.ok) throw new Error('Failed to add to favorites');
        await this.fetchFavorites();
      } catch (error) {
        this.error = error.message;
        console.error('Error adding to favorites:', error);
      } finally {
        this.isLoading = false;
      }
    },
    async playSong(song) {
      try {
        // Emit event to parent component or vuex store to handle playback
        this.$emit('play-song', song);
      } catch (error) {
        console.error('Error playing song:', error);
      }
    },
    formatDuration(seconds) {
      const minutes = Math.floor(seconds / 60);
      const remainingSeconds = seconds % 60;
      return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
    }
  },
  mounted() {
    this.fetchFavorites();
  }
}
</script>

<style scoped>
.favorites-container {
  padding: 20px;
}

.favorites-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.view-options {
  display: flex;
  gap: 10px;
}

.view-btn {
  background: none;
  border: none;
  padding: 8px;
  cursor: pointer;
  color: #666;
}

.view-btn.active {
  color: #1db954;
}

/* Grid View Styles */
.favorites-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 20px;
}

.song-card {
  background: white;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.song-cover {
  position: relative;
  aspect-ratio: 1;
}

.song-cover img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.song-actions {
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

.song-card:hover .song-actions {
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

.action-btn.play {
  background: #1db954;
  color: white;
}

.song-info {
  padding: 15px;
}

.song-info h3 {
  margin: 0;
  font-size: 16px;
}

.song-info p {
  margin: 5px 0 0;
  color: #666;
  font-size: 14px;
}

/* List View Styles */
.favorites-list {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.song-item {
  display: flex;
  align-items: center;
  padding: 10px;
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.song-thumbnail {
  width: 60px;
  height: 60px;
  border-radius: 4px;
  object-fit: cover;
}

.song-details {
  flex: 1;
  margin-left: 15px;
}

.song-details h3 {
  margin: 0;
  font-size: 16px;
}

.song-details p {
  margin: 5px 0 0;
  color: #666;
  font-size: 14px;
}

.song-duration {
  color: #666;
  margin: 0 20px;
}

.item-actions {
  display: flex;
  gap: 10px;
}
</style>

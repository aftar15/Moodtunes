<template>
  <div class="favorite-management">
    <div class="favorites-list">
      <h2>User Favorites</h2>
      <table class="favorites-table">
        <thead>
          <tr>
            <th>User</th>
            <th>Song Title</th>
            <th>Artist</th>
            <th>Mood</th>
            <th>Favorited At</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="favorite in favorites" :key="favorite.id">
            <td>{{ favorite.user.name }}</td>
            <td>{{ favorite.song.title }}</td>
            <td>{{ favorite.song.artist }}</td>
            <td>{{ favorite.song.mood }}</td>
            <td>{{ formatDate(favorite.created_at) }}</td>
            <td>
              <button @click="removeFavorite(favorite.id)" class="btn-delete">Remove</button>
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

    <!-- Stats Section -->
    <div class="stats-section">
      <h2>Favorite Statistics</h2>
      <div class="stats-grid">
        <div class="stat-card">
          <span class="stat-value">{{ stats.totalFavorites }}</span>
          <span class="stat-label">Total Favorites</span>
        </div>
        <div class="stat-card">
          <span class="stat-value">{{ stats.uniqueUsers }}</span>
          <span class="stat-label">Unique Users</span>
        </div>
        <div class="stat-card">
          <span class="stat-value">{{ stats.uniqueSongs }}</span>
          <span class="stat-label">Unique Songs</span>
        </div>
        <div class="stat-card">
          <span class="stat-value">{{ stats.mostFavoritedMood }}</span>
          <span class="stat-label">Most Popular Mood</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'FavoriteManagement',
  data() {
    return {
      favorites: [],
      currentPage: 1,
      totalPages: 1,
      stats: {
        totalFavorites: 0,
        uniqueUsers: 0,
        uniqueSongs: 0,
        mostFavoritedMood: '-'
      }
    };
  },
  methods: {
    async fetchFavorites() {
      try {
        const response = await axios.get(`/api/admin/favorites?page=${this.currentPage}`);
        this.favorites = response.data.data;
        this.totalPages = response.data.last_page;
      } catch (error) {
        console.error('Error fetching favorites:', error);
      }
    },
    async fetchStats() {
      try {
        const response = await axios.get('/api/admin/favorites/stats');
        this.stats = response.data;
      } catch (error) {
        console.error('Error fetching favorite stats:', error);
      }
    },
    async removeFavorite(id) {
      if (confirm('Are you sure you want to remove this favorite?')) {
        try {
          await axios.delete(`/api/admin/favorites/${id}`);
          await this.fetchFavorites();
          await this.fetchStats();
        } catch (error) {
          console.error('Error removing favorite:', error);
        }
      }
    },
    formatDate(date) {
      return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });
    },
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
        this.fetchFavorites();
      }
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
        this.fetchFavorites();
      }
    }
  },
  mounted() {
    this.fetchFavorites();
    this.fetchStats();
  }
};
</script>

<style scoped>
.favorite-management {
  padding: 20px;
}

h2 {
  color: #444;
  margin-bottom: 20px;
}

.favorites-list {
  background: white;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  margin-bottom: 30px;
}

.favorites-table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 20px;
}

.favorites-table th,
.favorites-table td {
  padding: 12px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

.favorites-table th {
  background-color: #f5f5f5;
  font-weight: bold;
}

.btn-delete {
  padding: 6px 12px;
  border: none;
  border-radius: 4px;
  background-color: #f44336;
  color: white;
  cursor: pointer;
  font-size: 12px;
  transition: background-color 0.3s;
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

.stats-section {
  background: white;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  margin-top: 20px;
}

.stat-card {
  background: #f8f9fa;
  padding: 20px;
  border-radius: 8px;
  text-align: center;
  transition: transform 0.3s;
}

.stat-card:hover {
  transform: translateY(-5px);
}

.stat-value {
  display: block;
  font-size: 24px;
  font-weight: bold;
  color: #2196F3;
  margin-bottom: 10px;
}

.stat-label {
  color: #666;
  font-size: 14px;
}
</style>

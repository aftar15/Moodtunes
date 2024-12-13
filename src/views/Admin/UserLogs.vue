<template>
  <div class="user-logs">
    <!-- Filters -->
    <div class="filters">
      <div class="filter-group">
        <label for="actionType">Action Type</label>
        <select id="actionType" v-model="filters.actionType" class="form-control">
          <option value="">All Actions</option>
          <option value="login">Login</option>
          <option value="logout">Logout</option>
          <option value="play">Play Music</option>
          <option value="favorite">Add to Favorites</option>
          <option value="playlist">Playlist Action</option>
        </select>
      </div>

      <div class="filter-group">
        <label for="dateRange">Date Range</label>
        <select id="dateRange" v-model="filters.dateRange" class="form-control">
          <option value="today">Today</option>
          <option value="week">Last 7 Days</option>
          <option value="month">Last 30 Days</option>
          <option value="all">All Time</option>
        </select>
      </div>

      <button @click="applyFilters" class="btn-primary">Apply Filters</button>
    </div>

    <!-- Activity Summary -->
    <div class="summary-cards">
      <div class="summary-card">
        <i class="fas fa-users"></i>
        <div class="card-content">
          <span class="card-value">{{ summary.activeUsers }}</span>
          <span class="card-label">Active Users</span>
        </div>
      </div>
      <div class="summary-card">
        <i class="fas fa-play-circle"></i>
        <div class="card-content">
          <span class="card-value">{{ summary.totalPlays }}</span>
          <span class="card-label">Total Plays</span>
        </div>
      </div>
      <div class="summary-card">
        <i class="fas fa-heart"></i>
        <div class="card-content">
          <span class="card-value">{{ summary.newFavorites }}</span>
          <span class="card-label">New Favorites</span>
        </div>
      </div>
      <div class="summary-card">
        <i class="fas fa-list"></i>
        <div class="card-content">
          <span class="card-value">{{ summary.playlistActions }}</span>
          <span class="card-label">Playlist Actions</span>
        </div>
      </div>
    </div>

    <!-- Logs Table -->
    <div class="logs-container">
      <h2>Activity Log</h2>
      <table class="logs-table">
        <thead>
          <tr>
            <th>User</th>
            <th>Action</th>
            <th>Details</th>
            <th>IP Address</th>
            <th>Timestamp</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="log in logs" :key="log.id">
            <td>{{ log.user.name }}</td>
            <td>
              <span :class="['action-badge', log.action.toLowerCase()]">
                {{ log.action }}
              </span>
            </td>
            <td>{{ log.details }}</td>
            <td>{{ log.ip_address }}</td>
            <td>{{ formatDate(log.created_at) }}</td>
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
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'UserLogs',
  data() {
    return {
      logs: [],
      currentPage: 1,
      totalPages: 1,
      filters: {
        actionType: '',
        dateRange: 'week'
      },
      summary: {
        activeUsers: 0,
        totalPlays: 0,
        newFavorites: 0,
        playlistActions: 0
      }
    };
  },
  methods: {
    async fetchLogs() {
      try {
        const response = await axios.get('/api/admin/logs', {
          params: {
            page: this.currentPage,
            action_type: this.filters.actionType,
            date_range: this.filters.dateRange
          }
        });
        this.logs = response.data.data;
        this.totalPages = response.data.last_page;
      } catch (error) {
        console.error('Error fetching logs:', error);
      }
    },
    async fetchSummary() {
      try {
        const response = await axios.get('/api/admin/logs/summary', {
          params: {
            date_range: this.filters.dateRange
          }
        });
        this.summary = response.data;
      } catch (error) {
        console.error('Error fetching summary:', error);
      }
    },
    formatDate(date) {
      return new Date(date).toLocaleString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
      });
    },
    applyFilters() {
      this.currentPage = 1;
      this.fetchLogs();
      this.fetchSummary();
    },
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
        this.fetchLogs();
      }
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
        this.fetchLogs();
      }
    }
  },
  mounted() {
    this.fetchLogs();
    this.fetchSummary();
  }
};
</script>

<style scoped>
.user-logs {
  padding: 20px;
}

h2 {
  color: #444;
  margin-bottom: 20px;
}

.filters {
  background: white;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  margin-bottom: 30px;
  display: flex;
  gap: 20px;
  align-items: flex-end;
}

.filter-group {
  flex: 1;
}

.filter-group label {
  display: block;
  margin-bottom: 8px;
  color: #555;
}

.form-control {
  width: 100%;
  padding: 8px 12px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 14px;
}

.btn-primary {
  background-color: #4CAF50;
  color: white;
  padding: 8px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
  transition: background-color 0.3s;
  height: 35px;
}

.btn-primary:hover {
  background-color: #45a049;
}

.summary-cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  margin-bottom: 30px;
}

.summary-card {
  background: white;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  display: flex;
  align-items: center;
  gap: 15px;
}

.summary-card i {
  font-size: 24px;
  color: #2196F3;
}

.card-content {
  display: flex;
  flex-direction: column;
}

.card-value {
  font-size: 24px;
  font-weight: bold;
  color: #333;
}

.card-label {
  color: #666;
  font-size: 14px;
}

.logs-container {
  background: white;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.logs-table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 20px;
}

.logs-table th,
.logs-table td {
  padding: 12px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

.logs-table th {
  background-color: #f5f5f5;
  font-weight: bold;
}

.action-badge {
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 12px;
  font-weight: bold;
  color: white;
}

.action-badge.login {
  background-color: #4CAF50;
}

.action-badge.logout {
  background-color: #f44336;
}

.action-badge.play {
  background-color: #2196F3;
}

.action-badge.favorite {
  background-color: #E91E63;
}

.action-badge.playlist {
  background-color: #9C27B0;
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
</style>
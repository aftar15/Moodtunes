<template>
  <div class="dashboard">
    <!-- Stats Cards -->
    <div class="stats">
      <div class="card" v-for="(value, key) in stats" :key="key"
           :style="getCardStyle(key)">
        <span class="value">{{ value }}</span>
        <span class="label">
          <i :class="getIconClass(key)"></i>
          {{ formatStatName(key) }}
        </span>
      </div>
    </div>

    <!-- Recent Activities -->
    <h2>Recent Activities</h2>
    <table class="activity-table">
      <thead>
        <tr>
          <th>Activity Description</th>
          <th>Date/Time</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="activity in activities" :key="activity.description">
          <td>{{ activity.description }}</td>
          <td>{{ activity.datetime }}</td>
          <td>
            <button class="btn-3d">{{ activity.action }}</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import axios from 'axios';

const API_URL = 'http://localhost:8000/api';

// Create axios instance with default config
const apiClient = axios.create({
  baseURL: API_URL,
  withCredentials: true,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'X-Requested-With': 'XMLHttpRequest'
  }
});

// Add token to requests
apiClient.interceptors.request.use(config => {
  const token = localStorage.getItem('token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

export default {
  name: 'AdminDashboard',
  data() {
    return {
      stats: {
        totalUsers: 0,
        totalSongs: 0,
        totalPlaylists: 0,
        totalFavorites: 0
      },
      activities: []
    };
  },
  methods: {
    getIconClass(key) {
      const icons = {
        totalUsers: 'fas fa-users',
        totalSongs: 'fas fa-music',
        totalPlaylists: 'fas fa-list',
        totalFavorites: 'fas fa-heart'
      };
      return icons[key] || 'fas fa-chart-bar';
    },
    formatStatName(key) {
      return key.replace(/([A-Z])/g, ' $1')
        .replace(/^./, str => str.toUpperCase());
    },
    getCardStyle(key) {
      const colors = {
        totalUsers: '#4CAF50',
        totalSongs: '#2196F3',
        totalPlaylists: '#9C27B0',
        totalFavorites: '#F44336'
      };
      return {
        backgroundColor: colors[key] || '#607D8B'
      };
    },
    async fetchStats() {
      try {
        const response = await apiClient.get('/admin/dashboard/stats');
        this.stats = response.data;
      } catch (error) {
        console.error('Error fetching stats:', error);
      }
    },
    async fetchActivities() {
      try {
        const response = await apiClient.get('/admin/dashboard/activities');
        this.activities = response.data;
      } catch (error) {
        console.error('Error fetching activities:', error);
      }
    }
  },
  created() {
    this.fetchStats();
    this.fetchActivities();
  }
};
</script>

<style scoped>
.dashboard {
  padding: 20px;
}

.stats {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  margin-bottom: 30px;
}

.card {
  padding: 20px;
  border-radius: 10px;
  color: white;
  text-align: center;
  transition: transform 0.3s;
}

.card:hover {
  transform: translateY(-5px);
}

.card .value {
  display: block;
  font-size: 2em;
  font-weight: bold;
  margin-bottom: 10px;
}

.card .label {
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.1em;
}

.card .label i {
  margin-right: 8px;
}

.activity-table {
  width: 100%;
  border-collapse: collapse;
  background: white;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.activity-table th,
.activity-table td {
  padding: 12px 15px;
  text-align: left;
}

.activity-table thead {
  background-color: #f8f9fa;
}

.activity-table tbody tr:nth-child(even) {
  background-color: #f8f9fa;
}

.activity-table tbody tr:hover {
  background-color: #f1f3f5;
}

.btn-3d {
  background-color: #4CAF50;
  color: white;
  padding: 8px 15px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: all 0.3s;
}

.btn-3d:hover {
  transform: translateY(-2px);
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}
</style>
<template>
  <div>
    <div class="dashboard-stats">
      <div class="stat-card">
        <h3>My Playlists</h3>
        <p>{{ playlistCount }}</p>
      </div>
      <div class="stat-card">
        <h3>Favorite Songs</h3>
        <p>{{ favoritesCount }}</p>
      </div>
      <div class="stat-card">
        <h3>Recently Played</h3>
        <p>{{ recentlyPlayedCount }}</p>
      </div>
    </div>

    <div class="recent-activity">
      <h2>Recent Activity</h2>
      <div class="activity-list">
        <div v-for="activity in recentActivities" :key="activity.id" class="activity-item">
          <i :class="activity.icon"></i>
          <div class="activity-details">
            <p>{{ activity.description }}</p>
            <small>{{ activity.timestamp }}</small>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'DashboardContent',
  data() {
    return {
      playlistCount: 0,
      favoritesCount: 0,
      recentlyPlayedCount: 0,
      recentActivities: []
    }
  },
  methods: {
    async fetchDashboardData() {
      try {
        // Implement API calls to fetch dashboard data
      } catch (error) {
        console.error('Error fetching dashboard data:', error);
      }
    }
  },
  mounted() {
    this.fetchDashboardData();
  }
}
</script>

<style scoped>
.dashboard-stats {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  margin-bottom: 30px;
}

.stat-card {
  background: white;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.stat-card h3 {
  margin: 0;
  color: #666;
}

.stat-card p {
  margin: 10px 0 0;
  font-size: 24px;
  font-weight: bold;
}

.recent-activity {
  background: white;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.activity-list {
  margin-top: 15px;
}

.activity-item {
  display: flex;
  align-items: center;
  padding: 10px 0;
  border-bottom: 1px solid #eee;
}

.activity-item:last-child {
  border-bottom: none;
}

.activity-item i {
  margin-right: 15px;
  font-size: 20px;
  color: #2196f3;
}

.activity-details p {
  margin: 0;
}

.activity-details small {
  color: #666;
}
</style>

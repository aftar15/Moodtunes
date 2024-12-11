<template>
  <div class="dashboard-layout">
    <!-- Sidebar -->
    <SidebarAdmin />

    <!-- Main Content -->
    <div class="dashboard-content">
      <HeaderAdmin title="Dashboard" />
      <div class="stats">
        <Card value="20" label="Total Songs" color="#f28b82" />
        <Card value="5" label="Moods" color="#aecbfa" />
        <Card value="4" label="Playlist" color="#ccff90" />
        <Card value="15" label="Listeners" color="#e8eaed" />
        <Card value="10" label="Favorites" color="yellow" />
      </div>
      <h2>Recent Activities</h2>
      <Table
        :columns="['Activity Description', 'Date/Time', 'Action']"
        :rows="recentActivities"
      />
    </div>
  </div>
</template>

<script>
import Card from "@/components/Card.vue";
import Table from "@/components/Table.vue";
import HeaderAdmin from "@/components/HeaderAdmin.vue";
import SidebarAdmin from "@/components/SidebarAdmin.vue";

export default {
  components: { HeaderAdmin, Card, Table, SidebarAdmin },
  data() {
    return {
      recentActivities: [
        { description: "Sarah logged in", datetime: "Oct 9, 2024 2:30 PM", action: "View" },
        { description: 'John added "Lo-Fi Beats"', datetime: "Oct 9, 2024 3:15 PM", action: "View" },
      ],
    };
  },
};
</script>

<style scoped>
/* Parent container for layout */
.dashboard-layout {
  display: flex;
  min-height: 100vh; /* Ensures the layout takes the full height of the screen */
}

/* Sidebar styles */
.dashboard-layout .SidebarAdmin {
  flex: 0 0 250px; /* Fixed width for the sidebar */
  background-color: #f4f4f4;
  padding: 20px;
  box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
  height: 100vh; /* Sidebar spans full height */
}

/* Main content styles */
.dashboard-content {
  flex: 1; /* Content takes the remaining space */
  padding: 20px;
  background-color: #f8f9fa;
  overflow-y: auto; /* Adds scroll if content exceeds the viewport */
}

.stats {
  justify-content: center;
  display: flex;
  gap: 15px;
  margin-bottom: 20px;
}
</style>
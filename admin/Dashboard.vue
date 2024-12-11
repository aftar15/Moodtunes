<template>
  <link href="https://unpkg.com/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

  <div class="dashboard-layout">
    <!-- Sidebar -->
    <SidebarAdmin />

    <!-- Main Content -->
    <div class="dashboard-content">
      <HeaderAdmin title="Dashboard" />
      <div class="stats">

        <!-- Total Songs Card with Music Note Icon -->
<!-- Total Songs Card with Music Note Icon -->
<div class="card" 
     style="--card-color: #ffccbc; --light-color: #ffab91; --dark-color: #e57373; --edge-color: #ef5350;">
    <span class="value">20</span>
    <span class="label"><i class="fas fa-music"></i> Total Songs</span> <!-- Font Awesome Music Note Icon -->
</div>

<!-- Moods Card with Happy Icon -->
<div class="card" 
     style="--card-color: #aecbfa; --light-color: #c3e0ff; --dark-color: #82a6d9; --edge-color: #82a6d9;">
    <span class="value">5</span>
    <span class="label"><i class="fas fa-smile"></i> Moods</span> <!-- Font Awesome Happy Icon -->
</div>

<!-- Playlist Card with Playlist Icon -->
<div class="card" 
     style="--card-color: #ccff90; --light-color: #e5ffa8; --dark-color: #9bcc6d; --edge-color: #8ebc68;">
    <span class="value">4</span>
    <span class="label"><i class="fas fa-list"></i> Playlist</span> <!-- Font Awesome Playlist Icon -->
</div>

<!-- Listeners Card with Headphones Icon -->
<div class="card" 
     style="--card-color: #e8eaed; --light-color: #ffffff; --dark-color: #c3c5c8; --edge-color: #b0b2b5;">
    <span class="value">15</span>
    <span class="label"><i class="fas fa-headphones-alt"></i> Listeners</span> <!-- Font Awesome Headphones Icon -->
</div>

<!-- Favorites Card with Heart Icon -->
<div class="card" 
     style="--card-color: #ffff88; --light-color: #ffffa8; --dark-color: #d4d455; --edge-color: #bdbd40;">
    <span class="value">10</span>
    <span class="label"><i class="fas fa-heart"></i> Favorites</span> <!-- Font Awesome Heart Icon -->
</div>
</div>


<h2>Recent Activities</h2>
      <!-- Table -->
      <table class="activity-table">
        <thead>
          <tr>
            <th>Activity Description</th>
            <th>Date/Time</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="activity in recentActivities" :key="activity.description">
            <td>{{ activity.description }}</td>
            <td>{{ activity.datetime }}</td>
            <td>
              <button class="btn-3d" @click="openReceipt(activity)">View</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modal to show Receipt -->
  <div v-if="showReceiptModal" class="modal">
    <div class="modal-content">
      <h3>Activity Receipt</h3>
      <p><strong>Description:</strong> {{ selectedActivity.description }}</p>
      <p><strong>Date/Time:</strong> {{ selectedActivity.datetime }}</p>
      <div class="button-container">
      <button class="close" @click="closeReceiptModal">Close</button>
    </div>
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
      showReceiptModal: false,
      selectedActivity: null,
    };
  },
  methods: {
    // Open the modal to show the selected activity's receipt
    openReceipt(activity) {
      this.selectedActivity = activity;  // Bind selected activity to the modal
      this.showReceiptModal = true;  // Show the modal
    },

    // Close the modal
    closeReceiptModal() {
      this.showReceiptModal = false;  // Hide the modal
      this.selectedActivity = null;  // Reset selected activity
    },

    // Log activity (simulated for this demo)
    logActivity(description, action) {
      const newActivity = {
        description,
        datetime: new Date().toLocaleString(),
        action,
      };
      this.recentActivities.push(newActivity);  // Add activity to the list
    },
  },
  mounted() {
    // Simulate logging an activity (you can trigger this based on actual actions in your app)
    this.logActivity("User logged in to MoodTunes", "View");
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
  gap: 20px;
  margin-bottom: 20px;
}

/* Card styles */
.card {
  margin-top: 20px;
  width: 150px;
  height: 100px;
  background: var(--card-color);
  border-radius: 10px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  font-weight: bold;
  font-size: 1rem;
  color: rgb(0, 0, 0);
  position: relative;
  box-shadow: 
    0 4px 6px rgba(0, 0, 0, 0.2), /* Soft shadow for depth */
    inset 0 2px 4px rgba(255, 255, 255, 0.1); /* Inner shadow for dimension */
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  background: linear-gradient(145deg, var(--light-color), var(--dark-color));
}

/* Left colored edge for added depth */
.card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  height: 100%;
  width: 6px;
  background: var(--edge-color);
  border-radius: 10px 0 0 10px;
}

/* Hover effect */
.card:hover {
  transform: translateY(-4px); /* Slight lift */
  box-shadow: 
    0 6px 8px rgba(0, 0, 0, 0.25), 
    inset 0 3px 6px rgba(255, 255, 255, 0.15); /* Stronger shadow */
}



/* Table Styling */
.activity-table {
  /*width: 100%;
  border-collapse: collapse;
  margin: 20px 0;
  font-size: 0.9rem;
  background-color:white;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);*/
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
  text-align: center;
  border: 1px solid #ddd;
}

.activity-table th, .activity-table td {
 /* border: 1px solid #ddd;
  padding: 12px;
  text-align: center;*/
  padding: 12px;
  border: 1px solid #ddd;
}

/*.activity-table th {
  background-color: #f4f4f4;
  font-weight: bold;
  text-transform: uppercase;
}*/



/* Modal Styling */
.modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  background: #ffffff;
  width: 400px;
  border-radius: 10px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
  padding: 20px;
  position: relative;
}

.modal-content h3 {
  margin-bottom: 20px;
  font-size: 1.25rem;
  text-align: center;
}

.modal-content p {
  margin: 10px 0;
  font-size: 0.95rem;
  line-height: 1.4;
}

/* Close Button 
.modal-content .close {
  position: absolute;
  top: 10px;
  right: 10px;
  background: #ff5252;
  color: white;
  border: none;
  border-radius: 50%;
  width: 30px;
  height: 30px;
  font-size: 1rem;
  cursor: pointer;
  display: flex;
  justify-content: center;
  align-items: center;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.modal-content .close:hover {
  background: #e53935;
}*/

.modal-content button {
  margin-top: 15px;
  padding: 10px 20px;
  background-color: #4285f4;
  color: white;
  border: none;
  border-radius: 5px;
  font-size: 0.9rem;
  cursor: pointer;
}

.modal-content button:hover {
  background-color: #3367d6;
}

.btn-3d {
  padding: 8px 16px;
  background: linear-gradient(145deg, #3b82f6, #2563eb); /* Hint of blue */
  border: none;
  border-radius: 8px;
 /* box-shadow: 
    3px 3px 5px rgba(0, 0, 0, 0.2), 
    -3px -3px 5px rgba(255, 255, 255, 0.7);*/
  color: white;
  font-weight: bold;
  cursor: pointer;
  /*transition: all 0.2s ease;*/
  box-shadow: 4px 4px 8px rgba(0, 0, 0, 0.2), 
              -4px -4px 8px rgba(255, 255, 255, 0.8);
  cursor: pointer;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.btn-3d:hover {
  background: linear-gradient(145deg, #2563eb, #3b82f6);
  box-shadow: 
    2px 2px 4px rgba(0, 0, 0, 0.25), 
    -2px -2px 4px rgba(255, 255, 255, 0.9);
    transform: translateY(-2px);
}

.btn-3d:active {
  box-shadow: inset 2px 2px 4px rgba(0, 0, 0, 0.25), 
              inset -2px -2px 4px rgba(255, 255, 255, 0.9);
  background: linear-gradient(145deg, #1e40af, #2563eb);
}

.close {
  display: inline-block;
  padding: 10px 20px;
  background: linear-gradient(145deg, #f04c4c, #d84343);
  border: none;
  border-radius: 10px;
  box-shadow: 
    4px 4px 6px rgba(0, 0, 0, 0.2), /* Outer shadow for 3D effect */
    -4px -4px 6px rgba(255, 255, 255, 0.7); /* Highlight */
  color: #333;
  font-weight: bold;
  cursor: pointer;
  transition: all 0.2s ease;
  margin-left: 150px;
}

.close:hover {
  background: linear-gradient(145deg, #d84343, #f04c4c);
  box-shadow: 
    2px 2px 4px rgba(0, 0, 0, 0.25), 
    -2px -2px 4px rgba(255, 255, 255, 0.9);
}

.close:active {
  box-shadow: inset 2px 2px 4px rgba(0, 0, 0, 0.25), 
              inset -2px -2px 4px rgba(255, 150, 150, 0.9);
  background: linear-gradient(145deg, #c93636, #e03b3b);
}


</style>


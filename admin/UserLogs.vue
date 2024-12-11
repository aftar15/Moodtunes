<template>
  <div class="admin-layout">
    <!-- Sidebar -->
    <SidebarAdmin />
    <div class="main-content">
      <!-- Header -->
      <HeaderAdmin title="User Management" />

      <!-- Search Bar -->
      <SearchbarAdmin @search="filterUsers" />

      <!-- User Table -->
      <div class="table-container">
        <table class="user-table">
          <thead>
            <tr>
              <th>User ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Password</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in userLogs" :key="user.id">
              <td>{{ user.id }}</td>
              <td>{{ user.name }}</td>
              <td>{{ user.email }}</td>
              <td>{{ user.password }}</td>
              <td>
                <button @click="deleteUser(user.id)" class="delete-button">
                  Delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import HeaderAdmin from "@/components/HeaderAdmin.vue";
import SearchbarAdmin from "@/components/SearchbarAdmin.vue";
import Table from "@/components/Table.vue";
import SidebarAdmin from "@/components/SidebarAdmin.vue";
import axios from "axios";

export default {
  components: { HeaderAdmin, Table, SearchbarAdmin, SidebarAdmin },
  data() {
    return {
      columns: ["User ID", "Name", "Email", "Password", "Action"], // Define table columns
      userLogs: [
        // Default user for fallback
        {
          id: 1,
          name: "Default User",
          email: "default.user@moodtunes.com",
          password: "********",
        },
      ],
    };
  },
  methods: {
    // Fetch user data from backend
    async fetchUserLogs() {
      try {
        const response = await axios.get("https://your-backend-url/api/users");
        this.userLogs = response.data.map(user => ({
          id: user.id,
          name: user.name,
          email: user.email,
          password: "********",
        }));
      } catch (error) {
        console.error("Error fetching user data:", error);
      }
    },
    // Delete a user by ID
    async deleteUser(id) {
      try {
        await axios.delete(`https://your-backend-url/api/users/${id}`);
        this.userLogs = this.userLogs.filter(user => user.id !== id);
      } catch (error) {
        console.error("Error deleting user:", error);
      }
    },
    // Filter functionality for the search bar
    filterUsers(searchTerm) {
      this.userLogs = this.userLogs.filter(user =>
        user.name.toLowerCase().includes(searchTerm.toLowerCase())
      );
    },
  },
  mounted() {
    this.fetchUserLogs(); // Fetch user data on component mount
  },
};
</script>




<style scoped>
.admin-layout {
  display: flex;
  height: 100vh;
  overflow: hidden;
}
.main-content {
  flex: 1; /* Allow the main content to take up the remaining space */
  padding: 20px;
  background-color: #f8f9fa; /* Optional background for contrast */
  overflow-y: auto;
}

.user-table {
  
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

.user-table th,
.user-table td {
  
  border: 1px solid #ddd;
  padding: 8px;
  text-align: center;
}



.delete-button {
  border: none;
  padding: 6px 12px;
  border-radius: 5px;
  cursor: pointer;
  transition: transform 0.2s, box-shadow 0.3s ease;
  box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2); 
  background: linear-gradient(135deg, #f44336, #f6685e); 
  color: white;
  font-size: 14px;
  
}


.delete-button {
  background: linear-gradient(135deg, #f44336, #f6685e);
  color: white;
  border: none;
  padding: 6px 12px;
  border-radius: 5px;
  cursor: pointer;
  transition: transform 0.2s, box-shadow 0.3s ease;
  box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
}


.delete-button:hover {
  transform: scale(1.1);
}

</style>
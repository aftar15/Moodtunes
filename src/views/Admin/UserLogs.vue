<template>
  <div class="admin-layout">
    <SidebarAdmin />
    <div class="main-content">
      <HeaderAdmin title="User Management" />
      <SearchbarAdmin @search="filterSongs" />
      <Table
        :columns="['User ID', 'Name', 'Email', 'Password', 'Action']"
        :rows="userLogs"
      />
    </div>
  </div>
</template>

<script>
import HeaderAdmin from "@/components/HeaderAdmin.vue";
import SearchbarAdmin from "@/components/SearchbarAdmin.vue";
import Table from "@/components/Table.vue";
import SidebarAdmin from "@/components/SidebarAdmin.vue";

export default {
  components: { HeaderAdmin, Table, SearchbarAdmin, SidebarAdmin },
  data() {
    return {
      userLogs: [
        { id: 1, name: "Alice Johnson", email: "alice.johnson@gmail.com", password: "********", action: "Delete" },
        { id: 2, name: "Bob Smith", email: "bob.smith@gmail.com", password: "********", action: "Delete" },
        // Add more users here
      ],
    };
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

.table {
  margin-top: 20px;
}
</style>
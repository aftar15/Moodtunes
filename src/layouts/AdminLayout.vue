<template>
  <div class="admin-layout">
    <SidebarAdmin />
    <div class="main-content">
      <HeaderAdmin :title="currentRouteName" />
      <router-view></router-view>
    </div>
  </div>
</template>

<script>
import HeaderAdmin from "@/components/HeaderAdmin.vue";
import SidebarAdmin from "@/components/SidebarAdmin.vue";

export default {
  name: 'AdminLayout',
  components: {
    HeaderAdmin,
    SidebarAdmin
  },
  computed: {
    currentRouteName() {
      return this.$route.name?.replace('Admin', '').replace(/([A-Z])/g, ' $1').trim() || 'Dashboard';
    }
  }
};
</script>

<style scoped>
.admin-layout {
  display: flex;
  min-height: 100vh;
}

.main-content {
  flex: 1;
  padding: 20px;
  background-color: #f5f6fa;
  overflow-y: auto;
}
</style>

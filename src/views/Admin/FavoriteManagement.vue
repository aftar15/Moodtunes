<template>
  <div class="admin-layout">
    <SidebarAdmin />
    <div class="main-content">
      <HeaderAdmin title="Favorite Management" />
      <SearchbarAdmin @search="filterFavorites" />
      <Table
        :columns="columns"
        :rows="filteredFavorites"
      />
    </div>
  </div>
</template>

<script>
import HeaderAdmin from "@/components/HeaderAdmin.vue";
import SearchbarAdmin from "@/components/SearchbarAdmin.vue";
import Table from "@/components/Table.vue";
import SidebarAdmin from "../../components/SidebarAdmin.vue";

export default {
  components: { HeaderAdmin, SearchbarAdmin, Table, SidebarAdmin },
  data() {
    return {
      columns: ["Song Title", "Artist", "Mood", "Emoji", "Favorites Count (Listeners)", "Action"],
      favorites: [
        { title: "Happy", artist: "Pharrell Williams", mood: "Happy", emoji: "😊", count: 7, action: "View" },
        { title: "Lose Yourself", artist: "Eminem", mood: "Motivated", emoji: "💪", count: 5, action: "View" },
        // Add more favorites here
      ],
      filteredFavorites: [],
    };
  },
  methods: {
    filterFavorites(keyword) {
      this.filteredFavorites = this.favorites.filter((favorite) =>
        Object.values(favorite).some((value) =>
          value.toString().toLowerCase().includes(keyword.toLowerCase())
        )
      );
    },
  },
  mounted() {
    this.filteredFavorites = this.favorites;
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
  flex: 1; /* Remaining space for content */
  padding: 20px;
  background-color: #f8f9fa; /* Light background for contrast */
  overflow-y: auto;
}

.table {
  margin-top: 20px;
}
</style>

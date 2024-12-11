<template>
  <div class="admin-layout">
    <SidebarAdmin />
    <div class="main-content">
      <HeaderAdmin title="Music Management" />
      <SearchbarAdmin @search="filterSongs" />
      <button class="add-button" @click="addSong">Add</button>
      <Table
        :columns="columns"
        :rows="filteredSongs"
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
  components: { HeaderAdmin, SearchbarAdmin, Table, SidebarAdmin },
  data() {
    return {
      columns: ["Song Title", "Artist", "Mood", "Emoji", "Action"],
      songs: [
        { title: "Happy", artist: "Pharrell Williams", mood: "Happy", emoji: "😊", action: "Delete" },
        { title: "Lose Yourself", artist: "Eminem", mood: "Motivated", emoji: "💪", action: "Delete" },
        // Add more songs here
      ],
      filteredSongs: [],
    };
  },
  methods: {
    filterSongs(keyword) {
      this.filteredSongs = this.songs.filter((song) =>
        Object.values(song).some((value) =>
          value.toString().toLowerCase().includes(keyword.toLowerCase())
        )
      );
    },
    addSong() {
      alert("Add song functionality");
    },
  },
  mounted() {
    this.filteredSongs = this.songs;
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

.add-button {
  margin: 10px 0;
  padding: 10px 20px;
  background: #5a83db;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}
.add-button:hover {
  background: #4a6fcf;
}
</style>

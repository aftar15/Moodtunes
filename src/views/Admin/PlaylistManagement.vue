<template>
    <div class="admin-layout">
      <SidebarAdmin />
      <div class="main-content">
        <HeaderAdmin title="Playlist Management" />
        <SearchbarAdmin @search="filterPlaylists" />
        <Table
          :columns="columns"
          :rows="filteredPlaylists"
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
        columns: ["Song Title", "Artist", "Mood", "Emoji", "Playlist Count", "Actions"],
        playlists: [
          {
            title: "Blinding Lights",
            artist: "The Weeknd",
            mood: "Energetic",
            emoji: "⚡",
            count: 15, // Number of users who added the song to their playlist
            action: "Edit/Delete",
          },
          {
            title: "Perfect",
            artist: "Ed Sheeran",
            mood: "Romantic",
            emoji: "❤️",
            count: 20,
            action: "Edit/Delete",
          },
          {
            title: "Uptown Funk",
            artist: "Bruno Mars",
            mood: "Fun",
            emoji: "🎉",
            count: 10,
            action: "Edit/Delete",
          },
          // Add more playlist items here
        ],
        filteredPlaylists: [],
      };
    },
    methods: {
      filterPlaylists(keyword) {
        this.filteredPlaylists = this.playlists.filter((playlist) =>
          Object.values(playlist).some((value) =>
            value.toString().toLowerCase().includes(keyword.toLowerCase())
          )
        );
      },
    },
    mounted() {
      this.filteredPlaylists = this.playlists; // Initialize with all playlist items
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
    flex: 1; /* Take remaining space for content */
    padding: 20px;
    background-color: #f8f9fa; /* Light background for contrast */
    overflow-y: auto;
  }
  
  .table {
    margin-top: 20px;
  }
  </style>
  
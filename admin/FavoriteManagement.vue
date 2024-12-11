<template>
  <div class="admin-layout">
    <SidebarAdmin />
    <div class="main-content">
      <HeaderAdmin title="Favorite Management" />
      <SearchbarAdmin @search="filterFavorites" />
      <table class="table">
        <thead>
          <tr>
            <th v-for="column in columns" :key="column">{{ column }}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="favorite in filteredFavorites" :key="favorite.title">
            <td>{{ favorite.title }}</td>
            <td>{{ favorite.artist }}</td>
            <td>{{ favorite.mood }}</td>
            <td>{{ favorite.emoji }}</td>
            <td>{{ favorite.count }}</td>
            <td>
              <button class="view-btn" @click="showReceiptModal(favorite)">
                View
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Modal to show receipt -->
      <div v-if="showModal" class="modal">
        <div class="modal-content">
          <h3>Favorite Details</h3><br>
          <p><strong>Song Title:</strong> {{ selectedFavorite.title }}</p>
          <p><strong>Artist:</strong> {{ selectedFavorite.artist }}</p>
          <p><strong>Mood:</strong> {{ selectedFavorite.mood }}</p>
          <p><strong>Emoji:</strong> {{ selectedFavorite.emoji }}</p>
          <p><strong>Favorites Count:</strong> {{ selectedFavorite.count }}</p>
          <button class="close" @click="closeModal">Close</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import HeaderAdmin from "@/components/HeaderAdmin.vue";
import SearchbarAdmin from "@/components/SearchbarAdmin.vue";
import SidebarAdmin from "../../components/SidebarAdmin.vue";

export default {
  components: { HeaderAdmin, SearchbarAdmin, SidebarAdmin },
  data() {
    return {
      columns: ["Song Title", "Artist", "Mood", "Emoji", "Favorites Count (Listeners)", "Action"],
      favorites: [
        { title: "Happy", artist: "Pharrell Williams", mood: "Happy", emoji: "😊", count: 7 },
        { title: "Lose Yourself", artist: "Eminem", mood: "Motivated", emoji: "💪", count: 5 },
      ],
      filteredFavorites: [],
      showModal: false,
      selectedFavorite: {},
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
    showReceiptModal(favorite) {
      this.selectedFavorite = favorite;
      this.showModal = true;
    },
    closeModal() {
      this.showModal = false;
    },
  },
  mounted() {
    this.filteredFavorites = this.favorites;
  },
};
</script>

<style scoped>
/* Layout styles */
.admin-layout {
  display: flex;
  height: 100vh;
  overflow: hidden;
}

.main-content {
  flex: 1;
  padding: 20px;
  background-color: #f8f9fa;
  overflow-y: auto;
}

/* Table styles */
.table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
  text-align: center;
  border: 1px solid #ddd;
}

.table th,
.table td {
  padding: 12px;
  border: 1px solid #ddd;
}

/* 3D View Button */
/* 3D View Button */
.view-btn {
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

.view-btn:hover {
  background: linear-gradient(145deg, #2563eb, #3b82f6);
  box-shadow: 
    2px 2px 4px rgba(0, 0, 0, 0.25), 
    -2px -2px 4px rgba(255, 255, 255, 0.9);
    transform: translateY(-2px);
  
}

.view-btn:active {
  box-shadow: inset 2px 2px 4px rgba(0, 0, 0, 0.25), 
              inset -2px -2px 4px rgba(255, 255, 255, 0.9);
  background: linear-gradient(145deg, #1e40af, #2563eb);
}

/*Modal styles */
.modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  background: #fff;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
  width: 400px;
  text-align: center;
}



/* Close button styles */
.close {
  display: inline-block;
  padding: 10px 20px;
  background: linear-gradient(145deg, #f04c4c, #d84343);
  border: none;
  border-radius: 10px;
  box-shadow: 
    4px 4px 6px rgba(0, 0, 0, 0.2), /* Outer shadow for 3D effect */
    -4px -4px 6px rgba(255, 255, 255, 0.7); /* Highlight */
  color: #ffffff;
  font-weight: bold;
  cursor: pointer;
  transition: all 0.2s ease;
  margin-top: 20px;
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


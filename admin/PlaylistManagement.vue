<template>
  <div class="admin-layout">
    <SidebarAdmin />
    <div class="main-content">
      <HeaderAdmin title="Playlist Management" />
      <SearchbarAdmin @search="filterPlaylists" />

      <table class="table">
        <thead>
          <tr>
            <th v-for="column in columns" :key="column">{{ column }}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(playlist, index) in filteredPlaylists" :key="index">
            <td>{{ playlist.title }}</td>
            <td>{{ playlist.artist }}</td>
            <td>{{ playlist.mood }}</td>
            <td>{{ playlist.emoji }}</td>
            <td>{{ playlist.count }}</td>
            <td>
              <!-- Edit Button -->
  <button class="btn-3d btn-edit" @click="openEditModal(playlist, index)">Edit</button>
  <!-- Delete Button -->
  <button class="btn-3d btn-delete" @click="openDeleteModal(playlist, index)">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Edit Modal -->
      <div v-if="isEditModalOpen" class="modal">
        <div class="modal-content">
          <h2>Edit Playlist</h2>
          <form @submit.prevent="saveChanges" class="edit-form"><br>
            <label>
              Song Title:
              <input v-model="selectedPlaylist.title" type="text" placeholder="Enter Song Title" />
            </label>
            <label>
              Artist:
              <input v-model="selectedPlaylist.artist" type="text" placeholder="Enter Artist Name" />
            </label>
            <label>
              Mood:
              <input v-model="selectedPlaylist.mood" type="text" placeholder="Enter Mood" />
            </label>
            <label>
              Emoji:
              <input v-model="selectedPlaylist.emoji" type="text" placeholder="Enter Emoji" />
            </label>
            <label>
              Playlist Count:
              <input v-model="selectedPlaylist.count" type="number" placeholder="Enter Count" />
            </label>
            <div class="modal-actions">
              <button class="btn-3d btn-save" type="submit">Save</button>
              <button class="btn-3d btn-cancel" @click="closeEditModal">Cancel</button>
            </div>
          </form>
        </div>
      </div>

      <!-- Delete Modal -->
      <div v-if="isDeleteModalOpen" class="modal">
        <div class="modal-content">
          <h2>Confirm Deletion</h2> <br>
          <p>Are you sure you want to delete the playlist "{{ selectedPlaylist.title }}"?</p>
          <div class="modal-actions">
            <button class="btn-3d btn-delete" @click="confirmDelete">Delete</button>
            <button class="btn-3d btn-cancel" @click="closeDeleteModal">Cancel</button>
          </div>
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
      columns: ["Song Title", "Artist", "Mood", "Emoji", "Playlist Count", "Actions"],
      playlists: [
        {
          title: "Blinding Lights",
          artist: "The Weeknd",
          mood: "Energetic",
          emoji: "⚡",
          count: 15,
        },
        {
          title: "Perfect",
          artist: "Ed Sheeran",
          mood: "Romantic",
          emoji: "❤️",
          count: 20,
        },
        {
          title: "Uptown Funk",
          artist: "Bruno Mars",
          mood: "Fun",
          emoji: "🎉",
          count: 10,
        },
      ],
      filteredPlaylists: [],
      isEditModalOpen: false,
      isDeleteModalOpen: false,
      selectedPlaylist: null,
      selectedPlaylistIndex: null,
      errorMessage: null, // For validation errors
    };
  },
  methods: {
    // Filter playlists based on search keyword
    filterPlaylists(keyword) {
      this.filteredPlaylists = this.playlists.filter((playlist) =>
        Object.values(playlist).some((value) =>
          value.toString().toLowerCase().includes(keyword.toLowerCase())
        )
      );
    },

    // Open the edit modal with the selected playlist
    openEditModal(playlist, index) {
      this.selectedPlaylist = { ...playlist }; // Create a copy to avoid direct mutation
      this.selectedPlaylistIndex = index;
      this.isEditModalOpen = true;
    },

    // Close the edit modal and reset state
    closeEditModal() {
      this.isEditModalOpen = false;
      this.selectedPlaylist = null;
      this.selectedPlaylistIndex = null;
      this.errorMessage = null;
    },

    // Save changes and update the table
    saveChanges() {
      // Validation: Ensure no empty fields
      if (
        !this.selectedPlaylist.title ||
        !this.selectedPlaylist.artist ||
        !this.selectedPlaylist.mood ||
        !this.selectedPlaylist.emoji ||
        this.selectedPlaylist.count === null
      ) {
        this.errorMessage = "All fields are required!";
        return;
      }

      // Update playlist data if validation passes
      if (this.selectedPlaylistIndex !== null) {
        this.$set(this.playlists, this.selectedPlaylistIndex, this.selectedPlaylist);
        this.closeEditModal();
      }
    },

    // Open the delete confirmation modal
    openDeleteModal(playlist, index) {
      this.selectedPlaylist = playlist;
      this.selectedPlaylistIndex = index;
      this.isDeleteModalOpen = true;
    },

    // Close the delete modal and reset state
    closeDeleteModal() {
      this.isDeleteModalOpen = false;
      this.selectedPlaylist = null;
      this.selectedPlaylistIndex = null;
    },

    // Confirm deletion of a playlist
    confirmDelete() {
      if (this.selectedPlaylistIndex !== null) {
        this.playlists.splice(this.selectedPlaylistIndex, 1);
        this.closeDeleteModal();
      }
    },
  },
  mounted() {
    this.filteredPlaylists = this.playlists; // Initialize with all playlist items
  },
};
</script>


<style scoped>
.btn-edit {
  margin-right: 10px; /* Add space between Edit and Delete buttons */
}

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

.table {
  margin-top: 20px;
  width: 100%;
  border-collapse: collapse;
  text-align: center;
}

.table th,
.table td {
  padding: 8px;
  border: 1px solid #ddd;
}

.btn-3d {
  padding: 8px 16px;
  background: linear-gradient(145deg, #3b82f6, #2563eb);
  border: none;
  border-radius: 8px;
  color: white;
  font-weight: bold;
  cursor: pointer;
  box-shadow: 4px 4px 8px rgba(0, 0, 0, 0.2), -4px -4px 8px rgba(255, 255, 255, 0.8);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.btn-3d:hover {
  background: linear-gradient(145deg, #2563eb, #3b82f6);
  box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.25), -2px -2px 4px rgba(255, 255, 255, 0.9);
  transform: translateY(-2px);
}

.btn-3d:active {
  box-shadow: inset 2px 2px 4px rgba(0, 0, 0, 0.25), inset -2px -2px 4px rgba(255, 255, 255, 0.9);
  background: linear-gradient(145deg, #1e40af, #2563eb);
}

.btn-save {
  background: linear-gradient(145deg, #4caf50, #388e3c);
}

.btn-save:hover {
  background: linear-gradient(145deg, #388e3c, #4caf50);
}

.btn-cancel {
  background: linear-gradient(145deg, #f4f4f4, #e0e0e0);
  color: #333;
}

.btn-cancel:hover {
  background: linear-gradient(145deg, #e0e0e0, #f4f4f4);
}

.btn-delete {
  background: linear-gradient(145deg, #f87171, #ef4444);
}

.btn-delete:hover {
  background: linear-gradient(145deg, #ef4444, #f87171);
}

/* Modal styles */
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
}

.modal-content {
  background: white;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
  width: 400px;
}

.edit-form label {
  display: flex;
  flex-direction: column;
  font-weight: bold;
  margin-bottom: 10px;
  font-size: 14px;
}

.edit-form input {
  padding: 8px;
  border: 1px solid #ddd;
  border-radius: 6px;
  margin-top: 5px;
  font-size: 14px;
  outline: none;
  transition: border-color 0.2s ease;
}

.edit-form input:focus {
  border-color: #3b82f6;
}

.modal-actions {
  display: flex;
  justify-content: space-between;
  margin-top: 20px;
}

.modal-actions button {
  flex: 1;
  margin: 0 5px;
}
</style>


  
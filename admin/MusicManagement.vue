<template>
  <div class="admin-layout">
    <SidebarAdmin />
    <div class="main-content">
      <HeaderAdmin title="Music Management" />
      <!-- Search bar component -->
      <SearchbarAdmin @search="filterSongs" />
      <div class="controls">
        <button class="add-button" @click="openAddModal">Add Song</button>
      </div>

      <table class="music-table">
        <thead>
          <tr>
            <th>Song Title</th>
            <th>Artist</th>
            <th>Image</th>
            <th>Song (Audio)</th>
            <th>Mood</th>
            <th>Emoji</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(song, index) in filteredSongs" :key="index">
            <td>{{ song.title }}</td>
            <td>{{ song.artist }}</td>
            <td>
              <img :src="song.image" alt="Song Image" class="song-image" />
            </td>
            <td>
              <audio :src="song.song" controls class="song-audio"></audio>
            </td>
            <td>{{ song.mood }}</td>
            <td>{{ song.emoji }}</td>
            <td>
              <button class="edit-button" @click="editSong(index)">Edit</button>
              <button class="delete-button" @click="deleteSong(index)">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Add/Edit Song Modal -->
      <div v-if="showAddModal" class="modal-overlay">
        <div class="modal">
          <h2>{{ isEditing ? "Edit Song" : "Add New Song" }}</h2>
          <form @submit.prevent="addSong">
            <label>
              Song Title:
              <input type="text" v-model="newSong.title" required />
            </label>
            <label>
              Artist:
              <input type="text" v-model="newSong.artist" required />
            </label>
            <label>
              Mood:
              <input type="text" v-model="newSong.mood" required />
            </label>
            <label>
              Emoji:
              <input type="text" v-model="newSong.emoji" required />
            </label>

            <div v-if="newSong.image">
              <p>Current Image:</p>
              <img :src="newSong.image" alt="Song Image" class="song-preview-image" />
            </div>
            <label>
              Image:
              <input
                type="file"
                @change="handleImageUpload"
                accept="image/*"
                :required="!isEditing"
              />
            </label>

            <div v-if="newSong.song">
              <p>Current Song:</p>
              <audio :src="newSong.song" controls class="song-preview-audio"></audio>
            </div>
            <label>
              Song (Audio):
              <input
                type="file"
                @change="handleAudioUpload"
                accept="audio/*"
                :required="!isEditing"
              />
            </label>

            <div class="modal-actions">
              <button type="submit" class="add-button">
                {{ isEditing ? "Save Changes" : "Add" }}
              </button>
              <button type="button" class="cancel-button" @click="closeModal">
                Cancel
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>



<script>
import HeaderAdmin from "@/components/HeaderAdmin.vue";
import SearchbarAdmin from "@/components/SearchbarAdmin.vue";
import SidebarAdmin from "@/components/SidebarAdmin.vue";

export default {
  components: { HeaderAdmin, SearchbarAdmin, SidebarAdmin },
  data() {
    return {
      songs: [], // All songs
      filteredSongs: [], // Filtered results
      showAddModal: false,
      originalSongIndex: null,
      newSong: {
        title: "",
        artist: "",
        image: "",
        song: "",
        mood: "",
        emoji: "",
      },
      isEditing: false,
    };
  },
  methods: {
    // Open modal to add a new song
    openAddModal() {
      this.showAddModal = true;
      this.resetNewSong();
      this.isEditing = false;
    },

    // Filter songs based on the keyword input
    filterSongs(keyword) {
      const lowercasedKeyword = keyword.toLowerCase();
      if (keyword) {
        // Filter songs based on all fields combined
        this.filteredSongs = this.songs.filter((song) =>
          Object.values(song)
            .join(" ") // Combine all fields into a single string
            .toLowerCase()
            .includes(lowercasedKeyword)
        );
      } else {
        // Reset filtered songs when the search term is empty
        this.updateFilteredSongs();
      }
    },

    // Handle image file upload
    handleImageUpload(event) {
      const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
          this.newSong.image = e.target.result;
        };
        reader.readAsDataURL(file);
      }
    },

    // Handle audio file upload
    handleAudioUpload(event) {
      const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
          this.newSong.song = e.target.result;
        };
        reader.readAsDataURL(file);
      }
    },

    // Add a new song or update an existing one
    addSong() {
      if (this.isEditing) {
        this.songs.splice(this.originalSongIndex, 1, { ...this.newSong });
        this.originalSongIndex = null;
        this.isEditing = false;
      } else {
        this.songs.push({ ...this.newSong });
      }
      // Update filtered songs whenever a song is added or edited
      this.updateFilteredSongs();
      this.resetNewSong();
      this.closeModal();
    },

    // Update filtered songs to match the current songs list
    updateFilteredSongs() {
      this.filteredSongs = [...this.songs]; // Reset filtered songs to all songs
    },

    // Reset the newSong object
    resetNewSong() {
      this.newSong = {
        title: "",
        artist: "",
        image: "",
        song: "",
        mood: "",
        emoji: "",
      };
    },

    // Delete a song
    deleteSong(index) {
      this.songs.splice(index, 1);
      this.updateFilteredSongs();
    },

    // Edit an existing song
    editSong(index) {
      const song = this.songs[index];
      this.newSong = { ...song };
      this.showAddModal = true;
      this.isEditing = true;
      this.originalSongIndex = index;
    },

    // Close the modal and reset form
    closeModal() {
      this.showAddModal = false;
      this.resetNewSong();
      this.isEditing = false;
      this.originalSongIndex = null;
    },
  },
  mounted() {
    // Initialize filteredSongs with all songs on component mount
    this.updateFilteredSongs();
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
  background-color: #1a73e8; /* A rich blue color */
  color: #fff;
  border: none;
  padding: 10px 20px;
  font-size: 14px;
  border-radius: 8px;
  cursor: pointer;
  margin-right: 4px; /* Space between add and cancel button */
  box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
  transition: all 0.3s ease;
  font-weight: bold;
  text-shadow: 0px 1px 2px rgba(0, 0, 0, 0.3);
  transform: translateY(-2px);
}

.add-button:hover {
  background-color: #4285f4; /* Slightly lighter blue */
  box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.25), inset 0px -3px 8px rgba(255, 255, 255, 0.15);
  transform: translateY(-4px) scale(1.05);
}

.music-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

.music-table th,
.music-table td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: center;
}

.song-image {
  width: 50px;
  height: 50px;
  object-fit: cover;
}

.song-audio {
  width: 100px;
}

.song-preview-image {
  max-width: 100px;
  max-height: 100px;
  height: 50px;
  margin-bottom: 5px;
}

.song-preview-audio {
  margin-bottom: 10px;
  height: 30px;
  width: 22.5rem;
}

.edit-button,
.delete-button,
.add-button,
.cancel-button {
  background: linear-gradient(135deg, #2196f3, #21cbf3);
  color: white;
  border: none;
  padding: 6px 12px;
  border-radius: 5px;
  cursor: pointer;
  transition: transform 0.2s, box-shadow 0.3s ease;
  box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
}

.edit-button:hover,
.delete-button:hover,
.add-button:hover,
.cancel-button:hover {
  transform: scale(1.1);
}

.delete-button {
  background: linear-gradient(135deg, #f44336, #f6685e);
  margin-left: 8px;
}

.cancel-button {
  background: linear-gradient(135deg, #f0f0f0, #cccccc);
  color: #333;
  box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.15);
  margin-bottom: 5px;
  font-weight: bold;
  margin-left: 7px;
  
}

.cancel-button:hover {
  transform: scale(1.1);
  box-shadow: 0px 8px 12px rgba(0, 0, 0, 0.25);
}

.controls {
  margin-top: 20px;
  display: flex;
  justify-content: flex-start; /* Aligns the buttons to the left */
  gap: 4px; /* Adds 4px space between buttons in the .controls container */
}

.controls button {
  margin-right: 4px; /* Adds space between the buttons */
}

/* Add a new group for Edit/Delete button container */
.button-group-edit-delete {
  display: flex;
  gap: 4px; /* 4px gap between edit and delete buttons */
}

.button-group-add-cancel {
  display: flex;
  gap: 4px; /* 4px gap between add and cancel buttons */
}

.modal-overlay {
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

.modal {
  background: white;
  padding: 20px;
  border-radius: 10px;
  width: 400px;
  box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);
}

.modal h2 {
  margin-bottom: 20px;
  font-size: 1.5em;
  font-weight: bold;
  color: #333;
}

.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 4px; /* Adds 4px space between buttons in the modal */
}

input[type="text"],
input[type="file"] {
  width: 100%;
  padding: 10px;
  margin: 5px 0 15px 0;
  border-radius: 8px;
  border: 1px solid #ddd;
  box-sizing: border-box;
  font-size: 14px;
  transition: all 0.3s ease;
}

input[type="text"]:focus,
input[type="file"]:focus {
  border-color: #1a73e8;
  outline: none;
  box-shadow: 0 0 8px rgba(26, 115, 232, 0.3);
}

input[type="text"]::placeholder,
input[type="file"]::placeholder {
  color: #bbb;
}

label {
  display: block;
  font-weight: 500;
  margin-bottom: 5px;
}

.modal label {
  font-size: 14px;
}
</style>

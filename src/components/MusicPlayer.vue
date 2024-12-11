<template>
  <div>
    <Header />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">



    <div class="player-container">
    <div class="song-information">
      <!-- Cover Image -->
     
      <img
        :src="cover"
        alt="Song Cover"
        class="cover-img"
        :class="{ rotating: isRotating }"
      />
      <!-- Song Title and Artist -->
      <h3 class="song-title">{{ title }}</h3>
      <p class="artist-name">{{ artist }}</p>
    </div>

    <div class="icon">
      <!-- Equalizer Icon -->
      <button @click="rotateImage" class="icon-btn">
        <i class="bx bx-equalizer"></i>
      </button>

  <!-- Loop Icon -->
  <button @click="toggleLoop" class="icon-btn">
    <i 
    :class="[
      'bx', 
      loopState === 'allLoop' ? 'bx-repeat' : '',
      loopState === 'singleLoop' ? 'bx-repeat' : '',
      loopState === 'shuffleLoop' ? 'bx-shuffle' : '',
      loopIconColor
    ]"
    @click="toggleLoop"
  >
    <!-- Show "1" for single loop -->
    <span v-if="loopState === 'singleLoop'" class="loop-number">1</span>
  </i>
  </button>

  <!-- Add to Playlist Icon -->
  <button @click="addToPlaylist" class="icon-btn">
    <i class="bx bx-list-plus"></i>
  </button>

  <!-- Favorite Icon -->
  <button @click="toggleFavorite" class="icon-btn" :class="{ liked: isLiked }">
    <i :class="isLiked ? 'bxs-heart' : 'bx-heart'"></i>
  </button>
</div>



          <div class="controls">
              <div class="time-info">
                  <input type="range" v-model="progress" @input="updateProgress" />
                  <div class="time-display">
                      <span>{{ formatTime(currentTime) }}</span> / <span>{{ formatTime(duration) }}</span>
                  </div>
              </div>

              <div class="button-container">
    <!-- Previous Track Button -->
    <button @click="prevTrack" class="icons-btn">
      <i class="bx bx-skip-previous"></i>
    </button>

    <!-- Play/Pause Button -->
    <button @click="togglePlay" class="icons-btn">
      <i :class="isPlaying ? 'bx bx-pause' : 'bx bx-play'"></i>
    </button>

    <!-- Next Track Button -->
    <button @click="nextTrack" class="icons-btn">
      <i class="bx bx-skip-next"></i>
    </button>
  </div>
          </div>


          <audio ref="audioPlayer" @loadedmetadata="setDuration" @timeupdate="updateTime"></audio>
      </div>
  </div>
</template>

<script>
import Header from "../components/Header.vue";

export default {
  name: "MusicPlayer",
  components: { Header },
  data() {
    return {
      title: this.$route.query.title || "Unknown Title",
      artist: this.$route.query.artist || "Unknown Artist",
      cover: this.$route.query.cover || "default-cover.jpg",
      isRotating: false, // Rotation state
      isLooping: false, // Track the looping state
      loopState: 'allLoop', // Default loop state (all loop)
      audioSrc: this.$route.query.audio || "",
      isPlaying: false,
      isLiked: false,
      duration: 0,
      currentTime: 0,
      progress: 0,
      playlist: this.$route.query.playlist ? JSON.parse(this.$route.query.playlist) : [],
      currentIndex: parseInt(this.$route.query.currentIndex) || 0,
      shuffleMode: false // Flag to track if shuffle mode is active
    };
  },
  methods: {
    playAudio() {
      this.$refs.audioPlayer.play();
      this.isPlaying = true;
    },
    pauseAudio() {
      this.$refs.audioPlayer.pause();
      this.isPlaying = false;
    },
    togglePlay() {
      this.isPlaying ? this.pauseAudio() : this.playAudio();
    },
    rotateImage() {
      // Toggle the rotation state
      console.log('Rotate button clicked');
      this.isRotating = !this.isRotating;
    },
    handleLikeButtonClick() {
      if (this.isLoggedIn) {
        this.toggleLike();
      } else {
        alert("Please log in to like this song.");
        setTimeout(() => {
          this.$router.push({ name: "Login" });
        }, 1000);
      }
    },
    handleAddToPlaylistClick() {
      if (this.isLoggedIn) {
        this.addToPlaylist();
      } else {
        alert("Please log in to add this song to your playlist.");
        setTimeout(() => {
          this.$router.push({ name: "Login" });
        }, 1000);
      }
    },
    toggleLike() {
      this.isLiked = !this.isLiked;
    },
    addToPlaylist() {
      // Logic for adding the song to the playlist
    },
    updateProgress() {
      this.$refs.audioPlayer.currentTime = (this.progress / 100) * this.duration;
    },
    setDuration() {
      this.duration = this.$refs.audioPlayer.duration;
    },
    updateTime() {
      this.currentTime = this.$refs.audioPlayer.currentTime;
      this.progress = (this.currentTime / this.duration) * 100;
    },
    formatTime(seconds) {
      const minutes = Math.floor(seconds / 60);
      seconds = Math.floor(seconds % 60);
      return `${minutes}:${seconds < 10 ? "0" + seconds : seconds}`;
    },

    // **Next Button Functionality**
    nextTrack() {
      if (this.$refs.audioPlayer.currentTime < this.$refs.audioPlayer.duration - 10) {
        // If we're not at the end, skip forward 10 seconds
        this.$refs.audioPlayer.currentTime += 10;
      } else {
        // Handle loop modes
        if (this.loopState === 'allLoop') {
          if (this.currentIndex < this.playlist.length - 1) {
            this.currentIndex++;
          } else {
            this.currentIndex = 0; // Loop back to the first song
          }
          this.updateSong();
        } else if (this.loopState === 'singleLoop') {
          // If single loop, just keep looping the same song
          this.updateSong();
        } else if (this.loopState === 'shuffleLoop') {
          // Shuffle mode
          if (this.shuffleMode) {
            // If shuffleMode is ON, only shuffle the playlist on the next song
            if (this.currentIndex < this.playlist.length - 1) {
              this.currentIndex++; 
            } else {
              this.currentIndex = 0; 
            }
            this.shufflePlaylist(); 
          } else {
            // If shuffleMode is OFF, proceed to the next song in the original order
            if (this.currentIndex < this.playlist.length - 1) {
              this.currentIndex++;
            } else {
              this.currentIndex = 0;
            }
            this.updateSong();
          }
        }
      }
    },

    // **Previous Button Functionality**
    prevTrack() {
      if (this.$refs.audioPlayer.currentTime <= 10) {
        if (this.currentIndex > 0) {
          this.currentIndex--;
        } else {
          this.currentIndex = this.playlist.length - 1; // Loop back to the last song
        }
        this.updateSong();
      } else {
        this.$refs.audioPlayer.currentTime -= 10;
      }
    },

    updateSong() {
      const currentSong = this.playlist[this.currentIndex];
      this.title = currentSong.title;
      this.artist = currentSong.artist;
      this.cover = currentSong.cover;
      this.audioSrc = currentSong.audio;
      this.$refs.audioPlayer.src = currentSong.audio;
      this.playAudio(); // Start playing the song
    },

    toggleLoop() {
      // Switch loop state in the sequence: allLoop -> singleLoop -> shuffleLoop -> allLoop
      if (this.loopState === 'allLoop') {
        this.loopState = 'singleLoop';
        this.$refs.audioPlayer.loop = true; // Enable looping for the current song
        console.log('Loop Mode: Single Loop'); // Optional: Debugging log
      } else if (this.loopState === 'singleLoop') {
        this.loopState = 'shuffleLoop';
        this.$refs.audioPlayer.loop = false; // Disable single song looping
        this.shuffleMode = true; // Activate shuffleMode
        console.log('Loop Mode: Shuffle'); // Optional: Debugging log
      } else if (this.loopState === 'shuffleLoop') {
        this.loopState = 'allLoop';
        this.$refs.audioPlayer.loop = false; // Play songs sequentially
        this.shuffleMode = false; // Deactivate shuffleMode
        console.log('Loop Mode: Loop All'); // Optional: Debugging log
      }
    },

    // Shuffle playlist for shuffle mode
    shufflePlaylist() {
      // Fisher-Yates Shuffle Algorithm
      for (let i = this.playlist.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [this.playlist[i], this.playlist[j]] = [this.playlist[j], this.playlist[i]];
      }
      // Reset currentIndex to play from the beginning of the shuffled playlist
      this.currentIndex = 0;
      this.updateSong();
    },

    // **Handle Audio End Event**
    handleAudioEnd() {
      if (this.loopState === 'allLoop' || (this.loopState === 'shuffleLoop' && this.shuffleMode)) {
        this.nextTrack(); // Automatically play the next song in both allLoop and shuffleLoop modes
      }
    },

    // **Function to start playing the initial song**
    playInitialSong() {
      if (this.playlist.length > 0) {
        this.audioSrc = this.playlist[this.currentIndex].audio;
        this.$refs.audioPlayer.src = this.audioSrc;
        this.playAudio();
      }
    }
  },
  mounted() {
    // Make sure you have an audio element in your template
    this.playInitialSong(); // Call the function to play the initial song

    // Listen for the "ended" event on the audio player
    this.$refs.audioPlayer.addEventListener('ended', this.handleAudioEnd);
  },

  beforeDestroy() {
    // Clean up the event listener when the component is destroyed
    this.$refs.audioPlayer.removeEventListener('ended', this.handleAudioEnd);
  }
};
</script>
<style scoped>
  .player-container {
    margin-top:45px;
    width: 35%; /* Maintain the original width */
    height: 520px; /* Original height */
    background: linear-gradient(135deg, rgba(173, 216, 230, 0.3), rgba(255, 255, 255, 0.1)); /* Gradient background with a hint of blue */
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 15px 25px rgba(0, 0, 0, 0.2); /* Enhanced shadow for depth */
    text-align: center;
    backdrop-filter: blur(15px); /* Frosted glass effect */
    border: 1px solid rgba(255, 255, 255, 0.3); /* Light border for glass effect */
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    justify-content: center;
    align-items: center;
    margin: 30px auto; 
    margin-top: 4.5rem;
    
  }
  .song-information {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center; /* Centers everything vertically */
  text-align: center;
  gap: 10px;
  margin-top: 20px; /* Ensures there's 20px margin at the top of the whole section */
}

.cover-img {
  margin: 0;
    margin-bottom: 15px;
    width: 160px;
    height: 160px;
    border-radius: 50%;
    transition: transform 1s linear;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3); /* Adds a shadow for depth */
    transform: perspective(500px) rotateX(10deg) rotateY(10deg); /* Adds a 3D tilt */
}

.rotating {
    animation: spin infinite 3s linear;
}
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.icon-btn .bx-repeat.active {
  color: blue; /* Style for active loop */
}
.loop-number {
  position: absolute;
  top: 0; /* Position the number at the top */
  right: 0; /* Position the number at the right */
  font-size: 10px; /* Smaller font size */
  background-color: #3498db; /* Blue background */
  color: white;
  width: 16px; /* Size of the circle */
  height: 16px; /* Same height as width to make it a circle */
  border-radius: 50%; /* Make it round */
  display: flex;
  justify-content: center;
  align-items: center; /* Center the number inside the circle */
  z-index: 10; /* Ensure it's above the icon */
}


.song-title {
  color: #d15f73;
  font-size: 24px;
  margin: 0; /* Remove any extra margins */
}

.artist-name {
  color: #888;
  font-size: 16px;
  margin: 0; /* Remove any extra margins */
}

.controls {
  display: flex;
  flex-direction: column; /* Changed to column to center the seek bar */
  align-items: center;
  gap: 20px;
  margin-top: 20px;
}

#seek-bar {
  width: 100px; /* Adjust width as needed */
  appearance: none;
  height: 5px;
  background: #ddd;
  border-radius: 5px;
  cursor: pointer;
}


  .time-display {
    margin-top: 5px;
    font-size: 14px;
    color: #878181;
  }
  .time-info {
      margin-bottom: 10px;
  }
  .button-container {
    display: flex;
  justify-content: center;
  align-items: center;
  margin-top: -10px;
  gap: 40px;
  }

  button {
      background-color: #68a4ff;
      border: none;
      padding: 15px;
      border-radius: 50%;
      cursor: pointer;
      transition: background-color 0.3s;
      font-size: 1rem;
      color: #ffffff;
  }
  button:hover {
      background-color: #4a90e2;
  }
  .icon {
    display: flex;
    justify-content: center; /* Center icons horizontally */
    align-items: center; /* Center icons vertically */
    max-width: 100%; /* Use full width of the container */
    margin: 0 auto;
    margin-top: 10px;
    gap: 50px; /* Maintain spacing between icons */
}

.icon-btn {
  background: none;
  border: none;
  cursor: pointer;
  padding: 0;
  font-size: 30px;
  display: flex;
  justify-content: center;
  align-items: center;
  width: 50px; /* Optional: Set consistent size */
  height: 50px; /* Optional: Set consistent size */
  color: black; /* Makes the icons black */
  position: relative; /* Important: This allows us to position the number 1 inside the icon */
  transition: color 0.2s ease-in-out; /* Smooth color change on hover */
  
}

.icon-btn:hover {
  color: #ffffff; /* Optional: Changes color on hover */
}

.icons-btn i {
  font-size: 25px; /* Slightly smaller font for the icon itself */
}

  .liked {
    color: red;
  }
  .controls-container {
      display: flex;
      flex-direction: column;
      align-items: center;
  }
  .grid-container {
      display: grid;
      grid-template-columns: repeat(4, 150px);
      gap: 20px;
  }
  .grid-item {
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 20px;
      background-color: #e9e9e9;
      border-radius: 8px;
      text-align: center;
      text-decoration: none;
      color: black;
      font-weight: bold;
  }
  .grid-item img {
      width: 60px;
      height: 60px;
      margin-bottom: 10px;
  }
  .grid-item:hover {
      background-color: #dcdcdc;
      cursor: pointer;
  }
</style>
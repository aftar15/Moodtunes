// Sidebar toggle function for MoodTunes page
function toggleSidebar() {
  const sidebar = document.getElementById("sidebar");
  sidebar.style.width = sidebar.style.width === "250px" ? "0" : "250px";
}

// Function to store song data in sessionStorage when a song card is clicked
function setSongData(cover, title, artist, audio) {
  sessionStorage.setItem('cover', cover);
  sessionStorage.setItem('title', title);
  sessionStorage.setItem('artist', artist);
  sessionStorage.setItem('audio', audio);
}

// Function to play audio and redirect to the music player page
function playAudio(songId) {
  const audioElement = document.getElementById(songId);
  const songItem = audioElement.closest('.song-item');
  const title = songItem.querySelector('h3').textContent;
  const artist = songItem.querySelector('p').textContent;
  const cover = songItem.querySelector('img').src;
  const audioSrc = audioElement.src;

  // Store current song and playlist in sessionStorage
  sessionStorage.setItem('audio', audioSrc);
  sessionStorage.setItem('title', title);
  sessionStorage.setItem('artist', artist);
  sessionStorage.setItem('cover', cover);
  sessionStorage.setItem('autoplay', 'true');

  // Create playlist from all songs and store in sessionStorage
  const playlist = Array.from(document.querySelectorAll('.song-item')).map(item => ({
    audio: item.querySelector('audio').src,
    title: item.querySelector('h3').textContent,
    artist: item.querySelector('p').textContent,
    cover: item.querySelector('img').src
  }));
  sessionStorage.setItem('playlist', JSON.stringify(playlist));

  // Redirect to the music player page
  window.location.href = 'musicplayer.html';
}

// Music player logic with next and previous buttons
document.addEventListener('DOMContentLoaded', () => {
  const playerTitle = document.getElementById('player-title');
  const playerArtist = document.getElementById('player-artist');
  const playerCover = document.getElementById('player-cover');
  const audioPlayer = document.getElementById('audio-player');
  const playButton = document.getElementById('play-btn');
  const nextButton = document.getElementById('next-btn');
  const prevButton = document.getElementById('prev-btn');
  const seekBar = document.getElementById('seek-bar');
  const currentTimeElement = document.getElementById('current-time');
  const durationElement = document.getElementById('duration');

  // Retrieve song details and playlist from sessionStorage
  const title = sessionStorage.getItem('title');
  const artist = sessionStorage.getItem('artist');
  const cover = sessionStorage.getItem('cover');
  const audioSrc = sessionStorage.getItem('audio');
  const autoplay = sessionStorage.getItem('autoplay') === 'true';
  const playlist = JSON.parse(sessionStorage.getItem('playlist')) || [];
  let currentTrackIndex = playlist.findIndex(track => track.audio === audioSrc);

  // Initialize the player with song details
  if (title && artist && cover && audioSrc) {
    playerTitle.textContent = title;
    playerArtist.textContent = artist;
    playerCover.src = cover;
    audioPlayer.src = audioSrc;

    // Autoplay if specified
    if (autoplay) {
      audioPlayer.play().catch(err => console.warn('Autoplay blocked:', err));
      sessionStorage.setItem('autoplay', 'false');
    }

    // Handle play/pause toggle
    let isPlaying = autoplay;
    playButton.addEventListener('click', () => {
      if (isPlaying) {
        audioPlayer.pause();
        playButton.textContent = '▶️';
      } else {
        audioPlayer.play();
        playButton.textContent = '⏸️';
      }
      isPlaying = !isPlaying;
    });

    // Next button functionality
    nextButton.addEventListener('click', () => {
      currentTrackIndex = (currentTrackIndex + 1) % playlist.length;
      loadTrack(currentTrackIndex);
    });

    // Previous button functionality
    prevButton.addEventListener('click', () => {
      currentTrackIndex = (currentTrackIndex - 1 + playlist.length) % playlist.length;
      loadTrack(currentTrackIndex);
    });

    // Function to load a track by index
    function loadTrack(index) {
      const track = playlist[index];
      if (track) {
        playerTitle.textContent = track.title;
        playerArtist.textContent = track.artist;
        playerCover.src = track.cover;
        audioPlayer.src = track.audio;
        playButton.textContent = '▶️';
      }
    }
  } else {
    console.error('Song details are missing in sessionStorage');
  }

  // Function to format time in minutes and seconds
  function formatTime(seconds) {
    const minutes = Math.floor(seconds / 60);
    seconds = Math.floor(seconds % 60);
    return `${minutes}:${seconds < 10 ? "0" + seconds : seconds}`;
  }

  // Set duration once audio metadata is loaded
  audioPlayer.addEventListener('loadedmetadata', () => {
    durationElement.textContent = formatTime(audioPlayer.duration);
    seekBar.max = 100;
  });

  // Update seek bar and current time as audio plays
  audioPlayer.addEventListener('timeupdate', () => {
    const progress = (audioPlayer.currentTime / audioPlayer.duration) * 100;
    seekBar.value = progress || 0;
    currentTimeElement.textContent = formatTime(audioPlayer.currentTime);
  });

  // Change audio position when user interacts with seek bar
  seekBar.addEventListener('input', () => {
    const newTime = (seekBar.value / 100) * audioPlayer.duration;
    audioPlayer.currentTime = newTime;
  });
});

// Load song data for music player page
function loadSongData() {
  const cover = sessionStorage.getItem('cover');
  const title = sessionStorage.getItem('title');
  const artist = sessionStorage.getItem('artist');
  const audio = sessionStorage.getItem('audio');

  // Set elements with retrieved values or use default
  document.getElementById('player-cover').src = cover || 'default-cover.jpg';
  document.getElementById('player-title').textContent = title || 'Unknown Title';
  document.getElementById('player-artist').textContent = artist || 'Unknown Artist';

  const audioPlayer = document.getElementById('audio-player');
  audioPlayer.src = audio || '';
}

// Check if on music player page and load song data
if (window.location.pathname.includes('musicplayer.html')) {
  window.addEventListener('DOMContentLoaded', loadSongData);
}

<template>
    <MusicPlayer />
   <div v-if="isVisible" class="popup" id="login-popup">
     <div class="popup-card">
       <p>You need to log in to add to favorites</p>
       <div class="popup-buttons">
         <button class="login" @click="redirectToLogin">Login</button>
         <button class="cancel" @click="hidePopup">Cancel</button>
       </div>
     </div>
   </div>
 </template>
 
 <script>
 export default {
   name: "LoginPopup",
   data() {
     return {
       isVisible: false, // Controls the visibility of the popup
     };
   },
   methods: {
     // Show the popup by setting isVisible to true
     showPopup() {
       this.isVisible = true;
     },
     // Hide the popup by setting isVisible to false
     hidePopup() {
       this.isVisible = false;
     },
     // Redirect user to the login page
     redirectToLogin() {
       this.$router.push("/login"); // Assumes Vue Router is set up
     },
   },
 };
 </script>
 
 <style scoped>
 .popup {
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
 
 .popup-card {
   background: white;
   padding: 20px;
   border-radius: 8px;
   text-align: center;
   box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
 }
 
 .popup-buttons {
   margin-top: 20px;
   display: flex;
   justify-content: space-around;
 }
 
 button {
   padding: 10px 20px;
   border: none;
   border-radius: 4px;
   cursor: pointer;
 }
 
 button.login {
   background-color: blue;
   color: white;
 }
 
 button.cancel {
   background-color: gray;
   color: white;
 }
 </style>
 
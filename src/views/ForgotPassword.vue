<template>
  <div class="container">
    <div class="forgot-password-form">
      <div class="logo">
        <router-link to="/home">
          <img src="@/assets/images/em.png" alt="MoodTunes Logo" />
        </router-link>
      </div>
      <h2>Reset Password</h2>
      <form @submit.prevent="resetPassword">
        <label for="email">E-Mail</label>
        <input 
          type="email" 
          id="email" 
          v-model="email" 
          placeholder="Enter your email address" 
          required 
        />

        <button type="submit">Send Reset Link</button>

        <p>Remember your password? <router-link to="/login">Login Here</router-link></p>
      </form>
    </div>
  </div>
</template>

<script>
import { authService } from '@/services/auth';

export default {
  data() {
    return {
      email: "",
    };
  },
  methods: {
    async resetPassword() {
      try {
        await authService.forgotPassword(this.email);
        this.$router.push('/login');
      } catch (error) {
        console.error('Password reset failed:', error);
      }
    },
  },
};
</script>

<style scoped>
.container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background: linear-gradient(135deg, #1e1e1e 0%, #2d2d2d 100%);
}

.forgot-password-form {
  background: rgba(255, 255, 255, 0.1);
  padding: 2rem;
  border-radius: 10px;
  width: 100%;
  max-width: 400px;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}

.logo {
  text-align: center;
  margin-bottom: 2rem;
}

.logo img {
  max-width: 150px;
}

h2 {
  color: #fff;
  text-align: center;
  margin-bottom: 2rem;
}

form {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

label {
  color: #fff;
  font-size: 0.9rem;
}

input {
  padding: 0.8rem;
  border: none;
  border-radius: 5px;
  background: rgba(255, 255, 255, 0.1);
  color: #fff;
}

input::placeholder {
  color: rgba(255, 255, 255, 0.5);
}

button {
  padding: 0.8rem;
  background: #007bff;
  color: #fff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background 0.3s;
}

button:hover {
  background: #0056b3;
}

p {
  text-align: center;
  color: #fff;
  margin-top: 1rem;
}

a {
  color: #007bff;
  text-decoration: none;
}

a:hover {
  text-decoration: underline;
}
</style>

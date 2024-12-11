<template>
  <div class="container">
    <div class="login-form">
      <div class="logo">
        <router-link to="/home">
          <img src="@/assets/images/em.png" alt="MoodTunes Logo" />
        </router-link>
      </div>
      <h2>Login To Continue</h2>
      <form @submit.prevent="login">
        <label for="email">E-Mail</label>
        <input
          type="email"
          id="email"
          v-model="email"
          placeholder="Enter Your E-Mail"
          required
        />

        <label for="password">Password</label>
        <input
          type="password"
          id="password"
          v-model="password"
          placeholder="Enter Your Password"
          required
        />

        <button type="submit">Login</button>

        <div class="social-login">
          <button class="google" @click.prevent="loginWithGoogle">
            <img
              src="@/assets/images/google.png"
              alt="Google Icon"
              class="iconss"
            />
            Google
          </button>
          <button class="facebook" @click.prevent="loginWithFacebook">
            <img
              src="@/assets/images/fb.png"
              alt="Facebook Icon"
              class="iconss"
            />
            Facebook
          </button>
        </div>
        <p>
          <router-link to="/forgot-password">Forgot password?</router-link>
        </p>
      </form>
      <p>
        Don’t have an account?
        <router-link to="/signup">Sign Up Here</router-link>
      </p>
    </div>
  </div>
</template>

<script>
import { authService } from '@/services/auth';

export default {
  data() {
    return {
      email: '',
      password: '',
    };
  },
  methods: {
    async login() {
      try {
        const response = await authService.login(this.email, this.password);
        this.$router.push('/home');
      } catch (error) {
        alert(error);
      }
    },
    logout() {
      localStorage.removeItem('adminAuth');
      this.$router.push('/login');
    },
    loginWithGoogle() {
      console.log('Logging in with Google');
    },
    loginWithFacebook() {
      console.log('Logging in with Facebook');
    },
  }
};
</script>

  
<style scoped>
    .container {
      display: flex;
      justify-content: center;
      align-items: center;
      width: 100%;
      height: 100vh;
      background: linear-gradient(135deg, #c2e9fb 40%, #e2fcfd);
    }
    .login-form {
      background: #ffffff;
      padding: 2rem;
      border-radius: 40px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      width: 100%;
      max-width: 400px;
      text-align: center;
    }
    .logo img {
      width: 80px;
      height: 80px;
      transition: transform 0.3s ease;
    }
    .logo img:hover {
      transform: scale(1.1);
    }
    h2 {
      font-size: 1.5rem;
      margin-bottom: 1rem;
    }
    form {
      display: flex;
      flex-direction: column;
    }
    label {
      text-align: left;
      margin: 0.3rem 0 0.2rem;
      font-weight: bold;
    }
    input[type="text"],
    input[type="email"],
    input[type="password"] {
      padding: 0.4rem;
      border: 1px solid #000;
      border-radius: 25px;
      margin-bottom: 0.5rem;
    }
    button[type="submit"] {
      background: #4a90e2;
      color: #fff;
      padding: 0.7rem;
      border: none;
      border-radius: 40px;
      font-size: 1rem;
      cursor: pointer;
      transition: background 0.3s;
    }
    button[type="submit"]:hover {
      background: #357ab8;
    }
    .iconss {
      width: 30px;
      height: 20px;
      margin-right: 5px;
      vertical-align: middle;
      border-radius: 30px;
    }
    .social-login {
      display: flex;
      justify-content: space-between;
      margin: 1rem 0;
    }
    .social-login button {
      border: 1px solid #000;
      padding: 0.2rem;
      border-radius: 40px;
      width: 48%;
      cursor: pointer;
    }
    .google, .facebook {
      background-color: #fff;
      color: #000;
    }
    .social-login button:hover {
      opacity: 0.8;
    }
    a {
      color: #4a90e2;
      text-decoration: none;
    }
    a:hover {
      color: #357ab8;
      text-decoration: underline;
    }
    p {
      margin-top: 0.5rem;
      font-size: 0.9rem;
    }
</style>
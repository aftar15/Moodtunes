<template>
  <div class="container">
    <div class="login-form">
      <div class="logo">
        <router-link to="/home">
          <img src="@/assets/images/em.png" alt="MoodTunes Logo" />
        </router-link>
      </div>
      <h2>Login To Continue</h2>
      
      <!-- Error Message -->
      <div v-if="error" class="error-message">
        {{ error }}
      </div>

      <form @submit.prevent="login">
        <label for="email">E-Mail</label>
        <input
          type="email"
          id="email"
          v-model="email"
          placeholder="Enter Your E-Mail"
          required
          :disabled="loading"
        />

        <label for="password">Password</label>
        <input
          type="password"
          id="password"
          v-model="password"
          placeholder="Enter Your Password"
          required
          :disabled="loading"
        />

        <button type="submit" :disabled="loading">
          {{ loading ? 'Logging in...' : 'Login' }}
        </button>

        <div class="social-login">
          <button class="google" @click.prevent="loginWithGoogle" :disabled="loading">
            <img src="@/assets/images/google.png" alt="Google Icon" class="iconss" />
            Google
          </button>
          <button class="facebook" @click.prevent="loginWithFacebook" :disabled="loading">
            <img src="@/assets/images/fb.png" alt="Facebook Icon" class="iconss" />
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
export default {
  name: 'Login',
  data() {
    return {
      email: '',
      password: '',
      error: null,
      loading: false
    };
  },
  methods: {
    async login() {
      this.error = null;
      this.loading = true;
      
      try {
        const response = await fetch('http://localhost:8000/api/auth/login', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
          },
          credentials: 'include',
          body: JSON.stringify({
            email: this.email,
            password: this.password
          })
        });

        const data = await response.json();

        if (!response.ok) {
          throw new Error(data.message || 'Login failed');
        }

        // Store the token and user data
        localStorage.setItem('token', data.token);
        localStorage.setItem('user', JSON.stringify(data.user));
        
        // Redirect based on user role
        if (data.is_admin) {
          this.$router.push('/admin/dashboard');
        } else {
          this.$router.push('/user/dashboard');
        }
      } catch (error) {
        console.error('Login error:', error);
        this.error = error.message || 'An error occurred during login';
      } finally {
        this.loading = false;
      }
    },
    loginWithGoogle() {
      // Implement Google login
    },
    loginWithFacebook() {
      // Implement Facebook login
    }
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
    .error-message {
      color: #dc3545;
      background-color: #f8d7da;
      border: 1px solid #f5c6cb;
      padding: 10px;
      margin-bottom: 15px;
      border-radius: 4px;
      text-align: center;
    }
    button:disabled {
      opacity: 0.7;
      cursor: not-allowed;
    }
</style>
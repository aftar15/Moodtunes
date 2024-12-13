<template>
    <div class="container">
      <div class="register-form">
        <div class="logo">
          <router-link to="/home">
            <img src="@/assets/images/em.png" alt="MoodTunes Logo" />
          </router-link>
        </div>
        <h2>Create Your Account</h2>
        <div v-if="error" class="error-message">{{ error }}</div>
        <form @submit.prevent="signup">
          <label for="name">Username</label>
          <input type="text" id="name" v-model="name" placeholder="Enter Your Username" required />
  
          <label for="email">E-Mail</label>
          <input type="email" id="email" v-model="email" placeholder="Enter Your E-Mail" required />
  
          <label for="password">Password</label>
          <input 
            type="password" 
            id="password" 
            v-model="password" 
            placeholder="Enter Your Password" 
            required 
            minlength="8"
          />
  
          <label for="password_confirmation">Confirm Password</label>
          <input
            type="password"
            id="password_confirmation"
            v-model="password_confirmation"
            placeholder="Confirm Your Password"
            required
            minlength="8"
          />
  
          <button type="submit">Sign Up</button>
  
          <div class="social-login">
            <button class="google" @click.prevent="signupWithGoogle">
              <img src="@/assets/images/google.png" alt="Google Icon" class="iconss" /> Google
            </button>
            <button class="facebook" @click.prevent="signupWithFacebook">
              <img src="@/assets/images/fb.png" alt="Facebook Icon" class="iconss" /> Facebook
            </button>
          </div>
  
          <p>Already have an account? <router-link to="/login">Log In Here</router-link></p>
        </form>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'SignUp',
    data() {
      return {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        error: null
      };
    },
    methods: {
      async getCsrfToken() {
        try {
          await fetch('http://localhost:8000/sanctum/csrf-cookie', {
            method: 'GET',
            credentials: 'include',
          });
        } catch (error) {
          console.error('Error fetching CSRF token:', error);
        }
      },
      async signup() {
        try {
          this.error = null;
          if (this.password !== this.password_confirmation) {
            this.error = "Passwords do not match";
            return;
          }

          // Get CSRF token first
          await this.getCsrfToken();
          
          const response = await fetch('http://localhost:8000/api/auth/register', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'include',
            body: JSON.stringify({
              name: this.name,
              email: this.email,
              password: this.password,
              password_confirmation: this.password_confirmation
            })
          });

          const data = await response.json();

          if (!response.ok) {
            if (data.errors) {
              // Handle validation errors
              const errorMessages = Object.values(data.errors).flat();
              throw new Error(errorMessages.join('\n'));
            }
            throw new Error(data.message || 'Registration failed');
          }

          // Store token and user data
          if (data.token && data.user) {
            localStorage.setItem('token', data.token);
            localStorage.setItem('user', JSON.stringify(data.user));
            // Redirect to user dashboard since new registrations are always regular users
            this.$router.push('/user/dashboard');
          } else {
            // If we don't get a token and user data, something went wrong
            throw new Error('Invalid response from server');
          }
        } catch (error) {
          this.error = error.message || 'Registration failed. Please try again.';
          console.error('Registration error:', error);
        }
      },
      signupWithGoogle() {
        // Implement Google signup
      },
      signupWithFacebook() {
        // Implement Facebook signup
      }
    },
  };
  </script>
  
  <style scoped>
  .container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background: linear-gradient(135deg, #71b7e6, #9b59b6);
  }
  
  .register-form {
    background: white;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 400px;
  }
  
  .logo {
    text-align: center;
    margin-bottom: 20px;
  }
  
  .logo img {
    width: 100px;
    height: 100px;
  }
  
  h2 {
    text-align: center;
    color: #333;
    margin-bottom: 30px;
  }
  
  .error-message {
    background-color: #ffe6e6;
    color: #ff0000;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 20px;
    text-align: center;
  }
  
  form {
    display: flex;
    flex-direction: column;
  }
  
  label {
    margin-bottom: 5px;
    color: #666;
  }
  
  input {
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
  }
  
  button {
    padding: 12px;
    background: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    margin-bottom: 20px;
  }
  
  button:hover {
    background: #45a049;
  }
  
  .social-login {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
  }
  
  .social-login button {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
  }
  
  .google {
    background: #db4437;
  }
  
  .facebook {
    background: #4267B2;
  }
  
  .google:hover {
    background: #c53929;
  }
  
  .facebook:hover {
    background: #365899;
  }
  
  .iconss {
    width: 20px;
    height: 20px;
  }
  
  p {
    text-align: center;
    color: #666;
  }
  
  a {
    color: #4CAF50;
    text-decoration: none;
  }
  
  a:hover {
    text-decoration: underline;
  }
  </style>
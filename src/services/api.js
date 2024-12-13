import axios from 'axios';

const api = axios.create({
    baseURL: 'http://localhost:8000/api',
    withCredentials: true,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
    }
});

// Function to get CSRF token before sensitive requests
const getCsrfToken = async () => {
    try {
        await axios.get('http://localhost:8000/sanctum/csrf-cookie', { 
            withCredentials: true,
            headers: {
                'Accept': 'application/json'
            }
        });
    } catch (error) {
        console.error('Failed to retrieve CSRF token:', error);
        throw error;
    }
};

// Add request interceptor to add auth token and CSRF token
api.interceptors.request.use(
    async (config) => {
        // Ensure CSRF token is retrieved for POST/PUT/DELETE requests
        if (['post', 'put', 'delete'].includes(config.method.toLowerCase())) {
            try {
                await getCsrfToken();
            } catch (error) {
                console.error('CSRF token retrieval failed:', error);
            }
        }

        // Add auth token if available
        const authToken = localStorage.getItem('token');
        if (authToken) {
            config.headers.Authorization = `Bearer ${authToken}`;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// Add response interceptor to handle common errors
api.interceptors.response.use(
    (response) => response,
    (error) => {
        console.error('API Error:', {
            status: error.response?.status,
            data: error.response?.data,
            headers: error.response?.headers,
            config: error.config
        });

        if (error.response?.status === 401) {
            // Handle unauthorized access
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            window.location.href = '/login';
        }
        return Promise.reject(error);
    }
);

export default api;

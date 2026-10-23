
import axios from 'axios';

const api = axios.create({
  baseURL:
    process.env.REACT_APP_API_BASE_URL ||
    'http://127.0.0.1:8000/api/v1',

  headers: {
    Accept: 'application/json',
    'Content-Type': 'application/json',
  },

  timeout: 15000,
});

export default api;
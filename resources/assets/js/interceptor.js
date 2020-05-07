import axios from 'axios';
import {mainURL} from './config';

const API_URL = process.env.API_URL || mainURL;

export default axios.create({
  baseURL: API_URL,
  headers: {
    'Content-Type': 'application/json',
    'Authorization': 'Bearer ' + localStorage.getItem('user-token')
  }
})

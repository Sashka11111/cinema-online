import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo is now loaded conditionally only for room pages
 * See room-watch.blade.php for Echo initialization
 */


import axios from "axios";

axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('bearer');

export default axios

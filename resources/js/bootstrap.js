import axios from 'axios'
axios.defaults.withCredentials = true
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
axios.defaults.xsrfCookieName = 'XSRF-TOKEN'
axios.defaults.xsrfHeaderName = 'X-XSRF-TOKEN'

let hasCsrf = false
async function ensureCsrf(){ if(!hasCsrf){ await axios.get('/sanctum/csrf-cookie'); hasCsrf=true } }
axios.interceptors.request.use(async (cfg)=> {
    if (!hasCsrf && !cfg.url.includes('/sanctum/csrf-cookie')) await ensureCsrf()
    return cfg
})
axios.interceptors.response.use(r=>r, async (error)=>{
    const {response, config} = error || {}
    const s = response?.status
    if (s === 419 || s === 401) {
        hasCsrf = false; await ensureCsrf()
        if (!config.__retry) { config.__retry = true; return axios(config) }
    }
    return Promise.reject(error)
})
window.axios = axios

import axios from 'axios'
import configData from '../constants/config.json'

const axiosClient = axios.create({
  baseURL: configData.SERVER_URL,
  headers: {
    'Content-Type': 'application/json',
  },
})

axiosClient.interceptors.response.use(
  function (response) {
    return response
  },
  function (error) {
    console.log(error)
    return error
  },
)

export default axiosClient

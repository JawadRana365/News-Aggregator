import { configureStore } from '@reduxjs/toolkit'
import searchParamSlice from './searchParam/searchParamSlice'
import userSlice from './user/userSlice'

export default configureStore({
  reducer: {
    searchParamReducer: searchParamSlice,
    userReducer: userSlice
  },
})
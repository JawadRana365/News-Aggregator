import { createSlice } from '@reduxjs/toolkit'


export const userSlice = createSlice({
  name: 'user',
  initialState: {
    user: null,
    token: localStorage.getItem('loginToken')
  },
  reducers: {
    setUser: (state, action) => { 
        return { 
            ...state,
            user : action.payload 
        }
    },
    setToken: (state, action) => { 
        return { 
            ...state,
            token : action.payload 
        }
    },
  },
})

// Action creators are generated for each case reducer function
export const { setUser,setToken } = userSlice.actions

export default userSlice.reducer
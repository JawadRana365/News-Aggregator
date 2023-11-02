import { createSlice } from '@reduxjs/toolkit'

function getPreviousDay(date = new Date()) {
  const previous = new Date(date.getTime());
  previous.setDate(date.getDate() - 1);
  return previous;
}

const todayDate = () => {
    const date = getPreviousDay(new Date())
    return `${date.getDate()< 10 ? `0${date.getDate()}` : date.getDate()}-${date.getMonth() + 1 < 10 ? `0${date.getMonth() + 1}` : date.getMonth() + 1}-${date.getFullYear()}`
}

export const searchParamSlice = createSlice({
  name: 'searchParam',
  initialState: {
    searchParam: {
        search: "",
        date: todayDate(),
        category: "all",
        source: "all"
    }
  },
  reducers: {
    setSearchParam: (state, action) => { 
        return { 
            ...state,
            searchParam : action.payload 
        }
    },
  },
})

// Action creators are generated for each case reducer function
export const { setSearchParam } = searchParamSlice.actions

export default searchParamSlice.reducer
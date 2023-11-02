import './App.css';
import Footer from './components/footer/Footer';
import Header from './components/header/Header';
import Home from './pages/Home';
import { BrowserRouter as Router, Routes, Route } from "react-router-dom";
import { useEffect, useState } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { setUser } from './redux/user/userSlice';
import { setSearchParam } from './redux/searchParam/searchParamSlice';
import axiosClient from './utils/helpers/server';
import Login from './pages/Login';
import Registeration from './pages/Registeration';
import Profile from './pages/Profile';
import Logout from './pages/Logout';

function App() {
  const dispatch = useDispatch()
  const token = useSelector((state) => state.userReducer.token)
  const searchParam = useSelector((state) => state.searchParamReducer.searchParam)
  const [userData,setUserData] = useState()
  useEffect(()=>{
    axiosClient.get(`/user`, {
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`
      }
    }).then(resp => {
      if(resp.status == 200){
        dispatch(setUser(resp.data))
        setUserData(resp.data)
        axiosClient.get(`/user-preferences`, {
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`
          },
        }).then(resp => {
          if(resp.status == 200){
            dispatch(setSearchParam({
              search: searchParam.search,
              date: searchParam.date,
              category: resp.data.preferences.category,
              source: resp.data.preferences.source
            }))
          }
        })
      }else{
        dispatch(setUser(null))
        setUserData(undefined)
      }
    })
  },[token])
  return (
    <>
      <Header/>
      <Router>
        <Routes>
          <Route path="" element={<Home />} />
          { userData ?
            <>
              <Route path="/profile" element={<Profile />} />
              <Route path="/logout" element={<Logout />} />
            </>
            :
            <>
              <Route path="/registration" element={<Registeration />} />
              <Route path="/login" element={<Login />} />
            </>
          }
        </Routes>
      </Router>
      <Footer/>
    </>
  );
}

export default App;

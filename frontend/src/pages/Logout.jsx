import React, { useEffect } from 'react'
import { useDispatch } from 'react-redux';
import { useNavigate } from 'react-router-dom';
import { setUser, setToken } from '../redux/user/userSlice';

export default function Logout() {
    const navigate = useNavigate(); 
    const dispatch = useDispatch()

    useEffect(()=> {
        localStorage.clear();
        dispatch(setUser(null))
        dispatch(setToken(null))
        navigate("/")
    },[])

    return (
        <></>
    )
}

import React, { useState } from 'react'
import axiosClient from '../utils/helpers/server'
import { useNavigate } from 'react-router-dom';
import { useDispatch } from 'react-redux';
import { setToken, setUser } from '../redux/user/userSlice';

export default function Login() {
    const navigate = useNavigate(); 
    const[email,setEmail] = useState("")
    const[password,setPassword] = useState("")
    const[errorMessage,setErrorMessage] = useState("")
    const dispatch = useDispatch()

    const loginBtnClicked = () => {
        let data = {
            "email": email,
            "password": password
        }
        axiosClient.post("/auth/loginUser", data).then(resp => {
            if(resp.status == 200){
                localStorage.setItem("loginToken", resp.data.token);
                dispatch(setToken(resp.data.token))
                dispatch(setUser(resp.data.user))
                navigate("/")
            }else{
                setErrorMessage("Invalid email or password")
                setTimeout(()=>{
                    setErrorMessage("")
                },2000)
            }
        })
    }

    return (
        <>
            <div className="flex items-center h-screen w-full">
                <div className="w-full bg-white rounded shadow-lg p-8 m-4 md:max-w-sm md:mx-auto">
                <span className="block w-full text-xl uppercase font-bold mb-4">Login</span>      
                    <div className="mb-4">
                        <div className="mb-4 md:w-full">
                            <label className="block text-xs mb-1">Email</label>
                            <input 
                                className="w-full border rounded p-2 outline-none focus:shadow-outline" 
                                type="email" 
                                name="email" 
                                placeholder="Email"
                                value={email}
                                onChange={(e) => setEmail(e.currentTarget.value)}
                            />
                        </div>
                        <div className="mb-6 md:w-full">
                            <label className="block text-xs mb-1">Password</label>
                            <input 
                                className="w-full border rounded p-2 outline-none focus:shadow-outline" 
                                type="password" 
                                name="password"
                                placeholder="Password"
                                value={password}
                                onChange={(e) => setPassword(e.currentTarget.value)}
                            />
                        </div>
                        { errorMessage != ""?
                            <div className="mb-6 md:w-full">
                                <label className="block text-xs text-red-600 mb-1">{errorMessage}</label>
                            </div>
                            :<></>
                        }
                        <button 
                            className="bg-blue-500 hover:bg-green-700 text-white uppercase text-sm font-semibold px-4 py-2 rounded"
                            onClick={loginBtnClicked}
                        >Login</button>
                    </div>
                    <a className="text-blue-700 text-center text-sm" href="/registration">Don't have account register?</a>
                </div>
            </div>
        </>
    )
}

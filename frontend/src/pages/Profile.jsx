import React from 'react'
import { useSelector } from 'react-redux'

export default function Profile() {
  const user = useSelector((state) => state.userReducer.user)
  return (
    <>
    <div className="flex items-center h-screen w-full">
        <div className="w-full bg-white rounded shadow-lg p-8 m-4 md:max-w-sm md:mx-auto">
        <span className="block w-full text-xl uppercase font-bold mb-4">Profile</span>      
            <div className="mb-4">
                <div className="mb-6 md:w-full">
                    <label className="block text-xs mb-1">Name</label>
                    <input 
                        className="w-full border rounded p-2 outline-none focus:shadow-outline" 
                        type="text" 
                        name="name"
                        placeholder="name"
                        value={user.name}
                        readOnly={true}
                    />
                </div>
                <div className="mb-4 md:w-full">
                    <label className="block text-xs mb-1">Email</label>
                    <input 
                        className="w-full border rounded p-2 outline-none focus:shadow-outline" 
                        type="email" 
                        name="email" 
                        placeholder="Email"
                        value={user.email}
                        readOnly={true}
                    />
                </div>
            </div>
        </div>
    </div>
</>
  )
}

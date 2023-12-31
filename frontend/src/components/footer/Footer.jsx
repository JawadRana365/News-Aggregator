import React from 'react'
import { useSelector } from 'react-redux'

export default function Footer() {
  const user = useSelector((state) => state.userReducer.user)
  return (
    <>
      <footer className="bg-white rounded-lg shadow m-4 dark:bg-gray-800">
          <div className="w-full mx-auto max-w-screen-xl p-4 md:flex md:items-center md:justify-between">
            <span className="text-sm text-gray-500 sm:text-center dark:text-gray-400">© 2023 <a href="https://flowbite.com/" className="hover:underline">Innoscripta</a>. All Rights Reserved.
          </span>
          <ul className="flex flex-wrap items-center mt-3 text-sm font-medium text-gray-500 dark:text-gray-400 sm:mt-0">
              <li>
                  <a href="/" className="mr-4 hover:underline md:mr-6 ">Home</a>
              </li>
              <li>
                  <a href={ user ? "/profile" : "/login" } className="mr-4 hover:underline md:mr-6">{ user ? "Profile" : "Login"}</a>
              </li>
          </ul>
          </div>
      </footer>
    </>
  )
}

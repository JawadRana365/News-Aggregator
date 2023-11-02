import React, { useEffect, useState } from 'react'
import Datepicker from "react-tailwindcss-datepicker";
import DropDown from '../dropDown/DropDown';
import { useDispatch, useSelector } from 'react-redux';
import { setSearchParam } from '../../redux/searchParam/searchParamSlice';
import axiosClient from '../../utils/helpers/server';

function getPreviousDay(date = new Date()) {
    const previous = new Date(date.getTime());
    previous.setDate(date.getDate() - 1);
  
    return previous;
}

  
export default function SearchForm() {
    const dispatch = useDispatch()
    const searchParam = useSelector((state) => state.searchParamReducer.searchParam)
    const token = useSelector((state) => state.userReducer.token)
    const user = useSelector((state) => state.userReducer.user)
    const [datePickerValue, setDatePickerValue] = useState({
        startDate: getPreviousDay(new Date()),
        endDate: getPreviousDay(new Date())
    });
    const [search, setSearch] = useState(searchParam.search)
    const [category, setCategory] = useState(searchParam.category)
    const [source, setSource] = useState(searchParam.source)
    const[sucessMessage,setSucessMessage] = useState("")

    const handleValueChange = newValue => {
        setDatePickerValue(newValue);
    };

    const handleSearchButtonClicked = () => {
        console.log(datePickerValue.startDate)
        dispatch(setSearchParam({
            search: search,
            date: datePickerValue.startDate == null ? searchParam.date : datePickerValue.startDate,
            category: category,
            source: source
        }))
    }

    useEffect(()=>{
        setCategory(searchParam.category)
        setSource(searchParam.source)
    },[searchParam])

    const handleSeveButtonClicked = () => {
        if(user){
            let data = {
                "category" : category,
                "source" : source
            }
            axiosClient.post(`/user-preferences`,data, {
                headers: {
                  'Content-Type': 'application/json',
                  'Authorization': `Bearer ${token}`
                },
              }).then(resp => {
                if(resp.status == 200){
                    setSucessMessage("Preferences Saved Successfully")
                    setTimeout(()=>{
                        setSucessMessage("")
                    },2000)
                }
            })
        }
    }

    return (
        <>
            <div className="w-full">
                <form className="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                    <div className="mb-2 grid grid-cols-1 gap-2 md:grid-cols-2 md:gap-2">
                        <input 
                            value={search}
                            onChange={(e) => setSearch(e.currentTarget.value)}
                            className="shadow mx-2 appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                            type="text" 
                            placeholder="Search..."/>
                        <Datepicker 
                            containerClassName={"mx-2 relative text-gray-700"}
                            inputClassName={"border rounded text-gray-700 relative transition-all duration-300 py-2.5 pl-4 pr-14 w-full border-gray-300 dark:bg-slate-800 dark:text-white/80 dark:border-slate-600 rounded-lg tracking-wide font-light text-sm placeholder-gray-400 bg-white focus:ring disabled:opacity-40 disabled:cursor-not-allowed focus:border-blue-500 focus:ring-blue-500/20"}
                            primaryColor={"blue"}  
                            placeholder={"Search by date..."} 
                            displayFormat={"DD-MM-YYYY"} 
                            useRange={false} 
                            asSingle={true} 
                            value={datePickerValue} 
                            onChange={handleValueChange} />
                        <DropDown value={category} setvalue={setCategory} options={
                            [
                                {
                                    id: "all",
                                    name: "All"
                                },
                                {
                                    id: "sport",
                                    name: "Sports"
                                },
                                {
                                    id: "busienss",
                                    name: "Busienss"
                                },
                                {
                                    id: "software",
                                    name: "Software"
                                }
                            ]
                        } />
                        <DropDown value={source} setvalue={setSource} options={
                            [
                                {
                                    id: "all",
                                    name: "All"
                                },
                                {
                                    id: "New API",
                                    name: "The News"
                                },
                                {
                                    id: "New York Times",
                                    name: "New York Times"
                                },
                                {
                                    id: "The Guardian",
                                    name: "The Guardians"
                                }
                            ]
                            } />
                    </div>

                    <div className="flex items-center justify-end">
                        { sucessMessage != ""?
                            <div className="mb-6 md:w-full">
                                <label className="block text-xs text-green-600 mb-1">{sucessMessage}</label>
                            </div>
                            :<></>
                        }
                    </div>
                    <div className="flex items-center justify-end">
                        { user &&
                            <button
                                onClick={handleSeveButtonClicked}
                                className="mx-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="button">
                                Save Preference
                            </button>
                        }
                        <button
                            onClick={handleSearchButtonClicked}
                            className="mx-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="button">
                            Search
                        </button>
                    </div>
                </form>
            </div>
        </>
    )
}

import React from 'react'

export default function DropDown({value,setvalue,options}) {

    const menuItemChanged = (e) => {
        setvalue(e.currentTarget.value)
    }

    return (
        <select 
            onChange={(e) => menuItemChanged(e)}
            value={value}
            className="shadow mx-2 appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            {options.map((option,index) => (
                <option value={option.id} key={index}>{option.name}</option>
            ))}
        </select>
    )
}

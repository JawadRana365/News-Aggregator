<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Preferences;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PreferencesController extends Controller
{
    /**
    * Get User Prefrences
    * @param Request $request
    * @return Preferences
    */
    public function getUserPreferences(Request $request)
    {
        try {
            $preferences = Preferences::where('user_id', $request->user()->id)->first();
            return response()->json([
                'status' => true,
                'message' => 'Preference Data Retrived Successfully',
                'preferences' => $preferences,
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    /**
    * Save User Prefrences
    * @param Request $request
    * @return Preferences
    */
    public function saveUserPreferences(Request $request)
    {
        try {
            //Validated
            $validatePreferences = Validator::make($request->all(), 
            [
                'category' => 'required',
                'source' => 'required',
            ]);

            if($validatePreferences->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validatePreferences->errors()
                ], 401);
            }

            if(Preferences::where('user_id', $request->user()->id)->exists()){
                $preferences = Preferences::where('user_id', $request->user()->id)->update([
                    'user_id' => $request->user()->id,
                    'category' => $request->category,
                    'source' => $request->source,
                ]);
            }else{
                $preferences = Preferences::create([
                    'user_id' => $request->user()->id,
                    'category' => $request->category,
                    'source' => $request->source,
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Preference Data Saved Successfully',
                'preferences' => $preferences,
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}

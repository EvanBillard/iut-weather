<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CityController extends Controller
{   
    // Save a city
    public function store(Request $request)
    {
        $cityName = $request->input('city_to_save');
        
        // Is city already saved ?
        if (!City::where('user_id', Auth::id())->where('name', $cityName)->exists()) {
            $city = new City();
            $city->name = $cityName;
            $city->user_id = Auth::id();
            $city->save();
        }

        return redirect()->route('dashboard');
    }

    // Delete a city
    public function destroy($id)
    {
        $city = City::findOrFail($id);
        $city->delete();

        return redirect()->route('dashboard');
    }

    // Set a favorite
    public function setFavorite($id)
    {
        $city = City::findOrFail($id);
        
        // One favorite per user
        City::where('user_id', Auth::id())->update(['is_favorite' => false]);

        $city->is_favorite = true;
        $city->save();

        return redirect()->route('dashboard');
    }
}

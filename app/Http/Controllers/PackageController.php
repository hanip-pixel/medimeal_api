<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PackageController extends Controller
{
    // GET semua paket
    public function index()
    {
        $packages = DB::table('meal_packages')->where('is_available', 1)->get();
        return response()->json($packages);
    }

    // GET detail paket
    public function show($id)
    {
        $package = DB::table('meal_packages')->where('id', $id)->first();
        if (!$package) {
            return response()->json(['message' => 'Package not found'], 404);
        }
        return response()->json($package);
    }

    // POST tambah paket
    public function store(Request $request)
    {
        $id = DB::table('meal_packages')->insertGetId([
            'name' => $request->name,
            'description' => $request->description,
            'medical_condition' => $request->medical_condition,
            'price' => $request->price,
            'duration_days' => $request->duration_days ?? 7,
            'calories' => $request->calories ?? 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['success' => true, 'id' => $id], 201);
    }

    // PUT update paket
    public function update(Request $request, $id)
    {
        DB::table('meal_packages')->where('id', $id)->update([
            'name' => $request->name,
            'price' => $request->price,
            'updated_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }
 
    // DELETE paket (soft delete)
    public function destroy($id)
    {
        DB::table('meal_packages')->where('id', $id)->update(['is_available' => false]);
        return response()->json(['success' => true]);
    }
} 
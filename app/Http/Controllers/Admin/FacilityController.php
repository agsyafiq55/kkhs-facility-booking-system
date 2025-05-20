<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FacilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $facilities = Facility::latest()->paginate(10);
        return view('admin.facilities.index', compact('facilities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.facilities.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'capacity' => 'nullable|integer|min:1',
            'status' => 'required|string|in:available,maintenance,unavailable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'has_addons' => 'boolean',
            'has_sub_facilities' => 'boolean',
            'opening_time' => 'nullable|date_format:H:i',
            'closing_time' => 'nullable|date_format:H:i',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('facilities', 'public');
            $validated['image_path'] = $path;
        }

        $facility = Facility::create($validated);

        if ($facility->has_addons || $facility->has_sub_facilities) {
            return redirect()->route('admin.facilities.show', $facility)
                ->with('success', 'Facility created successfully. Please set up additional options.');
        }

        return redirect()->route('admin.facilities.index')
            ->with('success', 'Facility created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Facility $facility)
    {
        return view('admin.facilities.show', compact('facility'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Facility $facility)
    {
        return view('admin.facilities.edit', compact('facility'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Facility $facility)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'capacity' => 'nullable|integer|min:1',
            'status' => 'required|string|in:available,maintenance,unavailable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'has_addons' => 'boolean',
            'has_sub_facilities' => 'boolean',
            'opening_time' => 'nullable|date_format:H:i',
            'closing_time' => 'nullable|date_format:H:i',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($facility->image_path && Storage::disk('public')->exists($facility->image_path)) {
                Storage::disk('public')->delete($facility->image_path);
            }
            
            $path = $request->file('image')->store('facilities', 'public');
            $validated['image_path'] = $path;
        }

        $facility->update($validated);

        return redirect()->route('admin.facilities.index')
            ->with('success', 'Facility updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Facility $facility)
    {
        // Delete associated image if exists
        if ($facility->image_path && Storage::disk('public')->exists($facility->image_path)) {
            Storage::disk('public')->delete($facility->image_path);
        }

        $facility->delete();

        return redirect()->route('admin.facilities.index')
            ->with('success', 'Facility deleted successfully.');
    }
}

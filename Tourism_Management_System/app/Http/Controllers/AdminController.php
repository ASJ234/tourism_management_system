<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function dashboard()
    {
        $destinations = Destination::with('creator')->latest()->get();
        return view('admin.dashboard', compact('destinations'));
    }

    public function destinations()
    {
        $destinations = Destination::with('creator')->latest()->get();
        return view('admin.destinations.index', compact('destinations'));
    }

    public function createDestination()
    {
        return view('admin.destinations.create');
    }

    public function storeDestination(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'region' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $uploadPath = public_path('uploads/destinations');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/destinations'), $imageName);
        }

        $destination = Destination::create([
            'name' => $request->name,
            'description' => $request->description,
            'location' => $request->location,
            'region' => $request->region,
            'image_url' => 'uploads/destinations/' . $imageName,
            'created_by' => auth()->id(),
            'is_active' => $request->boolean('is_active', true)
        ]);

        return redirect()->route('admin.destinations')
            ->with('success', 'Destination created successfully.');
    }

    public function editDestination(Destination $destination)
    {
        return view('admin.destinations.edit', compact('destination'));
    }

    public function updateDestination(Request $request, Destination $destination)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'region' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($destination->image_url) {
                $oldImagePath = public_path($destination->image_url);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            
            $uploadPath = public_path('uploads/destinations');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/destinations'), $imageName);
            $imagePath = 'uploads/destinations/' . $imageName;
        } else {
            $imagePath = $destination->image_url;
        }

        $destination->update([
            'name' => $request->name,
            'description' => $request->description,
            'location' => $request->location,
            'region' => $request->region,
            'image_url' => $imagePath,
            'is_active' => $request->boolean('is_active', true)
        ]);

        return redirect()->route('admin.destinations')
            ->with('success', 'Destination updated successfully.');
    }

    public function deleteDestination(Destination $destination)
    {
        if ($destination->image_url) {
            $imagePath = public_path($destination->image_url);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        $destination->delete();
        
        return redirect()->route('admin.destinations')
            ->with('success', 'Destination deleted successfully.');
    }
} 
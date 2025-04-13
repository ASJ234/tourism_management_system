<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Destination;
use App\Models\Booking;
use App\Models\ActivityLog;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function dashboard()
    {
        $data = [
            'totalUsers' => User::count(),
            'totalDestinations' => Destination::count(),
            'totalBookings' => Booking::count(),
            'paidBookings' => Booking::where('payment_status', 'Paid')->count(),
            'pendingBookings' => Booking::where('payment_status', 'Unpaid')->count(),
            'recentActivities' => ActivityLog::with(['user' => function($query) {
                $query->select('user_id', 'full_name', 'username');
            }])->latest()->take(5)->get(),
        ];

        return view('admin.dashboard', $data);
    }

    public function users()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function destinations()
    {
        $destinations = Destination::with('creator')->latest()->paginate(10);
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
            'image' => 'required|image|mimes:jpeg,jpg,png|max:10240',
            'is_active' => 'boolean'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
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
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:10240',
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
            Storage::disk('public')->delete($destination->image_url);
        }
        
        $destination->delete();

        return redirect()->route('admin.destinations')
            ->with('success', 'Destination deleted successfully.');
    }

    public function allDestinationImages()
    {
        $destinations = Destination::with('images')->latest()->get();
        return view('admin.destinations.all-images', compact('destinations'));
    }

    public function manageDestinationImages(Destination $destination)
    {
        $images = $destination->images()->latest()->get();
        return view('admin.destinations.images', compact('destination', 'images'));
    }
    public function uploadDestinationImages(Request $request, Destination $destination)
    {
        \Log::info('Upload Request Data:', $request->all());
        
        try {
            // Validate the request
            $request->validate([
                'images' => 'required|array',
                'images.*' => 'required|image|mimes:jpeg,jpg,png|max:10240'
            ], [
                'images.required' => 'No images were uploaded.',
                'images.*.required' => 'Each image file is required.',
                'images.*.image' => 'The file must be a valid image.',
                'images.*.mimes' => 'Only JPEG, JPG, and PNG images are allowed.',
                'images.*.max' => 'Image size must not exceed 10MB.'
            ]);

            $uploadedImages = [];
    
            // Ensure upload directory exists
            $uploadPath = public_path('uploads/destinations');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
    
            // Process each uploaded image
            foreach ($request->file('images') as $image) {
                // Log file details for debugging
                \Log::info('Processing file:', [
                    'original_name' => $image->getClientOriginalName(),
                    'mime_type' => $image->getMimeType(),
                    'size' => $image->getSize(),
                    'extension' => $image->getClientOriginalExtension()
                ]);

                // Additional file validation
                if (!$image->isValid()) {
                    \Log::error('Invalid file upload', [
                        'original_name' => $image->getClientOriginalName(),
                        'error' => $image->getError()
                    ]);
                    continue;
                }
    
                // Generate unique filename
                $filename = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();
                
                // Move the image
                if ($image->move($uploadPath, $filename)) {
                    // Create image record
                    $destinationImage = $destination->images()->create([
                        'image_path' => 'uploads/destinations/' . $filename,
                        'is_primary' => false
                    ]);
    
                    $uploadedImages[] = $destinationImage;
                    \Log::info('File uploaded successfully:', ['filename' => $filename]);
                } else {
                    \Log::error('Failed to move file:', ['filename' => $filename]);
                }
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true, 
                    'message' => count($uploadedImages) . ' image(s) uploaded successfully',
                    'images' => $uploadedImages
                ]);
            }
    
            return back()->with('success', count($uploadedImages) . ' image(s) uploaded successfully');
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Upload Validation Errors:', [
                'errors' => $e->errors()
            ]);
    
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }

            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            \Log::error('Image Upload Exception: ', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
    
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Upload failed: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Upload failed: ' . $e->getMessage());
        }
    }

    public function deleteDestinationImage(Destination $destination, $imageId)
    {
        $image = $destination->images()->findOrFail($imageId);
        
        // Delete the physical file
        if (file_exists(public_path($image->image_path))) {
            unlink(public_path($image->image_path));
        }
        
        // Delete the database record
        $image->delete();

        return redirect()->back()->with('success', 'Image deleted successfully.');
    }

    public function services()
    {
        $services = Service::latest()->paginate(10);
        return view('admin.services.index', compact('services'));
    }

    public function reviews()
    {
        $reviews = Review::with(['user', 'reviewable'])->latest()->paginate(10);
        return view('admin.reviews.index', compact('reviews'));
    }

    public function activities()
    {
        $activities = ActivityLog::with('user')
            ->latest()
            ->paginate(20);
        return view('admin.activities.index', compact('activities'));
    }

    public function settings()
    {
        return view('admin.settings.index');
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string',
            'contact_email' => 'required|email',
            'contact_phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        // Update settings in the database
        foreach ($validated as $key => $value) {
            setting([$key => $value])->save();
        }

        return redirect()->route('admin.settings')
            ->with('success', 'Settings updated successfully.');
    }
} 
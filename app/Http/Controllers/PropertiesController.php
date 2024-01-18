<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\Gallery;
use App\Validators\PropertyValidators;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;



class PropertiesController extends Controller
{
    public function index(Request $request)
    {   
        $query = Property::query();

        // Apply filters as needed

        if ($request->filled('email')) {
            $query->where('city', $request->input('city'));
        }

        if ($request->filled('nofbrooms')) {
            $query->where('bedroom', $request->input('nofbrooms'));
        }

        if ($request->filled('noffloor')) {
            $query->where('floor_area', $request->input('noffloor'));
        }

        $query->orderBy('created_at', 'desc');

        $properties = $query->paginate(4); // You can adjust the number of items per page
  
       // dd($properties);
        return view('admin.property.index', compact('properties'));
    }


    public function show($id)
    {
        $user = Property::findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    public function create()
    {
        $roles = Property::all();
        return view('admin.property.add',compact('roles'));
    }


    public function store(Request $request)
    {
        $validator = PropertyValidators::validate('storeProperty', $request->all());

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            return redirect()->route('property.create')->withErrors($errors)->withInput();
        }

        $uniqueDFeaturedFileName = null;
        if ($request->hasFile('featured')) {
            $featuredImage = $request->file('featured');
            $uniqueFileName = Str::uuid() . '.' . $featuredImage->getClientOriginalExtension();
            $featuredImage->storeAs('assets/featured_images', $uniqueFileName, 'public'); // Adjust the storage path as needed
            $uniqueDFeaturedFileName =  $uniqueFileName;
        }

        $property = Property::create([
            'title' => $request->title,
            'price' => $request->price,
            'floor_area' => $request->floor_area,
            'bedroom' => $request->bedroom,
            'bathroom' => $request->bathroom,
            'city' => $request->city,
            'address' => $request->address,
            'description' => $request->description,
            'nearby_place' => $request->nearby_place,
            'featured_image' => $uniqueDFeaturedFileName,
            'created_by' => 1
        ]);

        $galleryImages = [];
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $galleryImage) {
                $uniqueFileName = Str::uuid() . '.' . $galleryImage->getClientOriginalExtension();
        
                $path = $galleryImage->storeAs('assets/gallery_images', $uniqueFileName, 'public'); // Adjust the storage path as needed
        
                // Store data in the gallery table
                $gallery = Gallery::create([
                    'property_id' => $property->id, // Assuming there is a 'property_id' column in the gallery table
                    'image_path' => $uniqueFileName, // Assuming there is an 'image_name' column in the gallery table
                ]);
        
                $galleryImages[] = $gallery;
            }
        }

        return redirect()->route('property.list')->with('success', 'Property added successfully.');
    }


    public function edit($id)
    { //dd($id);
        $property = Property::findOrFail($id);
        
        return view('admin.property.update', compact('property'));
    }

    public function update(Request $request, $id)
    {
        // Validate the request data
        $validator = PropertyValidators::validate('updateProperty', $request->all());

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            return redirect()->route('property.edit', ['id' => $id])->withErrors($errors)->withInput();
        }

        // Find the property by ID
        $property = Property::findOrFail($id);

        // Update the property data
        $property->update([
            'title' => $request->title,
            'price' => $request->price,
            'floor_area' => $request->floor_area,
            'bedroom' => $request->bedroom,
            'bathroom' => $request->bathroom,
            'city' => $request->city,
            'address' => $request->address,
            'description' => $request->description,
            'nearby_place' => $request->nearby_place,
        ]);

        // Update the featured image if a new one is provided
        if ($request->hasFile('featured')) {
            $featuredImage = $request->file('featured');
            $uniqueFileName = Str::uuid() . '.' . $featuredImage->getClientOriginalExtension();
            $featuredImage->storeAs('assets/featured_images', $uniqueFileName, 'public');
            // Delete the previous featured image (if any)
            if ($property->featured_image) {
                Storage::disk('public')->delete('assets/featured_images/' . $property->featured_image);
            }
            $property->update(['featured_image' => $uniqueFileName]);
        }

        // Update or create gallery images if new ones are provided
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $galleryImage) {
                $uniqueFileName = Str::uuid() . '.' . $galleryImage->getClientOriginalExtension();
                $path = $galleryImage->storeAs('assets/gallery_images', $uniqueFileName, 'public');
                // Create a new record in the gallery table
                $gallery = Gallery::create([
                    'property_id' => $property->id,
                    'image_path' => $uniqueFileName,
                ]);
            }
        }

        return redirect()->route('property.list')->with('success', 'Property updated successfully.');
    }

    public function destroy($id)
    {
        $property = Property::findOrFail($id);
        // Delete associated galleries
        $property->galleries()->delete();
        $property->delete();

        return redirect()->route('property.list')->with('success', 'Property deleted successfully.');
    }

    public function properties_list(){
        return view("landing.property_list");
    }

    public function property(){
        return view("landing.property");
    }
}

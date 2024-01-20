<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\Gallery;
use App\Validators\PropertyValidators;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PropertiesController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::query();

        // Apply filters as needed

        if ($request->filled('city')) {
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

        return view('admin.property.index', compact('properties'));
    }

    public function show($id)
    {
        $property = Property::findOrFail($id);
        return view('admin.property.show', compact('property'));
    }

    public function create()
    {
        return view('admin.property.add');
    }

    public function store(Request $request)
    {
        try {
            // Validation
            $validator = PropertyValidators::validate('storeProperty', $request->all());

            if ($validator->fails()) {
                $errors = $validator->errors()->toArray();
                return redirect()->route('property.create')->withErrors($errors)->withInput();
            }

            // Begin transaction
            DB::beginTransaction();

            // Featured Image
            $uniqueDFeaturedFileName = null;
            if ($request->hasFile('featured')) {
                $featuredImage = $request->file('featured');
                $uniqueFileName = Str::uuid() . '.' . $featuredImage->getClientOriginalExtension();
                $featuredImage->storeAs('assets/featured_images', $uniqueFileName, 'public');
                $uniqueDFeaturedFileName = $uniqueFileName;
            }

            // Create Property
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
                'created_by' => auth()->user()->id,
            ]);

            // Gallery Images
            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery') as $galleryImage) {
                    $uniqueFileName = Str::uuid() . '.' . $galleryImage->getClientOriginalExtension();
                    $galleryImage->storeAs('assets/gallery_images', $uniqueFileName, 'public');

                    // Store data in the gallery table
                    $gallery = new Gallery([
                        'property_id' => $property->id,
                        'image_path' => $uniqueFileName,
                    ]);

                    $gallery->save();
                }
            }

            // Commit the transaction
            DB::commit();

            return redirect()->route('property.list')->with('success', 'Property added successfully.');
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollback();
            return redirect()->route('property.create')->withErrors(['error' => 'An error occurred. Please try again.'])->withInput();
        }
    }

    public function edit($id)
    {
        $property = Property::findOrFail($id);
        return view('admin.property.update', compact('property'));
    }

    public function update(Request $request, $id)
    {
        try {
            // Validation
            $validator = PropertyValidators::validate('updateProperty', $request->all());

            if ($validator->fails()) {
                $errors = $validator->errors()->toArray();
                return redirect()->route('property.edit', ['id' => $id])->withErrors($errors)->withInput();
            }

            // Begin transaction
            DB::beginTransaction();

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
                    $galleryImage->storeAs('assets/gallery_images', $uniqueFileName, 'public');
                    // Create a new record in the gallery table
                    $gallery = new Gallery([
                        'property_id' => $property->id,
                        'image_path' => $uniqueFileName,
                    ]);

                    $gallery->save();
                }
            }

            // Commit the transaction
            DB::commit();

            return redirect()->route('property.list')->with('success', 'Property updated successfully.');
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollback();
            return redirect()->route('property.edit', ['id' => $id])->withErrors(['error' => 'An error occurred. Please try again.'])->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            // Begin transaction
            DB::beginTransaction();

            $property = Property::findOrFail($id);

            // Keep track of file paths to delete
            $filesToDelete = [];
            $featuredImagePath = $property->featured_image ? 'assets/featured_images/' . $property->featured_image :  null;

            // Delete associated galleries and note their files for deletion
            $galleries = $property->galleries;

            foreach ($galleries as $gallery) {
                // Note the file path for deletion
                $filesToDelete[] = 'assets/gallery_images/' . $gallery->image_path; // Adjust this according to your actual column name

                // Delete the gallery
                $gallery->delete();
            }

            $property->messages()->delete();
            $property->delete();
           

            // Commit the transaction
            DB::commit();

            // Delete associated files outside the database transaction
            foreach ($filesToDelete as $filePath) {
                if ($filePath) {
                    $this->deleteFile($filePath);
                }
            }

            $this->deleteFile($featuredImagePath);

            return redirect()->route('property.list')->with('success', 'Property deleted successfully.');
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollback();
            return redirect()->route('property.list')->withErrors(['error' => 'An error occurred. Please try again.']);
        }
    }

    public function propertiesList(Request $request)
    {
        $query = Property::query();

        // Apply filters
        if ($request->filled('property_name')) {
            $query->where('title', 'like', '%' . $request->input('property_name') . '%');
        }

        if ($request->filled('city')) {
            $query->where('city', $request->input('city'));
        }

        if ($request->filled('bedroom')) {
            $query->where('bedroom', $request->input('bedroom'));
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        // Paginate the results
        $properties = $query->paginate(10); // You can adjust the number of items per page as needed

        // Pass the properties and input data to the view
        return view('landing.property_list', compact('properties'))->with($request->all());
    }

    public function property($id)
    {
        // Retrieve the property with its galleries and messages
        $property = Property::with('galleries', 'messages', 'user')->find($id);

        // Pass the property to the view
        return view('landing.property', compact('property'));
    }

    function deleteFile($filePath)
    {
        //check is not null and not empty
        if (!empty($filePath)) {
            // Delete the file from storage
            Storage::disk('public')->delete($filePath);
        }
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\Gallery;
use App\Validators\PropertyValidators;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Traits\FileTrait;

class PropertiesController extends Controller
{
    use FileTrait;


    /**
     * The index function retrieves properties based on filters provided in the request and displays
     * them in a paginated view.
     * 
     * @param Request request The  parameter is an instance of the Request class, which
     * represents an HTTP request. It contains information about the request, such as the request
     * method, URL, headers, and input data.
     * 
     * @return a view called 'admin.property.index' and passing the 'properties' variable to the view.
     */
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

        $query->where('created_by', auth()->id());

        $query->orderBy('created_at', 'desc');

        $properties = $query->paginate(4); // You can adjust the number of items per page

        return view('admin.property.index', compact('properties'));
    }

    /**
     * The show function retrieves a property with the given ID and returns a view with the property
     * data.
     * 
     * @param id The "id" parameter is used to identify the specific property that needs to be shown.
     * It is typically an integer value that corresponds to the unique identifier of the property in
     * the database.
     * 
     * @return a view called 'admin.property.show' and passing the 'property' variable to the view.
     */
    public function show($id)
    {
        $property = Property::findOrFail($id);
        return view('admin.property.show', compact('property'));
    }

    /**
     * The create function returns a view for adding a new property in the admin panel.
     * 
     * @return a view called 'admin.property.add'.
     */
    public function create()
    {
        $this->authorize('create', Property::class);
        return view('admin.property.add');
    }

    /**
     * The above function is used to store a new property in the database, including its featured image
     * and gallery images, with validation and error handling.
     * 
     * @param Request request The  parameter is an instance of the Request class, which
     * contains all the data that was sent with the HTTP request. It is used to retrieve input data,
     * files, headers, and other information from the request. In this code, it is used to retrieve
     * form data and uploaded files.
     * 
     * @return a redirect response. If the validation fails, it redirects back to the property create
     * page with the validation errors and the input data. If the property is successfully created, it
     * redirects to the property list page with a success message. If an exception occurs, it redirects
     * back to the property create page with an error message.
     */
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

    /**
     * The edit function retrieves a property by its ID and returns a view for updating the property.
     * 
     * @param id The  parameter is the unique identifier of the property that needs to be edited. It
     * is used to retrieve the specific property from the database using the `findOrFail` method.
     * 
     * @return a view called 'admin.property.update' and passing the 'property' variable to the view.
     */
    public function edit($id)
    {
        $property = Property::findOrFail($id);
        return view('admin.property.update', compact('property'));
    }


    /**
     * The function updates a property's data, including its title, price, floor area, bedroom,
     * bathroom, city, address, description, and nearby places, as well as its featured image and
     * gallery images, and returns a success message or an error message if an exception occurs.
     * 
     * @param Request request The  parameter is an instance of the Request class, which
     * contains all the data sent with the HTTP request. It is used to retrieve the form data submitted
     * by the user.
     * @param id The  parameter is the ID of the property that needs to be updated. It is used to
     * find the property in the database and update its data.
     * 
     * @return a redirect response. If the validation fails, it redirects back to the property edit
     * page with the validation errors and the input data. If the update is successful, it redirects to
     * the property list page with a success message. If an error occurs, it redirects back to the
     * property edit page with an error message.
     */
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

    /**
     * The above function is used to delete a property and its associated galleries, messages, and
     * files, while also handling any errors that may occur.
     * 
     * @param id The id parameter is the unique identifier of the property that needs to be deleted. It
     * is used to find the property record in the database and delete it.
     * 
     * @return a redirect response to the "property.list" route with a success message if the property
     * is deleted successfully. If an exception occurs, it returns a redirect response to the
     * "property.list" route with an error message.
     */
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

    /**
     * The function retrieves a list of properties based on the provided filters and returns them to
     * the view along with the input data.
     * 
     * @param Request request The  parameter is an instance of the Request class, which
     * represents an HTTP request. It contains all the data and information about the current request,
     * such as the request method, URL, headers, and input data.
     * 
     * @return a view called 'landing.property_list' with the variables 'properties' and 'request'
     * passed to it.
     */
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

    /**
     * The function retrieves a property with its galleries, messages, and user, and passes it to the
     * view.
     * 
     * @param id The "id" parameter is the unique identifier of the property that you want to retrieve.
     * It is used to find the property in the database and fetch its associated galleries, messages,
     * and user.
     * 
     * @return a view called 'landing.property' and passing the 'property' variable to the view.
     */
    public function property($id)
    {
        // Retrieve the property with its galleries and messages
        $property = Property::with('galleries', 'messages', 'user')->find($id);

        // Pass the property to the view
        return view('landing.property', compact('property'));
    }

}

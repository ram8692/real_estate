<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gallery;
use App\Traits\FileTrait;

class GalleryController extends Controller
{
    use FileTrait;

    
    /**
     * The index function retrieves galleries for a specific property and displays them with
     * pagination.
     * 
     * @param property_id The property_id parameter is used to filter the galleries based on a specific
     * property. It is passed as an argument to the index function. The function retrieves all the
     * galleries that have a matching property_id value using the where method of the Gallery model.
     * The galleries are then paginated with 10 galleries per
     * 
     * @return a view called 'admin.gallery.index' and passing the 'galleries' variable to the view.
     */
    public function index($property_id)
    {
        // Get galleries for a specific property with pagination
        $galleries = Gallery::where('property_id', $property_id)->paginate(10);

        return view('admin.gallery.index', compact('galleries'));
    }

    
    /**
     * The function destroys a gallery by its ID, deletes the associated image file, and redirects back
     * with a success message.
     * 
     * @param gallery_id The parameter `` is the ID of the gallery that needs to be deleted.
     * 
     * @return a redirect back to the previous page with a success message.
     */
    public function destroy($gallery_id)
    {
        // Find the gallery by ID
        $gallery = Gallery::findOrFail($gallery_id);

        $galleryImagePath = $gallery->image_path ? 'assets/gallery_images/' . $gallery->image_path :  null;
        // Delete the gallery
        $gallery->delete();

       $this->deleteFile($galleryImagePath);

        return redirect()->back()->with('success', 'Gallery deleted successfully.');
    }
}

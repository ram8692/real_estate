<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gallery;

class GalleryController extends Controller
{
    public function index($property_id)
    {
        // Get galleries for a specific property with pagination
        $galleries = Gallery::where('property_id', $property_id)->paginate(PAGINATED_PER_PAGE);

        return view('admin.gallery.index', compact('galleries'));
    }

    public function destroy($gallery_id)
    {
    
        // Find the gallery by ID and delete it
        $gallery = Gallery::findOrFail($gallery_id);
        $gallery->delete();

        return redirect()->back()->with('success', 'Gallery deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gallery;

class GalleryController extends Controller
{
    /**
     * Display a listing of galleries for a specific property.
     *
     * @param  int  $property_id
     * @return \Illuminate\View\View
     */
    public function index($property_id)
    {
        // Get galleries for a specific property with pagination
        $galleries = Gallery::where('property_id', $property_id)->paginate(PAGINATED_PER_PAGE);

        return view('admin.gallery.index', compact('galleries'));
    }

    /**
     * Remove the specified gallery from storage.
     *
     * @param  int  $gallery_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($gallery_id)
    {
        // Find the gallery by ID
        $gallery = Gallery::findOrFail($gallery_id);

        // Delete the gallery
        $gallery->delete();

        return redirect()->back()->with('success', 'Gallery deleted successfully.');
    }
}

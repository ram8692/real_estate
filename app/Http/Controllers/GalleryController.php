<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gallery;
use App\Traits\FileTrait;

class GalleryController extends Controller
{
    use FileTrait;

    /**
     * Display a listing of galleries for a specific property.
     *
     * @param  int  $property_id
     * @return \Illuminate\View\View
     */
    public function index($property_id)
    {
        // Get galleries for a specific property with pagination
        $galleries = Gallery::where('property_id', $property_id)->paginate(10);

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

        $galleryImagePath = $gallery->image_path ? 'assets/gallery_images/' . $gallery->image_path :  null;
        // Delete the gallery
        $gallery->delete();

       $this->deleteFile($galleryImagePath);

        return redirect()->back()->with('success', 'Gallery deleted successfully.');
    }
}

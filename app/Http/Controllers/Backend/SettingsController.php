<?php

namespace App\Http\Controllers\Backend;

use Auth;
use Intervention\Image\Facades\Image;

use App\Models\SiteInfo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:update website information')->only('edit');
    }
    public function edit(Request $request) {
        $site_info = SiteInfo::first();

        if ($request->isMethod('post')) {
            $this->validate($request, [
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'email' => 'required|email|max:255',
                'address' => 'required|string',
                'short_about' => 'nullable|string',
                'currency' => 'nullable|string',
                'map_embed' => 'nullable|string',
                'return_policy' => 'nullable|string',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image type and size
                'print_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'fav_icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,ico|max:1024'
            ]);

            // Updating site info fields
            $site_info->name = $request->name;
            $site_info->phone = $request->phone;
            $site_info->email = $request->email;
            $site_info->currency = $request->currency;
            $site_info->address = $request->address;
            $site_info->short_about = $request->short_about;
            $site_info->map_embed = $request->map_embed;
            $site_info->return_policy = $request->return_policy;
            $site_info->barcode_height = $request->barcode_height;
            $site_info->barcode_width = $request->barcode_width;
            $site_info->user_id = Auth::user()->id;

            // Handle file uploads
            $site_info->logo = $this->handleImageUpload($request, 'logo', $site_info->logo, 180, 56);
            $site_info->print_logo = $this->handleImageUpload($request, 'print_logo', $site_info->print_logo, 120, 120);
            $site_info->fav_icon = $this->handleImageUpload($request, 'fav_icon', $site_info->fav_icon, 16, 16);

            // Save updated information
            $site_info->save();

            return redirect(route('site-info'))->with('flash_success', '
                <script>
                Toast.fire({
                  icon: `success`,
                  title: `Site information successfully updated`
                })
                </script>
            ');
        }

        return view("settings.site_info", compact('site_info'));
    }

    // Helper function to handle image uploads
    protected function handleImageUpload(Request $request, $fieldName, $oldFileName, $width, $height) {
        if ($request->hasFile($fieldName)) {
            // Delete old file if exists
            if (file_exists('images/logo/'.$oldFileName) && !empty($oldFileName)) {
                @unlink('images/logo/'.$oldFileName);
            }

            // Get file extension
            $extension = strtolower($request->file($fieldName)->getClientOriginalExtension());

            // Generate new file name
            $file_name = $fieldName . '_' . time() . '.' . $extension;

            // Resize and save the new image
            $image = Image::make($request->file($fieldName))->resize($width, $height);
            $image->save('images/logo/' . $file_name);

            return $file_name;
        }

        return $oldFileName; // Return the old file name if no new file was uploaded
    }

}

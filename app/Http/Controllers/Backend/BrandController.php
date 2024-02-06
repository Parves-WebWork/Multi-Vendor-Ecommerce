<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use Image;

class BrandController extends Controller
{
    public function AllBrand()
    {
        $brands = Brand::latest()->get();
        return view('backend.brand.brand_all', compact('brands'));
    }

    public function AddBrand()
    {
        return view('backend.brand.brand_add');
    }

    public function StoreBrand(Request $request)
    {
        $image = $request->file('brand_imag');
        $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
        Image::make($image)->resize(300, 300)->save('upload/brand/' . $name_gen);

        $save_url = 'upload/brand/' . $name_gen;

        Brand::insert([
            'brand_name' => $request->brand_name,
            'brand_slug' => strtolower(str_replace(' ', '_', $request->brand_name)),
            'brand_imag' => $save_url,
        ]);

        $notification = array(
            'message' => 'Brand Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.brand')->with($notification);
    }

    public function EditBrand($id){

        $brand = Brand::findOrFail($id);

        return view('backend.brand.brand_edit',compact('brand'));
    }



    public function UpdateBrand(Request $request)
    {
        $brand_id = $request->id;
        $old_img = $request->old_image;

        if ($request->hasFile('brand_imag')) {
            $image = $request->file('brand_imag');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            Image::make($image)->resize(300, 300)->save('upload/brand/' . $name_gen);

            if (file_exists($old_img) && !str_contains($old_img, 'no_image.jpg')) {
                unlink($old_img);
            }

            $save_url = 'upload/brand/' . $name_gen;

            Brand::findOrFail($brand_id)->update([
                'brand_name' => $request->brand_name,
                'brand_slug' => strtolower(str_replace(' ', '_', $request->brand_name)),
                'brand_imag' => $save_url,
            ]);

            $notification = array(
                'message' => 'Brand Update with image Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.brand')->with($notification);
        } else {
            // If no new image is provided, update other fields but keep the existing image
            Brand::findOrFail($brand_id)->update([
                'brand_name' => $request->brand_name,
                'brand_slug' => strtolower(str_replace(' ', '_', $request->brand_name)),
            ]);

            $notification = array(
                'message' => 'Brand Update without image Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.brand')->with($notification);
        }
    }


    public function DeletBrand($id)
    {
        $brand = Brand::findOrFail($id);
        $img = $brand->brand_imag;

        if (file_exists($img) && !str_contains($img, 'no_image.jpg')) {
            unlink($img);
        }

        Brand::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Brand Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
}

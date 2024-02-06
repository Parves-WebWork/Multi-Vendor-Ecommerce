<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Image;

class CategoryController extends Controller
{
    public function AllCategory()
    {
        $categorys = Category::latest()->get();
        return view('backend.category.all_category', compact('categorys'));
    }

    public function AddCategory()
    {
        return view('backend.category.add_category');
    }


    public function StoreCategory(Request $request)
    {
        $image = $request->file('category_imag');
        $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
        Image::make($image)->resize(120, 120)->save('upload/Category/' . $name_gen);

        $save_url = 'upload/Category/' . $name_gen;

        Category::insert([
            'category_name' => $request->category_name,
            'category_slug' => strtolower(str_replace(' ', '_', $request->category_name)),
            'category_imag' => $save_url,
        ]);

        $notification = array(
            'message' => 'Category Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.category')->with($notification);
    }


    public function EditCategory($id){

        $category =  Category::findOrFail($id);

        return view('backend.category.category_edit',compact('category'));
    }


    public function UpdateCategory(Request $request)
    {
        $category_id = $request->id;
        $old_img = $request->old_image;

        if ($request->hasFile('category_imag')) {
            $image = $request->file('category_imag');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            Image::make($image)->resize(300, 300)->save('upload/Category/' . $name_gen);

            if (file_exists($old_img) && !str_contains($old_img, 'no_image.jpg')) {
                unlink($old_img);
            }

            $save_url = 'upload/Category/' . $name_gen;

            Category::findOrFail($category_id)->update([
                'category_name' => $request->category_name,
                'category_slug' => strtolower(str_replace(' ', '_', $request->category_name)),
                'category_imag' => $save_url,
            ]);

            $notification = array(
                'message' => 'Category Update with image Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.category')->with($notification);
        } else {
            // If no new image is provided, update other fields but keep the existing image
            Category::findOrFail($category_id)->update([
                'category_name' => $request->category_name,
                'category_slug' => strtolower(str_replace(' ', '_', $request->category_name)),
            ]);

            $notification = array(
                'message' => 'Brand Update without image Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.category')->with($notification);
        }
    }

    public function DeleteCategory($id)
    {
        $category =  Category::findOrFail($id);
        $img = $category->category_imag;

        if (file_exists($img) && !str_contains($img, 'no_image.jpg')) {
            unlink($img);
        }

        Category::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Category Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

}

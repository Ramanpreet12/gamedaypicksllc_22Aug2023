<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ProductSize;
use Illuminate\Http\Request;
use App\Models\ProductImage;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Http\Requests\ProductRequest;
use DB;
use Illuminate\Foundation\Bootstrap\HandleExceptions;
use App\Exceptions\Handler;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $get_products = Product::with('product_images')->orderBy('id', 'DESC')->get();

        return view('backend.products.index', compact('get_products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        DB::beginTransaction();

        try {
            $product = Product::create([
                'product_name' => $request->product_name,
                'product_url' => $request->product_url,
                'product_type' => $request->product_type,
                // 'price' =>  $request->product_price,
                'description' => $request->description,
                'product_details' => $request->product_details,
                'product_color' => $request->product_color,
                'status' => 'inactive',
            ]);

            // Extensions to check
            $allowedfileExtension = ['jpeg', 'jpg', 'png', 'gif', 'webp', 'svg'];

            if ($request->hasFile('product_image')) {
                $product_images = $request->file('product_image');

                foreach ($product_images as $key => $product_image) {
                    $extension = $product_image->getClientOriginalExtension();
                    $check = in_array($extension, $allowedfileExtension);
                    // Checking the image extension
                    if (!$check) {
                        DB::rollback();
                        return redirect()->back()->with('message_error', 'Images must be jpeg, jpg, png, gif, webp, svg!');
                    }

                    $product_image_name = rand(1111, 9999) . '-' . $product_image->getClientOriginalName();
                    $product_image_file_name = str_replace(" ", "-", $product_image_name);
                    $product_image->storeAs('public/images/products/', $product_image_file_name);

                    if ($key == 0) {
                        $sortvalue = '1';
                    } else {
                        $sortvalue = '0';
                    }
                    // Insert images in product images table
                    $create_product_image = ProductImage::create([
                        'product_id' => $product->id,
                        'image_name' => $product_image_file_name,
                        'image_sort' => $sortvalue,
                        // 'status' =>'active'
                    ]);
                }
            }

            if ($create_product_image->id > 0) {
                DB::commit();
            } else {
                DB::rollback();
            }
            return redirect('admin/products/add-variants/' . $product->id)->with('success', 'Product added successfully');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('message_error', "Something went wrong!");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::with('product_images')->find($id);
        $get_product_variations = ProductVariation::where('product_id', $id)->with('productSize')->orderBy('id', 'desc')->get();
        $get_variation_count = ProductVariation::where('product_id', $id)->get()->count();

        return view('backend.products.edit', compact('product', 'get_product_variations' , 'get_variation_count'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
    {
        try {

            $product = Product::where('id', $id)->update([
                'product_name' => $request->product_name,
                'product_url' => $request->product_url,
                'product_type' => $request->product_type,
                // 'price' =>  $request->product_price,
                'description' => $request->description,
                'product_details' => $request->product_details,
                'product_color' => $request->product_color,
                'status' => $request->status,
            ]);
            // Extensions to check
            $allowedfileExtension = ['jpeg', 'jpg', 'png', 'gif', 'webp', 'svg'];


            if ($request->hasFile('product_image')) {
                $product_images = $request->file('product_image');
                foreach ($product_images as $key => $product_image) {
                    $extension = $product_image->getClientOriginalExtension();
                    $check = in_array($extension, $allowedfileExtension);
                    // Checking the image extension
                    if (!$check) {
                        return redirect()->back()->with('message_error', 'Images must be jpeg, jpg, png, gif, webp, svg!');
                    }

                    $product_image_name = rand(1111, 9999) . '-' . $product_image->getClientOriginalName();
                    $product_image_file_name = str_replace(" ", "-", $product_image_name);
                    $product_image->storeAs('public/images/products/', $product_image_file_name);


                    // get the number of images coming in request (array count)
                    if (count($product_images) == 1) {
                        $sortvalue = '1';
                        ProductImage::where('product_id', '=', $id)->update([
                            'image_sort' => '0'
                        ]);
                        ProductImage::where('id', $request->product_images)->update([
                            'image_sort' => '1'
                        ]);

                    } elseif (count($product_images) > 1) {
                        if ($key == 0) {
                            ProductImage::where('product_id', '=', $id)->update([
                                'image_sort' => '0'
                            ]);
                            $sortvalue = '1';
                        } else {
                            $sortvalue = '0';
                        }
                    }

                    // Insert images in product images table
                    $create_product_image = ProductImage::create([
                        'product_id' => $id,
                        'image_name' => $product_image_file_name,
                        'image_sort' => $sortvalue,
                        // 'status' =>'active'
                    ]);
                }

                return redirect()->back()->with('success', 'Product Images updated successfully');
            }

            // select image
            ProductImage::where('product_id', '=', $id)->update([
                'image_sort' => '0'
            ]);

            ProductImage::where('id', $request->image_check)->update([
                'image_sort' => '1'
            ]);


            return redirect()->route('products.index')->with('success', 'Product updated successfully');


        } catch (ErrorException $e) {

            return redirect()->back()->with('message_error', $e->getMessage());
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function deleteProducts($id)
    {
        $product = Product::find($id)->delete();
        if ($product) {
            return redirect()->route('products.index')->with('success', 'Product deleted successfully');
        } else {
            return redirect()->route('products.index')->with('message_error', 'Something went wrong');
        }

    }

    public function deleteProductImage($id)
    {

        //get Image
        $product_image = ProductImage::whereId($id)->first();

        // check if user is trying to delete the selected image with image_Sort value 1 (front image)

        if ($product_image->image_sort == 1) {
            return redirect()->back()->with('message_error', "Can't delete the Index image ! First make another image as index image then delete it.");
        }

        $Images_path = storage_path('app/public/images/products/');

        // // delete image folder
        unlink_image_video_from_db($Images_path, $product_image->image_name);

        // delete from db
        ProductImage::whereId($id)->delete();
        return redirect()->back()->with('success', 'Product Image deleted successfully');
    }



    // add variants
    public function addVariant(Request $request, $id)
    {

        if ($request->isMethod('post')) {
            $count_size = ProductVariation::where(['product_id' => $id, 'size_id' => $request->size_id,])->count();

            if ($count_size > 0) {
                return redirect()->back()->with('message_error', 'Size already exists! Please try another size.');
            }
            $product_variation = ProductVariation::create([
                'product_id' => $id,
                'size_id' => $request->size_id,
                'product_price' => $request->product_price,
                'product_quantity' => $request->product_quantity,
                'status' => $request->status
            ]);
            if ($product_variation->id > 0) {
                // update product status
                Product::where('id', $id)->update(['status' => 'active']);
            }
            return redirect()->back()->with('success', 'Product Variations Added Successfully');
        } else {

            $get_product = Product::find($id);
            $get_sizes = ProductSize::orderBy('id', 'desc')->get();
            $get_variation_count = ProductVariation::where('product_id', $id)->get()->count();

            return view('backend.products.add_variants', compact('get_product', 'get_sizes', 'id', 'get_variation_count'));
        }

    }

    public function editVariant(Request $request, $product_id, $variant_id)
    {

        if ($request->isMethod('put')) {
            ProductVariation::where(['product_id' => $product_id, 'id' => $variant_id])->update([
                'product_id' => $product_id,
                'size_id' => $request->size_id,
                'product_price' => $request->product_price,
                'product_quantity' => $request->product_quantity,
                'status' => $request->status
            ]);
            return redirect()->back()->with('success', 'Product Variations updated Successfully');

        } else {


            $get_product_variant = ProductVariation::where('product_id', $product_id)->find($variant_id);

            $get_sizes = ProductSize::orderBy('id', 'desc')->get();
            return view('backend.products.edit_variants', compact('get_product_variant', 'get_sizes', 'product_id', 'variant_id' ));
        }


    }


    public function deleteProductVariant($variant_id)
    {



        $product_variation = ProductVariation::find($variant_id);
        $get_variation_count = ProductVariation::where('product_id', $product_variation->product_id, )->get()->count();

        if ($get_variation_count == 1) {
            $product_variation->delete();
            // update product status
            Product::where('id', $product_variation->product_id)->update(['status' => 'inactive']);
            return redirect()->back()->with('message_error', 'This Product has been deactivated as there is no variation ! Please add at least one variation.');
        } else {
            $product_variation->delete();
        }

        // dd($product_variation);
        if ($product_variation) {
            return redirect()->back()->with('success', 'Product Variation deleted successfully');
        } else {
            return redirect()->back()->with('message_error', 'Something went wrong');
        }

    }


}

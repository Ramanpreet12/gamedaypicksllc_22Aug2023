<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use Validator;
use Storage;
use App\Http\Requests\BannerRequest;


class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banners = Banner::get();
        return view('backend.site_setting.banner.index' , compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.site_setting.banner.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BannerRequest $request)
    {
         if($request->isMethod('post')) {
                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $filename = "banner_".time().'.'.$image->getClientOriginalExtension();
                    $success = $image->storeAs('public/images/banners/' , $filename);
                    if (!isset($success)) {
                        return back()->withError('Could not upload Banner');
                    }
                }
                $banners = new Banner;
                $banners->heading  = $request->heading;
                $banners->image    = $filename;
                $banners->serial   = $request->serial;
                $banners->status   = $request->status;
                $banners->save();
                return redirect('admin/banner')->with('success' , 'Banner added successfully');
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
        $banners = Banner::find($id);
        return view('backend.site_setting.banner.edit', compact('banners'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BannerRequest $request , $id)
    {
        if ($request->isMethod('put')) {
            $data = array();
                $image     =   $request->file('image');
                if ($image) {
                    $extension =   $image->getClientOriginalExtension();
                    $filename  =   'banner_'.time() . '.' . $extension;
                    $success = $image->storeAs('public/images/banners/' , $filename);
                    if (!isset($success)) {
                        return back()->withError('Could not upload Banner');
                    }
                    $data["image"]=$filename;
                }

                $data["heading"]=$request->heading;
                $data["serial"]=$request->serial;
                $data["status"]=$request->status;
                $result=Banner::where('id',$id)->update($data);
                return redirect('admin/banner')->with('success' , 'Banner updated successfully');;
            }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function destroy($id)
    // {
    //     //
    // }
    public function destroy($id)
    {

            //$banners   = Banners::find($request->id);
            // $file_path = public_path().'/front/images/banners/'.$banners->image;
            // if (file_exists($file_path)) {
            //     unlink($file_path);
            // }
            Banner::find($id)->delete();
        return redirect('admin/banner')->with('success' , 'Banner deleted successfully');;
    }
}

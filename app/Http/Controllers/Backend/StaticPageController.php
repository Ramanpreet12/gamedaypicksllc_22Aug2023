<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StaticPage;

class StaticPageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function contactPage (Request $request)
    {
        if ($request->isMethod('put')) {
            $data = array();
            $image_file     =   $request->file('image');
            if ($image_file) {
                $image_filename = $image_file->getClientOriginalName();
                $success = $image_file->storeAs('public/images/static_page/' , $image_filename);
                if (!isset($success)) {
                    return back()->withError('Could not upload logo');
                }
                $data["images"]=$image_filename;
            }

                $data["heading"]=$request->heading;
                $data["sub_heading"]=$request->sub_heading;
                $data["content"]=$request->content;
                $data["status"]=$request->status;
                $data["type"]="contact";
                $result=StaticPage::where('type','contact')->update($data);
                return redirect()->back()->with('success' , 'Contact Page updated successfully');;
            }
        else {
            $contact_page_details = StaticPage::where('type' , 'contact')->first();

            return view('backend.site_setting.contactPage' , compact('contact_page_details'));
        }


    }

    public function aboutPage (Request $request)
    {
        if ($request->isMethod('put')) {
            $data = array();
            $image_file     =   $request->file('image');
            if ($image_file) {
                $image_filename = $image_file->getClientOriginalName();
                $success = $image_file->storeAs('public/images/static_page/' , $image_filename);
                if (!isset($success)) {
                    return back()->withError('Could not upload logo');
                }
                $data["images"]=$image_filename;
            }

                $data["heading"]=$request->heading;
                $data["sub_heading"]=$request->sub_heading;
                $data["content"]=$request->content;
                $data["status"]=$request->status;
                $data["type"]="about";
                $result=StaticPage::where('type','about')->update($data);
                return redirect()->back()->with('success' , 'About Page updated successfully');;
            }
        else {
            $about_page_details = StaticPage::where('type' , 'about')->first();


            return view('backend.site_setting.aboutPage' , compact('about_page_details'));
        }


        
    }


    public function privacyPage (Request $request)
    {
        if ($request->isMethod('put')) {
            $data = array();
                $data["heading"]=$request->heading;
                $data["sub_heading"]=$request->sub_heading;
                $data["content"]=$request->content;
                $data["status"]=$request->status;
                $data["type"]="privacy";
                $result=StaticPage::where('type','privacy')->update($data);
                return redirect()->back()->with('success' , 'Privacy Page updated successfully');;
            }
        else {
            $privacy_page_details = StaticPage::where('type' , 'privacy')->first();


            return view('backend.site_setting.privacyPage' , compact('privacy_page_details'));
        }


    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
}

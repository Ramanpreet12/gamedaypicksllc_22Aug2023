<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reviews;
use App\Models\SectionHeading;

class ReviewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function section_heading(Request $request)
    {
        if ($request->isMethod('post')) {
                    $request->validate(['section_heading'=> 'required']);
                    if ($request->section_heading) {
                        SectionHeading::where('name' , 'Reviews')->update([
                            'value' => $request->section_heading,
                        ]);
                    }
                    else{
                        SectionHeading::where('name' , 'Reviews')->update([
                            'value' => 'Reviews'
                        ]);
                    }

        return redirect('admin/reviews')->with('success_msg' , 'Reviews Title updated successfully');
        }
    }

    public function index()
    {
        $get_reviews = Reviews::get();
        $reviewsHeading = SectionHeading::where('name' , 'Reviews')->first();
        return view('backend.reviews.index' , compact('get_reviews' ,'reviewsHeading'));
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
        $review = Reviews::where('id' , $id)->first();
        return view('backend.reviews.edit' , compact('review'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request , $id)
    {
        if ($request->isMethod('put')) {

                $review =Reviews::where('id',$id)->update(['status' => $request->status]);
                return redirect()->route('reviews.index')->with('success_msg' , 'Reviews status updated successfully');;
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
        $review =  Reviews::find($id)->delete();
        if($review){
         return redirect()->route('reviews.index')->with('success_msg', 'Reviews deleted successfully');
        }else{
         return redirect()->route('reviews.index')->with('error_msg', 'Something went wrong');
        }
    }
}

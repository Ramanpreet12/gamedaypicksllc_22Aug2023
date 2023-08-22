<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\HomeSetting;
use App\Models\Vacation;
use App\Models\News;
use App\Models\SectionHeading;
use Illuminate\Support\Facades\Validator;
use Storage;
use App\Http\Requests\VacationPacRequest;


class VacationController extends Controller
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
                SectionHeading::where('name' , 'Vacation')->update([
                    'value' => $request->section_heading,
                ]);
            }
            else{
                SectionHeading::where('name' , 'Vacation')->update([
                    'value' => 'Vacation'
                ]);
            }

                    return redirect()->route('vacation.index')->with('message_success' , 'Video title updated successfully');
        }
    }

    public function index()
    {
        $VacationHeading = SectionHeading::where('name' , 'Vacation')->first();
        $vacations = Vacation::get();
        return view('backend.site_setting.vacationPac.index' , compact('VacationHeading' , 'vacations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.site_setting.vacationPac.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VacationPacRequest $request)
    {
        if($request->isMethod('post')) {
            if ($request->hasFile('image_video')) {
                $image_video = $request->file('image_video');
                $image_video_filename = $image_video->getClientOriginalName();
                $success = $image_video->storeAs('public/images/vacation/' , $image_video_filename);
                if (!isset($success)) {
                    return back()->withError('Could not upload Banner');
                }
            }

            $vacation = new Vacation;
            $vacation->title  = $request->title;
            $vacation->image_video    = $image_video_filename;
            $vacation->serial   = $request->serial;
            $vacation->status   = $request->status;
            $vacation->save();
            return redirect('admin/vacation')->with('message_success' , 'Vacation Pac added successfully');
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
       $vacation = Vacation::find($id);
        return view('backend.site_setting.vacationPac.edit', compact('vacation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(VacationPacRequest $request, $id)
    {
        if ($request->isMethod('put')) {
            $data = array();
                $image_video     =   $request->file('image_video');
                if ($image_video) {
                    $image_video_filename =   $image_video->getClientOriginalName();
                    // $image_video_filename  =   'banner_'.time() . '.' . $extension;
                    $success = $image_video->storeAs('public/images/vacation/' , $image_video_filename);
                    if (!isset($success)) {
                        return back()->withError('Could not upload Banner');
                    }
                    $data["image_video"]=$image_video_filename;
                }

                $data["title"]=$request->title;
                $data["serial"]=$request->serial;
                $data["status"]=$request->status;
                $result=Vacation::where('id',$id)->update($data);
                return redirect('admin/vacation')->with('message_success' , 'Vacation Pack updated successfully');;
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
        Vacation::find($id)->delete();
        return redirect('admin/vacation')->with('message_success' , 'Vacation Pac deleted successfully');;
    }
}

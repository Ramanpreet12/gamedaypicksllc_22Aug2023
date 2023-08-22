<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GeneralSetting;
// use Illuminate\Validation\Rule;
use Validator;

class GeneralSettingController extends Controller
{
    public function contactPage(Request $request) {
        if ($request->isMethod('put')) {
            $rules = array(
                'contact_section_heading'      => 'required',
                'contact_location_heading'     => 'required',
                'contact_page_content'         => 'required',
                'contact_form_heading'         => 'required',
                'contact_social_links_heading' => 'required',
                // 'contact_page_image'           => 'required',
            );

        $fieldNames = array(
                'contact_section_heading'       => 'Page Heading',
                'contact_location_heading'      => 'Location Heading',
                'contact_page_content'          => 'Content',
                'contact_form_heading'          => 'Enquiry Form Heading',
                'contact_social_links_heading'  => 'Social Links Heading',
                // 'contact_page_image'            => 'Image',
             );

        $validator = Validator::make($request->all(), $rules);
        $validator->setAttributeNames($fieldNames);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }else{
            $image_file     =   $request->file('contact_page_image');
            if ($image_file) {
                $image_filename = $image_file->getClientOriginalName();
                $success = $image_file->storeAs('public/images/static_page/' , $image_filename);
                if (!isset($success)) {
                    return back()->withError('Could not upload logo');
                }
                // $data["contact_page_image"]=$image_filename;
                GeneralSetting::where(['name' => 'contact_page_image'])->update(['value' => $image_filename]);
            }
            GeneralSetting::where(['name' => 'contact_section_heading'])->update(['value' => $request->contact_section_heading]);
            GeneralSetting::where(['name' => 'contact_location_heading'])->update(['value' => $request->contact_location_heading]);
            GeneralSetting::where(['name' => 'contact_page_content'])->update(['value' => $request->contact_page_content]);
            GeneralSetting::where(['name' => 'contact_form_heading'])->update(['value' => $request->contact_form_heading]);
            GeneralSetting::where(['name' => 'contact_social_links_heading'])->update(['value' => $request->contact_social_links_heading]);
                return redirect()->back()->with('success' , 'Contact Page updated successfully');
            }
        }
        else {
            $get_contact_details = GeneralSetting::where('type', 'contactPage')->get()->toArray();
            $contact_details = key_value('name', 'value', $get_contact_details);

            return view('backend.site_setting.contactPage' ,compact('contact_details'));
        }
    }


    public function match_fixture(Request $request) {

            $get_match_fixture_details = GeneralSetting::where('type', 'matchFixture')->get()->toArray();
            $match_fixture_details = key_value('name', 'value', $get_match_fixture_details);

            return view('backend.site_setting.matchFixture' , compact('match_fixture_details') );

    }

    public function match_fixture_edit(Request $request) {


            $rules = array(
                'match_fixture_section_heading'         => 'required',
                'match_fixture_selected_season_heading' => 'required',
                'match_fixture_select_season_heading'   => 'required',
                'match_fixture_selected_week_heading'   => 'required',
                'match_fixture_select_week_heading'     => 'required',
            );

            $fieldNames = array(
                'match_fixture_section_heading'         => 'Page Heading',
                'match_fixture_selected_season_heading' => 'Selected Season Heading',
                'match_fixture_select_season_heading'   => 'Select Season',
                'match_fixture_selected_week_heading'   => 'Selected Week',
                'match_fixture_select_week_heading'     => 'Select Week',
             );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($fieldNames);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }else{
            GeneralSetting::where(['name' => 'match_fixture_section_heading' , 'type' => 'matchFixture'])->update(['value' => $request->match_fixture_section_heading]);
            GeneralSetting::where(['name' => 'match_fixture_selected_season_heading' , 'type' => 'matchFixture'])->update(['value' => $request->match_fixture_selected_season_heading]);
            GeneralSetting::where(['name' => 'match_fixture_select_season_heading' , 'type' => 'matchFixture'])->update(['value' => $request->match_fixture_select_season_heading]);
            GeneralSetting::where(['name' => 'match_fixture_selected_week_heading' , 'type' => 'matchFixture'])->update(['value' => $request->match_fixture_selected_week_heading]);
            GeneralSetting::where(['name' => 'match_fixture_select_week_heading' , 'type' => 'matchFixture'])->update(['value' => $request->match_fixture_select_week_heading]);
                return redirect()->back()->with('success' , 'Match Fixture updated successfully');
            }

    }

    public function landing_count(Request $request) {
        if ($request->isMethod('put')) {
            $rules = array(
                'google_count'      => 'required',
                'facebook_count'      => 'required',

            );

        $fieldNames = array(
                'google_count'       => 'Google Count',
                'facebook_count'      => 'Facebook Count',

             );

        $validator = Validator::make($request->all(), $rules);
        $validator->setAttributeNames($fieldNames);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }else{

            GeneralSetting::where(['name' => 'google_count' , 'type' => 'landing_count'])->update(['value' => $request->google_count]);
            GeneralSetting::where(['name' => 'facebook_count' , 'type' => 'landing_count'])->update(['value' => $request->facebook_count]);
            return redirect()->back()->with('success' , 'Landing counts updated successfully');
        }
        }
        else{
            $get_landing_counts = GeneralSetting::where('type', 'landing_count')->get()->toArray();
            $landing_counts = key_value('name', 'value', $get_landing_counts);
            return view('backend.site_setting.landing_count' , compact('landing_counts'));
        }


    }

}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BannerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (request()->ismethod('post')) {
           $rules = [
            'heading' => 'required',
             'image' => 'required',
            'status' => 'required',
            'serial' => 'required'
           ];
        }
        elseif(request()->isMethod('put')){
            $rules = [
                'heading' => 'required',
                'status' => 'required',
                'serial' => 'required'
               ];
        }
        return $rules;
    }

    public function attributes()
    {
       return [
        'heading' => 'Heading',
        'image' => 'Image',
        'status' => 'Status',
        'serial' => 'Serial'
       ];

    }
}

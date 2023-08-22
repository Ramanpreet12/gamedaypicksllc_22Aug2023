<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VacationPacRequest extends FormRequest
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
             'title' => 'required',
              'image_video' => 'required',
             'status' => 'required',
             'serial' => 'required'
            ];
         }
         elseif(request()->isMethod('put')){
             $rules = [
                 'title' => 'required',
                 'status' => 'required',
                 'serial' => 'required'
                ];
         }
         return $rules;
    }
    public function attributes()
    {
       return [
        'title' => 'Title',
        'image' => 'Image or Video',
        'status' => 'Status',
        'serial' => 'Serial'
       ];

    }
}

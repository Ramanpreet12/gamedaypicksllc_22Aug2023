<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewsRequest extends FormRequest
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
              'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg,webp',
             'status' => 'required',
             'header' => 'required'
            ];
         }
         elseif(request()->isMethod('put')){
             $rules = [
                 'title' => 'required',
                 'image' => 'image|mimes:jpg,png,jpeg,gif,svg,webp',
                 'status' => 'required',
                 'header' => 'required'
                ];
         }
         return $rules;
    }
    public function attributes()
    {
       return [
        'title' => 'Title',
        'image' => 'Image',
        'status' => 'Status',
        'header' => 'Header'

       ];

    }
    }

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
             'product_name' => 'required',
              'product_image' => 'required',
            //   'product_image.*' => 'required|image|Mimes:jpeg,jpg,gif,png,webp,svg',
            //  'status' => 'required',
            //  'product_price' => 'required',
             'product_url' => 'required',
             'product_type' => 'required',
            //  'product_type' => 'required',
            ];
         }
         elseif(request()->isMethod('put')){
             $rules = [
                 'product_name' => 'required',
                //  'product_image.*' =>  'image|Mimes:jpeg,jpg,gif,png,webp,svg',
                //  'status' => 'required',
                //  'product_price' => 'required',
                 'product_url' => 'required',
                 'product_type' => 'required',
                //  'image_check' => 'required',
                ];
         }
         return $rules;
    }

    // public function messages()
    // {
    //    return [
    //     'product_image.*.Mimes' => 'Images with jpeg, jpg, gif, png, webp, svg extensions are acceptable',
    //    ];

    // }
}

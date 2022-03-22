<?php

namespace Lmate\Customer\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SimpleFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "firstname" => "required|min:6",
            "lastname" => "required",
            "email" => "required",
            "phone_number" => "required"
        ];
    }
    public function messages()
    {
        return[
        "firstname.required" => config('customer.firstname'),
        "lastname.required" => config('customer.lastname'),
        "phone_number.required" => config('customer.phone_number'),
        "email.required" => config('customer.email')
        ];
    }
}

<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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

        return $this->getCustomerRules($this->input('class'));

    }



    public function getCustomerRules($class)
    {
        $rules = [];
        switch($class){
            case "register":
                $rules = [
                    'name' =>  'required',
                    'email' => 'required|email|unique:users,email',
                    'password' =>  'required',
                ];
                break;
            case "login":
                $rules = [
                    'email' => 'required|email|exists:users,email',
                    'password' =>  'required',
                ];
                break;


        }
        return $rules;
    }
}

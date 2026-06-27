<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',

            // ssn: مطلوب، أرقام فقط، وبحد أقصى 20 خانة
            'ssn' => 'required|numeric|digits:9',

            // username & email: مع التحقق من عدم التكرار باستثناء المستخدم الحالي عند التعديل
            'username' =>'required|string|max:255|',
            'email' =>'required|email|unique:users,id',

            // password: مطلوب عند الإضافة، واختياري عند التعديل، مع التثبيت من التأكيد (confirmed)
            'password' => $this->user ? 'nullable|confirmed|min:8' : 'required|confirmed|min:8',

            // phone & whatsapp: أرقام فقط واختيارية
            'phone'    => 'nullable|regex:/^0[0-9]{9}$/',
            'whatsapp' => 'nullable|regex:/^0[0-9]{13}$/',

            'address' => 'nullable|string|max:500',
            'role' => 'required|string',

            // active: يجب أن تكون قيمتها إما 0 أو 1 حصراً
            'active' => 'required|in:0,1',
        ];
    }
}

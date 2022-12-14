<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InquiryRequest extends FormRequest
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
     * inputタグのname要素にattributeを設定する.
     */
    // public function attributes()
    // {
    //     return [
    //         'name' => 'お名前',
    //         'email' => 'メールアドレス',
    //         'contact_type' => 'お問い合わせの種類',
    //         'inquiry' => 'お問い合わせ',
    //     ];
    // }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|email',
            'inquiry' => 'required',
        ];
    }

        /**
     * 上記のルールに以外の時にメッセージを表示させる.
     * 
     * 'reading.regex:/^[ぁ-ん]+$/' ではなく、
     * 'reading.regex'でないと反映されない.
     */
    public function messages()
    {
        return [
            'name.required' => '入力してください.',
            'email.required' => '入力してください.',
            'contact_type.required' => '選択してください.',
            'inquiry.required' => '入力してください.',
        ];
    }

}

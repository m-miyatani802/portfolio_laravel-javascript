<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class registerRequest extends FormRequest
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
    public function attributes()
    {
        return [
            'reading' => 'ひらがな',
            'typing' => 'アルファベット'
        ];
    }

    /**
     * inputタグのname要素にルールを設定する.
     * 
     * ^[ぁ-ん]+$  -> ひらがな
     * ^[a-z]+$    -> アルファベット
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'reading' => 'regex:/^[ぁ-ん]+$/',
            'typing' => 'regex:/^[a-z_.\-]+$/',
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
            'reading.regex' => ':attributeで入力してください.',
            'typing.regex' => ':attributeで入力してください.'
        ];
    }
}

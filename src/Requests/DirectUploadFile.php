<?php

namespace ArtinCMS\LFM\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class DirectUploadFile extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    private $options;

    public function authorize()
    {
        $section = $this->request->get('section');
        $LFM = LFM_GetSection($section);
        $options = $LFM['options'];
        $this->options = $options;
        $this->file_ratio = isset($options['ratio']) ? $options['ratio'] : NULL;

        return true;
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $roles = [
            'file'    => 'required|lfm_check_size|lfm_check_true_mime_type|lfm_check_img_dimensions',
            'section' => 'required'
        ];

        return $roles;
    }

    protected function failedValidation(Validator $validator)
    {
        $api_errors = [];
        $errors = $validator->errors();
        foreach ($errors->getMessages() as $values)
        {
            foreach ($values as $key => $value)
            {
                $api_errors[ $key ] = $value;
            }
        }
        $res =
            [
                'errors'            => $api_errors,
                'success'           => false,
                'pre_required_erro' => true,
            ];
        throw new HttpResponseException(
            response()
                ->json($res, 200)
                ->withHeaders(['Content-Type' => 'text/plain', 'charset' => 'utf-8'])
        );
    }

    protected function getValidatorInstance()
    {
        $options = $this->options;
        $validator = parent::getValidatorInstance();
        $validator->addImplicitExtension('lfm_check_size', function ($attribute, $values, $parameters) use ($options) {
            if ($options['size_file'])
            {
                foreach ($values as $file)
                {
                    $size = $file->getSize();
                    if ($size > $options['size_file'])
                    {
                        return false;
                    }
                }
            }

            return true;
        });

        $validator->addImplicitExtension('lfm_check_img_dimensions', function ($attribute, $values, $parameters) use ($options) {
            if ($this->file_ratio)
            {
                foreach ($values as $file)
                {
                    $image_info = getimagesize($file);
                    if ($image_info && isset($image_info[0]) && isset($image_info[1]) && $image_info[1] != 0)
                    {
                        $width = $image_info[0];
                        $height = $image_info[1];
                        $ratio = $width / $height;
                        if ($ratio != $this->file_ratio)
                        {
                            return false;
                        }
                    }
                }
            }

            return true;
        });

        $validator->addImplicitExtension('lfm_check_true_mime_type', function ($attribute, $values, $parameters) use ($options) {
            if ($options['true_mime_type'])
            {
                foreach ($values as $file)
                {
                    $mime_type = $file->getMimeType();
                    if (!in_array($mime_type, $options['true_mime_type']))
                    {
                        return false;
                    }
                }
            }

            return true;
        });

        return $validator;
    }

    public function messages()
    {
        $ratio = @isset($this->options['ratio_str']) ? @$this->options['ratio_str'] : '' ;
        $messages = [
            'file.required'                 => 'آپلود فایل ضروری است .',
            'file.lfm_check_size'           => 'خطا در سایز فایل .',
            'file.lfm_check_true_mime_type' => 'خطا در نوع فایل آپلود شده .',
            'file.lfm_check_img_dimensions' => 'تصویر دارای ابعاد نامناسب است(ابعاد قابل قبول ' . $ratio . ')',
        ];

        return $messages;
    }
}

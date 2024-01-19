<?php 
namespace App\Validators;

use Illuminate\Support\Facades\Validator;

class MessageValidators extends Validator
{
    public static function validate(string $action, array $data)
    {
        $rules = self::getValidationRules($action);

        return Validator::make($data, $rules);
    }

    private static function getValidationRules(string $action): array
    {
        switch ($action) {
            case 'validateReply':
                return [
                    'content' => 'required',
                ];
                case 'validatesendMessage':
                    return [
                        'name' => 'required:string',
                        'email' => 'required|email',
                        'contact' => 'required|numeric',
                        'content' => 'required|string',
                    ];
            // Add more cases for other actions
            default:
                return [];
        }
    }
}

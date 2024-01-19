<?php 
namespace App\Validators;

use Illuminate\Support\Facades\Validator;

class AuthValidators extends Validator
{
    public static function validate(string $action, array $data)
    {
        $rules = self::getValidationRules($action);

        return Validator::make($data, $rules);
    }

    private static function getValidationRules(string $action): array
    {
        switch ($action) {
            case 'login':
                return [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
                ];
                case 'register':
                return [
                    'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users',
      'password' => 'required|string|min:8',
      'role_id' => 'required|integer',
                ];
            // Add more cases for other actions
            default:
                return [];
        }
    }
}

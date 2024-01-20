<?php
namespace App\Validators;

use Illuminate\Support\Facades\Validator;

class PropertyValidators extends Validator
{
    public static function validate(string $action, array $data)
    {
        $rules = self::getValidationRules($action);

        return Validator::make($data, $rules);
    }

    private static function getValidationRules(string $action): array
    {
        switch ($action) {
            case 'storeProperty':
                return [
                    'title' => 'required|string|max:255',
                    'price' => 'required|numeric',
                    'floor_area' => 'required|numeric',
                    'bedroom' => 'required|numeric',
                    'bathroom' => 'required|numeric',
                    'city' => 'required|string|max:255',
                    'address' => 'required|string',
                    'description' => 'required|string',
                    'featured.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'nearby_place' => 'nullable|string|max:255',
                ];
            // Add more cases for other actions
            default:
                return [];
        }
    }
}

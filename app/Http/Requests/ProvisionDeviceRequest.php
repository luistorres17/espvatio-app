<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; // <-- 1. Importar Rule

class ProvisionDeviceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Es un endpoint público
    }

    public function rules(): array
    {
        return [
            // 2. Validamos que el token exista en la tabla 'provisioning_tokens'
            'provisioning_token' => [
                'required', 
                'string',
                Rule::exists('provisioning_tokens', 'token')
            ],

            // 3. Validamos que el 'chip_id' sea único en la columna 'serial_number'
            //    PERO ignoramos los registros con 'deleted_at' (soft-deleted).
            'chip_id' => [
                'required', 
                'string', 
                'max:255',
                Rule::unique('devices', 'serial_number')
                    ->whereNull('deleted_at')
            ],
        ];
    }
}
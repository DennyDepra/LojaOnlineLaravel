<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CpfValido implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $valor = trim((string) $value);

        // Aceita somente 11 dígitos seguidos ou no formato 000.000.000-00
        if (!preg_match('/^\d{3}\.?\d{3}\.?\d{3}-?\d{2}$/', $valor)) {
            $fail('O CPF deve conter 11 dígitos numéricos (com ou sem máscara).');
            return;
        }

        // Remove pontos e hífen para validar os números
        $cpf = preg_replace('/\D/', '', $valor);

        // Bloqueia sequências repetidas como 111.111.111-11
        if (strlen($cpf) !== 11 || preg_match('/^(\d)\1{10}$/', $cpf)) {
            $fail('O CPF informado é inválido.');
            return;
        }

        // Valida os dois dígitos verificadores
        for ($t = 9; $t < 11; $t++) {
            $soma = 0;
            for ($c = 0; $c < $t; $c++) {
                $soma += (int) $cpf[$c] * (($t + 1) - $c);
            }
            $digito = ((10 * $soma) % 11) % 10;

            if ((int) $cpf[$t] !== $digito) {
                $fail('O CPF informado é inválido.');
                return;
            }
        }
    }
}
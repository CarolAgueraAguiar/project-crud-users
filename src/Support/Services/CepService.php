<?php

namespace Support\Services;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Support\Exceptions\ErrorException;

class CepService
{
    const URL = 'https://viacep.com.br/ws/';

    public function search(string $cep): array
    {
        $cepResponse = Http::get(self::URL . $cep . '/json/');
        $isInvalidCep = $cepResponse->failed() || isset($cepResponse->json()['erro']);

        throw_if($isInvalidCep, new ErrorException('CEP inválido', Response::HTTP_BAD_REQUEST));

        return $cepResponse->json();
    }
}

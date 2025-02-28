<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

/**
 * Обработчик аутентификации
 *
 * @package App\Security
 */
class CustomEntryPoint implements AuthenticationEntryPointInterface
{
    /**
     * Если пользователь не проходит успешно аутентификацию - возвращает ошибку 401
     *
     * @param Request $request
     * @param AuthenticationException|null $authException
     * @return Response
     */
    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        return new Response('Вы НЕ залогинились', Response::HTTP_UNAUTHORIZED);
    }
}

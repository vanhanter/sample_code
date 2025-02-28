<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Аутентификация
 *
 * @package App\Controller
 */
class SecurityController extends AbstractController
{
    /**
     * Дефолтное залогиневание
     *
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    #[Route(
        path: '/login',
        name: 'app_login',
        methods: ['GET', 'POST']
    )]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Получить ошибку входа, если она есть
        $error = $authenticationUtils->getLastAuthenticationError();

        // Последнее имя пользователя, введенное пользователем
        $lastUsername = $authenticationUtils->getLastUsername();

        // Если пользователь уже аутентифицирован заходим на страницу заказа
        if ($this->getUser()) {
            return new RedirectResponse('/order');
        }

        // Форма аутентификации
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * Дефолтное разлогиневание
     *
     * @return RedirectResponse
     */
    #[Route(
        path: '/logout',
        name: 'app_logout',
        methods: ['GET']
    )]
    public function logout(): RedirectResponse
    {
        return new RedirectResponse('/');
    }
}

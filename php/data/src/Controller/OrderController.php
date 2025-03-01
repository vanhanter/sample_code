<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Обработка заказов
 *
 * @package App\Controller
 */
final class OrderController extends AbstractController
{
    /**
     * Станица для создания заказа,
     * доступно только аутентифицированному пользователю с ролью ROLE_USER
     *
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    #[Route(
        path: '/order',
        name: 'route_order',
        methods: ['GET', 'POST']
    )]
    #[IsGranted('ROLE_USER')]
    public function order(EntityManagerInterface $entityManager, Request $request): Response
    {
        // Создаем новый объект Order и добавляем в него текущего пользователя
        $order = new Order();
        $order->setUser($this->getUser());

        // Создаем форму
        $form = $this->createForm(OrderType::class, $order);

        // Если форма отправлена и валидна - создаем заказ
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($order);
            $entityManager->flush();

            // Добавляем flash-сообщение
            $this->addFlash('success', 'Заказ создан');

            // Редирект на форму заказа
            return new RedirectResponse($request->getPathInfo());
        }

        // Отображаем форму в шаблоне
        return $this->render('main/order.html.twig', [
            'form' => $form,
        ]);
    }
}

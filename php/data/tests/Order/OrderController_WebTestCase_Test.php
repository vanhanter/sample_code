<?php

namespace App\tests\Order;

use App\Repository\ServiceRepository;
use App\Repository\UserRepository;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Тестирует заказы из App\Controller\OrderController::order
 *
 * @package App\tests
 */
class OrderController_WebTestCase_Test extends WebTestCase
{
    use RefreshDatabaseTrait;

    /**
     * НЕ аутентифицированный пользователь,
     * если откроет страницу заказа, получит ошибку 401 и сообщение об ошибке
     *
     * @return void
     */
    public function testNonAuthUser(): void
    {
        $client = static::createClient();
        $client->request('GET', '/order');

        $this->assertResponseStatusCodeSame(401);
        $this->assertSelectorTextContains('body', 'Вы НЕ залогинились');
    }

    /**
     * Аутентифицированный пользователь,
     * проверяем что форма заказа отобразилась корректно
     *
     * @return void
     */
    public function testAuthUserFormFieldsCorrect(): void
    {
        $client = static::createClient();

        // Берем любого одного пользователя
        $user = static::getContainer()->get(UserRepository::class)->findOneBy([]);

        $client->loginUser($user);
        $client->request('GET', '/order');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Заказать услугу');
        $this->assertSelectorExists('#order_service');
        $this->assertSelectorCount(4, 'option');
        $this->assertSelectorExists('#order_user');
        $this->assertSelectorExists('#order_submit');
    }

    /**
     * Аутентифицированный пользователь,
     * проверяем что при отправке валидных данных с формы - пользователя редиректит на страницу заказа,
     * и проверяем, что заказ создается в таблице Order тестовой БД
     *
     * @return void
     */
    public function testAuthUserCreateOrderSuccess(): void
    {
        $client = static::createClient();

        // Берем любого одного пользователя
        $user = static::getContainer()->get(UserRepository::class)->findOneBy([]);

        // Берем любую одну услугу
        $service = static::getContainer()->get(ServiceRepository::class)->findOneBy([]);

        $client->loginUser($user);

        $crawler = $client->request('GET', '/order');
        $form = $crawler->selectButton('order[submit]')->form([
            'order[service]' => $service->getId(),
            'order[user]' => $user->getEmail(),
        ]);
        $client->submit($form);

        $this->assertResponseRedirects('/order');
        $client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Заказать услугу');
        $this->assertCount(1, $user->getOrders());
    }

    /**
     * Аутентифицированный пользователь,
     * проверяем что при отправке НЕ валидных данных с формы - вернется та же страница,
     * но с описанием ошибки
     *
     * @return void
     */
    public function testAuthUserCreateOrderError(): void
    {
        $client = static::createClient();

        // Берем любого одного пользователя
        $user = static::getContainer()->get(UserRepository::class)->findOneBy([]);

        $client->loginUser($user);

        $crawler = $client->request('GET', '/order');
        $form = $crawler->selectButton('order[submit]')->form([
            'order[service]' => '',
            'order[user]' => $user->getEmail(),
        ]);
        $client->submit($form);

        $this->assertResponseStatusCodeSame(422);
        $this->assertSelectorTextContains('li', 'Объект услуги не может быть null');
        $this->assertSelectorTextContains('h1', 'Заказать услугу');
    }
}

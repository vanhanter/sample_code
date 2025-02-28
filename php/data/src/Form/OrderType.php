<?php

namespace App\Form;

use App\Entity\Order;
use App\Entity\Service;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Форма заказа
 *
 * @package App\Form
 * @requires App\Entity\User
 */
class OrderType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /*
         * При создании объекта текущей формы,
         * в объект формы должен быть добавлен объект пользователя,
         * что бы можно было вывести его почту
         */
        if(empty($options['data']->getUser()))
            throw new \RuntimeException('Объект пользователь НЕ передан в класс формы App\Form\OrderType');

        $builder
            // поле услуги
            ->add('service', EntityType::class, [
                'class' => Service::class,
                'label' => 'Услуга',
                'placeholder' => 'Выберите услугу',
                'choice_label' => function(Service $service) {

                    // В выпадающем списке услуг сразу комбинируем название и стоимость услуги
                    return $service->getName() . ' - ' . $service->getPrice();
                },
            ])
            // поле email
            ->add('user', TextType::class, [
                'label' => 'Ваша почта',
                'data' => $options['data']->getUser()->getEmail(),
                'disabled' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Подтвердить'
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}

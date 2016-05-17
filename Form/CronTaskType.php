<?php

namespace Frcho\Bundle\CrontaskBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Frcho\Bundle\CrontaskBundle\Entity;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Frcho\Bundle\CrontaskBundle\Util\Util;

class CronTaskType extends AbstractType {

    private $container;
    private $translator;

    const FORM_PREFIX = 'frcho_cron_task_type';

    public function __construct(Container $container) {
        $this->container = $container;
        $this->translator = $this->container->get('translator');
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
    
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();
            if ($data instanceof Entity\CronTask) {
            
                $resultInterval = Util::convertDaysHoursMinutes($data->getInterval(), $data->getRange(), true);

                $form->add('interval', TextType::class, array('data' => $resultInterval));
            }
        });

        $builder->add('name', TextType::class)
                ->add('range', TextType::class)
                ->add('statusTask', CheckboxType::class, array(
                    'label' => "  ",
                    'required' => false,
                ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Frcho\Bundle\CrontaskBundle\Entity\CronTask'
        ));
    }

    public function getBlockPrefix() {
        return self::FORM_PREFIX;
    }

}

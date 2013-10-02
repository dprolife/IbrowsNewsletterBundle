<?php

namespace Ibrows\Bundle\NewsletterBundle\Form;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityChoiceList;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;

class UnsubscribeType extends AbstractType
{
    /**
     * @var string
     */
    protected $managerName;

    /**
     * @var string
     */
    protected $groupClass;

    /**
     * @param string $managerName
     * @param string $groupClass
     */
    public function __construct($managerName, $groupClass)
    {
        $this->managerName = $managerName;
        $this->groupClass = $groupClass;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('groups', 'entity', array(
                'em' => $this->managerName,
                'class' => $this->groupClass,
                'expanded' => true,
                'multiple' => true,
            ))
        ;
    }

    /**
     * @return string
     */
    public function getName() {
        return 'ibrows_newsletterbundle_unsubscribe';
    }
}

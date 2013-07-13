<?php

	namespace Ibrows\Bundle\NewsletterBundle\Form;

	use Doctrine\Common\Persistence\ObjectManager;

	use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityChoiceList;
	use Symfony\Component\OptionsResolver\OptionsResolverInterface;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\Validator\Constraints\Email;

	class CreateSubscriberType extends AbstractType
	{
		protected $managerName;
		protected $subscriberClass;

		public function __construct($managerName, $subscriberClass)
		{
			$this->managerName = $managerName;
			$this->subscriberClass = $subscriberClass;
		}

		/**
		 * @param FormBuilderInterface $builder
		 * @param array $options
		 */
		public function buildForm(FormBuilderInterface $builder, array $options) {
			$builder
				->add('title')
				->add('firstname')
				->add('lastname')
				->add('email', null, array(
					'constraints' => new Email()
				))
				->add('gender')
				->add('companyname')
				->add('locale')
			;
		}

		/**
		 * @return string
		 */
		public function getName() {
			return 'ibrows_newsletterbundle_create_subscriber';
		}

	}

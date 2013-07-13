<?php

namespace Ibrows\Bundle\NewsletterBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Ibrows\Bundle\NewsletterBundle\Form\CreateSubscriberType;

/**
 * @Route("/subscriber")
 */
class SubscriberController extends AbstractController
{
    /**
     * @Route("/list", name="ibrows_newsletter_subscriber_list")
     */
    public function listAction()
    {
        $subscribers = $this->getSubscribers();

        return $this->render($this->getTemplateManager()->getSubscriber('list'), array(
            'subscribers' => $subscribers
        ));
    }

	/**
	 * @Route("/create", name="ibrows_newsletter_subscriber_create")
	 */
	public function createAction()
	{
		$entity = $this->container->getParameter('ibrows_newsletter.classes.model.subscriber');

		$subscriber = new $entity();

		$form = $this->createForm(new CreateSubscriberType($this->getMandantName(), $entity), $subscriber);

		if ($this->getRequest()->isMethod('POST'))
		{
			$form->bind($this->getRequest());

			if ($form->isValid())
			{
				$subscriber->setMandant($this->getMandant());

				$em = $this->getDoctrine()->getManager($this->getMandantName());
				$em->persist($form->getData());
				$em->flush();

				$this->container->get('session')->getFlashBag()->add(
					'notice',
					'subscriber.create.status_ok'
				);

				return $this->redirect($this->generateUrl('ibrows_newsletter_subscriber_list'));
			}
		}

		return $this->render($this->getTemplateManager()->getSubscriber('create'), array(
			'form' => $form->createView()
		));
	}

	/**
	 * @Route("/delete/{id}", requirements={"id" = "\d+"}, name="ibrows_newsletter_subscriber_delete")
	 */
	public function deleteAction($id)
	{
		$subManager = $this->getMandantManager()->getSubscriberManager($this->getMandantName());

		$member = $subManager->findOneBy(array('id' => $id));

		if (!$member)
			throw $this->createNotFoundException(sprintf("Subscriber %d not found in mandant \"%s\"", $id, $this->getMandantName()));

		$em = $this->getDoctrine()->getManager($this->getMandantName());
		$em->remove($member);
		$em->flush();

		$this->container->get('session')->getFlashBag()->add(
			'notice',
			'subscriber.delete.status_' . ($subManager->findOneBy(array('id' => $id)) ? 'ko' : 'ok')
		);

		return $this->redirect($this->generateUrl('ibrows_newsletter_subscriber_list'));
	}
}
<?php

namespace Ibrows\Bundle\NewsletterBundle\Controller;

use Ibrows\Bundle\NewsletterBundle\Model\Design\DesignInterface;
use Ibrows\Bundle\NewsletterBundle\Model\Subscriber\SubscriberGenderTitleInterface;
use Ibrows\Bundle\NewsletterBundle\Model\Subscriber\SubscriberInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ibrows\Bundle\NewsletterBundle\Entity\Newsletter;
use Ibrows\Bundle\NewsletterBundle\Entity\Subscriber;
use Ibrows\Bundle\NewsletterBundle\Block\BlockComposition;

/**
 * @Route("/render")
 */
class NewsletterRenderingController extends AbstractHashMandantController
{
    /**
     * @Route("/show/{mandantHash}/{newsletterHash}/{subscriberHash}", name="ibrows_newsletter_render_overview")
     */
    public function showAction($mandantHash, $newsletterHash, $subscriberHash)
    {
        $this->setMandantNameByHash($mandantHash);

        $newsletter = $this->getNewsletterByHash($newsletterHash);
        $mandant = $this->getMandant();
        $subscriber = $this->getSubscriberByHash($newsletter, $subscriberHash);

        $request = $this->getRequest();
        if(!$request->query->get('context')){
            $this->addNewsletterReadLog($newsletter, $subscriber, "Newsletter read: logged by ".__METHOD__);
        }

        $renderername = $this->getMandant()->getRendererName();
        $bridge = $this->getRendererBridge();
        $context = $this->getRequest()->query->get('context');
        
		$overview = $this->getRendererManager()->renderNewsletter(
			$renderername, 
			$bridge, 
			$newsletter, 
			$mandant, 
			$subscriber,
			$context
		);

        return $this->render($this->getTemplateManager()->getNewsletter('overview'), array(
            'overview' => $overview
        ));
    }
    
    /**
     * @Route("/show/design/{id}", name="ibrows_newsletter_render_design")
     */
    public function showDesignPreviewAction($id)
    {
        $dm = $this->getDesignManager();
        $design = $dm->get($id);

        $newsletter = $this->createTestNewsletter($design);
        $subscriber = $this->createTestSubscriber();
        $mandant = $this->getMandant();

        $renderername = $this->getMandant()->getRendererName();
        $bridge = $this->getRendererBridge();
        $context = $this->getRequest()->query->get('context');

        $overview = $this->getRendererManager()->renderNewsletter(
            $renderername,
            $bridge,
            $newsletter,
            $mandant,
            $subscriber,
            $context
        );

        return $this->render($this->getTemplateManager()->getNewsletter('overview'), array(
            'overview' => $overview
        ));
    }

    /**
     * @param DesignInterface $design
     * @return Newsletter
     */
    protected function createTestNewsletter(DesignInterface $design)
    {
        $newsletter = new Newsletter();

        $newsletter->setCreatedAt(new \DateTime());
        $newsletter->setDesign($design);
        $newsletter->setMandant($this->getMandant());

        $newsletter->setName('Newsletter name');
        $newsletter->setSubject('Newsletter subject');

        $newsletter->setReturnMail('return@newsletter.com');
        $newsletter->setSenderMail('sender@newsletter.com');
        $newsletter->setSenderName('Sender Name');

        return $newsletter;
    }

    /**
     * @return Subscriber
     */
    protected function createTestSubscriber()
    {
        $subscriber = new Subscriber();

        $subscriber->setFirstname('Firstname');
        $subscriber->setLastname('Lastname');

        $subscriber->setEmail('mail@subscriber.com');
        $subscriber->setCompanyname('Subscriber Company');

        $subscriber->setLocale($this->getRequest()->getLocale());
        $subscriber->setGender(SubscriberGenderTitleInterface::GENDER_MALE);
        $subscriber->setTitle(SubscriberGenderTitleInterface::TITLE_FORMAL);

        return $subscriber;
    }
}
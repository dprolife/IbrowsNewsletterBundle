<?php

namespace Ibrows\Bundle\NewsletterBundle\Model\Design;

use Ibrows\Bundle\NewsletterBundle\Model\Mandant\MandantInterface;
use Ibrows\Bundle\NewsletterBundle\Renderer\RenderableInterface;

interface DesignInterface extends RenderableInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * @return MandantInterface
     */
    public function getMandant();

    /**
     * @return string
     */
    public function __toString();
}

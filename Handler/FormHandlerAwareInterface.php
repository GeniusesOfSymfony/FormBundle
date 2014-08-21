<?php
namespace Gos\Bundle\FormBundle\Handler;

interface FormHandlerAwareInterface
{
    /**
     * @return boolean
     */
    public function process($data = null, array $options = []);
}

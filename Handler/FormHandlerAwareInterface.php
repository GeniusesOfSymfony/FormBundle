<?php
namespace Gos\Bundle\FormBundle\Handler;

interface FormHandlerAwareInterface
{
    public function process($data = null, array $options = array());
}

<?php
namespace Gos\Bundle\FormBundle\Handler;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

interface FormHandlerInterface
{
    public function getName();

    public function setRequestStack(RequestStack $requestStack);

    public function setFormFactory(FormFactoryInterface $formFactory);

    public function setFormName($formName);

    public function setFormMethod($method = FormHandler::POST_METHOD);

    public function getForm();

    public function createForm($formData = null, array $formOptions = array());

    public function handle($method = FormHandler::POST_METHOD);

    public function createView();
}

<?php
namespace Gos\Bundle\FormBundle\Handler;

use Closure;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
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

    public function hasRequestedMe(Request $request, FormInterface $form);

    public function createView();
}

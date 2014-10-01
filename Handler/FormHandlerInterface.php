<?php
namespace Gos\Bundle\FormBundle\Handler;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

interface FormHandlerInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return void
     */
    public function setRequestStack(RequestStack $requestStack);

    /**
     * @return void
     */
    public function setFormFactory(FormFactoryInterface $formFactory);

    /**
     * @return void
     */
    public function setFormName($formName);

    /**
     * @return void
     */
    public function setFormMethod($method = FormHandler::POST_METHOD);

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getForm();

    /**
     * @return void
     */
    public function createForm($formData = null, array $formOptions = []);

    /**
     * @return boolean
     */
    public function handle($method = FormHandler::POST_METHOD);

    /**
     * @return \Symfony\Component\Form\FormView
     */
    public function createView();

    /**
     * @return boolean
     */
    public function process($data = null, array $options = []);
}

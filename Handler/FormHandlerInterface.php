<?php
namespace Gos\Bundle\FormBundle\Handler;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

interface FormHandlerInterface
{
    const POST_METHOD = 'POST';
    const GET_METHOD = 'GET';
    const PUT_METHOD = 'PUT';
    const DELETE_METHOD = 'DELETE';


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
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getForm();

    /**
     * @return void
     */
    public function createForm($formData = null, array $formOptions = []);

    /**
     * @return \Symfony\Component\Form\FormView
     */
    public function createView();

    /**
     * @param null  $data
     * @param array $options
     *
     * @return bool
     * @throws \Exception
     */
    public function handle($data = null, array $options = []);
}

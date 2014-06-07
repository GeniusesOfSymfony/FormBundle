<?php
namespace Gos\Bundle\FormBundle\Handler;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class FormHandler
 * @package Gos\Bundle\CoreBundle\Form\Handler
 *  FormHandler, can be use standalone (without extend, parent or anything)
 */
class FormHandler implements FormHandlerInterface
{
    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @var string
     */
    protected $formName;

    /**
     * @var array
     */
    protected $formOptions = array();

    /**
     * @var null|FormInterface
     */
    protected $form = null;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @var string
     */
    protected $formMethod;

    const POST_METHOD = 'POST';
    const GET_METHOD = 'GET';
    const PUT_METHOD = 'PUT';
    const DELETE_METHOD = 'DELETE';

    /**
     * @param RequestStack $requestStack
     */
    public function setRequestStack(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @param FormFactoryInterface $formFactory
     */
    public function setFormFactory(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * @param string $formName
     */
    public function setFormName($formName)
    {
        $this->formName = $formName;
    }

    public function setFormMethod($method = self::POST_METHOD)
    {
        $this->formMethod = $method;
    }

    /**
     * @return null
     * @throws \Exception
     */
    public function getForm()
    {
        if (null === $this->form) {
            throw new \Exception('You must create form before retrieve it');
        }

        return $this->form;
    }

    /**
     * @param null  $data
     * @param array $options
     */
    public function createForm($formData = null, array $formOptions = array())
    {
        /**
         * Symfony 3.0 Form $data as argument is deprecated, use $options['data'] instead
         */
        if (null !== $formData) {
            $this->formOptions['data'] = $formData;
        }

        $this->form = $this->formFactory->create($this->formName, null, array_merge($this->formOptions, $formOptions));
    }

    /**
     * @return \Symfony\Component\Form\FormView
     */
    public function createView()
    {
        return $this->form->createView();
    }

    /**
     * @param string   $method
     * @param callable $successHandler
     * @param callable $errorHandler
     *
     * @return bool|null
     * @throws \Exception
     */
    public function handle($method = self::POST_METHOD)
    {
        if (true !== $this->supportsClass()) {
            throw new \Exception(sprintf('Class %s not supported', get_class($this->form)));
        }

        $request = $this->getRequest();
        $form = $this->getForm();
        $this->setFormMethod($method);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->onSuccess($form->getData());
            return true;
        } else {
            $this->onError($form->getData());
            return false;
        }
    }

    /**
     * @param array $options
     * Implemented to handle form inside sub request properly.
     *
     * @return mixed
     */
    protected function getRequest(array $options = array())
    {
        return $this->requestStack->getCurrentRequest();
    }

    /**
     * @param $method
     *
     * @return mixed
     */
    protected function resolveParameters($method, Request $request)
    {
        return $method === self::POST_METHOD ? $request->request : $request->query;
    }

    /**
     * @param $data
     *
     * @return bool
     */
    protected function onSuccess($data)
    {
        //stub, to implement if needed
        return true;
    }

    /**
     * @param $data
     *
     * @return bool
     */
    protected function onError($data)
    {
        //stub, to implement if needed
        return false;
    }

    /**
     * @return string
     */
    public function getName()
    {
        //stub, to implement if needed
        return $this->formName;
    }

    /**
     * @return bool
     */
    protected function supportsClass()
    {
        //stub, to implement if needed
        return true;
    }


}

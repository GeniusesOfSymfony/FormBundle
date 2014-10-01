<?php
namespace Gos\Bundle\FormBundle\Handler;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

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
    protected $formOptions = [];

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
     * @return FormInterface
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
     * @return boolean
     * Generic/Simple process, feel free to override it.
     */
    public function process($data = null, array $options = [])
    {
        $this->createForm($data, $options);

        return $this->handle();
    }

    /**
     * @param array $formOptions
     */
    public function createForm($formData = null, array $formOptions = [])
    {
        /**
         * Symfony 3.0 Form $data as argument is deprecated, use $options['data'] instead
         */
        if (null !== $formData) {
            $this->formOptions['data'] = $formData;
        }

        $this->form = $this->formFactory->create(
            $this->formName,
            null,
            array_merge($this->formOptions, $formOptions)
        );
    }

    /**
     * @return \Symfony\Component\Form\FormView
     */
    public function createView()
    {
        return $this->form->createView();
    }

    /**
     * @param string $method
     *
     * @return boolean
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

        if($form->isSubmitted()){
            if ($form->isValid()) {
                $this->onSuccess($form->getData());

                return true;
            }

            $this->onError($form->getData());
            return false;
        }
    }

    /**
     * @param array $options
     *                       Implemented to handle form inside sub request properly.
     *
     * @return Request|null
     */
    protected function getRequest(array $options = [])
    {
        return $this->requestStack->getCurrentRequest();
    }

    /**
     * @param $method
     *
     * @return \Symfony\Component\HttpFoundation\ParameterBag
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

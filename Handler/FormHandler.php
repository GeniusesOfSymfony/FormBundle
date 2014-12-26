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
     * @param null  $data
     * @param array $options
     *
     * @return bool
     * @throws \Exception
     */
    public function handle($data = null, array $options = [])
    {
        $this->createForm($data, $options);

        if (true !== $this->supportsClass()) {
            throw new \Exception(sprintf('Class %s not supported', get_class($this->form)));
        }

        return $this->doHandle($this->getRequest(), $this->getForm());
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
     * @return bool
     * @throws \Exception
     */
    protected function doHandle(Request $request, FormInterface $form)
    {
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->onSuccess($request, $form->getData());

                return true;
            }

            $this->onError($request, $form->getData());

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
    public function onSuccess(Request $request, $data)
    {
        //stub, to implement if needed
        return true;
    }

    /**
     * @param $data
     *
     * @return bool
     */
    public function onError(Request $request, $data)
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

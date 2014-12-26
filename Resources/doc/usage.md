Usage
=====

First, you must create your form handler class like following :

```php
class RegistrationFormHandler extends FormHandler
{
    /**
     * @var UserHandler
     */
    protected $userHandler;

    /**
     * @param UserHandler $userHandler
     * Use the dependency injection to inject your deps like any class.
     */
    public function __construct(UserHandler $userHandler)
    {
        $this->userHandler = $userHandler;
    }

    /**
     * @param UserInterface $data
     *
     * @return bool|void
     */
    public function onSuccess($data)
    {
        //Do your stuff here
    }

    //You can also create onError($data) method if you need it, she will be called when
    //the form validation failed.
}
```

Your handler is now created. By default we provide a generic process :

```php
    /**
     * @return boolean
     * Generic/Simple process, feel free to override it.
     */
    public function handle($data = null, array $options = [])
    {
        $this->createForm($data, $options);

        return $this->handle();
    }
```

And a generic form instanciation :

```php
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
```

Feel free to override these methods to adapt your business when it's needed.

You can have a restrictive approach (It's interesting for huge project) with :

```php
    /**
     * @return bool
     */
    protected function supportsClass()
    {
        //stub, to implement if needed
        return true;
    }
```

By default your handler can support all form (No restriction), but you can link form handler with form type if any confusion can be happen (name, state).

### Register your service

```yml
parameters:
    gos_user_bundle_registration_form_name: gos_user_bundle_registration_form
    gos_user_bundle_registration_form: Gos\Bundle\UserBundle\Form\Type\RegistrationType
    gos_user_bundle_registration_handler: Gos\Bundle\UserBundle\Form\Handler\RegistrationFormHandler

services:
    gos.user_bundle.registration.form:
        class: %gos_user_bundle_registration_form%
        tags:
            - { name: form.type, alias: %gos_user_bundle_registration_form_name% }

    gos.user_bundle.registration.form_handler:
        class: %gos_user_bundle_registration_handler%
        arguments:
            - @gos.user_bundle.handler
        tags:
            - { name: form.handler, form: %gos_user_bundle_registration_form_name% }
```

NOTE : The `form` parameter tags is the related form alias, that allow us to instanciate your form directly without extra process from your part.

If you want share the same handler across many forms, just duplicate this service with appropriate name and form name.

### Controller

Here a concrete example :

```php
<?php
namespace Gos\Bundle\UserBundle\Controller;

use Gos\Bundle\PageBundle\Controller\AbstractController;
use Gos\Bundle\UserBundle\Form\Handler\RegistrationFormHandler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;

class UserController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registrationAction(Request $request)
    {
        $registrationFormHandler = $this->container->get('gos.user_bundle.registration.form_handler');

        if (true === $registrationFormHandler->handle()) {
            //Do your stuff, like add flashbag
            return new RedirectResponse($this->container->get('router')->generate('homepage'));
        }

        return $this->renderView('GOSUserBundle:User:registration.html.twig', array(
            'form' => $registrationFormHandler->createView()
        ));
    }
}
```
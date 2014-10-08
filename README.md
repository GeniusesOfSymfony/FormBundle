#Gos Form Bundle#

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/GeniusesOfSymfony/FormBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/GeniusesOfSymfony/FormBundle/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/GeniusesOfSymfony/FormBundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/GeniusesOfSymfony/FormBundle/?branch=master)

**This project is currently in development, please take care.**

Installation
-------------

You need to have [composer](https://getcomposer.org/) to install dependencies.

```json
{
    "require": {
        "gos/form-bundle": "{last stable version}"
    }
}
```

Then run the command on the root of your project`composer update`

Add this line in your `AppKernel.php`

```php
$bundles = array(

	//Other bundles
    new Gos\Bundle\FormBundle\GosFormBundle(),
);
```

Usage
------

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
     * @param $data
     *
     * @return bool|void
     */
    protected function onSuccess($data)
    {
        switch ($this->getName()) {
            case 'gos_user_bundle_registration_form' :
                $handler = $this->userHandler->perform('create')->with($data);
            break;
        }

        if(isset($handler)){
            $handler->handle();
        }
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

NOTE : The `form` parameter tags must be exactly the same than your form.

If you want share the same handler across many forms, just duplicate this service with appropriate name and form name.

### Controller

Here a concrete example :

```php
<?php
namespace Gos\Bundle\UserBundle\Controller;

use Gos\Bundle\PageBundle\Controller\AbstractController;
use Gos\Bundle\UserBundle\Form\Handler\RegistrationFormHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;

class UserController extends AbstractController
{
    /**
     * @var \Gos\Bundle\UserBundle\Form\Handler\RegistrationFormHandler
     */
    protected $registrationFormHandler;

    /**
     * @param RegistrationFormHandler $registrationFormHandler
     */
    public function __construct(RegistrationFormHandler $registrationFormHandler) {
        $this->registrationFormHandler = $registrationFormHandler;
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registrationAction(Request $request)
    {
        $response = new Response();

        if (true === $this->registrationFormHandler->handle()) {
            $this->addFlashBag('success', 'user_registration.success');

            return $this->httpUtils->createRedirectResponse($request, 'login');
        }

        $content = $this->engine->render('GosUserBundle:User:registration.html.twig', [
            'form' => $this->registrationFormHandler->createView()
        ]);

        $response->setContent($content);

        return $response;
    }
}

}
```

Running the tests:
------------------

PHPUnit 3.5 or newer together with Mock_Object package is required. To setup and run tests follow these steps:

* go to the root directory of fixture
* run: composer install --dev
* run: phpunit

License
---------

The project is under MIT lisence, for more information see the LICENSE file inside the project

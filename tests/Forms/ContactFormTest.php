<?php

namespace App\Tests\Forms;

use App\Form\ContactType;
use App\Tests\Forms\fixtures\RecaptchaMock;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Class ContactFormTest.
 */
class ContactFormTest extends TestCase
{
    /** @var TestKernel */
    private $kernel;

    /**
     * @var mixed (FormFactoryInterface)
     */
    private $formFactory;

    /**
     * @var mixed (Object)
     */
    private $twig;

    /**
     * @var array<string,string>
     */
    private $formData;

    /** ------------------------------- SETUP ------------------------------- */
    public function setUp(): void
    {
        $this->kernel = new TestKernel(uniqid(), false);

        $this->formData = [
            'firstname' => 'Quentin',
            'lastname' => 'QGT',
            'email' => 'email@domain.com',
            'message' => 'lorem ipsum',
            'captcha' => 'token',
        ];
    }

    /** ------------------------------- TESTS ------------------------------- */
    public function testFormInvalid_ifFirstnameEmpty(): void
    {
        //GIVEN
        $container = $this->bootKernel('default.yml');

        /** @var RecaptchaMock $recaptchaMock */
        $recaptchaMock = $container->get('karser_recaptcha3.google.recaptcha');
        $recaptchaMock->nextSuccess = true;

        //WHEN
        $form = $this->createContactForm($this->formFactory);

        $formData = $this->formData;
        $formData['firstname'] = '';

        $form->submit($formData);

        //THEN
        $this->assertFormHasError($form);
    }

    public function testFormInvalid_ifFirstnameNull(): void
    {
        //GIVEN
        $container = $this->bootKernel('default.yml');

        /** @var RecaptchaMock $recaptchaMock */
        $recaptchaMock = $container->get('karser_recaptcha3.google.recaptcha');
        $recaptchaMock->nextSuccess = true;

        //WHEN
        $form = $this->createContactForm($this->formFactory);

        $formData = $this->formData;
        $formData['firstname'] = null;

        $form->submit($formData);

        //THEN
        $this->assertFormHasError($form);
    }

    public function testFormInvalid_ifFirstnameLessThanMinLength(): void
    {
        //GIVEN
        $container = $this->bootKernel('default.yml');

        /** @var RecaptchaMock $recaptchaMock */
        $recaptchaMock = $container->get('karser_recaptcha3.google.recaptcha');
        $recaptchaMock->nextSuccess = true;

        //WHEN
        $form = $this->createContactForm($this->formFactory);

        $formData = $this->formData;
        $formData['firstname'] = 'Q';

        $form->submit($formData);

        //THEN
        $this->assertFormHasError($form);
    }

    public function testFormInvalid_ifFirstnameMoreThanMaxLength(): void
    {
        //GIVEN
        $container = $this->bootKernel('default.yml');

        /** @var RecaptchaMock $recaptchaMock */
        $recaptchaMock = $container->get('karser_recaptcha3.google.recaptcha');
        $recaptchaMock->nextSuccess = true;

        //WHEN
        $form = $this->createContactForm($this->formFactory);

        $formData = $this->formData;
        $formData['firstname'] = $this->generateRandomString(101);

        $form->submit($formData);

        //THEN
        $this->assertFormHasError($form);
    }

    public function testFormInvalid_ifLastnameEmpty(): void
    {
        //GIVEN
        $container = $this->bootKernel('default.yml');

        /** @var RecaptchaMock $recaptchaMock */
        $recaptchaMock = $container->get('karser_recaptcha3.google.recaptcha');
        $recaptchaMock->nextSuccess = true;

        //WHEN
        $form = $this->createContactForm($this->formFactory);

        $formData = $this->formData;
        $formData['lastname'] = '';

        $form->submit($formData);

        //THEN
        $this->assertFormHasError($form);
    }

    public function testFormInvalid_ifLastnameNull(): void
    {
        //GIVEN
        $container = $this->bootKernel('default.yml');

        /** @var RecaptchaMock $recaptchaMock */
        $recaptchaMock = $container->get('karser_recaptcha3.google.recaptcha');
        $recaptchaMock->nextSuccess = true;

        //WHEN
        $form = $this->createContactForm($this->formFactory);

        $formData = $this->formData;
        $formData['lastname'] = null;

        $form->submit($formData);

        //THEN
        $this->assertFormHasError($form);
    }

    public function testFormInvalid_ifLastnameLessThanMinLength(): void
    {
        //GIVEN
        $container = $this->bootKernel('default.yml');

        /** @var RecaptchaMock $recaptchaMock */
        $recaptchaMock = $container->get('karser_recaptcha3.google.recaptcha');
        $recaptchaMock->nextSuccess = true;

        //WHEN
        $form = $this->createContactForm($this->formFactory);

        $formData = $this->formData;
        $formData['lastname'] = 'G';

        $form->submit($formData);

        //THEN
        $this->assertFormHasError($form);
    }

    public function testFormInvalid_ifLastnameMoreThanMaxLength(): void
    {
        //GIVEN
        $container = $this->bootKernel('default.yml');

        /** @var RecaptchaMock $recaptchaMock */
        $recaptchaMock = $container->get('karser_recaptcha3.google.recaptcha');
        $recaptchaMock->nextSuccess = true;

        //WHEN
        $form = $this->createContactForm($this->formFactory);

        $formData = $this->formData;
        $formData['lastname'] = $this->generateRandomString(101);

        $form->submit($formData);

        //THEN
        $this->assertFormHasError($form);
    }

    public function testFormInvalid_ifEmailEmpty(): void
    {
        //GIVEN
        $container = $this->bootKernel('default.yml');

        /** @var RecaptchaMock $recaptchaMock */
        $recaptchaMock = $container->get('karser_recaptcha3.google.recaptcha');
        $recaptchaMock->nextSuccess = true;

        //WHEN
        $form = $this->createContactForm($this->formFactory);

        $formData = $this->formData;
        $formData['email'] = '';

        $form->submit($formData);

        //THEN
        $this->assertFormHasError($form);
    }

    public function testFormInvalid_ifEmailNull(): void
    {
        //GIVEN
        $container = $this->bootKernel('default.yml');

        /** @var RecaptchaMock $recaptchaMock */
        $recaptchaMock = $container->get('karser_recaptcha3.google.recaptcha');
        $recaptchaMock->nextSuccess = true;

        //WHEN
        $form = $this->createContactForm($this->formFactory);

        $formData = $this->formData;
        $formData['email'] = null;

        $form->submit($formData);

        //THEN
        $this->assertFormHasError($form);
    }

    public function testFormInvalid_ifEmailFails(): void
    {
        //GIVEN
        $container = $this->bootKernel('default.yml');

        /** @var RecaptchaMock $recaptchaMock */
        $recaptchaMock = $container->get('karser_recaptcha3.google.recaptcha');
        $recaptchaMock->nextSuccess = true;

        //WHEN
        $form = $this->createContactForm($this->formFactory);

        $formData = $this->formData;
        $formData['email'] = 'emaildomain.com';

        $form->submit($formData);

        //THEN
        $this->assertFormHasError($form);
    }

    public function testFormInvalid_ifMessageEmpty(): void
    {
        //GIVEN
        $container = $this->bootKernel('default.yml');

        /** @var RecaptchaMock $recaptchaMock */
        $recaptchaMock = $container->get('karser_recaptcha3.google.recaptcha');
        $recaptchaMock->nextSuccess = true;

        //WHEN
        $form = $this->createContactForm($this->formFactory);

        $formData = $this->formData;
        $formData['message'] = '';

        $form->submit($formData);

        //THEN
        $this->assertFormHasError($form);
    }

    public function testFormInvalid_ifMessageNull(): void
    {
        //GIVEN
        $container = $this->bootKernel('default.yml');

        /** @var RecaptchaMock $recaptchaMock */
        $recaptchaMock = $container->get('karser_recaptcha3.google.recaptcha');
        $recaptchaMock->nextSuccess = true;

        //WHEN
        $form = $this->createContactForm($this->formFactory);

        $formData = $this->formData;
        $formData['message'] = null;

        $form->submit($formData);

        //THEN
        $this->assertFormHasError($form);
    }

    public function testFormInvalid_ifMessageLessThanMinLength(): void
    {
        //GIVEN
        $container = $this->bootKernel('default.yml');

        /** @var RecaptchaMock $recaptchaMock */
        $recaptchaMock = $container->get('karser_recaptcha3.google.recaptcha');
        $recaptchaMock->nextSuccess = true;

        //WHEN
        $form = $this->createContactForm($this->formFactory);

        $formData = $this->formData;
        $formData['message'] = $this->generateRandomString(9);

        $form->submit($formData);

        //THEN
        $this->assertFormHasError($form);
    }

    public function testFormJavascriptPresent_ifEnabled(): void
    {
        //GIVEN
        $this->bootKernel('default.yml');
        $form = $this->createContactForm($this->formFactory);

        $template = $this->twig->createTemplate('{{ form_widget(form) }}');

        //WHEN
        $view = $template->render(['form' => $form->createView()]);

        //THEN
        self::assertContains('<input type="hidden" id="contact_captcha" name="contact[captcha]" />', $view);
        self::assertContains('<script type="text/javascript" src="https://www.google.com/recaptcha/api.js?render=key&onload=recaptchaCallback_contact_captcha" async defer></script>', $view);
        self::assertContains('var recaptchaCallback_contact_captcha', $view);
        self::assertContains("document.getElementById('contact_captcha').value = token;", $view);
    }

    public function testFormJavascriptAbsent_ifDisabled(): void
    {
        //GIVEN
        $this->bootKernel('disabled.yml');
        $form = $this->createContactForm($this->formFactory);

        $template = $this->twig->createTemplate('{{ form_widget(form) }}');

        //WHEN
        $view = $template->render(['form' => $form->createView()]);

        //THEN
        self::assertContains('<input type="hidden" id="contact_captcha" name="contact[captcha]" />', $view);
        self::assertNotContains('<script src="https://www.google.com/recaptcha/api.js?render=key"></script>', $view);
        self::assertNotContains("document.getElementById('contact_captcha').value = token;", $view);
    }

    public function testFormValid_ifEnabled(): void
    {
        //GIVEN
        $container = $this->bootKernel('default.yml');

        /** @var RecaptchaMock $recaptchaMock */
        $recaptchaMock = $container->get('karser_recaptcha3.google.recaptcha');
        $recaptchaMock->nextSuccess = true;

        //WHEN
        $form = $this->createContactForm($this->formFactory);

        //THEN
        $form->submit($this->formData);

        self::assertTrue($form->isSubmitted());
        self::assertTrue($form->isValid());
    }

    public function testFormInvalid_ifCaptchaFails(): void
    {
        //GIVEN
        $container = $this->bootKernel('default.yml');

        /** @var RecaptchaMock $recaptchaMock */
        $recaptchaMock = $container->get('karser_recaptcha3.google.recaptcha');
        $recaptchaMock->nextSuccess = false;
        $recaptchaMock->nextErrorCodes = ['test1', 'test2'];

        $form = $this->createContactForm($this->formFactory);

        //WHEN
        $form->submit($this->formData);

        //THEN
        $this->assertFormHasError($form, 'There were problems with your captcha. Please try again or contact with support and provide following code(s): "test1; test2"');
    }

    public function testFormInvalid_ifCaptchaEmpty(): void
    {
        //GIVEN
        $container = $this->bootKernel('default.yml');

        /** @var RecaptchaMock $recaptchaMock */
        $recaptchaMock = $container->get('karser_recaptcha3.google.recaptcha');
        $recaptchaMock->nextSuccess = false;

        $form = $this->createContactForm($this->formFactory);

        $formData = $this->formData;
        $formData['captcha'] = '';

        //WHEN
        $form->submit($formData);

        //THEN
        $this->assertFormHasError($form, 'The captcha value is missing');
    }

    public function testFormInvalid_ifCaptchaNull(): void
    {
        //GIVEN
        $container = $this->bootKernel('default.yml');

        /** @var RecaptchaMock $recaptchaMock */
        $recaptchaMock = $container->get('karser_recaptcha3.google.recaptcha');
        $recaptchaMock->nextSuccess = false;

        $form = $this->createContactForm($this->formFactory);

        $formData = $this->formData;
        $formData['captcha'] = null;

        //WHEN
        $form->submit($formData);

        //THEN
        $this->assertFormHasError($form, 'The captcha value is missing');
    }

    public function testFormInvalid_ifCaptchaUndefined(): void
    {
        //GIVEN
        $container = $this->bootKernel('default.yml');

        /** @var RecaptchaMock $recaptchaMock */
        $recaptchaMock = $container->get('karser_recaptcha3.google.recaptcha');
        $recaptchaMock->nextSuccess = false;

        $form = $this->createContactForm($this->formFactory);

        $formData = $this->formData;
        unset($formData['captcha']);

        //WHEN
        $form->submit($formData);

        //THEN
        $this->assertFormHasError($form, 'The captcha value is missing');
    }

    public function testFormValid_ifCaptchaFails_butDisabled(): void
    {
        //GIVEN
        $container = $this->bootKernel('disabled.yml');

        /** @var RecaptchaMock $recaptchaMock */
        $recaptchaMock = $container->get('karser_recaptcha3.google.recaptcha');
        $recaptchaMock->nextSuccess = false;

        $form = $this->createContactForm($this->formFactory);

        //WHEN
        $form->submit($this->formData);

        //THEN
        self::assertTrue($form->isSubmitted());
        self::assertTrue($form->isValid());
    }

    /** ------------------------------- METHODS ------------------------------- */

    /**
     * @param FormInterface<mixed> $form
     */
    private function assertFormHasError(FormInterface $form, string $expectedMessage = ''): void
    {
        self::assertTrue($form->isSubmitted());
        self::assertFalse($form->isValid());
        if ('' != $expectedMessage) {
            /* @phpstan-ignore-next-line */
            self::assertSame($expectedMessage, $form->getErrors()[0]->getMessage());
        }
    }

    private function bootKernel(string $config): ContainerInterface
    {
        $this->kernel->setConfigurationFilename(__DIR__.'/fixtures/config/'.$config);
        $this->kernel->boot();
        $container = $this->kernel->getContainer();
        $this->formFactory = $container->get('form.factory');
        $this->twig = $container->get('twig');

        return $container;
    }

    /**
     * @return FormInterface<mixed>
     */
    private function createContactForm(FormFactoryInterface $formFactory): FormInterface
    {
        return $formFactory->createBuilder(ContactType::class, null, [
            'csrf_protection' => false,
        ])->getForm();
    }

    private function generateRandomString(int $length = 10): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; ++$i) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}

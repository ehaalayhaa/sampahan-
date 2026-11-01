<?php

use App\Services\CaptchaService;
use CodeIgniter\Test\CIUnitTestCase;

final class CaptchaServiceTest extends CIUnitTestCase
{
    public function testSelfHostedChallengeIsReusedUntilExpired(): void
    {
        $captcha = (new ReflectionClass(CaptchaService::class))->newInstanceWithoutConstructor();
        $session = service('session');

        $provider = new ReflectionProperty(CaptchaService::class, 'provider');
        $provider->setAccessible(true);
        $provider->setValue($captcha, 'selfhosted');

        $sessionProp = new ReflectionProperty(CaptchaService::class, 'session');
        $sessionProp->setAccessible(true);
        $sessionProp->setValue($captcha, $session);

        $session->set('captcha_answer', '42');
        $session->set('captcha_expiry', time() + 600);
        $session->set('captcha_question', '7 + 5');

        $this->assertSame('7 + 5', $captcha->ensureChallenge());
        $this->assertTrue($captcha->verify('42'));
    }
}

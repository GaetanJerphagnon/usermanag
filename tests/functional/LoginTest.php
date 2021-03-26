<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginTest extends WebTestCase
{

    public function login(string $email, string $password)
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertPageTitleSame('Log in!');

        $crawler = $client->submitForm('Log in', [
            'email' => $email,
            'password' => $password,
        ]);

        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();
        return $client;
    }

    public function testValidLogin(): void
    {
        $client = $this->login('admin@gmail.com', 'admin');
        $this->assertPageTitleSame('Homepage');

    }

    public function testInvalidLogin(): void
    {
        $client = $this->login('admin@gmail.com', 'wrongPassword');
        
        $this->assertStringContainsString('Invalid credentials', $client->getResponse()->getContent());
        $this->assertPageTitleSame('Log in!');

    }

    public function testInvalidEmailLogin(): void
    {
        $client = $this->login('wrong@gmail.com', 'wrongPassword');
        
        $this->assertStringContainsString('Email could not be found', $client->getResponse()->getContent());
        $this->assertPageTitleSame('Log in!');

    }

    public function testNotVerifiedlLogin(): void
    {
        $client = $this->login('not@verified.com', '1234');
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();

        $this->assertStringContainsString('Please check your mails to verify your address', $client->getResponse()->getContent());
        $this->assertPageTitleSame('Log in!');

    }
}

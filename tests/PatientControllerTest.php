<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Patient;
use Doctrine\ORM\EntityManagerInterface;

class PatientControllerTest extends WebTestCase
{
    private $client;
    private $em;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->em->close();
    }


    public function testRegistrationSuccess()
    {
        $this->client->request(
            'POST',
            '/registration',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'email' => 'test_' . uniqid() . '@test.com',
                'password' => 'password123',
                'phoneNumber' => '0600000000'
            ])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $data = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('patientId', $data);
    }

    public function testRegistrationInvalidJson()
    {
        $this->client->request(
            'POST',
            '/registration',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            'invalid json'
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testRegistrationMissingFields()
    {
        $this->client->request(
            'POST',
            '/registration',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'email' => 'test@test.com'
            ])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }


    public function testSearchPatients()
    {
        $this->client->request('GET', '/api/patients/search?query=test');

        $this->assertResponseStatusCodeSame(401);

        $data = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertIsArray($data);
    }


    public function testMedicalRecordUnauthorized()
    {
        $this->client->request('GET', '/api/patient/medicalRecord');

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testChangeMedicalRecordUnauthorized()
    {
        $this->client->request(
            'PUT',
            '/api/medicalrecord/change',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testDeleteMedicalRecordUnauthorized()
    {
        $this->client->request('DELETE', '/api/medicalrecord/delete');

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }


    private function createAuthenticatedUser(): Patient
    {
        $patient = new Patient();
        $patient->setEmail('auth_' . uniqid() . '@test.com');
        $patient->setPassword('password');
        $patient->setPhoneNumber('0600000000');

        $this->em->persist($patient);
        $this->em->flush();

        return $patient;
    }

    public function testGetMedicalRecordAuthenticated()
    {
        $user = $this->createAuthenticatedUser();

        $this->client->loginUser($user);

        $this->client->request('GET', '/api/patient/medicalRecord');

        $this->assertResponseIsSuccessful();
    }

    public function testDeleteMedicalRecordAuthenticated()
    {
        $user = $this->createAuthenticatedUser();

        $this->client->loginUser($user);

        $this->client->request('DELETE', '/api/medicalrecord/delete');

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}
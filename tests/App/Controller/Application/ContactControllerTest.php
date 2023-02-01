<?php

namespace App\Controller\Application;

use App\Entity\Contact;
use App\Services\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client as Guzzle;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ContactControllerTest extends WebTestCase
{
    private ?KernelBrowser $client;
    private ?EntityManager $entityManager;
    private array $data = [
        'name' => null,
        'surname' => null,
        'newsletter' => null,
        'email' => null,
        'contactNumber' => null,
    ];

    protected function setUp(): void {
        parent::setUp();
        $this->client = static::createClient([], ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT' => 'application/json']);
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $this->data = $this->generateRandomData();
    }

    public function testSuccessfulIndex(): void {
        // TODO: Test the index function of the ContactController is callable using GET and POST, returns data in the correct structure and is filterable when posting "filters" JSON object with the form-data
        /** @see ContactViewModel::getFilters for a brief idea of filters available and what to test */
    }

    public function testSuccessfulCreateContact(): void {
        $this->cleanTableOfTestRecord();

        // Create record
        $this->client->xmlHttpRequest('POST', '/api/contacts/create', $this->data);
        self::assertResponseIsSuccessful();
        self::assertResponseFormatSame('json');

        // Check database to ensure record is created
        $records = $this->findMatchingRecords();
        self::assertCount(1, $records);

        self::assertQueuedEmailCount(1);

        $this->cleanTableOfTestRecord();
    }

    public function testUnsuccessfulCreateContact(): void {
        $this->cleanTableOfTestRecord();
        
        $newData = $this->data;
        unset($newData['name']);
        $newData['email'] = 'user';
        $newData['newsletter'] = null;

        $this->client->xmlHttpRequest('POST', '/api/contacts/create', $newData);
        self::assertResponseIsUnprocessable();
        self::assertResponseFormatSame('json');

        // Check database to ensure record is not created
        $records = $this->findMatchingRecords();
        self::assertCount(0, $records);

        $this->cleanTableOfTestRecord();
    }

    public function testSuccessfulReadContact(): void {
        $this->cleanTableOfTestRecord();

        $contact = new Contact();
        foreach ($this->data as $key => $value) {
            $contact->{sp_setter($key)}($value);
        }
        /** @var ValidatorInterface $validator */
        $validator = self::getContainer()->get(ValidatorInterface::class);
        $errors = sp_extract_errors($validator->validate($contact));
        if (!empty($errors)) {
            $this->addWarning('There seem to be errors with the contact - bailing out');
            return;
        }
        $this->entityManager->persist($contact);
        $this->entityManager->flush();
        $this->entityManager->refresh($contact);
        $id = $contact->getId();

        $this->client->request('GET', "/api/contacts/{$id}");
        self::assertResponseIsSuccessful();
        self::assertResponseFormatSame('json');

        $response = json_decode($this->client->getResponse()->getContent(), true);
        self::assertSame($this->data, $response);

        $this->cleanTableOfTestRecord();
    }

    public function testUnsuccessfulReadContact(): void {
        $id = 'random-str';
        $this->client->request('GET', "/api/contacts/{$id}");
        self::assertResponseStatusCodeSame(404);
        self::assertResponseFormatSame('json');
    }

    public function testSuccessfulUpdateContact(): void {
        $this->cleanTableOfTestRecord();

        $contact = new Contact();
        foreach ($this->data as $key => $value) {
            $contact->{sp_setter($key)}($value);
        }
        /** @var ValidatorInterface $validator */
        $validator = self::getContainer()->get(ValidatorInterface::class);
        $errors = sp_extract_errors($validator->validate($contact));
        if (!empty($errors)) {
            $this->addWarning('There seem to be errors with the contact - bailing out');
            return;
        }
        $this->entityManager->persist($contact);
        $this->entityManager->flush();
        $this->entityManager->refresh($contact);
        $id = $contact->getId();

        $newData = $this->generateRandomData();
        $dataToSend = [];
        foreach ($newData as $key => $value) {
            if (random_int(0, 1) === 1) {
                $dataToSend[$key] = $value;
            }
        }
        if (empty($dataToSend)) {
            $dataToSend = $newData;
        }

        $this->client->request('PUT', "/api/contacts/{$id}", $dataToSend);
        self::assertResponseIsSuccessful();
        self::assertResponseFormatSame('json');


        self::assertQueuedEmailCount(1);

        $newRecord = array_merge($this->data, $dataToSend);
        $records = $this->findMatchingRecords($newRecord);
        self::assertCount(1, $records);

        $this->cleanTableOfTestRecord($newRecord);
    }

    public function testUnsuccessfulUpdateContact(): void {
        $this->cleanTableOfTestRecord();

        $contact = new Contact();
        foreach ($this->data as $key => $value) {
            $contact->{sp_setter($key)}($value);
        }
        /** @var ValidatorInterface $validator */
        $validator = self::getContainer()->get(ValidatorInterface::class);
        $errors = sp_extract_errors($validator->validate($contact));
        if (!empty($errors)) {
            $this->addWarning('There seem to be errors with the contact - bailing out');
            return;
        }
        $this->entityManager->persist($contact);
        $this->entityManager->flush();
        $this->entityManager->refresh($contact);
        $id = $contact->getId();

        $this->client->request('PUT', "/api/contacts/{$id}", ['email' => 'fail']);
        self::assertResponseIsUnprocessable();
        self::assertResponseFormatSame('json');

        // record should remain unchanged
        $records = $this->findMatchingRecords($this->data);
        self::assertCount(1, $records);


        $id = 'random-id';
        $this->client->request('PUT', "/api/contacts/{$id}", $this->data);
        self::assertResponseStatusCodeSame(404);

        $this->cleanTableOfTestRecord();
    }

    public function testSuccessfulDeleteContact(): void {
        $this->cleanTableOfTestRecord();

        $contact = new Contact();
        foreach ($this->data as $key => $value) {
            $contact->{sp_setter($key)}($value);
        }
        /** @var ValidatorInterface $validator */
        $validator = self::getContainer()->get(ValidatorInterface::class);
        $errors = sp_extract_errors($validator->validate($contact));
        if (!empty($errors)) {
            $this->addWarning('There seem to be errors with the contact - bailing out');
            return;
        }
        $this->entityManager->persist($contact);
        $this->entityManager->flush();
        $this->entityManager->refresh($contact);
        $id = $contact->getId();

        $this->client->request('DELETE', "/api/contacts/{$id}");
        self::assertResponseIsSuccessful();
        self::assertResponseFormatSame('json');

        // record should remain unchanged
        $records = $this->findMatchingRecords($this->data);
        self::assertCount(0, $records);

        $this->cleanTableOfTestRecord();
    }

    public function testUnsuccessfulDeleteContact(): void {
        $this->cleanTableOfTestRecord();

        $contact = new Contact();
        foreach ($this->data as $key => $value) {
            $contact->{sp_setter($key)}($value);
        }
        /** @var ValidatorInterface $validator */
        $validator = self::getContainer()->get(ValidatorInterface::class);
        $errors = sp_extract_errors($validator->validate($contact));
        if (!empty($errors)) {
            $this->addWarning('There seem to be errors with the contact - bailing out');
            return;
        }
        $this->entityManager->persist($contact);
        $this->entityManager->flush();

        $id = 'random-id';

        $this->client->request('DELETE', "/api/contacts/{$id}");
        self::assertResponseStatusCodeSame(404);
        self::assertResponseFormatSame('json');

        // record should remain unchanged
        $records = $this->findMatchingRecords($this->data);
        self::assertCount(1, $records);

        $this->cleanTableOfTestRecord();
    }

    private function findMatchingRecords(?array $data = null): array {
        if ($data === null) {
            $data = $this->data;
        }

        // Ensure that above record is not already in the database
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $recordsQb = $queryBuilder
            ->select('c')
            ->from(Contact::class, 'c');

        foreach ($data as $key => $value) {
            $recordsQb->andWhere($queryBuilder->expr()->eq("c.$key", ":$key"));
            $recordsQb->setParameter(":$key", $value);
        }
        return $recordsQb->getQuery()->getResult();
    }

    private function cleanTableOfTestRecord(?array $data = null): void {
        $records = $this->findMatchingRecords($data);

        // If it is in the database remove it for the test
        foreach ($records ?? [] as $contact) {
            $this->entityManager->remove($contact);
        }
        if (count($records) > 0) {
            $this->entityManager->flush();
        }
    }

    private function generateRandomData(): array {
        $guzzleClient = new Guzzle();
        $response = $guzzleClient->get("https://random-data-api.com/api/v2/users");
        $responseData = json_decode($response->getBody()->getContents(), true);

        return [
            'name' => $responseData['first_name'],
            'surname' => $responseData['last_name'],
            'email' => str_replace(['\'', '`', ''], '', $responseData['email']),
            'contactNumber' => str_replace([' ', '-', '(', ')'], '', preg_replace('/x\d+/', '', $responseData['phone_number'])),
            'newsletter' => $responseData['id'] > 5000
        ];
    }
}

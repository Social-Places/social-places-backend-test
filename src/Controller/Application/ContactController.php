<?php

namespace App\Controller\Application;

use App\Attributes\ImportExportAttribute;
use App\Attributes\ImportProcessorAttribute;
use App\Entity\Contact;
use App\ViewModels\ContactViewModel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ContactController extends BaseVueController
{
    protected ?string $entity = ContactViewModel::class;

    public function __construct(
        SerializerInterface $serializer,
        Security $security,
        ParameterBagInterface $parameterBag,
        RequestStack $requestStack,
        EntityManagerInterface $entityManager,
        RouterInterface $router,
        private readonly ValidatorInterface $validator
    ) {
        parent::__construct($serializer,$security,$parameterBag,$requestStack,$entityManager,$router);
    }

    #[Route('/api/contacts/index', name: 'api_contacts', methods: ['POST', 'GET'])]
    public function index(Request $request): JsonResponse {
        return $this->_index($request);
    }

    #[Route('/api/contacts/create', name: 'api_contacts_create', methods: 'POST')]
    public function createContact(Request $request): JsonResponse {
        // TODO: Accepting a POST request on the route provided, create the necessary record for the Entity\Model returning the necessary information, alert and status code.
        // TODO: Important: Validation
        // TODO: Email
        return $this->json([]);
    }

    #[Route('/api/contacts/{contact}', name: 'api_contacts_read', methods: 'GET')]
    public function readContact(Request $request, ?Contact $contact = null): JsonResponse {
        // TODO: Accepting a GET request with an identifier and handling any issues there with, return the expected data of the record.
        return $this->json([]);
    }

    #[Route('/api/contacts/{contact}', name: 'api_contacts_update', methods: ['POST', 'PUT'])]
    public function updateContact(Request $request, ?Contact $contact = null): JsonResponse {
        // TODO: Accepting a PUT\POST request on the route, update the record and handle the errors and validation as required.
        return $this->json([]);
    }

    #[Route('/api/contacts/{contact}', name: 'api_contacts_delete', methods: 'DELETE')]
    public function deleteContact(Request $request, ?Contact $contact = null): JsonResponse {
        // TODO: Accepting a DELETE request, remove the record from the database.
        return $this->json([]);
    }

    // <editor-fold desc="Helpful or supporting code">
    #[Route('/api/contacts/filters', name: 'api_contacts_filters', methods: ['POST', 'GET'])]
    public function getFilters(): JsonResponse {
        return $this->_getFilters();
    }

    protected function getBaseResults(Request $request): array {
        return $this->_getBaseResults($request);
    }
    // </editor-fold>
}

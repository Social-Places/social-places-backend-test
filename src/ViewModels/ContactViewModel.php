<?php

namespace App\ViewModels;

use App\Classes\Column;
use App\Classes\Filter;
use App\Constants\ApplicationConstants;
use App\Entity\Contact;
use App\Enums\StoreStatus;
use App\ViewModels\Options\QueryOptions;
use Doctrine\ORM\QueryBuilder;

class ContactViewModel extends BaseTableViewModel
{
    public const SESSION = 'contacts';
    public const ALIAS = 'c';
    public const RELATING_ENTITY = Contact::class;

    public function hasAccess(?array $options = []): bool {
        return true;
    }

    public function getFilters(?array $options = [], $filterOptions = null): array {
        return [
            Filter::createFilter('Newsletter', 'newsletter')
                ->setField('newsletter')
                ->setMultiple(true)
                ->setData(ApplicationConstants::YES_NO_CHOICES)
                ->setExpression(Filter::AND)
        ];
    }

    public function getColumns(?array $options = [], $columnOptions = null): array {
        return [
            Column::createColumn('Name', 'center', true, 'name')->setDefaultASC(),
            Column::createColumn('Surname', 'center', true, 'surname'),
            Column::createColumn('Email', 'center', true, 'email'),
            Column::actionColumn(),
        ];
    }

    public function getQuery(?array $options = [], ?QueryOptions $queryOptions = null): QueryBuilder {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select(self::getAlias())->from(self::RELATING_ENTITY, self::getAlias());
        return $qb;
    }

    /**
     * @param Contact $item
     * @param array|null $options
     * @return array
     */
    public function processResult($item, ?array $options = []): array {
        return [
            'id' => $item->getId(),
            'name' => $item->getName(),
            'surname' => $item->getSurname(),
            'email' => $item->getEmail(),
            'newsletter' => $item->getNewsletter() ? 'Yes' : 'No',
        ];
    }

    public function getSearchableFields(?array $options = [], $searchableFieldOptions = null): array {
       return [];
    }

    public function getExportHeadings(?array $options = []): array {
        return [];
    }

    public function getExportRow($item, ?array $options = [], $exportRowOptions = null): array {
        return [];
    }
}

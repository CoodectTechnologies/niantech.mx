<?php

namespace App\Services\Integrations\Odoo\Customer;

use App\DTO\Integrations\Odoo\Customer\CustomerDTO;
use App\Integrations\Odoo;
use App\Models\Country;
use App\Models\User;
use Generator;

class CustomerService
{
    protected Odoo $odoo;

    public function __construct() {
        $this->odoo = new Odoo;
    }
    public function find(int $customerId): array {
        $result = $this->odoo->getCustomers(domain: [['id', '=', $customerId]]);
        $customerDto = CustomerDTO::handle($result[0] ?? []);

        return $customerDto->toArray() ?? [];
    }
    public function findByEmail(string $email): array {
        $result = $this->odoo->getCustomers(domain: [['email', '=', $email]]);
        $customerDto = CustomerDTO::handle($result[0] ?? []);

        return $customerDto->toArray() ?? [];
    }
    public function getByEmails(array $emails): array {
        if (empty($emails)) {
            return [];
        }
        $result = $this->odoo->getCustomers(domain: [['email', 'in', $emails]]);
        $mapped = [];
        foreach ($result as $customerData) {
            $customerDto = CustomerDTO::handle($customerData);
            $mapped[$customerDto->email] = $customerDto->toArray();
        }

        return $mapped;
    }
    public function getAll(array $domain = [], array $params = [], bool $singleRequest = false): Generator {
        $page = $params['page'] ?? 1;
        $perPage = $params['per_page'] ?? 1000;

        do {
            $result = $this->getPaginated($page, $perPage, $domain, $params);
            $hasNext = $result['paging']['has_next'];
            $page++;
            yield $result['data'];
        } while ($hasNext && ! $singleRequest);
    }
    public function getPaginated(int $page, int $perPage, array $domain = [], array $params = []): array {
        $paginated = [];
        $offset = ($page - 1) * $perPage;
        unset($params['page'], $params['per_page']);
        $params = array_merge($params, [
            'offset' => $offset,
            'limit' => $perPage,
        ]);
        $rawCustomers = $this->odoo->getCustomers(domain: $domain, params: $params);
        foreach ($rawCustomers as $customerData) {
            $customerDto = CustomerDTO::handle($customerData);
            $paginated[$customerDto->providerId] = $customerDto->toArray();
        }

        return [
            'data' => $paginated,
            'paging' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'has_next' => count($rawCustomers) === $perPage,
            ],
        ];
    }
    public function save(User $user): array {
        $customer = [];
        $email = strtolower(trim(strval($user->email)));
        if (empty($email) || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $customer;
        }

        $data = [
            'country_id' => $user->country->provider_id ?? Country::query()->validate()->where('default', true)->first()->id ?? null,
            'name' => $user->name,
            'email' => $email,
            'phone' => $user->phone,
        ];

        if (! $user->provider_id) {
            $customer = $this->findByEmail($email); // Si ya existe en odoo pero no en la web
            if (! isset($customer['provider_id']) || ! $customer['provider_id']) {
                $customer = $this->create($data);
            }
        } else {
            $customer = $this->update($user->provider_id, $data);
        }

        return $customer;
    }
    public function create(array $data): array {
        $customerIds = $this->odoo->createCustomer($data);
        if (($customerId = $customerIds[0] ?? null)) {
            $customer = $this->find($customerId);

            return $customer;
        }

        return [];
    }
    public function update(int $customerId, array $data): array {
        $updated = $this->odoo->updateCustomer($customerId, $data);
        if ($updated) {
            $customer = $this->find($customerId);

            return $customer;
        }

        return [];
    }
}

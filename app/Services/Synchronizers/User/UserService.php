<?php

namespace App\Services\Synchronizers\User;

use App\Enums\Role\Role as EnumsRole;
use App\Exceptions\OdooException;
use App\Http\Controllers\Controller;
use App\Integrations\Odoo;
use App\Models\Country;
use App\Models\User;
use App\Services\Integrations\Odoo\Customer\CustomerService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class UserService extends Controller
{
    protected CustomerService $customerService;
    protected array $countriesAvailable = [];
    protected ?int $defaultCountryId = null;

    public function __construct() {
        $this->customerService = new CustomerService;
        $this->loadCountries();
    }
    public function save(): array {
        return activity()->withoutLogs(function () {
            $startTime = microtime(true);
            $result = [
                'created' => 0,
                'updated' => 0,
                'skipped' => 0,
                'failed' => 0,
            ];

            $this->syncProviderToLocal($result);
            $this->syncLocalToProvider($result);

            $result['time'] = microtime(true) - $startTime;

            return $result;
        });
    }
    protected function syncProviderToLocal(array &$result): void {
        $countriesAvaliables = $this->countriesAvailable;

        foreach ($this->customerService->getAll() as $customers) {
            $emails = array_filter(array_map(fn ($c) => strtolower(trim($c['email'] ?? '')), $customers));

            $localUsers = User::query()
                ->whereIn('email', $emails)
                ->orWhere(function ($query) use ($customers) {
                    foreach ($customers as $c) {
                        if (! empty($c['provider_id']) && ! empty($c['provider'])) {
                            $query->orWhere(function ($q) use ($c) {
                                $q->where('provider', $c['provider'])->where('provider_id', $c['provider_id']);
                            });
                        }
                    }
                })
                ->get()
                ->collect();

            foreach ($customers as $customer) {
                try {
                    if (! $this->isSyncable($customer, $countriesAvaliables)) {
                        $result['skipped'] += 1;

                        continue;
                    }

                    $user = $localUsers->first(function ($u) use ($customer) {
                        return ($u->provider === $customer['provider'] && $u->provider_id == $customer['provider_id'])
                            || strtolower(trim($u->email)) === strtolower(trim($customer['email']));
                    });

                    if (! $user) {
                        $this->createLocal($customer, $countriesAvaliables);
                        $result['created'] += 1;

                        continue;
                    }

                    $isUpdated = $this->updateLocal($user, $customer);
                    if ($isUpdated) {
                        $result['updated'] += 1;
                    }
                } catch (OdooException $e) {
                    $result['failed'] += 1;
                    Log::channel('odoo.general')->error('Error syncing customer: '.$e->getMessage(), [
                        'customer' => $customer,
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                    ]);
                } catch (Throwable $e) {
                    $result['failed'] += 1;
                    Log::channel('odoo.general')->error('Error syncing customer: '.$e->getMessage(), [
                        'customer' => $customer,
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                    ]);
                }
            }
        }
    }
    protected function syncLocalToProvider(array &$result): void {
        $countriesAvaliables = $this->countriesAvailable;
        $defaultCountryId = $this->defaultCountryId;

        User::query()
            ->where(function ($query) {
                $query->whereNull('provider_id')->orWhere('provider_id', '');
            })
            ->where(function ($query) {
                $query->whereNull('provider')->orWhere('provider', '')->orWhere('provider', Odoo::$code);
            })
            ->orderBy('id')
            ->chunkById(200, function ($users) use ($countriesAvaliables, $defaultCountryId, &$result) {
                $emails = $users->map(fn ($u) => strtolower(trim($u->email)))->toArray();
                $odooCustomers = $this->customerService->getByEmails($emails);
                foreach ($users as $user) {
                    try {
                        if (! $this->isSyncableUser($user)) {
                            $result['skipped'] += 1;

                            continue;
                        }

                        $email = strtolower(trim($user->email));
                        $customer = $odooCustomers[$email] ?? [];

                        $data = [
                            'country_id' => $countriesAvaliables[$customer['country_id'] ?? '']['id'] ?? $defaultCountryId,
                            'name' => $user->name,
                            'email' => $email,
                            'phone' => $user->phone,
                        ];

                        if (! ($customer['provider_id'] ?? false)) {
                            $customer = $this->customerService->create($data);
                        } else {
                            $customer = $this->customerService->update((int) $customer['provider_id'], $data);
                        }

                        if (empty($customer['provider_id'])) {
                            $result['failed'] += 1;
                            Log::channel('odoo.general')->error('Error syncing local user to provider: customer was not created or updated.', [
                                'user_id' => $user->id,
                                'email' => $email,
                            ]);

                            continue;
                        }

                        if ($this->updateLocal($user, $customer)) {
                            $result['updated'] += 1;
                        }
                    } catch (Throwable $e) {
                        $result['failed'] += 1;
                        Log::channel('odoo.general')->error('Error syncing local user to provider: '.$e->getMessage(), [
                            'user_id' => $user->id,
                            'email' => $user->email,
                            'file' => $e->getFile(),
                            'line' => $e->getLine(),
                        ]);
                    }
                }
            });
    }
    protected function isSyncable(array $customer, array $countriesAvaliables): bool {
        $email = $customer['email'] ?? null;
        if (empty($email) || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        if ($customer['country_id'] && ! in_array($customer['country_id'], array_keys($countriesAvaliables))) {
            return false;
        }

        return true;
    }
    protected function isSyncableUser(User $user): bool {
        return ! empty(trim(strval($user->name))) && ! empty($user->email) && filter_var($user->email, FILTER_VALIDATE_EMAIL);
    }
    protected function findUserLocal(array $customer): ?User {
        $user = User::query()
            ->where('provider', $customer['provider'])
            ->where('provider_id', $customer['provider_id'])
            ->first();

        if ($user) {
            return $user;
        }

        return User::query()
            ->where('email', $customer['email'])
            ->first();
    }
    protected function createLocal(array $customer, array $countriesAvaliables): User {
        $name = $this->getName($customer);
        $user = User::createQuietly([
            'country_id' => $countriesAvaliables[$customer['country_id']]['id'] ?? $this->defaultCountryId,
            'name' => $name,
            'slug' => $this->buildUniqueSlug($name),
            'email' => $customer['email'],
            'phone' => $customer['phone'],
            'provider' => $customer['provider'],
            'provider_id' => $customer['provider_id'],
            'password' => null,
            'email_verified_at' => now(),
        ]);
        $user->assignRole(EnumsRole::CLIENT->value);

        return $user;
    }
    protected function updateLocal(User $user, array $customer): bool {
        $isUpdated = false;
        $newEmail = strtolower(trim($customer['email']));
        $newName = $this->getName($customer);

        if ($user->provider != $customer['provider']) {
            $user->provider = $customer['provider'];
            $isUpdated = true;
        }
        if ($user->provider_id != $customer['provider_id']) {
            $user->provider_id = $customer['provider_id'];
            $isUpdated = true;
        }
        $newCountryId = $this->countriesAvailable[$customer['country_id']]['id'] ?? $this->defaultCountryId;
        if ($newCountryId && $user->country_id != $newCountryId) {
            $user->country_id = $newCountryId;
            $isUpdated = true;
        }
        if ($user->name != $newName) {
            $user->name = $newName;
            $user->slug = $this->buildUniqueSlug($newName, $user->id);
            $isUpdated = true;
        }
        if ($user->email != $newEmail) {
            $existsWithEmail = User::query()->where('email', $newEmail)->where('id', '!=', $user->id)->exists();
            if (! $existsWithEmail) {
                $user->email = $newEmail;
                $isUpdated = true;
            }
        }
        if ($customer['phone'] && ($user->phone != $customer['phone'])) {
            $user->phone = $customer['phone'];
            $isUpdated = true;
        }
        if (! $user->email_verified_at) {
            $user->email_verified_at = now();
            $isUpdated = true;
        }
        if ($isUpdated) {
            $user->saveQuietly();
        }
        if (! $user->hasRole(EnumsRole::CLIENT->value) && ! $user->hasRole(EnumsRole::ADMINISTRATOR->value)) {
            $user->assignRole(EnumsRole::CLIENT->value);
        }

        return $isUpdated;
    }
    protected function getName(array $customer): string {
        $name = trim(strval($customer['name'] ?? ''));
        if ($name) {
            return $name;
        }

        return trim(strval($customer['display_name'] ?? 'Cliente sin nombre'));
    }
    protected function buildUniqueSlug(string $name, ?int $ignoreUserId = null): string {
        $base = Str::slug($name);
        if (empty($base)) {
            $base = 'cliente-sin-nombre';
        }

        $slug = $base;
        $suffix = 2;
        while ($this->slugExists($slug, $ignoreUserId)) {
            $slug = $base.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }
    protected function slugExists(string $slug, ?int $ignoreUserId = null): bool {
        $query = User::query()->where('slug', $slug);
        if ($ignoreUserId) {
            $query->where('id', '!=', $ignoreUserId);
        }

        return $query->exists();
    }
    protected function loadCountries(): void {
        $countries = Country::query()->validate()->get(['id', 'provider_id', 'default']);
        $this->countriesAvailable = $countries->keyBy('provider_id')->toArray();
        $this->defaultCountryId = $countries->firstWhere('default', true)?->id ?? null;
    }
}

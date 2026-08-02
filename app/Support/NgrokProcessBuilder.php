<?php

namespace App\Support;

use JnJairo\Laravel\Ngrok\NgrokProcessBuilder as BaseNgrokProcessBuilder;
use Symfony\Component\Process\Process;

class NgrokProcessBuilder extends BaseNgrokProcessBuilder
{
    /**
     * Build ngrok command.
     *
     * @param string $hostHeader
     * @param string $port
     * @param string $host
     * @param array<int, string> $extra
     * @return \Symfony\Component\Process\Process
     */
    public function buildProcess(
        string $hostHeader = '',
        string $port = '80',
        string $host = '',
        array $extra = [],
    ): Process {
        $extra = $this->applyDefaultReservedDomain($extra);

        return parent::buildProcess($hostHeader, $port, $host, $extra);
    }

    /**
     * Add --url option when a reserved ngrok domain is configured.
     *
     * @param array<int, string> $extra
     * @return array<int, string>
     */
    private function applyDefaultReservedDomain(array $extra): array
    {
        if ($this->hasOption($extra, '--url') || $this->hasOption($extra, '--domain')) {
            return $extra;
        }

        $domain = config('ngrok.reserved_domain');

        if (! is_string($domain) || trim($domain) === '') {
            return $extra;
        }

        $normalizedDomain = $this->normalizeDomain($domain);

        if ($normalizedDomain === '') {
            return $extra;
        }

        $extra[] = '--url=' . $normalizedDomain;

        return $extra;
    }

    /**
     * Check if an option already exists in extra args.
     *
     * @param array<int, string> $extra
     */
    private function hasOption(array $extra, string $option): bool
    {
        foreach ($extra as $argument) {
            if (! is_string($argument)) {
                continue;
            }

            if ($argument === $option || str_starts_with($argument, $option . '=')) {
                return true;
            }
        }

        return false;
    }

    /**
     * Normalize value to ngrok supported url/domain format.
     */
    private function normalizeDomain(string $domain): string
    {
        $domain = trim($domain, " \t\n\r\0\x0B\"'");

        if ($domain === '') {
            return '';
        }

        if (str_contains($domain, '://')) {
            $parsed = parse_url($domain, PHP_URL_HOST);

            if (! is_string($parsed) || $parsed === '') {
                return '';
            }

            return $parsed;
        }

        return $domain;
    }
}

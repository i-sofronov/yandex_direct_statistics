<?php

namespace App\Http\Clients;

use GuzzleHttp\Client;
use Carbon\Carbon;
use Exception;

class YandexDirectClient
{
    private Client $httpClient;
    private ?string $accessToken = null;

    public function __construct()
    {
        $this->httpClient = new Client([
            'base_uri' => 'https://api.direct.yandex.com/',
            'timeout' => 30,
        ]);
    }

    public function setAccessToken(string $token): self
    {
        $this->accessToken = $token;
        return $this;
    }

    public function getAccountInfo(): array
    {
        $this->ensureTokenSet();

        $accountResponse = $this->httpClient->post('live/v4/json', [
            'headers' => [
                'Authorization' => "Bearer {$this->accessToken}",
            ],
            'json' => [
                'method' => 'AccountManagement',
                'token' => $this->accessToken,
                'param' => [
                    'Action' => 'Get'
                ]
            ]
        ]);

        $accountInfo = json_decode($accountResponse->getBody(), true);

        if (isset($accountInfo['error'])) {
            throw new Exception("Yandex Direct API error: " . $accountInfo['error']['error_string']);
        }

        if(!isset($accountInfo['data']['Accounts']) || !isset($accountInfo['data']['Accounts'][0])){
            throw new Exception("Yandex Direct API error: Can't get account info");
        }

        return $accountInfo['data']['Accounts'][0];
    }

    public function getOrganizations(): array
    {
        $this->ensureTokenSet();

        try {
            $response = $this->httpClient->post('json/v5/businesses', [
                'headers' => [
                    'Authorization' => "Bearer {$this->accessToken}",
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'method' => 'get',
                    'params' => [
                        'FieldNames' => ['Id', 'Name'],
                    ]
                ]
            ]);

            return $this->handleResponse($response);
        } catch (Exception $e) {
            return [];
        }
    }

    public function getAllTimeStatistics(array $fieldNames = []): array
    {
        $this->ensureTokenSet();

        $defaultFields = [
            'Date',
            'CampaignId',
            'CampaignName',
            'Impressions',
            'Clicks',
            'Ctr',
            'Cost',
            'Conversions',
            'CostPerConversion',
            'AvgCpc',
        ];

        $response = $this->httpClient->post('json/v5/reports', [
            'headers' => [
                'Authorization' => "Bearer {$this->accessToken}",
                'Content-Type' => 'application/json',
                'processingMode' => 'auto'
            ],
            'json' => [
                'params' => [
                    'SelectionCriteria' => (object) [],
                    'FieldNames' => $defaultFields,
                    'ReportName' => 'Campaign Statistics',
                    'ReportType' => 'CAMPAIGN_PERFORMANCE_REPORT',
                    'Format' => 'TSV',
                    'IncludeVAT' => 'NO',
                    'DateRangeType' => 'ALL_TIME',
                ]
            ]
        ]);

        $statusCode = $response->getStatusCode();
        $headers = $response->getHeaders();

        switch ($statusCode){
            case 200:
                return $this->parseReport($response->getBody()->getContents());

            case 201:
            case 202:
                $retryIn = isset($headers['retryIn'][0]) ? (int)$headers['retryIn'][0] : 1;
                sleep($retryIn);
                return $this->getAllTimeStatistics($fieldNames);

            case 400:
                $errorContent = $response->getBody()->getContents();
                throw new \Exception("API Error 400: " . $errorContent);

            case 500:
                $errorContent = $response->getBody()->getContents();
                throw new \Exception("Server Error 500: " . $errorContent);

            default:
                throw new \Exception("Unexpected status code: " . $statusCode);
        }
    }

    public function getStatisticsByDateRange(Carbon $from, Carbon $to, ?string $clientLogin = null): array
    {
        $this->ensureTokenSet();

        $response = $this->httpClient->post('json/v5/reports', [
            'headers' => [
                'Authorization' => "Bearer {$this->accessToken}",
            ],
            'json' => [
                'params' => [
                    'SelectionCriteria' => [
                        'DateFrom' => $from->format('Y-m-d'),
                        'DateTo' => $to->format('Y-m-d'),
                    ],
                    'FieldNames' => [
                        'Date',
                        'CampaignId',
                        'CampaignName',
                        'Impressions',
                        'Clicks',
                        'Ctr',
                        'Cost',
                        'Conversions',
                        'CostPerConversion',
                        'AvgCpc',
                    ],
                    'ReportName' => 'Campaign Statistics ' . date('Y-m-d H:i:s'),
                    'ReportType' => 'CAMPAIGN_PERFORMANCE_REPORT',
                    'DateRangeType' => 'CUSTOM_DATE',
                    'Format' => 'TSV',
                    'IncludeVAT' => 'NO',
                ]
            ]
        ]);

        return $this->parseReport($response->getBody()->getContents());
    }

    public function getCampaigns(?string $clientLogin = null, ?array $campaignsIds = null): array
    {
        $this->ensureTokenSet();

        $response = $this->httpClient->post('json/v5/campaigns', [
            'headers' => $this->buildHeaders($clientLogin),
            'json' => [
                'method' => 'get',
                'params' => [
                    'FieldNames' => ['Id', 'Name', 'Funds'],
                    'SelectionCriteria' => $campaignsIds ? [
                        'Ids' => $campaignsIds
                    ] : (object)[],
                ]
            ]
        ]);

        return $this->handleResponse($response);
    }

    public function testConnection(): bool
    {
        try {
            $this->getAccountInfo();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    private function buildHeaders(?string $clientLogin = null): array
    {
        $headers = [
            'Authorization' => "Bearer {$this->accessToken}",
            'Content-Type' => 'application/json',
        ];

        if ($clientLogin) {
            $headers['Client-Login'] = $clientLogin;
        }

        return $headers;
    }

    private function ensureTokenSet(): void
    {
        if (!$this->accessToken) {
            throw new \RuntimeException('Access token not set for Yandex Direct');
        }
    }

    private function handleResponse($response): array
    {
        $data = json_decode($response->getBody(), true);

        if (isset($data['error'])) {
            throw new Exception("Yandex Direct API error: " . $data['error']['error_string']);
        }

        return $data['result'] ?? [];
    }

    private function parseReport(string $tsvData): array
    {
        $lines = explode("\n", trim($tsvData));
        $data = [];
        $headers = [];
        $inDataSection = false;

        foreach ($lines as $line) {
            $line = trim($line);

            if (empty($line)) {
                continue;
            }

            if (str_starts_with($line, 'Initial Sync') ||
                str_starts_with($line, 'Total rows:') ||
                str_starts_with($line, 'This report was') ||
                str_starts_with($line, 'Report fields:')) {
                continue;
            }

            if (str_contains($line, "Date\t") && str_contains($line, "CampaignId\t")) {
                $headerLine = str_replace("Ctrl\t", "Ctr\t", $line);
                $headers = str_getcsv($headerLine, "\t");
                $inDataSection = true;
                continue;
            }

            if ($inDataSection && !empty($headers)) {
                $values = str_getcsv($line, "\t");

                if (count($values) === count($headers)) {
                    $row = array_combine($headers, $values);
                    $row = $this->normalizeRowData($row);

                    $data[] = $row;
                }
            }
        }

        return $data;
    }

    private function normalizeRowData(array $row): array
    {
        if (isset($row['Date'])) {
            $row['Date'] = date('Y-m-d', strtotime($row['Date']));
        }

        foreach ($row as $key => &$value) {
            if ($key === 'Date' || $key === 'CampaignName') {
                continue;
            }

            if ($value === '--' || $value === '' || $value === null) {
                $value = 0;
                continue;
            }

            switch ($key) {
                case 'CampaignId':
                    $value = (int)$value;
                    break;

                case 'Impressions':
                case 'Clicks':
                case 'Conversions':
                    $value = (int)str_replace([' ', ','], '', $value);
                    break;

                case 'Cost':
                case 'CostPerConversion':
                case 'AvgCpc':
                    $cost = (float)str_replace([' ', ','], '', $value);
                    $value = $cost / 1000000;
                    break;

                case 'Ctr':
                    if (str_contains($value, '%')) {
                        $value = (float)str_replace(['%', ' ', ','], '', $value) / 100;
                    } else {
                        $value = (float)str_replace([' ', ','], '', $value);
                    }
                    break;

                default:
                    if (is_numeric($value)) {
                        $value = (float)$value;
                    }
                    break;
            }
        }

        return $row;
    }
}

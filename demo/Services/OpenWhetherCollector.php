<?php

namespace BadPixxel\Widgets\Demo\Services;

use DateTime;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Webmozart\Assert\Assert;

class OpenWhetherCollector
{
    const API_URL = "https://archive-api.open-meteo.com/v1/archive";

    /**
     * @var string
     */
    private string $timezone = 'Europe/Paris';

    public function __construct(
        private readonly CacheInterface $appCache,
    ) {
    }

    /**
     * Fetch historical temperature data for Paris over a defined date range using Open-Meteo API.
     *
     * @param DateTime $startDate Start date
     * @param DateTime $endDate End date
     * @param string $interval Interval for data points
     *
     * @return array|null Returns an associative array with date => temperature or null on failure.
     */
    public function fetchTemperatureHistory(
        string $cityName,
        DateTime $startDate,
        DateTime $endDate,
        string $interval = 'd'
    ): ?array
    {
        //==============================================================================
        // Build query parameters
        $params = array_replace_recursive(
            $this->getFrCityCoordinates($cityName),
            $this->getDatesParameter($startDate, $endDate),
            $this->getGroupByParameter($interval),
        );
        //==============================================================================
        // Execute the cURL request
        $response = $this->request($params);
        //==============================================================================
        // Build Dataset
        return $this->doDataset($response ?? array());
    }

    /**
     * Retrieve GPS coordinates (latitude and longitude) of the largest cities in France.
     *
     * @return array<string, array<string, float>>
     */
    public function getFrCitiesCoordinates(): array
    {
        return array(
            'Paris' => array('latitude' => 48.8566, 'longitude' => 2.3522),
            'Marseille' => array('latitude' => 43.2965, 'longitude' => 5.3698),
            'Lyon' => array('latitude' => 45.7640, 'longitude' => 4.8357),
            'Toulouse' => array('latitude' => 43.6045, 'longitude' => 1.4442),
            'Nice' => array('latitude' => 43.7102, 'longitude' => 7.2620),
            'Nantes' => array('latitude' => 47.2184, 'longitude' => -1.5536),
            'Strasbourg' => array('latitude' => 48.5734, 'longitude' => 7.7521),
            'Montpellier' => array('latitude' => 43.6108, 'longitude' => 3.8767),
            'Bordeaux' => array('latitude' => 44.8378, 'longitude' => -0.5792),
            'Lille' => array('latitude' => 50.6292, 'longitude' => 3.0573),
        );
    }

    /**
     * Retrieve GPS coordinates (latitude and longitude) of the city.
     *
     * @return array<string, float>
     */
    public function getFrCityCoordinates(?string $cityName): array
    {
        return $this->getFrCitiesCoordinates()[$cityName]
            ?? array('latitude' => 48.8566, 'longitude' => 2.3522)
        ;
    }

    /**
     *
     */
    private function doDataset(array $data): array
    {
        //==============================================================================
        // Build Daily Records Dataset
        if (($daily = ($data['daily'] ?? null)) && is_array($daily)) {
            $temperatureHistory = [];
            $time = $daily['time'] ?? null;
            foreach (is_array($time) ? $time : array() as $index => $date) {
                /**
                 * @var array<string, array<scalar, float>> $daily
                 */
                $temperatureHistory[$index] = [
                    'date' => $date,
                    'min' => $daily['temperature_2m_min'][$index],
                    'max' => $daily['temperature_2m_max'][$index],
                    'avg' => round(
                        ($daily['temperature_2m_min'][$index] + $daily['temperature_2m_max'][$index]) /2,
                        2
                    ),
                ];
            }

            return $temperatureHistory;
        }
        //==============================================================================
        // Build Hourly Records Dataset
        if (($hourly = ($data['hourly'] ?? null)) && is_array($hourly)) {
            $temperatureHistory = [];
            $time = $hourly['time'] ?? null;
            foreach (is_array($time) ? $time : array() as $index => $date) {
                /**
                 * @var string $date
                 * @var array<string, array<scalar, float>> $hourly
                 */
                $temperatureHistory[$index] = [
                    'date' => substr($date, -5),
                    'avg' => $hourly['temperature_2m'][$index],
                ];
            }

            return $temperatureHistory;
        }

        return array();
    }

    /**
     *
     */
    private function getDatesParameter(DateTime $start, Datetime $end): array
    {
        $now = new DateTime("-1 hour");
        if ($end > $now) {
            $end = $now;
        }

        return array(
            'start_date' => $start->format('Y-m-d'),
            'end_date' => $end->format('Y-m-d'),
        );

    }

    /**
     *
     */
    private function getGroupByParameter(string $groupBy = 'd'): array {
        Assert::inArray($groupBy, ['h', 'd', 'w', 'm']);

        return match($groupBy) {
            'h' => array('hourly' => 'temperature_2m'),
            default => array('daily' => 'temperature_2m_min,temperature_2m_max'),
        };

    }

    /**
     *
     */
    private function request(array $parameters): ?array
    {
        //==============================================================================
        // Build Request Url
        $parameters['timezone'] ??= $this->timezone;
        $url = self::API_URL . "?" . http_build_query($parameters);
        //==============================================================================
        // Build Cache Key
        $cacheKey = sprintf("%s-%s", md5(self::class), md5($url));
        //==============================================================================
        // The callable will only be executed on a cache miss.
        return $this->appCache->get($cacheKey, function (ItemInterface $item) use ($url): ?array {
            $item->expiresAfter(60);

            return self::executeRequest($url);
        });
    }

    /**
     * Executes a cURL request to the specified URL and processes the JSON response.
     *
     * @param string $url The URL to send the request to.
     *
     * @return array|null The decoded JSON response as an associative array, or null if an error occurred.
     */
    private static function executeRequest(string $url): ?array
    {
        Assert::notEmpty($url);
        //==============================================================================
        // Initialize cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        //==============================================================================
        // Execute the cURL request
        $response = curl_exec($ch);
        //==============================================================================
        // Check for cURL errors
        if (curl_errno($ch)) {
            curl_close($ch);

            return null;
        }
        curl_close($ch);

        //==============================================================================
        // Decode the JSON response
        try {
            $data = json_decode((string) $response, true, 512, JSON_THROW_ON_ERROR);

            return is_array($data) ? $data : null;
        } catch (\Throwable) {
            return null;
        }
    }

}
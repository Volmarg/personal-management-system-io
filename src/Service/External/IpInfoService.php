<?php

namespace App\Service\External;

use App\Controller\System\IncomingRequestController;
use Exception;
use ipinfo\ipinfo\Details;
use ipinfo\ipinfo\IPinfo;
use ipinfo\ipinfo\IPinfoException;
use Psr\Log\LoggerInterface;

/**
 * Handles returning information about given ip address
 *
 * Class IpInfoService
 * @package App\Service\External
 */
class IpInfoService
{
    /**
     * This value has been taken from dashboard page of the service
     * There is no other way to obtain count of request done / left other than saving each request
     * and then checking toward that limit number how many are there still left
     */
    const FREE_ACCOUNT_IP_CHECKS_LIMIT_PER_MONTH   = 50000;
    const FREE_ACCOUNT_IP_CHECKS_WARNING_THRESHOLD = 40000;

    const PROPERTY_NAME_COUNTRY = 'country';

    const COUNTRY_POLAND_SHORTNAME  = "PL";
    const COUNTRY_GERMANY_SHORTNAME = "DE";

    const IP_LOCALHOST       = "127.0.0.1";
    const IP_NAMED_LOCALHOST = "localhost";

    const POSSIBLE_LOCALHOST_IPS = [
      self::IP_LOCALHOST,
      self::IP_NAMED_LOCALHOST,
    ];

    /**
     * @var IPinfo $ipInfo
     */
    private IPinfo $ipInfo;

    /**
     * @var IncomingRequestController $incomingRequestController
     */
    private IncomingRequestController $incomingRequestController;

    /**
     * @var LoggerInterface $logger
     */
    private LoggerInterface $logger;

    /**
     * IpInfoService constructor.
     *
     * @param IPinfo $ipInfo
     * @param IncomingRequestController $incomingRequestController
     * @param LoggerInterface $logger
     */
    public function __construct(IPinfo $ipInfo, IncomingRequestController $incomingRequestController, LoggerInterface $logger)
    {
        $this->incomingRequestController = $incomingRequestController;
        $this->logger                    = $logger;
        $this->ipInfo                    = $ipInfo;
    }

    /**
     * Will return the api information for provided IP
     *
     * @param string $ip
     * @return Details
     * @throws IPinfoException
     * @throws \Doctrine\DBAL\Exception
     */
    public function getInfoForIp(string $ip): Details
    {
        $this->checkIpChecksLeft();
        $informationForIp = $this->ipInfo->getDetails($ip);
        return $informationForIp;
    }

    /**
     * Will return country shortname (code), for example:
     * - Poland -> PL
     * - Germany -> DE
     *
     * @param string $ip
     * @return string
     * @throws IPinfoException
     * @throws Exception
     */
    public function getCountryShortNameForIp(string $ip): string
    {
        $informationForIp = $this->getInfoForIp($ip);
        if( !property_exists($informationForIp, self::PROPERTY_NAME_COUNTRY) ){
            throw new Exception("Something is wrong with response from IpInfo - no country was returned for Ip: {$ip}");
        }

        $country = $informationForIp->{self::PROPERTY_NAME_COUNTRY};
        return $country;
    }

    /**
     * Will check how many IP checks are there left for this month
     *
     * @throws \Doctrine\DBAL\Exception
     */
    private function checkIpChecksLeft(): void
    {
        $countOfRequestToPage = $this->incomingRequestController->getCountOfRequestInCurrentYearAndMonth();
        if(
                  $countOfRequestToPage > self::FREE_ACCOUNT_IP_CHECKS_WARNING_THRESHOLD
            &&  !($countOfRequestToPage > self::FREE_ACCOUNT_IP_CHECKS_LIMIT_PER_MONTH)
        ){
            $this->logger->emergency("The count of free ip checks is slowly reaching the limit for this month", [
                "limit"        => self::FREE_ACCOUNT_IP_CHECKS_LIMIT_PER_MONTH,
                "warningLimit" => self::FREE_ACCOUNT_IP_CHECKS_WARNING_THRESHOLD,
                "made"         => $countOfRequestToPage,
            ]);
        }

        if($countOfRequestToPage > self::FREE_ACCOUNT_IP_CHECKS_LIMIT_PER_MONTH){
            $this->logger->emergency("The amount of free IP check calls has been exceeded", [
                "limit" => self::FREE_ACCOUNT_IP_CHECKS_LIMIT_PER_MONTH,
                "made"  => $countOfRequestToPage,
            ]);
        }
    }
}
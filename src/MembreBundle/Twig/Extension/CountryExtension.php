<?php

/**
 * Created by PhpStorm.
 * User: Mouadh
 * Date: 11/06/2017
 * Time: 03:19
 */

namespace MembreBundle\Twig\Extension;

use Symfony\Component\Intl\Intl;

class CountryExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter("country", array($this, "countryFilter")),
        );
    }

    public function countryFilter($countryCode, $locale = "en")
    {
        \Locale::setDefault($locale);

        $countryName = "";
        if ($countryCode) {
            $countryName = Intl::getRegionBundle()->getCountryName($countryCode);
        }

        return $countryName ?: $countryCode;
    }

    public function getName()
    {
        return "country_extension";
    }
}
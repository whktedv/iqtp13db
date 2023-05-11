<?php
   return [
       // icon identifier
       'iqtp13dbadminicon' => [
            // icon provider class
           'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
            // the source SVG for the SvgIconProvider
           'source' => 'EXT:iqtp13db/Resources/Public/Icons/user_plugin_iqtp13dbadmin.svg',
       ],
       'iqtp13dbwebappicon' => [
           // icon provider class
           'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
           // the source SVG for the SvgIconProvider
           'source' => 'EXT:iqtp13db/Resources/Public/Icons/user_plugin_iqtp13dbwebapp.svg',
       ],
   ];
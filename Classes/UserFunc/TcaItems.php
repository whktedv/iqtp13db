<?php
namespace Ud\Iqtp13db\UserFunc;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

class TcaItems
{
    /**
     * Get beratungsarten items from TypoScript
     *
     * @param array $config
     * @return void
     */
    public function getBeratungsarten(&$config)
    {
        $this->getItemsFromSettings($config, 'beratungsart');
    }

    /**
     * Get anerkennungsberatung items from TypoScript
     *
     * @param array $config
     * @return void
     */
    public function getAnerkennungsberatung(&$config)
    {
        $this->getItemsFromSettings($config, 'anerkennungsberatung');
    }

    /**
     * Get qualifizierungsberatung items from TypoScript
     *
     * @param array $config
     * @return void
     */
    public function getQualifizierungsberatung(&$config)
    {
        $this->getItemsFromSettings($config, 'qualifizierungsberatung');
    }

    /**
     * Generic method to get items from TypoScript settings
     *
     * @param array $config
     * @param string $settingsKey
     * @return void
     */
    protected function getItemsFromSettings(&$config, $settingsKey)
    {
        $configurationManager = GeneralUtility::makeInstance(ConfigurationManager::class);
        $settings = $configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
            'Iqtp13db'
        );

        if (isset($settings[$settingsKey]) && is_array($settings[$settingsKey])) {
            foreach ($settings[$settingsKey] as $key => $label) {
                $config['items'][] = [
                    $label,
                    (string)$key
                ];
            }
        }
    }
}
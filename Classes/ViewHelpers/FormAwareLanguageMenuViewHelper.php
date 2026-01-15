<?php
declare(strict_types = 1);
namespace Ud\Iqtp13db\ViewHelpers;

use TYPO3\CMS\Core\Site\Entity\SiteLanguage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;


class FormAwareLanguageMenuViewHelper extends AbstractViewHelper
{

    protected $escapeOutput = false;

    public function initializeArguments(): void
    {
        $this->registerArgument('currentStep', 'int', 'Current form step', false, 1);
        $this->registerArgument('class', 'string', 'CSS class for menu', false, 'language-menu');
        $this->registerArgument('teilnehmer', 'string', 'Current form data', false, []);        
    }

    public function render(): string
    {
        $iconFactory = GeneralUtility::makeInstance(IconFactory::class);

        $currentStep = $this->arguments['currentStep'];
        $class = $this->arguments['class'];
        $formData = $this->arguments['teilnehmer'];

        $typoScriptFrontendController = $this->getTypoScriptFrontendController();
        $site = $typoScriptFrontendController->getSite();
        $currentLanguage = $typoScriptFrontendController->getLanguage();

        // Korrekter Aufruf für TYPO3 12
        $languages = $site->getLanguages();
        $output = '<ul id="language_menu" class="' . htmlspecialchars($class) . '">';
        
        foreach ($languages as $language) {
            if (! $language->isEnabled()) {
                continue;
            }

            $languageId = $language->getLanguageId();
            $isActive = $languageId === $currentLanguage->getLanguageId();

            // Parameter mit Formulardaten
            $params = [
                'L' => $languageId,
                'tx_iqtp13db_iqtp13dbwebapp' => [
                    'action' => $currentStep,
                    'controller' => 'Teilnehmer',
                    'langmenuchange' => 1
                ]
            ];

            // Formulardaten hinzufügen (z.B. teilnehmer-Objekt)
            if (! empty($formData)) {
                $params['tx_iqtp13db_iqtp13dbwebapp']['teilnehmer'] = $formData;
            }

            $url = $typoScriptFrontendController->cObj->typoLink_URL([
                'parameter' => $typoScriptFrontendController->id,
                'additionalParams' => '&' . http_build_query($params),
                'useCacheHash' => false
            ]);

            // Flag-Icon generieren
            $icon = $iconFactory->getIcon($language->getFlagIdentifier(), Icon::SIZE_SMALL);

            $activeClass = $isActive ? ' active' : '';
            $output .= sprintf('<li class="%s"><a href="%s" class="language-link%s" hreflang="%s" title="%s">', $activeClass, htmlspecialchars($url), $activeClass, htmlspecialchars($language->getHreflang()), htmlspecialchars($language->getTitle()));

            $output .= $icon->render();
            $output .= sprintf("%s</a></li>", htmlspecialchars($language->getNavigationTitle() ?: $language->getTitle()));
        }

        $output .= '</ul>';

        return $output;
    }

    protected function buildLanguageUrl(SiteLanguage $language, string $actionName, TypoScriptFrontendController $tsfe): string
    {
        $languageId = $language->getLanguageId();
        $currentPageId = $tsfe->id;

        // Build URL with cObj
        $conf = [
            'parameter' => $currentPageId,
            'language' => $languageId,
            'forceAbsoluteUrl' => false,
            'addQueryString' => true,
            'addQueryString.' => [
                'exclude' => 'id,L,tx_iqtp13db_iqtp13dbwebapp[action],tx_iqtp13db_iqtp13dbwebapp[controller]'
            ],
            'additionalParams' => '&tx_iqtp13db_iqtp13dbwebapp[action]=' . $actionName . '&tx_iqtp13db_iqtp13dbwebapp[controller]=Teilnehmer'
        ];

        return $tsfe->cObj->typoLink_URL($conf);
    }

    /**
     * Konvertiert ein Domain-Model oder Array in ein serialisierbares Array
     */
    protected function convertToArray($data): array
    {
        if (is_array($data)) {
            return $data;
        }

        if (is_object($data)) {
            $result = [];

            // Alle Getter-Methoden aufrufen
            $methods = get_class_methods($data);
            foreach ($methods as $method) {
                if (strpos($method, 'get') === 0 && $method !== 'get') {
                    $property = lcfirst(substr($method, 3));
                    $value = $data->$method();

                    // Nur einfache Datentypen serialisieren
                    if (is_scalar($value) || $value === null) {
                        $result[$property] = $value;
                    } elseif ($value instanceof \DateTime) {
                        $result[$property] = $value->format('Y-m-d H:i:s');
                    }
                }
            }

            return $result;
        }

        return [];
    }

    protected function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}
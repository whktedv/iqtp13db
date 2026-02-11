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
        $this->registerArgument('teilnehmer', 'string', 'Current form data', false, []);
        $this->registerArgument('direkt', 'int', 'Current direktlink state', false, 0);
        $this->registerArgument('plz', 'string', 'Current plz', false, []);
    }

    public function render(): string
    {
        $iconFactory = GeneralUtility::makeInstance(IconFactory::class);

        $currentStep = $this->arguments['currentStep'];
        $formData = $this->arguments['teilnehmer'];
        $direkt = $this->arguments['direkt'];
        $plz = $this->arguments['plz'];

        $typoScriptFrontendController = $this->getTypoScriptFrontendController();
        $site = $typoScriptFrontendController->getSite();
        $currentLanguage = $typoScriptFrontendController->getLanguage();

        // Korrekter Aufruf für TYPO3 12
        $languages = $site->getLanguages();
        $output = '';
        
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
                    'langmenuchange' => 1,
                    'direkt' => $direkt,
                    'plz' => $plz
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

    protected function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}
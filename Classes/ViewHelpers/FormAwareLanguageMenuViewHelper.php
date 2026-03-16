<?php
declare(strict_types = 1);
namespace Ud\Iqtp13db\ViewHelpers;

use TYPO3\CMS\Core\Site\Entity\SiteLanguage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Imaging\IconFactory;
// Ab Typo3 13: use TYPO3\CMS\Core\Imaging\IconSize;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Routing\PageArguments;
use TYPO3\CMS\Core\Http\ServerRequestFactory;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
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

        // In TYPO3 13: Request über den Rendering-Kontext holen
        $request = $this->renderingContext->getRequest();

        $site = $request->getAttribute('site');
        $currentLanguage = $request->getAttribute('language');
        $currentPageId = $request->getAttribute('routing') instanceof PageArguments
            ? $request->getAttribute('routing')->getPageId()
            : (int)($request->getQueryParams()['id'] ?? 0);
        $cObj = $request->getAttribute('currentContentObject')
            ?? GeneralUtility::makeInstance(ContentObjectRenderer::class);

        $languages = $site->getLanguages();
        $output = '';
        
        foreach ($languages as $language) {
            if (! $language->isEnabled()) {
                continue;
            }

            // TYPO3 13: getLanguageId() → getId()
            $languageId = $language->getLanguageId();
            $isActive   = $languageId === $currentLanguage->getLanguageId();

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

            $url = $cObj->typoLink_URL([
                'parameter'        => $currentPageId,
                'additionalParams' => '&' . http_build_query($params),
                'useCacheHash'     => false,
            ]);
            
            // Ab TYPO3 13: Icon::SIZE_SMALL → IconSize::SMALL (Enum)
            //$icon = $iconFactory->getIcon($language->getFlagIdentifier(), IconSize::SMALL);
            
            // Flag-Icon generieren: Typo3 12 deprecated:
            $icon = $iconFactory->getIcon($language->getFlagIdentifier(), Icon::SIZE_SMALL);
            
            $activeClass = $isActive ? ' active' : '';
            $output .= sprintf('<li class="%s"><a href="%s" class="language-link%s" hreflang="%s" title="%s">', 
                $activeClass, 
                htmlspecialchars($url), 
                $activeClass, 
                htmlspecialchars($language->getHreflang()), 
                htmlspecialchars($language->getTitle())
            );

            $output .= $icon->render();
            $output .= sprintf("%s</a></li>", htmlspecialchars($language->getNavigationTitle() ?: $language->getTitle()));
        }

        return $output;
    }

    protected function buildLanguageUrl(SiteLanguage $language, string $actionName, ContentObjectRenderer $cObj, int $currentPageId): string
    {
        $languageId = $language->getLanguageId();

        $conf = [
            'parameter'        => $currentPageId,
            'language'         => $languageId,
            'forceAbsoluteUrl' => false,
            'addQueryString'   => true,
            'addQueryString.'  => [
                'exclude' => 'id,L,tx_iqtp13db_iqtp13dbwebapp[action],tx_iqtp13db_iqtp13dbwebapp[controller]',
            ],
            'additionalParams' => '&tx_iqtp13db_iqtp13dbwebapp[action]=' . $actionName
                                . '&tx_iqtp13db_iqtp13dbwebapp[controller]=Teilnehmer',
        ];

        return $cObj->typoLink_URL($conf);
    }
}
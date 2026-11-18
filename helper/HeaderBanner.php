<?php
namespace OmekaTheme\Helper;

use Laminas\View\Helper\AbstractHelper;

/**
 * Resolve S homepage visibility and restrict editor-supplied banner CSS.
 */
class HeaderBanner extends AbstractHelper
{
    public function __invoke($site)
    {
        $view = $this->getView();
        $url = $view->themeSettingAssetUrl('banner');
        $visibility = $view->themeSetting('banner_visibility') ?: 'all';
        if (!$url || $visibility === 'none') {
            return null;
        }

        if ($visibility === 'home' || $visibility === 'inner') {
            $homepage = $site->homepage();
            if (!$homepage) {
                $pages = $site->linkedPages();
                $homepage = $pages ? reset($pages) : null;
            }
            $pageSlug = $view->params()->fromRoute('page-slug');
            $controller = $view->params()->fromRoute('controller', '');
            $isHome = $homepage
                ? $pageSlug === $homepage->slug()
                : in_array($controller, ['Index', 'Omeka\Controller\Site\Index'], true)
                    && $view->params()->fromRoute('action', 'index') === 'index';
            if (($visibility === 'home') !== $isHome) {
                return null;
            }
        }

        $positions = ['center' => 'center', 'flex_start' => 'top', 'flex_end' => 'bottom'];
        $position = (string) $view->themeSetting('banner_position');
        $style = '--banner-position:' . ($positions[$position] ?? 'center') . ';';
        foreach (['banner_height' => '--banner-height', 'banner_height_mobile' => '--banner-height-mobile'] as $key => $property) {
            $length = trim((string) $view->themeSetting($key));
            if (preg_match('/^(?:auto|0|(?:\d+(?:\.\d+)?|\.\d+)(?:px|em|rem|vh|vw|vmin|vmax|%))$/i', $length)) {
                $style .= $property . ':' . $length . ';';
            }
        }

        return ['url' => $url, 'alt' => (string) $view->themeSetting('banner_alt'), 'style' => $style];
    }
}

<?php
namespace OmekaTheme\Helper;

use Laminas\View\Helper\AbstractHelper;

/**
 * Resolve fixed/toggle layouts without using translated labels as identifiers.
 */
class BrowseLayout extends AbstractHelper
{
    public function __invoke($useQuery = true)
    {
        $view = $this->getView();
        $setting = $view->themeSetting('browse_layout', 'grid');
        $toggle = in_array($setting, ['togglegrid', 'togglelist'], true);
        $layout = in_array($setting, ['list', 'togglelist'], true) ? 'list' : 'grid';
        if ($toggle && $useQuery) {
            $requested = $view->params()->fromQuery('browse_layout');
            if (in_array($requested, ['grid', 'list'], true)) {
                $layout = $requested;
            }
        }
        return ['layout' => $layout, 'toggle' => $toggle];
    }
}

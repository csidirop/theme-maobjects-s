<?php
namespace OmekaTheme\Helper;

use Laminas\View\Helper\AbstractHelper;

/**
 * Parse Classic-compatible footer link lists using the current S site's URL.
 */
class FooterLinks extends AbstractHelper
{
    public function __invoke($value)
    {
        $pairs = [];
        $links = [];

        foreach (preg_split('/\r\n|\r|\n/', (string) $value) as $line) {
            // Decode as a JSON pair to support quotes and colons in labels/URLs.
            $pair = json_decode('{' . trim($line) . '}', true);
            if (is_array($pair) && count($pair) === 1) {
                $pairs[] = $pair;
                continue;
            }

            // Only strip markup for legacy rich-text input, preserving literal
            // angle brackets in valid plain-text labels for output escaping.
            $line = preg_replace('/<br\s*\/?>|<\/(?:p|div|li)>/i', "\n", $line);
            $line = html_entity_decode(strip_tags($line), ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $line = str_replace("\xc2\xa0", ' ', $line);
            foreach (explode("\n", $line) as $legacyLine) {
                $pair = json_decode('{' . trim($legacyLine) . '}', true);
                if (is_array($pair) && count($pair) === 1) {
                    $pairs[] = $pair;
                }
            }
        }

        foreach ($pairs as $pair) {
            $href = $this->resolveUrl(html_entity_decode((string) key($pair), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
            $label = current($pair);
            if ($href !== '' && is_string($label) && trim($label) !== '') {
                $links[] = [
                    'href' => $href,
                    'label' => trim(html_entity_decode($label, ENT_QUOTES | ENT_HTML5, 'UTF-8')),
                ];
            }
        }

        return $links;
    }

    /**
     * A leading slash means this site, including its installation subdirectory.
     * Return an empty string for unsupported or ambiguous destinations.
     */
    public function resolveUrl($href)
    {
        $href = trim((string) $href);
        if ($href === '' || preg_match('/[\x00-\x20\x7f\\\\]/', $href)) {
            return '';
        }

        if (preg_match('~^https?://~i', $href)) {
            return filter_var($href, FILTER_VALIDATE_URL) ? $href : '';
        }
        if (preg_match('/^(?:mailto|tel):[^?#]+/i', $href)) {
            return $href;
        }

        if ($href[0] !== '/' || strpos($href, '//') === 0) {
            return '';
        }
        $path = rawurldecode(explode('?', explode('#', $href, 2)[0], 2)[0]);
        if (preg_match('~(?:^|/)\.{1,2}(?:/|$)|[\x00-\x20\x7f\\\\]~', $path)) {
            return '';
        }

        return rtrim($this->getView()->currentSite()->url(), '/') . $href;
    }
}

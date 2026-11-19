<?php
namespace OmekaTheme\Helper;

use Laminas\View\Helper\AbstractHelper;

/**
 * Link to native API representations of a resource or the displayed page.
 */
class MetadataFormats extends AbstractHelper
{
    public function __invoke($resources)
    {
        $view = $this->getView();
        if (!$view->themeSetting('metadata_output_links', true) || !$resources) {
            return [];
        }
        $isPage = is_array($resources);
        $resource = $isPage ? reset($resources) : $resources;
        $resourceName = $resource->resourceName();
        if (!in_array($resourceName, ['items', 'item_sets', 'media'], true)) {
            return [];
        }
        $route = ['resource' => $resourceName];
        $query = [];
        if ($isPage) {
            // IDs preserve route/module constraints too, including Hierarchy.
            // Limit output to this page rather than exporting the whole site.
            $query['id'] = array_map(function ($resource) { return $resource->id(); }, $resources);
            $query['per_page'] = count($resources);
            foreach (['sort_by', 'sort_order'] as $key) {
                $value = $view->params()->fromQuery($key);
                if (is_string($value) && $value !== '') {
                    $query[$key] = $value;
                }
            }
        } else {
            $route['id'] = $resource->id();
        }
        $formats = ['jsonld' => ['JSON-LD', 'application/ld+json']];
        if (version_compare(\Omeka\Module::VERSION, '4.1.0', '>=')) {
            $formats += [
                'rdfxml' => ['RDF/XML', 'application/rdf+xml'],
                'turtle' => ['Turtle', 'text/turtle'],
                'n3' => ['N3', 'text/n3'],
                'ntriples' => ['N-Triples', 'application/n-triples'],
            ];
        }
        $links = [];
        foreach ($formats as $format => $data) {
            $query['format'] = $format;
            $links[] = [
                'label' => $data[0],
                'type' => $data[1],
                'url' => $view->url('api/default', $route, ['query' => $query]),
            ];
        }
        return $links;
    }
}

<?php

declare(strict_types=1);

namespace Bic\Tiled\Loader;

use Bic\Tiled\Map\Map;

final class TmxLoader extends Loader
{
    /**
     * {@inheritDoc}
     */
    public function load(string $data, iterable $paths = []): Map
    {
        $map = \simplexml_load_string($data);

        return $this->mapFromArray(
            $this->xmlToArray($map),
        );
    }

    /**
     * @param \SimpleXMLElement $map
     * @return array
     */
    private function xmlToArray(\SimpleXMLElement $map): array
    {
        if ($map->getName() !== 'map') {
            throw new \InvalidArgumentException('Invalid map root element');
        }

        $result = $this->attributesToArray($map);

        foreach ($map as $child) {
            switch ($child->getName()) {
                case 'properties':
                    $result['properties'] = $this->propertiesToArray($child);
                    break;
            }
        }

        return $result;
    }

    /**
     * @param \SimpleXMLElement $el
     * @return array
     */
    private function propertiesToArray(\SimpleXMLElement $el): array
    {
        $result = [];

        foreach ($el as $property) {
            $result[] = $this->propertyToArray($property);
        }

        return $result;
    }

    /**
     * @param \SimpleXMLElement $el
     * @return array
     */
    private function propertyToArray(\SimpleXMLElement $el): array
    {
        $result = $this->attributesToArray($el);

        if (!\array_key_exists('value', $result)) {
            $result['value'] = (string)$el;
        }

        return $result;
    }

    /**
     * @param \SimpleXMLElement $el
     * @return array
     */
    private function attributesToArray(\SimpleXMLElement $el): array
    {
        $result = [];

        foreach ($el->attributes() as $name => $attribute) {
            $result[$name] = (string)$attribute[0];
        }

        return $result;
    }
}

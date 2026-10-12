<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Turns the designer's Illustrator SVG export into the interactive drawing
 * (module:isometry). Replaces the Python/poppler scripts of Hüningerstrasse.
 *
 * - decodes Illustrator's escaped ids (_x31_._OG → "1. OG")
 * - marks floor layers (data-iso-floor), the base layer and apartment shapes (data-iso)
 * - marks side/top faces by their fill colour (iso-face / iso-top) for status colours
 * - drops the XML prolog, width/height and editor metadata
 */
class BuildIsometry extends Command
{
    protected $signature = 'isometry:build {source? : Illustrator SVG export (default: config isometry.source)}';

    protected $description = 'Build resources/isometry/building.svg from the Illustrator SVG export';

    public function handle(): int
    {
        $source = $this->argument('source') ?? config('isometry.source');

        if (! is_file($source)) {
            $this->error("Source not found: {$source}");

            return self::FAILURE;
        }

        $dom = new \DOMDocument;
        $dom->preserveWhiteSpace = false;

        if (! @$dom->load($source)) {
            $this->error('Source is not valid SVG/XML.');

            return self::FAILURE;
        }

        $svg = $dom->documentElement;
        $xpath = new \DOMXPath($dom);
        $xpath->registerNamespace('svg', 'http://www.w3.org/2000/svg');

        [$faceClasses, $topClasses] = $this->fillClasses($xpath);

        $floors = config('isometry.floors');
        $foundFloors = [];
        $refs = [];

        foreach (iterator_to_array($xpath->query('//svg:g[@id]')) as $group) {
            $name = $this->name($group);

            if ($name === config('isometry.base')) {
                $group->setAttribute('class', 'iso-base');
            }

            if (array_key_exists($name, $floors)) {
                $group->setAttribute('class', 'iso-floor');
                $group->setAttribute('data-iso-floor', $floors[$name]);
                $foundFloors[] = $name;

                foreach ($xpath->query('.//*[@id]', $group) as $shape) {
                    $ref = $this->ref($this->name($shape));
                    if ($ref !== null) {
                        $shape->setAttribute('data-iso', $ref);
                        $refs[] = $ref;
                    }
                }
            }
        }

        // Faces: by class (Illustrator writes fills into <style>) or by fill attribute.
        foreach ($xpath->query('//svg:path|//svg:polygon|//svg:rect|//svg:polyline') as $shape) {
            $classes = preg_split('/\s+/', trim($shape->getAttribute('class')));
            $fill = strtolower($shape->getAttribute('fill'));

            if (array_intersect($classes, $faceClasses) || $fill === strtolower(config('isometry.face_fill'))) {
                $shape->setAttribute('class', trim($shape->getAttribute('class').' iso-face'));
            } elseif (array_intersect($classes, $topClasses) || $fill === strtolower(config('isometry.top_fill'))) {
                $shape->setAttribute('class', trim($shape->getAttribute('class').' iso-top'));
            }
        }

        // Root: responsive, labelled; Illustrator's ids on the root are useless here.
        foreach (['width', 'height', 'id', 'data-name', 'version', 'x', 'y', 'style'] as $attribute) {
            $svg->removeAttribute($attribute);
        }
        $svg->setAttribute('class', 'iso-svg');
        $svg->setAttribute('role', 'img');
        $svg->setAttribute('aria-label', config('isometry.label'));
        $svg->setAttribute('data-isometry', '');

        foreach (iterator_to_array($xpath->query('//comment()')) as $comment) {
            $comment->parentNode->removeChild($comment);
        }

        file_put_contents(config('isometry.output'), $dom->saveXML($svg)."\n");

        $missing = array_diff(array_keys($floors), $foundFloors);
        $this->info('Written: '.str_replace(base_path().'/', '', config('isometry.output')));
        $this->line('Floors: '.implode(', ', $foundFloors).($missing ? '  (missing: '.implode(', ', $missing).')' : ''));
        $this->line('Apartments: '.count($refs).' – '.implode(', ', $refs));
        $this->line('Face classes: '.implode(', ', $faceClasses).' / top classes: '.implode(', ', $topClasses));

        return $missing ? self::FAILURE : self::SUCCESS;
    }

    /** Illustrator escapes characters in ids: "_x31_._OG" = "1._OG", spaces become "_". data-name keeps the original. */
    private function name(\DOMElement $element): string
    {
        if ($element->hasAttribute('data-name')) {
            return $element->getAttribute('data-name');
        }

        $id = preg_replace_callback('/_x([0-9A-Fa-f]{2})_/', fn ($m) => chr(hexdec($m[1])), $element->getAttribute('id'));

        return str_replace('_', ' ', $id);
    }

    private function ref(string $name): ?string
    {
        // Illustrator appends "-2", "_2" etc. to duplicate names; the pattern decides what counts.
        return preg_match(config('isometry.ref_pattern'), $name)
            ? preg_replace(config('isometry.ref_pattern'), config('isometry.ref_format'), $name)
            : null;
    }

    /** @return array{0: array<int,string>, 1: array<int,string>} classes whose fill is the face / top colour */
    private function fillClasses(\DOMXPath $xpath): array
    {
        $face = $top = [];

        foreach ($xpath->query('//svg:style') as $style) {
            preg_match_all('/([^{}]+)\{([^}]*)\}/', $style->textContent, $rules, PREG_SET_ORDER);

            foreach ($rules as [, $selectors, $body]) {
                if (! preg_match('/fill:\s*(#[0-9a-fA-F]{3,6})/', $body, $fill)) {
                    continue;
                }

                preg_match_all('/\.([\w-]+)/', $selectors, $classes);
                $color = strtolower($fill[1]);

                if ($color === strtolower(config('isometry.face_fill'))) {
                    $face = [...$face, ...$classes[1]];
                } elseif ($color === strtolower(config('isometry.top_fill'))) {
                    $top = [...$top, ...$classes[1]];
                }
            }
        }

        return [array_values(array_unique($face)), array_values(array_unique($top))];
    }
}

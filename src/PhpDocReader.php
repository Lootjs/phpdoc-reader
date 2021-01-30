<?php

namespace Loot\PhpDocReader;

use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\True_;

final class PhpDocReader
{
    /**
     * @var string
     */
    private $comment;

    /**
     * @var string[]
     */
    private $lines;

    /**
     * @var PhpDocLine[]
     */
    private $annotations;

    /**
     * @var string[]
     */
    private $nativeTypes = [
        'boolean', 'bool',
        'integer', 'int',
        'float', 'double',
        'string',
        'array',
        'object',
        'resource',
    ];

    /**
     * PhpDocReader constructor.
     * @param string $comment
     */
    public function __construct(string $comment)
    {
        $this->comment = $comment;
        $this->removeWrapper();
        $this->splitComment();
    }

    /**
     * removes comment tags
     */
    private function removeWrapper(): void
    {
        $this->comment = substr($this->comment, 3, -2);
    }

    /**
     * Split comment to array
     */
    private function splitComment(): void
    {
        $lines = explode("\n", $this->comment);

        /** remove spaces and wildcard */
        $lines = array_map([$this, 'cleanUpLine'], $lines);

        /** remove blank lines */
        $lines = array_filter($lines);

        /** reindex array */
        $this->lines = array_values($lines);

        $this->annotations = array_map([$this, 'destructuration'], $lines);
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        if (isset($this->lines[0]) && $this->lineHasParam($this->lines[0]) === false) {
            return $this->lines[0];
        }

        return '';
    }

    /**
     * @return PhpDocLine[]
     */
    public function getAnnotations(): array
    {
        return $this->annotations;
    }

    public function getLines(): array
    {
        return $this->lines;
    }

    /**
     * @param string $line
     * @return string
     */
    public function cleanUpLine(string $line)
    {
        return trim(
            str_replace('*', '', $line)
        );
    }

    public function destructuration(string $line)
    {
        $chunk = explode(' ', $line);
        $key = array_shift($chunk);

        if (!$this->lineHasParam($key)) {
            $phpDocLine = new PhpDocLine('');
            $phpDocLine->setDescription($line);

            return $phpDocLine;
        }

        $phpDocLine = new PhpDocLine($key);
        $type = array_shift($chunk);

        if ($this->stringIsType($type)) {
            $phpDocLine->setType($type);
            $variableName = array_shift($chunk);
            $value = implode(' ', $chunk);

            if ($variableName && $this->stringIsVariable($variableName)) {
                $phpDocLine->setVariable($variableName);
                $phpDocLine->setDescription($value);
            } else {
                array_unshift($chunk, $variableName);
                $phpDocLine->setDescription(implode(' ', $chunk));
            }
        } else {
            array_unshift($chunk, $type);
            $phpDocLine->setDescription(implode(' ', $chunk));
        }

        return $phpDocLine;
    }

    /**
     * @param string $line
     * @param string $type
     * @return bool
     */
    private function lineHasParam(string $line, string $type = '@'): bool
    {
        return Str::startsWith($line, $type);
    }

    /**
     * @param string $line
     * @return bool
     */
    private function stringIsVariable(string $line): bool
    {
        return Str::startsWith($line, '$');
    }

    /**
     * @param string $type
     * @return bool
     */
    private function stringIsType(string $type): bool
    {
        if (class_exists($type) || in_array($type, $this->nativeTypes)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $name
     * @return PhpDocLine
     */
    public function getAnnotation(string $name)
    {
        foreach ($this->getAnnotations() as $annotation) {
            if ($annotation->getName() === $name || $annotation->getName() === ('@'.$name)) {
                return $annotation;
            }
        }

        throw new \RuntimeException('Annotation ['.$name.'] not found');
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasAnnotation(string $name): bool
    {
        foreach ($this->getAnnotations() as $annotation) {
            if ($annotation->getName() === $name || $annotation->getName() === ('@'.$name)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $name
     * @return array
     */
    public function getAnnotationsByName(string $name): array
    {
        return array_filter($this->getAnnotations(), function (PhpDocLine $annotation) use ($name) {
            if ($annotation->getName() === $name || $annotation->getName() === ('@'.$name)) {
                return $annotation;
            }
        });
    }
}

<?php

namespace Pieldefoca\Lux\Support;

class Translator
{
    public function saveTranslations(array $translations, string $path)
    {
        $string = $this->getTranslationString($translations);

        file_put_contents($path, $string);
    }

    public function getTranslationString(array $translations)
    {
        $string = "<?php\n\nreturn [\n";
        $currentLetter = '';
        foreach($translations as $key => $translation) {
            $firstLetter = str($translation)->substr(0, 1)->upper()->toString();
            if($currentLetter !== $firstLetter) {
                $string .= "\n\t// @{$firstLetter}\n";
                $currentLetter = $firstLetter;
            }
            $translation = addslashes($translation);
            $string .= "\t\"{$key}\" => \"{$translation}\",\n";
        }
        $string .= "\n];";

        return $string;
    }
}
<?php

namespace Pieldefoca\Lux\Support;

class Translator
{
    public function saveTranslations(array $translations, string $path)
    {
        $string = $this->getTranslationString($translations);

        // $this->backup($path);

        file_put_contents($path, $string);
    }

    protected function backup(string $path)
    {
        $pathinfo = pathinfo($path);
        $dirname = $pathinfo['dirname'];
        $basename = $pathinfo['basename'];

        $backupPath = "{$dirname}/backup";

        if(! file_exists($backupPath)) {
            mkdir($backupPath, 777, true);
        }

        copy($path, "{$backupPath}/{$basename}");
    }

    public function getTranslationString(array $translations)
    {
        $string = "<?php\n\nreturn [\n";
        foreach($translations as $key => $translation) {
            $translation = addslashes($translation);
            $string .= "\t\"{$key}\" => \"{$translation}\",\n";
        }
        $string .= "\n];";

        return $string;
    }
}
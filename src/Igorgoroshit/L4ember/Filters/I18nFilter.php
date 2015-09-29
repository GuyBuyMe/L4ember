<?php namespace Igorgoroshit\L4ember\Filters;

use Assetic\Asset\AssetInterface;
use Assetic\Filter\FilterInterface;
use Codesleeve\AssetPipeline\Filters\FilterHelper;

class I18nFilter extends FilterHelper implements FilterInterface 
{
    public function __construct($basePath = '/app/assets/javascripts/')
    {
        $this->basePath = $basePath;
    }

    public function filterLoad(AssetInterface $asset)
    {
    }
 
    public function filterDump(AssetInterface $asset)
    {

        $relativePath = ltrim($this->getRelativePath($this->basePath, $asset->getSourceRoot() . '/'), '/');
        $filename =  pathinfo($asset->getSourcePath(), PATHINFO_FILENAME);
        
        $filename = pathinfo($filename, PATHINFO_FILENAME);

        $dirname = explode("translations/", dirname($relativePath . $filename));

        $parts = explode('/', $dirname[1]);
        $locale = array_shift($parts);
        //array_push($parts);
        $fullpath   = implode('.', $parts);

        $content = str_replace('"', '\\"', $asset->getContent());
        $content = str_replace("\r\n", "\n", $content);
        $content = str_replace("\n", "\\n", $content);

        $json  = 'Em.I18n.setp("' . $fullpath . '", "' . $filename . '", JSON.parse("';
        $json .= $content;
        $json .= '"));/*'.$locale.'*/' . PHP_EOL;

        $asset->setContent($json);
    }
}
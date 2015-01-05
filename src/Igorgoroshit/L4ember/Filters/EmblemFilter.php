<?php namespace Igorgoroshit\L4ember\Filters;

use Assetic\Asset\AssetInterface;
use Assetic\Filter\FilterInterface;
use Codesleeve\AssetPipeline\Filters\FilterHelper;

class EmblemFilter extends FilterHelper implements FilterInterface 
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
        
        $dirname = explode("templates/", dirname($relativePath . $filename) . '/');
        $parent_dir = ((count($dirname) > 0) ? $dirname[1] : "");

        $content = str_replace('"', '\\"', $asset->getContent());
        $content = str_replace(PHP_EOL, "\\n", $content);
        
        $emblem = 'Ember.TEMPLATES["' . $parent_dir . $filename . '"] = Emblem.compile(Ember.Handlebars, "';
        $emblem .= $content;
        $emblem .= '");' . PHP_EOL;

        $asset->setContent($emblem);
    }
}
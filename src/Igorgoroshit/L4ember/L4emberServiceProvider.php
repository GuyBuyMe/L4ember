<?php namespace Igorgoroshit\L4ember;

use Illuminate\Support\ServiceProvider;

class L4emberServiceProvider extends ServiceProvider {


	protected $defer = false;


	public function boot()
	{
		//print_r($this->app);die();
		$replace_once = 1;
		$project_base = $this->app['path'];
		//die($project_base . '/assets');
		$base = realpath($project_base . '/assets');
		//die($base);
		$base = str_replace($project_base, '', $base, $replace_once);
		$base = ltrim($base, '/');

		//die($project_base);
		\Event::listen('asset.pipeline.boot', function($pipeline) use ($base) {
			
			$config = $pipeline->getConfig();
			$config['paths'][] = $base . '/javascripts';
			$config['paths'][] = $base . '/stylesheets';
			$config['mimes']['javascripts'][] = '.emb';
			$config['mimes']['javascripts'][] = '.hbs';

			$config['filters']['.emb'] = array(
				new Filters\EmblemFilter($config['paths'])
			);
			
			$config['filters']['.hbs'] = array(
				new Filters\HandlebarsFilter($config['paths'])
			);

			$pipeline->setConfig($config);

		});

		$this->package('igorgoroshit/l4ember');
	}


	public function register(){}

	public function provides(){ return array(); }

}
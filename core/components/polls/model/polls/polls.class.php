<?php
/**
 * The Polls object
 *
 * @package polls
 */
class Polls {
	
	/**
     * Constructs the object
     *
     * @param modX &$modx A reference to the modX object
     * @param array $config An array of configuration options
     */
    function __construct(modX &$modx, array $config=array()) {
		$this->modx =& $modx;
		
		$basePath = $this->modx->getOption('polls.core_path',$config,$this->modx->getOption('core_path').'components/polls/');
		$assetsUrl = $this->modx->getOption('polls.assets_url',$config,$this->modx->getOption('assets_url').'components/polls/');
		
		$this->config = array_merge(array(
			'basePath' => $basePath,
			'corePath' => $basePath,
			'modelPath' => $basePath.'model/',
			'processorsPath' => $basePath.'processors/',
			'chunksPath' => $basePath.'elements/chunks/',
			'jsUrl' => $assetsUrl.'js/',
			'cssUrl' => $assetsUrl.'css/',
			'assetsUrl' => $assetsUrl,
			'connectorUrl' => $assetsUrl.'connector.php',
		), $config);
	   
		$this->modx->addPackage('polls', $this->config['modelPath']);
	}
	
	/**
     * Initializes the class into the proper context
     *
     * @access public
     * @param string $ctx
     */
    public function initialize($ctx='web') {
		switch ($ctx) {
			case 'mgr':
				$this->modx->lexicon->load('polls:default');
				
				if(!$this->modx->loadClass('polls.request.pollsControllerRequest', $this->config['modelPath'], true, true)) {
					return 'Could not load controller request handler.';
				}
				
				$this->request = new pollsControllerRequest($this);
				
				return $this->request->handleRequest();
            break;
			
            case 'connector':
				if(!$this->modx->loadClass('polls.request.pollsConnectorRequest', $this->config['modelPath'], true, true)) {
                    die('Could not load connector request handler.');
                }
				
				$this->request = new pollsConnectorRequest($this);
				
				return $this->request->handle();
            break;
            default: break;
        }
        return true;
    }
}

?>
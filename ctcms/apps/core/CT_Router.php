<?php
class CT_Router extends CI_Router {
	protected function _validate_request($segments)
	{
		if(strpos($segments[0],'.php') !== false){
			if($segments[0] == 'index.php'){
				unset($segments[0]);
				$segments = array_merge($segments);
			}else{
				$segments[0] = 'admin';
			}
		}
		$c = count($segments);
		$directory_override = isset($this->directory);

		// Loop through our segments and return as soon as a controller
		// is found or when such a directory doesn't exist
		while ($c-- > 0)
		{
			$test = $this->directory
				.ucfirst($this->translate_uri_dashes === TRUE ? str_replace('-', '_', $segments[0]) : $segments[0]);
			if ( ! file_exists(APPPATH.'controllers/'.$test.'.php')
				&& $directory_override === FALSE
				&& is_dir(APPPATH.'controllers/'.$this->directory.$segments[0])
			)
			{
				$this->set_directory(array_shift($segments), TRUE);
				continue;
			}
			return $segments;
		}
		// This means that all segments were actually directories
		return $segments;
	}
}
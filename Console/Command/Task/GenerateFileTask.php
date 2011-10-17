<?php

class GenerateFileTask extends Shell {

	/**
	 * Do system calls
	 *
	 * @param string $string A string containing placeholders e.g. :command: -f xy
	 * @param array $data Data to be filled into the marker string
	 * @return boolean
	 */
	function _execute($string, $data) {
		if (!$data['command'] = $this->_which($data['command'])) {
			return false;
		}
		$line = String::insert($string, $data, array(
			'before' => ':', 'after' => ':', 'clean' => true
			));
		exec(escapeshellcmd($line) , $output, $return);
		return $return !== 0 ? false : (empty($output) ? true : array_pop($output));
	}

	/**
	 * Helper method to determine the absolute path to a executable
	 *
	 * @param string $command
	 * @return mixed
	 */
	function _which($command) {
		static $found = array();

		if (is_array($command)) {
			foreach ($command as $c) {
				if ($result = $this->_which($c)) {
					return $result;
				}
			}
			return false;
		}

		if (isset($found[$command])) {
			return $found[$command];
		}

		if (!ini_get('safe_mode')) {
			$paths = ini_get('safe_mode_exec_dir');
		}
		if (!isset($paths)) {
			$paths = env('PATH');
		}
		$paths = explode(PATH_SEPARATOR, $paths);
		$paths[] = getcwd();

		$os = env('OS');
		$windows = !empty($os) && strpos($os, 'Windows') !== false;

		if (!$windows) {
			exec('which ' . $command . ' 2>&1', $output, $return);

			if ($return == 0) {
				return $found[$command] = current($output);
			}
		}

		if ($windows) {
			if($extensions = env('PATHEXT')) {
				$extensions = explode(PATH_SEPARATOR, $extensions);
			} else {
				$extensions = array('exe', 'bat', 'cmd', 'com');
			}
		}
		$extensions[] = '';

		foreach ($paths as $path) {
			foreach($extensions as $extension) {
				$file = $path . DS . $command;

				if (!empty($extension)) {
					$file .= '.' . $extension;
				}

				if (is_file($file)) {
					return $found[$command] = $file;
				}
			}
		}
		return false;
	}

	public function execute() {
		$this->out('OK');
		return true;
	}
}

?>

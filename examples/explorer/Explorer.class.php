<?php

class Explorer
{
	public $folders;

	public $files;

	public function ExploreFolder($path = null)
	{
		$absoluteRoot = realpath('../..');

		$absolutePath = realpath($absoluteRoot . '/' . $path);

		if (strpos($absolutePath, $absoluteRoot) === false) {

			return false;
		}

		$isRoot = (strcasecmp($absoluteRoot, $absolutePath) == 0);

		$contents = @dir($absolutePath);

		if (empty($contents)) {

			return false;
		}

		$this->folders = array();

		$this->files = array();

		while (($item = $contents->read()) !== false) {

			if (
			($item == '.') ||
			(($item == '..') && ($isRoot))) {

				continue;
			}

			if (is_dir($absolutePath . '/' . $item)) {

				$this->folders[] = $item;

			} else if (is_file($absolutePath . '/' . $item)) {

				$this->files[] = $item;
			}
		}

		if (sizeof($this->folders) > 0) {

			natcasesort($this->folders);

			$this->folders = array_values($this->folders);
		}

		if (sizeof($this->files)) {

			natcasesort($this->files);

			$this->files = array_values($this->files);
		}

		$contents->close();

		return true;
	}

}

?>
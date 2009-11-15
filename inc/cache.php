<?php
class cache
{
public $caching;

public function __construct()
{
$this->caching = $cache = new WP_Object_Cache();
}

public function add($key, $data, $flag = '', $expire = 10) 
{
	return $this->caching->add($key, $data, $flag, $expire);
}

public function delete($id, $flag = '') 
{
 return $this->caching->delete($id, $flag);
}

public function flush()
{
	return $this->caching->flush();
}

public function get($id, $flag = '')
{
	return $this->caching->get($id, $flag);
}


public function replace($key, $data, $flag = '', $expire = 0) 
{
	return $this->caching->replace($key, $data, $flag, $expire);
}

public function set($key, $data, $flag = '', $expire = 0) 
{
	return $this->caching->set($key, $data, $flag, $expire);
}

public function stats()
{
$this->caching->stats();
}
}
class WP_Object_Cache {
	var $cache = array ();
	var $non_existant_objects = array ();
	var $cache_hits = 0;
	var $cache_misses = 0;
	
	function add($id, $data, $group = 'default', $expire = '') {
		if (empty ($group))
			$group = 'default';

		if (false !== $this->get($id, $group, false))
			return false;

		return $this->set($id, $data, $group, $expire);
	}

	function delete($id, $group = 'default', $force = false) {
		if (empty ($group))
			$group = 'default';

		if (!$force && false === $this->get($id, $group, false))
			return false;

		unset ($this->cache[$group][$id]);
		$this->non_existant_objects[$group][$id] = true;
		return true;
	}
function flush() {
		$this->cache = array ();

		return true;
	}

	function get($id, $group = 'default') {
		if (empty ($group))
			$group = 'default';

		if (isset ($this->cache[$group][$id])) {
			$this->cache_hits += 1;
			if ( is_object($this->cache[$group][$id]) )
				return wp_clone($this->cache[$group][$id]);
			else
				return $this->cache[$group][$id];
		}

		if ( isset ($this->non_existant_objects[$group][$id]) )
			return false;

		$this->non_existant_objects[$group][$id] = true;
		$this->cache_misses += 1;
		return false;
	}

	function replace($id, $data, $group = 'default', $expire = '') {
		if (empty ($group))
			$group = 'default';

		if (false === $this->get($id, $group, false))
			return false;

		return $this->set($id, $data, $group, $expire);
	}
	function set($id, $data, $group = 'default', $expire = '') {
		if (empty ($group))
			$group = 'default';

		if (NULL === $data)
			$data = '';

		if ( is_object($data) )
			$data = wp_clone($data);

		$this->cache[$group][$id] = $data;

		if(isset($this->non_existant_objects[$group][$id]))
			unset ($this->non_existant_objects[$group][$id]);

		return true;
	}
	function stats() {
		echo "<p>";
		echo "<strong>Cache Hits:</strong> {$this->cache_hits}<br />";
		echo "<strong>Cache Misses:</strong> {$this->cache_misses}<br />";
		echo "</p>";

		foreach ($this->cache as $group => $cache) {
			echo "<p>";
			echo "<strong>Group:</strong> $group<br />";
			echo "<strong>Cache:</strong>";
			echo "<pre>";
			print_r($cache);
			echo "</pre>";
			
			return $cache;
		}
	}

}
?>

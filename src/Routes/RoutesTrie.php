<?php

namespace Baklava\Routes;

class RoutesTrie
{
    private $trie;

    public function __construct()
    {
        $this->trie = array(
            'children' => array()
        );
    }

    public function add($key, $value = null)
    {
        $trieLevel          = &$this->getTrieForKey($key, true);
        $trieLevel['value'] = $value;
    }

    public function isMember($key)
    {
        $trieLevel = $this->getTrieForKey($key);
        if ($trieLevel != false && array_key_exists('value', $trieLevel)) {
            return true;
        }

        return false;
    }

    public function prefixSearch($prefix)
    {
        $trieLevel = $this->getTrieForKey($prefix);
        if (false == $trieLevel) {
            return false;
        } else {
            return $this->getAllChildren($trieLevel, $prefix);
        }
    }

    private function &getTrieForKey($key, $create = false)
    {
        $trieLevel = &$this->trie;
        for ($i = 0; $i < strlen($key); $i++) {
            $character = $key[$i];
            if (!isset($trieLevel['children'][$character])) {
                if ($create) {
                    $trieLevel['children'][$character] = array(
                        'children' => array()
                    );
                } else {
                    // TODO: What????
                    $false = false;
                    return $false;
                }
            }

            $trieLevel = &$trieLevel['children'][$character];
        }

        return $trieLevel;
    }

    private function getAllChildren($trieLevel, $prefix)
    {
        $return = array();
        if (array_key_exists('value', $trieLevel)) {
            $return[$prefix] = $trieLevel['value'];
        }

        if (isset($trieLevel['children'])) {
            foreach ($trieLevel['children'] as $character => $trie) {
                $return = array_merge($return, $this->getAllChildren($trie, $prefix . $character));
            }
        }

        return $return;
    }
}

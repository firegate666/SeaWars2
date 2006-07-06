<?php
	Setting::write('charset', 'ISO-8859-1', 'Charset',false);

/**
* generate proper XML output
*/
class XML {
    protected $output;

    function &body(&$content, $root=null, $doctype=null, $version=null) {
        $xmldef = "<?xml version=\"1.0\" encoding=\"".
            Setting::read('charset','ISO-8859-1').'"?>'."\n";
        if(is_null($root) || ($root === true))
            $root = 'seawars';
        $version_attr = '';
        if (is_null($version))
            $version = "1.0";
        if(!empty($version)) $version_attr = ' version="'.$version.'"';
        $content = "<$root$version_attr>\n".$content."\n</$root>";        
        $doctypetag = '';
        if(!empty($doctype))
            $doctypetag = '<!DOCTYPE '.$doctype['name'].' SYSTEM "'
                        .$doctype['dtd'].'" >'."\n";
        return $xmldef.$doctypetag.$content;
    }

    /* transform an array to xml */
    function get(&$array, $root = true, $indent = 1, $doctype=null,
                $version=null) {
        $content = '';
        foreach($array as $k=>$v) {
            if (is_array($v)) {
                if (!count($v))
                    continue;
                /* numeric arrays are just a repetition of the current key ... */
                if (is_numeric(key($v))) {
                    $b = array();
                    foreach($v as $row) {
                        $b[$k] = $row;
                        $content .= str_repeat('  ', $indent)."\n".
                            XML::get($b, false, $indent + 1)."\n";
                    }
                } else {
                    $p = "\n".XML::get($v, false, $indent +
1).str_repeat('  ', $indent);
                    $content .= str_repeat('  ', $indent).
                        XML::tag($k, array(), $p, false)."\n";
                }
            } else {
                if(substr($k, 0, 8) == '__rawxml') {
            // FIXME this is a database xml field. this should
            // really be exported another way by the db so that
            // it can also be converted to other output types
            // easily
                    $k = substr($k, 8);
                    $content .= str_repeat('  ', $indent).XML::tag($k,
array(), $v)."\n";
                } else
                    $content .= str_repeat('  ', $indent).XML::tag($k,
array(), XML::escape($v))."\n";
            }
        }
        if ($root !== false)
            return XML::body($content, $root, $doctype, $version);
        return $content;
    }

    /**
    * generic function to produce a valid html tag
    *
    * @param string    $tagname     name of tag
    * @param array    $param     key/value pairs of array elements
    * @param string    $content     content to be included. Has to be
escaped before calling!!
    * @param boolean    $cansingle    this tag can be ended by />
    */
    function &tag($tagname, $param, &$content, $cansingle = true) {
        $tagname = strtolower($tagname);
        $r = '<'.$tagname;
        if (count($param) > 0) {
            foreach($param as $k=>$v) {
                $r .= '
'.XML::escape($k).'="'.addslashes(XML::escape($v)).'"';
            }
        }
        /*
        if ( ($content == '') && ($cansingle) && (__CLASS__ != 'xml')
            && (__CLASS__ != 'XML') )
            return $r .= ' />';
        */
        return $r . ">$content</$tagname>";
    }

    /**
    * escapes content to be valid html
    *
    * @param string    $content    contents to be escaped
    */
    function &escape(&$content) {
        return htmlspecialchars($content);
    }


    function XML() {
        $this->output = '';
    }

}

?>
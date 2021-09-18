<?php 

namespace AppBundle\Twig;

class AppExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('wordwrap', array($this, 'wordwrapFilter')),
            new \Twig_SimpleFilter('json_decode', array($this, 'jsonDecode')),
            new \Twig_SimpleFilter('str_pad', array($this, 'strPad')),
            new \Twig_SimpleFilter('to_src', array($this, 'toSrc')),
        );
    }

    public function wordwrapFilter($text, $limit = 5)
    {

    	$wrap = 0;
    	$wordwrap = "";

    	for ($i=0; $i < strlen($text); $i++) { 
			$wordwrap .= $text[$i];
    		if ($wrap < $limit) {
    			$wrap += 1;
    		} else {
    			$wrap = 0;
    			$wordwrap .= "<br>";
    		}
    	}

    	return $wordwrap;

    }

    public function getName()
    {
        return 'app_extension';
    }

    public function jsonDecode($str) {
        return json_decode($str);
    }

    public function strPad($input,$pad_length = 6)
    {
        return str_pad($input, $pad_length, '0', STR_PAD_LEFT);
    }

    public function toSrc($img_base64_encoded)
    {
        $imageContent = file_get_contents($img_base64_encoded);
        $path = tempnam(sys_get_temp_dir(), 'prefix');
        file_put_contents ($path, $imageContent);

        return $path;
    }
}
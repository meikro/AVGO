<?php
/**
 * @Ctcms open source management system
 * @copyright 2016-2017 ctcms.cn. All rights reserved.
 * @Author:Chi Tu
 * @Dtime:2016-08-11
 */
/**
 * PHP 富文本XSS过滤类
 *
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Xsshtml {
	private $m_dom;
	private $m_xss;
	private $m_ok;
	private $m_AllowAttr = array('title', 'src', 'href', 'id', 'class', 'style', 'width', 'height', 'alt', 'target', 'align');
	private $m_AllowTag = array('a', 'img', 'br', 'strong', 'b', 'code', 'pre', 'p', 'div', 'em', 'span', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'table', 'ul', 'ol', 'tr', 'th', 'td', 'hr', 'li', 'u');
	/**
     * 构造函数
     *
     * @param string $html 待过滤的文本
     * @param string $charset 文本编码，默认utf-8
     * @param array $AllowTag 允许的标签，如果不清楚请保持默认，默认已涵盖大部分功能，不要增加危险标签
     */
	public function __construct($params=array()){
		$html = !empty($params['html']) ? $params['html'] : '';
        $charset = !empty($params['charset']) ? $params['charset'] : 'utf-8';
		$AllowTag = !empty($params['tag']) ? $params['tag'] : array();
		$this->m_AllowTag = empty($AllowTag) ? $this->m_AllowTag : $AllowTag;
		$this->m_xss = strip_tags($html, '<' . implode('><', $this->m_AllowTag) . '>');
		if (empty($this->m_xss)) {
			$this->m_ok = FALSE;
			return ;
		}
		$this->m_xss = "<meta http-equiv=\"Content-Type\" content=\"text/html;charset={$charset}\">" . $this->m_xss;
		$this->m_dom = new DOMDocument();
		$this->m_dom->strictErrorChecking = FALSE;
		$this->m_ok = @$this->m_dom->loadHTML($this->m_xss);
	}

	/**
     * 获得过滤后的内容
     */
	public function getHtml()
	{
		if (!$this->m_ok) {
			return '';
		}
		$nodeList = $this->m_dom->getElementsByTagName('*');
		for ($i = 0; $i < $nodeList->length; $i++){
			$node = $nodeList->item($i);
			if (in_array($node->nodeName, $this->m_AllowTag)) {
				if (method_exists($this, "__node_{$node->nodeName}")) {
					call_user_func(array($this, "__node_{$node->nodeName}"), $node);
				}else{
					call_user_func(array($this, '__node_default'), $node);
				}
			}
		}
		return strip_tags($this->m_dom->saveHTML(), '<' . implode('><', $this->m_AllowTag) . '>');
	}

	private function __true_url($url){
		if (preg_match('#^https?://.+#is', $url)) {
			return $url;
		}else{
			return 'http://' . $url;
		}
	}

	private function __get_style($node){
		if ($node->attributes->getNamedItem('style')) {
			$style = $node->attributes->getNamedItem('style')->nodeValue;
			$style = str_replace('\\', ' ', $style);
			$style = str_replace(array('&#', '/*', '*/'), ' ', $style);
			$style = preg_replace('#e.*x.*p.*r.*e.*s.*s.*i.*o.*n#Uis', ' ', $style);
			return $style;
		}else{
			return '';
		}
	}

	private function __get_link($node, $att){
		$link = $node->attributes->getNamedItem($att);
		if ($link) {
			return $this->__true_url($link->nodeValue);
		}else{
			return '';
		}
	}

	private function __setAttr($dom, $attr, $val){
		if (!empty($val)) {
			$dom->setAttribute($attr, $val);
		}
	}

	private function __set_default_attr($node, $attr, $default = '')
	{
		$o = $node->attributes->getNamedItem($attr);
		if ($o) {
			$this->__setAttr($node, $attr, $o->nodeValue);
		}else{
			$this->__setAttr($node, $attr, $default);
		}
	}

	private function __common_attr($node)
	{
		$list = array();
		foreach ($node->attributes as $attr) {
			if (!in_array($attr->nodeName, 
				$this->m_AllowAttr)) {
				$list[] = $attr->nodeName;
			}
		}
		foreach ($list as $attr) {
			$node->removeAttribute($attr);
		}
		$style = $this->__get_style($node);
		$this->__setAttr($node, 'style', $style);
		$this->__set_default_attr($node, 'title');
		$this->__set_default_attr($node, 'id');
		$this->__set_default_attr($node, 'class');
	}

	private function __node_img($node){
		$this->__common_attr($node);
		$this->__set_default_attr($node, 'src');
		$this->__set_default_attr($node, 'width');
		$this->__set_default_attr($node, 'height');
		$this->__set_default_attr($node, 'alt');
		$this->__set_default_attr($node, 'align');
	}
	private function __node_a($node){
		$this->__common_attr($node);
		$href = $this->__get_link($node, 'href');
		$this->__setAttr($node, 'href', $href);
		$this->__set_default_attr($node, 'target', '_blank');
	}

	private function __node_embed($node){
		$this->__common_attr($node);
		$link = $this->__get_link($node, 'src');
		$this->__setAttr($node, 'src', $link);
		$this->__setAttr($node, 'allowscriptaccess', 'never');
		$this->__set_default_attr($node, 'width');
		$this->__set_default_attr($node, 'height');
	}

	private function __node_default($node){
		$this->__common_attr($node);
	}
}
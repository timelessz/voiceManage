<?php
/**
 * doitphp phpmailer module
 * 
 * 使用Smtp SERVER发送邮件
 * @author tommy <streen003@gmail.com>
 * @copyright  Copyright (c) 2010 Tommy Software Studio.
 * @link http://www.doitphp.com
 * @version $Id: PhpmailerModule.class.php 1.0 2011-04-04 23:30:00Z tommy $
 * @package module
 */


//load phpmailer file
include_once 'class.phpmailer.php';

class PHPmail extends PHPMailer {
	
	/**
	 * 构造函数
	 * 
	 * @access public
	 * @return boolean
	 */
	public function __construct() {
		
		$this->exceptions = true;
		
		return true;
	}
	
	/**
	 * 设置smtp server 连接参数
	 * 
	 * @access public
	 * @param array		$option	smtp服务器连接参数
	 * @return boolean
	 * 
	 * @example
	 * $option = array (
 	 *	'host' => 'smtp.tommycode.com',
 	 *	'username' => 'tommy',
     *	'password' => 'yourpassword',
     *	'from'=>'service@tommycode.com',
     *	'fromname'=>'tommy support',
     *	'reply'=>'service@tommycode.com',
     * );
     * 
     * $mailer = $this->module('phpmailer');
     * 
     * $mailer->set_smtp_config($option);
	 */
	public function set_smtp_config($option) {
		
		//parse params
		if (empty($option) || !is_array($option)) {
			return false;
		}
		
		$this->Host 	= $option['host'];
		$this->Username = $option['username'];
		$this->Password = $option['password'];
							
		$this->From 	= empty($option['from']) ? $option['username'] . '@' . str_replace('stmp.', '', $option['host']) : $option['from'];
		$this->FromName = empty($option['fromname']) ? $option['username'] : $option['fromname'];
		
		//设置smtp端口.
		$this->Port = empty($option['port']) ? 25 : $option['port'];
		
		if (empty($option['reply'])) {
			$this->AddReplyTo($this->From);
		} else {
			$this->AddReplyTo($option['reply']);
		}
		
		//设置SSL加密
		if ($option['ssl']) {						
			$this->SMTPSecure = 'ssl';
		}
		
		//clear unuseful memory    
		unset($option);
		
		return true;
	}
	
	/**
	 * 发送邮件内容
	 * 
	 * @access public
	 * @param string $to		所发送的邮件地址
	 * @param string $subject	邮件题目
	 * @param string $body		邮件内容, 支持html标签
	 * @return boolean
	 */
	public function send_mail($to, $subject, $body) {
		
		$this->IsSMTP();
		$this->SMTPAuth = true;
		
		$this->CharSet ="utf-8";
		$this->Encoding = "base64";
		
		$this->AddAddress($to);
		
		$this->Subject = $subject;
		$this->MsgHTML($body);
		$this->IsHTML(true);
				
		return $this->Send() ? true : false;
	}
	
	/**
	 * 析构函数
	 * 
	 * @access public
	 * @return void
	 */
	public function __destruct() {
		
	}
}
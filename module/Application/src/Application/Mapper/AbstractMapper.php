<?php
/**
 * Class AbstractMapper
 *
 * filename:   AbstractMapper.php
 * 
 * @author      Chukwuma J. Nze <chukkynze@nomsterz.com>
 * @since       1/23/14 11:18 PM
 * 
 * @copyright   Copyright (c) 2014 www.Nomsterz.com
 */ 
namespace Application\Mapper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AbstractMapper implements ServiceLocatorAwareInterface
{
    protected $service_manager;
	protected $Config;

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->service_manager = $serviceLocator;
    }

    public function getServiceLocator()
    {
        return $this->service_manager;
    }

    /**
     * Uses sha1 security to create a hash of a string $val
     *
     * @param $val string
     * @param $key string
     * @return string
     */
    public function createHash($val,$key)
    {
        $hash   =   hash_hmac('sha512', $val, $key);
        return $hash;
    }

    /**
     *
     * Encrypt/Decrypt function
     * Note strings should already hashed, salted and md5ed or sha1ed before even thinking of using this
     *
     * @param           $mode 'e'|'d' ==> encrypt|decrypt
     * @param           $string_to_convert
     * @param           $key
     *
     * @return array|bool|string
     */
    public function twoWayCrypt($mode, $string_to_convert, $key)
    {
        $encryptionMethod   =   "AES-256-CBC";
        $raw_output         =   FALSE;

        if($mode === "e")
        {
            // Encrypt
            $iv             =   mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_CAST_256, MCRYPT_MODE_CBC), MCRYPT_RAND);
            return  $iv . ":ntz:" . openssl_encrypt($string_to_convert, $encryptionMethod, $key, $raw_output, $iv);
        }
        elseif($mode === "d")
        {
            // Decrypt
            $expld          =   explode(':ntz:', $string_to_convert);
            return  openssl_decrypt($expld[1], $encryptionMethod, $key, $raw_output, $expld[0]);
        }
        else
        {
            return FALSE;
        }
    }

    public function _writeLog($priority='info', $message)
    {
        $this->getServiceLocator()->get('Zend\Log')->$priority($message);
    }


    protected function getConfig()
    {
        if (!$this->Config)
        {
            $this->Config   =   $this->getServiceLocator()->get('config');
        }

        return $this->Config;
    }

    public function areExceptionsAllowed()
    {
        $domainSegments     =   explode(".", $_SERVER['SERVER_NAME']);
        return $domainSegments[0] == 'www' ? FALSE : TRUE;
    }
}
<?php
namespace Tofu;
class Dictionary
{
    const INT = 'int';
    const UINT = 'uint';
    const ID = 'id';
    const DATE = 'date';
    const STRING = 'string';

    protected $_arrDictionary;

    public function __construct($arrDictionary = array())
    {
        //检查输入
        if (!is_array($arrDictionary)) {
            throw new InvalidArgumentException('__construct() expects parameter 1 to be array');
        }
        $this->_arrDictionary = $arrDictionary;
    }

    public function checkParams($arrParams = array())
    {
        //检查输入
        if (!is_array($arrParams)) {
            throw new InvalidArgumentException('checkParams() expects parameter 1 to be array');
        }
        //返回参数
        $arrReturn = array();

        //过滤字典外的参数
        $arrParams = array_intersect_key($arrParams, $this->_arrDictionary);
        //检查参数
        foreach ($this->_arrDictionary as $strKey => $arrDictionary) {
            $strType = $arrDictionary['type'];
            $bolOptional = $arrDictionary['optional'];
            $mixParam = $arrParams[$strKey];
            //参数未传，字典定义可选，跳过检查
            if ($bolOptional && !strlen($arrParams[$strKey])) {
                if (strlen($arrDictionary['default'])) {
                    $mixParam = $arrDictionary['default'];
                } else {
                    continue;
                }
            }
            //检查类型
            if (self::INT === $strType) {
                $mixParam = $this->checkInt($strKey, $mixParam);
            } else if (self::UINT === $strType) {
                $mixParam = $this->checkUint($strKey, $mixParam);
            } else if (self::ID === $strType) {
                $mixParam = $this->checkId($strKey, $mixParam);
            } else if (self::STRING === $strType) {
                $mixParam = strval($mixParam);
            } else if (self::DATE === $strType) {
                $mixParam = $this->checkDate($strKey, $mixParam);
            } else {
                throw new UnexpectedValueException('unexpected type');
            }
            $arrReturn[$strKey] = $mixParam;
        }
        return $arrReturn;
    }

    /**
     * checkInt 
     * 检查整型
     * @param mixed $mixParam 
     * @static
     * @access public
     * @return void
     */
    static public function checkInt($strKey, $mixParam)
    {
        if (!ctype_digit(strval($mixParam))) {
            throw new DomainException("$strKey is not a int");
        }
        return intval($mixParam);
    }

    /**
     * checkUint 
     * 检查无符号整型
     * @param mixed $mixParam 
     * @static
     * @access public
     * @return void
     */
    static public function checkUint($strKey, $mixParam)
    {
        try {
            $intParam = self::checkInt($strKey, $mixParam);
        } catch (DomainException $e) {
            throw new DomainException("$strKey is not a uint");
        }
        if ($intParam < 0) {
            throw new DomainException("$strKey is not a uint and less than 0");
        }
        return $intParam;
    }

    /**
     * checkId 
     * 检查id类型
     * @param mixed $mixParam 
     * @static
     * @access public
     * @return void
     */
    static public function checkId($strKey, $mixParam)
    {
        try {
            $mixParam = new MongoId($mixParam);
        } catch (MongoException $e) {
            throw new DomainException("$strKey is not a id");
        }
        return $mixParam;
    }

    /**
     * checkDate 
     * 检查日期类型
     * @access public
     * @return void
     */
    static public function checkDate($strKey, $mixParam)
    {
        $mixParam = strtotime($mixParam);
        if (false === $mixParam) {
            throw new DomainException("$strKey is not a date");
        }
        return $mixParam;
    }
}

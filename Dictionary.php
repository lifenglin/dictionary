<?php
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
        $this->_arrDictionary = $arrDictionary;
    }

    public function checkParams($arrParams = array())
    {
        //返回参数
        $arrReturn = array();

        //过滤字典外的参数
        $arrParams = array_intersect_key($arrParams, $this->_arrDictionary);
        //检查参数
        foreach ($this->_arrDictionary as $strKey => $arrDictionary) {
            $strType = $arrDictionary['type'];
            $bolOptional = $arrDictionary['optional'];
            //参数未传，字典定义可选，跳过检查
            if ($bolOptional && !strlen($arrParams[$strKey])) {
                $arrParams[$strKey] = $arrDictionary['default'];
                continue;
            }
            //检查类型
            if (self::INT === $strType) {
                $mixParam = $this->checkInt($mixParam);
            } else if (self::UINT === $strType) {
                $mixParam = $this->checkUint($mixParam);
            } else if (self::ID === $strType) {
                $mixParam = $this->checkId($mixParam);
            } else if (self::STRING === $strType) {
                $mixParam = strval($mixParam);
            } else {
                //throw
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
    static public function checkInt($mixParam)
    {
        if (!ctype_digit(strval($mixParam))) {
            //throw
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
    static public function checkUint($mixParam)
    {
        $intParam = $this->checkInt($mixParam);
        if ($intParam < 0) {
            //throw
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
    static public function checkId($mixParam)
    {
        try {
            $mixParam = new MongoId($mixParam);
        } catch (exception $e) {
            //throw
        }
        return $mixParam;
    }
}

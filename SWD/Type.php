<?php

namespace SWD;


class Type
{
    /*
     * Sherman Web Design
     * http://shermanwebdesign.com
     *
     * Author: Ken Sherman
     * Github Repo: https://github.com/kennstphn/SWD-Type
     *
     *********************************************************************
     * ***************************************************************** *
     * *                                                               * *
     * *  distributed under the WTFPL license -- http://www.wtfpl.net  * *
     * *                                                               * *
     * ***************************************************************** *
     *********************************************************************
     */

    /*
     * I have found it annoying lately to continue having to check for correct types and structures.
     * This class is a collection of methods to do that work
     */

    /*
     *  validate is used to throw exceptions if the data is not one of the types defined
     */
    static function validate($data, $typeOrTypeList){

        if ( ! self::is_valid($data, $typeOrTypeList)){
            throw new \Exception('Invalid data type '. gettype($data) . ', expected '.implode(' or ', $typeOrTypeList). '.');
        }

    }

    /*
     * is_valid is used to return a boolean if the type for the data is found in the array of
     * accepted types
     */
    static function is_valid($data, $typeOrTypeList){

        $typeList = self::ensure_array($typeOrTypeList);

        foreach($typeList as $type){
            if (! is_string($type)){ throw new \Exception('Invalid argument list');}
        }

        $actualType = gettype($data);
        if( ! in_array($actualType, $typeList )){
            return false;
        }

        return true;

    }

    static function is_array_of_strings($array){
        if (!is_array($array)){return false;}
        foreach($array as $item){
            if ( ! is_string($item)){return false;}
        }
        return true;
    }

    /*
     * The ensure function(s) restructure the data if necessary to fit the expected
     * type named by the function.
     */
    static function ensure_array($maybeArray, $keyIfNecessary=null){

        if(is_array($maybeArray)){
            return $maybeArray ;
        }

        if($keyIfNecessary){
            return array($keyIfNecessary => $maybeArray);
        }

        return array($maybeArray);
    }
    
    static function ensure_date($dateStringOrObject){

        self::validate($dateStringOrObject, array('object','string'));

        if(is_object($dateStringOrObject) && get_class($dateStringOrObject) === 'DateTime'){
            return $dateStringOrObject;
        }
        if(is_object($dateStringOrObject) && method_exists($dateStringOrObject, '__toString')){
            return new \DateTime($dateStringOrObject->__toString());
        }

        if( is_string($dateStringOrObject)){
            return new \DateTime($dateStringOrObject);
        }

        throw new \Exception('Unparseable '.gettype($dateStringOrObject));
    }


}
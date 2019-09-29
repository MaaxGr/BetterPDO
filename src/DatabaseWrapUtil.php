<?php

namespace gr\maax\betterpdo;

use ReflectionClass;
use ReflectionException;

class DatabaseWrapUtil {

    public static function assocToObjectArray($classPath, $assocEntries) {
        $returnArray = array();

        foreach ($assocEntries as $assoc) {
            array_push($returnArray, self::assocToObject($classPath, $assoc));
        }

        return $returnArray;
    }

    public static function assocToObject($classPath, $assocData) {
        try {
            $clazz = new ReflectionClass($classPath);
            $instance = $clazz->newInstance();

            foreach ($assocData as $key => $value) {
                $fieldName = self::snakeToPascalCase($key);

                $property = $clazz->getProperty($fieldName);

                if ($property != null) {
                    $property->setAccessible(true);
                    $property->setValue($instance, $value);
                }
            }

            return $instance;
        } catch (ReflectionException $e) {
            exit($e->getTraceAsString());
        }
    }

    private static function snakeToPascalCase($target) {
        return str_replace('_', '', ucwords($target, '_'));
    }
}
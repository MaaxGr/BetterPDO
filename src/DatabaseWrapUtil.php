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

                $property = self::findPropertyByName($clazz, $fieldName);

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

    private static function findPropertyByName(ReflectionClass $clazz, string $name) {
        $lcName = strtolower($name);

        foreach ($clazz->getProperties() as $property) {

            $lcPropName = strtolower($property->name);

            //try with same name
            if ($lcName == $lcPropName) {
                return $property;
            }

            //try without underscores
            if (str_replace('_', '', $lcName) == str_replace('_', '', $lcPropName)) {
                return $property;
            }
        }

        return null;
    }
}

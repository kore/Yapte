<?php
/**
 * This file is part of Yapte
 *
 * @version $Revision$
 */

namespace Yapte;

/**
 * Base meta data extractor
 *
 * @version $Revision$
 */
abstract class MetaDataExtractor
{
    /**
     * Try to extract meta data from string
     *
     * @param string $string
     * @return MetaData
     */
    abstract public function getMetaData($string);
}

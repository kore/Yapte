<?php
/**
 * This file is part of Yapte
 *
 * @version $Revision$
 */

namespace Yapte\Provider;

use Yapte\Struct;

/**
 * Provider base class
 *
 * @version $Revision$
 */
class Show extends Struct
{
    /**
     * Internal show ID
     *
     * @var mixed
     */
    public $internalId;

    /**
     * Show name
     *
     * @var string
     */
    public $name;
}

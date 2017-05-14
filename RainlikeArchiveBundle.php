<?php

namespace Rainlike\ArchiveBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Rainlike\ArchiveBundle\DependencyInjection\RainlikeArchiveExtension;

/**
 * Class RainlikeArchiveBundle
 * @package Rainlike\ArchiveBundle
 */
class RainlikeArchiveBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        return new RainlikeArchiveExtension();
    }
}

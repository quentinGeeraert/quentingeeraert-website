<?php

namespace App\Tests\Forms;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class TestKernel extends Kernel
{
    /**
     * @var string $$configurationFilename
     */
    private $configurationFilename;

    /**
     * Defines the configuration filename.
     *
     * @param string $filename
     */
    public function setConfigurationFilename($filename): void
    {
        $this->configurationFilename = $filename;
    }

    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        return [
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
            new \Karser\Recaptcha3Bundle\KarserRecaptcha3Bundle(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load($this->configurationFilename);
    }

    public function getLogDir()
    {
        return sys_get_temp_dir().'/var/log';
    }

    public function getCacheDir()
    {
        return sys_get_temp_dir().'/var/cache/'.$this->environment;
    }
}

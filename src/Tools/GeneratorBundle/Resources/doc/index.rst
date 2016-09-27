public function registerBundles()
{
    ...

    if (in_array($this->getEnvironment(), array('dev', 'test'))) {
        ...

        $bundles[] = new Sompom\GeneratorBundle\GeneratorBundle();
    }
}
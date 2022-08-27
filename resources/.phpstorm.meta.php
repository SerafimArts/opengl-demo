<?php

declare(strict_types=1);

namespace PHPSTORM_META {

    override(\Symfony\Component\DependencyInjection\ContainerBuilder::get(), map([
        '' => '@',
    ]));

    override(\Symfony\Component\DependencyInjection\Container::get(), map([
        '' => '@',
    ]));

    override(
        \App\Application::get(), map([
        '' => '@',
    ]));

}

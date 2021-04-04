[![Build Status](https://scrutinizer-ci.com/g/alex-patterson-webdev/laminas-factory/badges/build.png?b=master)](https://scrutinizer-ci.com/g/alex-patterson-webdev/laminas-factory/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alex-patterson-webdev/laminas-factory/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/alex-patterson-webdev/laminas-factory/?branch=master)
[![codecov](https://codecov.io/gh/alex-patterson-webdev/laminas-factory/branch/master/graph/badge.svg)](https://codecov.io/gh/alex-patterson-webdev/laminas-factory)

# Arp\LaminasFactory

## About

The Laminas Framework provides the ability to inject class dependencies into services via 'service factories', these are classes that implement
the interface `Laminas\ServiceManager\Factory\FactoryInterface`. This module provides components to aid with the creation of the service factories 
by allowing developers to easily fetch service specific configuration options from within the factory.

## Installation

Installation via [composer](https://getcomposer.org).

    composer require alex-patterson-webdev/laminas-factory ^3


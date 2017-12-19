# Hydrate

[![Build Status](https://travis-ci.org/Stratadox/Hydrate.svg?branch=master)](https://travis-ci.org/Stratadox/Hydrate)

The Hydrate module contains all that is needed to hydrate objects from an array
of data. This data could come from a sql database, a document store, a web API 
or any other source.

## Installation

Install using composer:

`composer require stratadox/hydrate`

## Subpackages
The Hydrate package contains no source code (save some integration tests)
Instead it composes several sub-packages. These packages are listed below.

### Hydrator
Classes that produce hydrated objects.

[![Source Code](https://img.shields.io/badge/source-github-brightgreen.svg)](https://github.com/Stratadox/Hydrator)
[![Build Status](https://travis-ci.org/Stratadox/Hydrator.svg?branch=master)](https://travis-ci.org/Stratadox/Hydrator)
[![Coverage Status](https://coveralls.io/repos/github/Stratadox/Hydrator/badge.svg?branch=master)](https://coveralls.io/github/Stratadox/Hydrator?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Stratadox/Hydrator/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Stratadox/Hydrator/?branch=master)

### Hydration mapping
Tools for mapping data structures to object properties.

[![Source Code](https://img.shields.io/badge/source-github-brightgreen.svg)](https://github.com/Stratadox/HydrationMapping)
[![Build Status](https://travis-ci.org/Stratadox/HydrationMapping.svg?branch=master)](https://travis-ci.org/Stratadox/HydrationMapping)
[![Coverage Status](https://coveralls.io/repos/github/Stratadox/HydrationMapping/badge.svg?branch=master)](https://coveralls.io/github/Stratadox/HydrationMapping?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Stratadox/HydrationMapping/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Stratadox/HydrationMapping/?branch=master)

### Hydration proxies
Lazy loading through proxy objects.

[![Source Code](https://img.shields.io/badge/source-github-brightgreen.svg)](https://github.com/Stratadox/HydrationProxies)
[![Build Status](https://travis-ci.org/Stratadox/HydrationProxies.svg?branch=master)](https://travis-ci.org/Stratadox/HydrationProxies)
[![Coverage Status](https://coveralls.io/repos/github/Stratadox/HydrationProxies/badge.svg?branch=master)](https://coveralls.io/github/Stratadox/HydrationProxies?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Stratadox/HydrationProxies/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Stratadox/HydrationProxies/?branch=master)

### Hydration 
Interfaces for the above subpackages.

[![Source Code](https://img.shields.io/badge/source-github-brightgreen.svg)](https://github.com/Stratadox/Hydration)

## Integration tests
This package contains tests that assert the packages work well together.

These tests do not generate code coverage; the code coverage statistics shown 
above are for solely for the unit tests.

### Hydrate database records
This class of tests asserts that (sqlite) database records get converted to an
object graph, lazily loading some of the objects.

# Documentation

## Introduction

*Hydrate* is a package that enables you to transform any kind of raw data, such
as the result of a database query or an API call, into an object graph.

## Installation

Install using composer:

`composer require stratadox/hydrate`


## Components

The *Hydrate* package consists of several modules:

- [**Hydrator**](https://github.com/Stratadox/Hydrator/blob/master/README.md): 

  Produces objects, complete with data in their properties, without calling constructor functions.
- [**HydrationMapping**](https://github.com/Stratadox/HydrationMapping/blob/master/README.md): 

  Provides instructions to the hydrators on how to map the input data to the object properties.
- [**Proxy**](https://github.com/Stratadox/Proxy/blob/master/README.md): 

  Provides proxy functionality for lazy loading of objects whose data is not loaded into the system yet.
- [**HydrationMapper**](https://github.com/Stratadox/HydrationMapper/blob/master/README.md): 

  Tools to ease the construction of hydration maps.  

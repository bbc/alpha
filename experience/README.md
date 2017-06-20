# BBC Alpha
## Experience Layer

This is the "experience layer" - or, a tightly-bound collection of PHP scripts
that are responsible for doing the dirty work of fetching data from services and rendering pages.

## Principles

1. We use PHP, because it scales. No, it _really_ scales. Ask Wikipedia, WordPress or Facebook. It’s even better if you put an edge cache in front of it.
2. We don’t use a framework, because they’re bloat for what we want to do. We have a small library of classes and utility functions.
3. PHP is ultimately a templating language (albeit one which gives you more than enough rope to hang yourself), so we don’t invent another one on top of it.
4. *Web servers* are really good at parsing and routing requests, so we don’t re-implement that in the application.
5. The *service layer* is responsible for talking to databases and providing a coherent view of resources we might drop into a template.
6. Transcluded *components* deal with the clever stuff, like video players, which we might want to use on various kinds of pages. Both SSIs and ESIs are suitable for including them.

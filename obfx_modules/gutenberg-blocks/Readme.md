OrbitFox Gutenberg Blocks
---------------------

General structure of this module should be as the following:

* Load the transpiled code from the `build` dir, aka the "dist" folder.
* The functionality for each block should be isolated in it's own folder inside the `blocks` dir.
* If a block needs server side rendering than it should have an extension class of the `/OrbitFox/Gutenberg_Block` class.
* Common components, like a placeholder, can easily stand inside the `components` dir.
* Any server side data handling should happen in the `store`.

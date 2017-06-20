## Licensing notice

Images (including logos) and typefaces are **NOT** licensed according to the terms of the Apache Licence 2.0. Additionally, they may be subject to third-party rights and constitute trademarks.

## Directory structure

Files in this part of the tree *may* be served entirely separately from the `public` directory: for example, they might be uploaded to an S3 bucket and served via CloudFront.

Resources in this directory should coexist with statically-served portions of components, and so must be placed in subdirectories taking the following form:

    <component>/            alpha/
        <release>/              current/
            ...                     ...

The directory structure within a release is defined per-component, but for Alpha we split resources into `css`, `js`, `fonts` and `img` subdirectories.

## Minification

Within `css` and `js`, minified versions of stylesheets will be generated (if possible) as part of the build process and distributed as `NAME.min.css` and `NAME.min.js`.

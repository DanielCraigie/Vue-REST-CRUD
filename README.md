# Vue REST CRUD

This project is an example of a basic CRUD (Create, Read, Update & Delete) form powered by [Vue.js](https://vuejs.org/) using a [RESTful](https://restfulapi.net/) PHP API.

##### Disclaimer: I have created this environment for experimentation purposes while learning Vue, as such it should **not** be used as a reference tool/working example for use in a production environment.

## Instructions

### Requirements

- Docker Compose [installed](https://docs.docker.com/compose/install/)
- A running Docker Engine

### Instillation

1. Clone this repository to your computer
2. Enter the newly created directory
3. Create a .env file (clone .env.example)
4. Run `docker-compose up`
   1. Run the `migrations` Container for a second time after the `composer` Container has finished

### Usage

Docker Compose will bind port 80 of the web server to 8080 on your host machine.

To use the site navigate to [http://127.0.0.1:8080]

## Components
### Docker

The platform is powered by a collection of [Docker](https://www.docker.com/) Containers to provide portability and a minimal footprint on host systems.

The containers (listed below) are built/configured though the `docker-compose.yaml` file:

- web - [NGINX](https://www.nginx.com/) to service HTTP requests and host static pages/assets.
- php-fpm - [PHP](https://php.net/) 7.3-fpm for dynamic page content, rendering and API requests.
- mysql - [MySQL](https://www.mysql.com/) 5.7 database.
- composer - PHP package manager.
- node - [NPM](https://www.npmjs.com/) package manager for JavaScript and Frontend tools.
- migrations - Database versioning.
 
The bottom three containers (composer/node/migrations) stop running once they have performed their tasks.  These containers should be manually run whenever changes are made to the Composer/NPM configuration files or when a new migration file needs to be deployed.  

### PHP

The [Slim](http://www.slimframework.com/) framework is providing the backend Web & API functionality in conjunction with the [Twig](https://twig.symfony.com/) template engine.

Third party extensions & plugins are installed/updated by the [Composer](https://getcomposer.org/) package manager (see the `composer.json` file).

The `symfony/dotenv` component handles $_ENV environment setup from the `.env` file (that will need to be cloned from `.env.example` before first use).

### JavaScript

JavaScript components are installed/updated using the NPM package manager (see the `package-json` file).

Vue and [jQuery](https://jquery.com/) provide the front end behaviours with [Bootstrap](https://getbootstrap.com/) & [Font Awesome](https://fontawesome.com/) adding layout & styling.

### MySQL

The Docker containers provide a MySQL 5.7 database that is versioned with the [Doctrine Migrations](https://symfony.com/doc/master/bundles/DoctrineMigrationsBundle/index.html) package.

Migration files added to the `migrations` directory will be applied each time the `migrations` container is executed.

## Dev Environment

The project comes with a docker-compose file to create images, configure and run the containers.

Some images are defined by Dockerfiles stored in the `.build` path.

## Directory Structure

The project files are stored within the following structure:

- .build - Docker resources and image definitions (Dockerfile)
- migrations - Database versioning scripts
- node_modules* - NPM packages
- public - Publicly accessible files/scripts
  - css - Style assets
  - js - JavaScript assets
  - webfonts - Font Assume assets
- src - PHP application files
  - controllers - Controller Classes (grouped by route)
  - routes - Request routing files
  - views - Twig template files and page assets (Vue/custom CSS)
- tests - PHP Unit tests
- vendor* - Composer packages

\* folders that are dynamically generated, you **must not** create/modify files in these directories.

The `npm-post-install.sh` file in the project root is a helper file triggered after NPM install to automatically build/populate the css/js/webfonts assets in the `public` path.

## Conventions

### Twig

- All Twig template files should be stored in the `views` path
- All Twig template files should have the .html.twig extension
- Twig template and asset files (.js/.css) should be stored in a folder with the Controllers name and named after the function that renders them (e.g Testing::index() => testing/index.(js|css|html.twig)
- Page template files should only be stored in the root views directory (e.g. views/default.html.twig)

### Vue

- Vue code is stored in .js files in the `views` path
- Vue files should be loaded into templates using teh `include()` Twig function
- Vue files should be named after the functions that render the template

  #### Vue Delimiters

   Both Vue and Twig use the same output delimiters "{{  }}" by default.  This means that in order to include Vue apps in pages being rendered by Twig, one of them will need to be modified to avoid collisions.

   From the Googling I have done so far, it looks easier to change the Vue delimiters (even though it needs to be done for each application/page).

   In this project, I define a `delimiters` attribute when invoking each instance of Vue:
 
   `var app = new Vue({ delimiters: ['v{', '}e'], el: ...`

   This allows the use of `{{ ... }}` for Twig and `v{ ... }e` for Vue.

## Known Issues

### Migrations

The `migrations` container is dependent on files in the `vendor` directory, so will not be able to function properly until the `composer` container has completed its initial install.
This means that when starting the Development environment for the first time you will need to re-run the `migrations` container to initialise the database.

## Resources

Original project work was based on the [PHP with Vue.js & MySQL: REST API CRUD Tutorial](https://www.techiediaries.com/vuejs-php-mysql-rest-crud-api-tutorial)

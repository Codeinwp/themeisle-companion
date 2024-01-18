# Contributing

Thank you for thinking about contributing to Orbit Fox! All sorts of contributions are welcomed.

## License 

Orbit Fox is [licensed under the GPLv2+](LICENSE.txt), and all contributions to Orbit Fox will be released under the GPLv2+ license. By contributing to this repository, you agree to release your contribution under the GPLv2+ license.

## Setting Up Local Environment

You need a WordPress Environment to run the plugin on. This project needs a lot of different packages to be installed before you can start coding, but we've made the process easier with Docker.

### Getting Started with Docker

The quickest way to get up and running is to use the provided [Docker](https://www.docker.com/) setup which takes care of setting up the environment for you within seconds. Install docker and docker-compose by following the most recent instructions on the Docker site.

Once Docker is installed, fork Orbit Fox repository. Clone your fork of this project and enter the working directory:

```
git clone http://github.com/YOUR-USERNAME/themeisle-companion/
cd themeisle-companion
```

Once inside the folder, you can run the following command to start your Docker container:

```
docker-compose up -d
```

This will make your WordPress instance up and running at http://localhost:8888

- If you want to use a different port for your environment, then you can change it from [docker-compose.yml](docker-compose.yml) file.

- If you're developing themes, or core WordPress functionality alongside Orbit Fox, you can make the WordPress files accessible in `/themeisle-companion/wordpress/` folder in your home directory.

- If you want bash access to your environment, you can use the following command to find the ID of your Docker container:

```
docker ps
```

And then you can get bash access with the following command:

```
docker exec -it <ID OF YOUR CONTAINER> bash
```

If you want to stop your Docker container, for the time being, you can use the following command:

```
docker-compose stop
```

Moreover, if you want to delete your Docker instance, you can use:

```
docker-compose down
```

### Installing Dependencies

Once you've bash access of your WordPress container, you need to enter your Orbit Fox directory with:

```
cd /var/www/html/wp-content/plugins/themeisle-companion/
```

Now, we need to install `npm` and `composer` dependencies with the following commands:

```
npm install
composer install
```

Now you can start making your changes to the plugin.

### Running Grunt Tasks

We use [Grunt](https://gruntjs.com/) for automating many tasks, such as compiling Sass to CSS, PHP Code Sniffer, and more.

Once you have made your changes, you can run Grunt tasks by running:

```
grunt local
```

It will take some time to finish all the tasks. If there are any issues found in PHP or JavaScript, it gives you a detailed log inside `/logs/` folder of the plugin. Fix those issues and run `grunt local` again until it finishes without an error.

### PHP_CodeSniffer

To run PHP_CodeSniffer, run `composer lint`. This will run the WordPress Coding Standards checks.
To fix automatically fixable issues, run `composer format`.

### PHP Unit Testing

Tests for PHP use PHPUnit as the testing framework, which is run with:

```
phpunit
```

## Coding Standards

Code must follow [WordPress Coding standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/), which is checked
when running tests (more on this below).

## Security and Sanitization

When [outputting data](https://developer.wordpress.org/plugins/security/securing-output/), escape it using WordPress' escaping functions such as `esc_html()`, `esc_attr__()`, `wp_kses()`, `wp_kses_post()`.

When reading [user input](https://developer.wordpress.org/plugins/security/securing-input/), sanitize it using WordPress' sanitization functions such as `sanitize_text_field()`, `sanitize_textarea_field()`.

When writing to the database, prepare database queries using ``$wpdb->prepare()``

Never trust user input. Sanitize it.

Make use of [WordPress nonces](https://codex.wordpress.org/WordPress_Nonces) for saving form submitted data.

Coding standards will catch any sanitization, escaping or database queries that aren't prepared.

## Types of Test

There are different types of tests that can be written:
- Acceptance Tests: Test as a non-technical user in the web browser.
- Functional Tests: Test the framework (WordPress).
- Integration Tests: Test code modules in the context of a WordPress website.
- Unit Tests: Test single PHP classes or functions in isolation.
- WordPress Unit Tests: Test single PHP classes or functions in isolation, with WordPress functions and classes loaded.

There is no definitive / hard guide, as a test can typically overlap into different types (such as Acceptance and Functional).

The most important thing is that you have a test for *something*.  If in doubt, an Acceptance Test will suffice.

### Writing an Acceptance Test

An acceptance test is a test that simulates a user interacting with the Plugin or Theme in a web browser.
Refer to Writing an End-to-End Test below.

### Writing an End-to-End Test

To write an End-to-End test, create a new file under `e2e-tests/specs` with the name of the spec or functionality you are testing, and add `.spec.test` to the file name.

E.g. for `e2e-tests/specs/checkout.spec.test.js`, the test file should be `checkout.spec.test.js`.

For more information on writing End-to-End tests, refer to the [Playwright documentation](https://playwright.dev/docs/test-intro).

You can check End-to-End [README](./e2e-tests/README.md) for more details.

## Writing a WordPress Unit Test

WordPress Unit tests provide testing of Plugin/Theme specific functions and/or classes, typically to assert that they perform as expected
by a developer.  This is primarily useful for testing our API class, and confirming that any Plugin registered filters return
the correct data.

To create a new WordPress Unit Test, create a new file under `tests/php/unit` with the name of the class you are testing, and the suffix `Test`.
The filename should be in `lower-case-with-dash`, and the class name should be in `CamelCase`.

E.g. for `tests/php/unit/class-api-test.php`, the test class should be `class APITest extends \PHPUnit\Framework\TestCase`.

```php
<?php
class APITest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \WpunitTester
     */
    protected $tester;
    
    public function setUp(): void
    {
        // Before...
        parent::setUp();
        // Your set up methods here.
    }
    public function tearDown(): void
    {
        // Your tear down methods here.
        // Then...
        parent::tearDown();
    }
    // Tests
    public function test_it_works()
    {
        $post = static::factory()->post->create_and_get();
        
        $this->assertInstanceOf(\WP_Post::class, $post);
    }
}
```

## Run PHPUnit Tests

Once you have written your code and test(s), run the tests to make sure there are no errors.

```bash
./vendor/bin/phpunit tests/php/unit/class-api-test.php
```

Any errors should be corrected by making applicable code or test changes.

## Run PHP CodeSniffer

In the Plugin's or Theme's directory, run the following command to run PHP_CodeSniffer, which will check the code meets Coding Standards
as defined in the `phpcs.tests.xml` configuration:

```bash
composer run lint 
```

`--standard=phpcs.tests.xml` tells PHP CodeSniffer to use the Coding Standards rules / configuration defined in `phpcs.tests.xml`.
These differ slightly from WordPress' Coding Standards, to ensure that writing tests isn't a laborious task, whilst maintain consistency
in test coding style.
`-v` produces verbose output
`-s` specifies the precise rule that failed

Any errors should be corrected by either:
- making applicable code changes
- running `composer run format` to automatically fix coding standards

Need to change the PHP or WordPress coding standard rules applied?  Either:
- ignore a rule in the affected code, by adding `phpcs:ignore {rule}`, where {rule} is the given rule that failed in the above output.
- edit the [phpcs.tests.xml](phpcs.tests.xml) file.

## Sending a Pull Request

A good workflow for pull requests to follow is listed below:

- Fork Orbit Fox repository
- Clone forked repository
- Use **development** branch for your changes, or create a new branch from **development** branch.
- Setup environment
- Make code changes
- Run Grunt tasks and PHPUnit tests.
- Push branch to forked repository
- Submit Pull Request

## Reporting Security Issues

ThemeIsle team takes security bugs very seriously. Do not report potential security vulnerabilities here. Email them privately to our team at friends@themeisle.com

## Reporting a Bug

We use [GitHub issues](https://github.com/Codeinwp/themeisle-companion/issues) for tracking bugs. When filing an issue, make sure to answer these questions:

- What version of Orbit Fox are you using?
- What version of WordPress are you using are you using?
- What did you do?
- What did you expect to see?
- What did you see instead?

Explain the problem and include additional details to help maintainers reproduce the problem:

- Use a clear and descriptive title for the issue to identify the problem.
- Describe the exact steps which reproduce the problem in as many details as possible, including screenshots if needed.

## Localizing Orbit Fox Plugin

You don't need to be a developer to contribute to Orbit Fox. You can also contribute to the project by translating it to your language. You can find your [locale here](https://translate.wordpress.org/projects/wp-plugins/themeisle-companion).

Language packs are automatically generated once 95% of the plugin's strings are translated and approved for a locale.

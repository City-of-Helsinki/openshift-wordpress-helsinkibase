# Basic Helsinki WP site

A basic template for creating a WordPress site in a container. Can be used in a Kubernetes environment, e.g. OpenShift, or with Docker.

Uses [Source-To-Image (S2I)](https://github.com/openshift/source-to-image/) to build the container and [Composer](https://getcomposer.org/) to install WordPress and its components.

Uses [msmtp](https://marlam.de/msmtp/msmtp.html) as the SMTP client.

## Configuration

### Composer

Basic Composer configuration is defined in `composer.json`. Provide additional WordPress components, i.e. themes and plugins, to S2I with `COMPOSER_REPOSITORIES` and `COMPOSER_PACKAGES` environment variables.

- Repository format: repo_name type url (separated with "|" pipe character)
- Package format: vendor-name/package-name:version (separated by space, version optional)

When installing packages from [github.com][https://github.com/], provide personal access token with `COMPOSER_AUTH` - see [1](https://getcomposer.org/doc/03-cli.md#composer-auth) and [2](https://getcomposer.org/doc/articles/authentication-for-private-packages.md#github-oauth). The token is required for higher rate limits and accessing private repositories.

See Docker section for examples.

### WordPress

WordPress setup is handled in `wp-config.php`, which loads the default configuration from `/config/default.php`.

Should you require per project configuration, create a `config/custom.php` file containing the necessary constant definitions.

The default configuration is only applied to the selected WordPress constants, which have not been defined in the custom configuration file.

## Docker

### Build example
    docker build --build-arg COMPOSER_AUTH='{"github-oauth": {"github.com": "ghp_your_github_pat"}}' -t wp-helsinki-base .

### Build example, repositories and packages provided as build args
    docker build \
    --build-arg COMPOSER_AUTH='{"github-oauth": {"github.com": "ghp_your_github_pat"}}' \
    --build-arg COMPOSER_REPOSITORIES="wordpress-helfi-helsinkiteema vcs https://github.com/City-of-Helsinki/wordpress-helfi-helsinkiteema | wordpress-helfi-site-core vcs https://github.com/City-of-Helsinki/wordpress-helfi-site-core" \
    --build-arg COMPOSER_PACKAGES="city-of-helsinki/wordpress-helfi-helsinkiteema city-of-helsinki/wordpress-helfi-site-core" \
    -t wp-helsinki-base.

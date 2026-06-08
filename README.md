# Basic Helsinki WP site

A basic template for creating a WordPress site in a container. Can be used in a Kubernetes environment, e.g. OpenShift, or with Docker.

Uses [Source-To-Image (S2I)](https://github.com/openshift/source-to-image/) to build the container and [Composer](https://getcomposer.org/) to install WordPress and its components.

Uses [msmtp](https://marlam.de/msmtp/msmtp.html) as the SMTP client.

## Configuration

### Composer

Basic Composer configuration is defined in `composer.json`. Provide additional WordPress components, i.e. themes and plugins, to S2I with `COMPOSER_REPOSITORIES` and `COMPOSER_PACKAGES` environment variables.

- Repository format: repo_name type url (separated with "|" pipe character)
- Package format: vendor-name/package-name:version (separated by space, version optional)

When installing packages from [github.com](https://github.com/), provide personal access token with `COMPOSER_AUTH` - see [1](https://getcomposer.org/doc/03-cli.md#composer-auth) and [2](https://getcomposer.org/doc/articles/authentication-for-private-packages.md#github-oauth). The token is required for higher rate limits and accessing private repositories.

See Docker section for examples.

### WordPress

WordPress setup is handled in `wp-config.php`, which loads the default configuration from `/config/default.php`.

Should you require per project configuration, create a `config/custom.php` file containing the necessary constant definitions.

The default configuration is only applied to the selected WordPress constants, which have not been defined in the custom configuration file.

### Audit logging

You can setup audit logging for the WordPress site by requiring [WP Resilient Logger](https://github.com/City-of-Helsinki/wp-resilient-logger) and [WP Activity Log](https://wordpress.org/plugins/wp-security-audit-log/). These will be installed as must-use plugins.

WP Resilient Logger expects to receive source and target configuration via `RESILIENT_LOGGER_SETTINGS` constant. If this is not defined in `config/custom.php`, then the constant defined in `config/default.php` will be used. When using the default constant, you can use the following `env` variables to configure the default Elastic Search log target.

|ENV|Type|Description|
|-|-|-|
| WP_RESILIENT_LOGGER_ES_HOST | string | Elastic Search host |
| WP_RESILIENT_LOGGER_ES_PORT | int | Elastic Search port |
| WP_RESILIENT_LOGGER_ES_SCHEME | string | Elastic Search scheme |
| WP_RESILIENT_LOGGER_ES_USERNAME | string | Elastic Search username |
| WP_RESILIENT_LOGGER_ES_PASSWORD | string | Elastic Search password |
| WP_RESILIENT_LOGGER_ES_INDEX | string | Elastic Search index name |
| WP_RESILIENT_LOGGER_ORIGIN | string | Log entry origin |
| WP_RESILIENT_LOGGER_STORE_OLD_ENTRIES_DAYS | int | How long log entries are stored locally |
| WP_RESILIENT_LOGGER_BATCH_LIMIT | int |Batch size when submitting log entries |
| WP_RESILIENT_LOGGER_CHUNK_SIZE | int | Chunk size when submitting log entries |
| WP_RESILIENT_LOGGER_SUBMIT_UNSENT_ENTRIES | bool | Should log entries be submitted or not |
| WP_RESILIENT_LOGGER_CLEAR_SENT_ENTRIES | bool | Should log entries be cleared or not |

Additional `env` variables to modify `config/default.php` setup of WP Resilient Logger.

|ENV|Type|Description|
|-|-|-|
| WP_RESILIENT_LOGGER_ENABLED | bool | Flag for setting Resilient Logger related constants. Defaults to `false` which implies that WP Resilient Logger is not installed. |
| WP_RESILIENT_LOGGER_WSAL_ENABLED | bool | Flag for setting WSAL related constants which implies that Activity Log is not installed. |
| WP_RESILIENT_LOGGER_WSAL_DISABLE_EVENTS_VIEW | bool | Sets `RESILIENT_LOGGER_WSAL_DISABLE_EVENTS_VIEW` constant and disables "WSAL Enable / Disable Events" view. |
| WP_RESILIENT_LOGGER_WSAL_DISALLOW_EDIT_SETTINGS | bool | Sets `RESILIENT_LOGGER_WSAL_DISALLOW_EDIT_SETTINGS` constant and disables WSAL settings view and editing |
| WP_RESILIENT_LOGGER_USE_WP_CRON | bool | Sets `RESILIENT_LOGGER_USE_WP_CRON` constant and enables WP cron schedule for submitting and clearing log entries. |

## Docker

### Build example
    docker build --build-arg COMPOSER_AUTH='{"github-oauth": {"github.com": "ghp_your_github_pat"}}' -t wp-helsinki-base .

### Build example, repositories and packages provided as build args
    docker build \
    --build-arg COMPOSER_AUTH='{"github-oauth": {"github.com": "ghp_your_github_pat"}}' \
    --build-arg COMPOSER_REPOSITORIES="wordpress-helfi-helsinkiteema vcs https://github.com/City-of-Helsinki/wordpress-helfi-helsinkiteema | wordpress-helfi-site-core vcs https://github.com/City-of-Helsinki/wordpress-helfi-site-core" \
    --build-arg COMPOSER_PACKAGES="city-of-helsinki/wordpress-helfi-helsinkiteema city-of-helsinki/wordpress-helfi-site-core" \
    -t wp-helsinki-base.
